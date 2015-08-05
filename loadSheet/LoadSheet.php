<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 31/5/2015
 * Time: 4:22
 */
require_once 'db.php';
require_once 'barCd.php';
require_once "pth.php";
require_once "ay.php";
require_once "fmtkey.php";
const QUEUE_PTH = "c:/xampp/htdocs/loadplan/pgm/loadsheet/queue/";
use LoadSheet\FmtKey as Fmt;

class LoadSheet
{
    public $tripId;

    function gen()
    {
        $this->tripId = $this->getTripId();
        if (is_null($this->tripId)) {
            echo "trip is not given\n";
            exit();
        }

        $con = db_con();
        $a = new Inp($this->tripId, $con);
        $inpRmk_OfOrd = $a->rmk_OfOrd;
        $inpRmk_OfAdr = $a->rmk_OfAdr;
        $inpContent = $a->content;
        $inpDrop = $a->drop;
        $inpTrip = $a->trip;
        $inpOrdAdr = $a->ordAdr;
        $inpOrd = $a->ord;

        $a = new Trip($inpTrip->tripDelvDte, $inpTrip->tripNo);
        $tripNo = $a->no;
        $tripPth = $a->pth;
        $tripDelvDte = $a->dte;

        pth_create_if_not_exist($tripPth);
        pth_clear_files($tripPth);

        $a = new GenHdr($inpTrip);
        $b = new GenDrop($inpDrop, $inpRmk_OfOrd, $inpRmk_OfAdr, $tripDelvDte, $inpContent, $tripPth);
        $c = new GenRmkOfAdr($inpRmk_OfOrd, $inpDrop, $inpOrdAdr);
        $d = new GenRmkOfOrd($inpRmk_OfAdr, $inpDrop, $inpOrd);
        $e = new GenBarCd($tripDelvDte, $tripNo);
        $f = new GenPng($con, $inpContent, $tripPth);

        $a->gen($tripPth);
        $b->gen($tripPth);
        $c->gen($tripPth);
        $d->gen($tripPth);
        $e->gen($tripPth);
        $f->gen($tripPth);
    }

    function getTripId()
    {
        $isVdt_trip = function ($trip) {
            $con = db_con();
            return runsql_isAny($con, "select trip from trip where trip=$trip");
        };

        $trip = null;
        if (isset($_SERVER['HTTP_HOST'])) {
            if (!isset($_REQUEST['trip'])) {
                echo "?trip= not set";
                return null;
            }
            $trip = $_REQUEST['trip'];
            if (!\LoadSheet\isVdt_trip($trip)) {
                echo "$trip <== not found<br>";
                return null;
            }
            return $trip;
        }
        $argv = $_SERVER['argv'];
        foreach ($argv as $idx => $arg) {
            if ($arg === '--trip') {
                return $argv[$idx + 1];
            }
        }
        echo "--trip is not given\n";
        return null;
    }
}

class GenBarCd
{
    private $tripDelvDte, $tripNo;

    function __construct($tripDelvDte, $tripNo)
    {
        $this->tripDelvDte = $tripDelvDte;
        $this->tripNo = $tripNo;
    }

    function gen($oupPth)
    {
        $file = $oupPth . 'barCd.png';
        $s = $this->tripDelvDte . ' ' . sprintf("%3d", $this->tripNo);
        $m = new \BarCd($s);
        $m->save_file($file);
    }
}

class GenRmkOfAdr
{
    /** @var array "$cus,$instLin" each ordAdrId, one record in $oupAy */
    public $oupAy; // "$cus,$instLin"  each ordAdrId, one record

    function __construct(
        $inpRmk_OfAdr,  // ordAdr instTxt
        $inpDrop,       // ordDrop | .. ordAdr ..
        $inpOrdAdr      // ordAdr | cusCd engShtNm chiShtNm adrCd adrNm
    )
    {
        $dta = dta_joinLine_byKey($inpRmk_OfAdr, "ordAdr", "instTxt", "(*) ");
        $dic = rmkNo_OfOrdAdr($inpDrop);
        $ordAdr_cus_adr = GenRmkOfAdr::ordAdr_cus_adr($inpOrdAdr);
        $o = array_fill(0, sizeof($dic) - 1, null);
        foreach ($dta as $dr) {
            $ordAdr = $dr['ordAdr'];
            $instTxt = $dr['instTxt'];
            $no = $dic[$ordAdr];
            $a = $ordAdr_cus_adr[$ordAdr];
            $cus = $a['cus'];
            $adr = $a['adr'];
            $o[$no - 1] = "$cus,$adr,$instTxt";
        }
        $this->oupAy = $o;
    }

    /** return $ordAdr => [$cus,$adr] from [ordAdr | cusCd engShtNm chiShtNm adrCd adrNm] */
    static function ordAdr_cus_adr($inpOrdAdr)
    {
        $o = [];
        foreach ($inpOrdAdr as $dr) {
            $ordAdr = $dr['ordAdr'];
            $cusCd = $dr['cusCd'];
            $engShtNm = $dr['engShtNm'];
            $chiShtNm = $dr['chiShtNm'];
            $adrCd = $dr['adrCd'];
            $adrNm = $dr['adrNm'];
            $cus = bld_cus($cusCd, $chiShtNm, $engShtNm);
            $adr = bld_adr($adrCd, $adrNm);
            $o[$ordAdr] = ['cus' => $cus, 'adr' => $adr];
        }
        return $o;
    }

    function gen($oupPth)
    {
        $file = $oupPth . 'rmk_OfAdr.txt';
        ay_write_file($this->oupAy, $file);
    }
}

class GenRmkOfOrd
{
    public $oupAy; // "$cus,$instLin"  : each ordId  one record

    function __construct(
        $inpRmk_OfOrd,  //  ord instTxt
        $inpDrop, // ordDrop | .. ord ..
        $inpOrd // ord | cusCd chiShtNm engShtNm
    )
    {
        $dta = dta_joinLine_byKey($inpRmk_OfOrd, "ord", "instTxt", "(*) ");
        $dic = rmkNo_OfOrd($inpDrop);
        $ord_cus = GenRmkOfOrd::ord_cus($inpOrd);
        $o = array_fill(0, sizeof($dic) - 1, null);
        foreach ($dta as $dr) {
            $ord = $dr['ord'];
            $instTxt = $dr['instTxt'];
            $no = $dic[$ord];
            $cus = $ord_cus[$ord];
            $o[$no - 1] = "$cus,$instTxt";
        }
        $this->oupAy = $o;
    }

    function ord_cus( // return ord => cus
        $inpOrd // ord | cusCd engShtNm chiShtNm
    )
    {
        $o = [];
        foreach ($inpOrd as $dr) {
            $ord = $dr['ord'];
            $cusCd = $dr['cusCd'];
            $engShtNm = $dr['engShtNm'];
            $chiShtNm = $dr['chiShtNm'];
            $o[$ord] = bld_cus($cusCd, $chiShtNm, $engShtNm);
        }
        return $o;
    }

    function gen($oupPth)
    {
        $file = $oupPth . 'rmk_OfOrd.txt';
        ay_write_file($this->oupAy, $file);
    }
}

class GenHdr
{
    private $ay;

    function __construct($inpTrip)
    {
        $a = $inpTrip;
        $driverTy = ($a->driverTy = 'INT') ? '司機' : '街車';
        $this->ay = ["tripDelvDte" => $a->dte . ' 行程#' . $a->tripNo,
            "driverTy" => $driverTy,
            "driver" => $a->driver,
            "leader" => $a->leader,
            "member" => $a->member];
    }

    function gen($oupPth)
    {
        ay_write_dic($this->ay, $oupPth . 'hdr.txt');
    }
}

class GenPng
{
    public $fmAy, $toAy;

    function __construct(
        $inpContent, // .. ordContent withImg ordNo ordDelvDte ..
        $tripPth)
    {
        foreach ($inpContent as $i) {
            {
                $ordContent = $i['ordContent'];
                $withImg = $i['withImg'];
                $ordNo = $i['ordNo'];
                $ordDelvDte = $i['ordDelvDte'];
            }
            if ($withImg === '0') continue;
            $fm = pngFile($ordContent, $ordNo, $ordDelvDte)['ffn'];
            if (is_file($fm)) {
                $to = $tripPth . $ordContent . '.png';
                array_push($this->fmAy, $fm);
                array_push($this->toAy, $to);
            } else
                echo "'Fm - file not exist, cannot copy [$fm]\n";
        }
    }

    function gen($oupPth)
    {
        foreach ($this->fmAy as $i => $fm) {
            $to = $this->toAy[$i];
            copy($fm, $to);
        }
    }
}

class GenDrop
{
    public $dropDta; // Adr AdrCd AdrContact AdrPhone Content Cus OrdBy OrdNo PagList Qty RmkOfAdr RmkOfOrd

    // above Lvs comes from genLoadSheet.xlsm->DropLinItmLvs

    function __construct($inpDrop,
                         $inpRmk_OfOrd,
                         $inpRmk_OfAdr,
                         $tripDelvDte,
                         $inpContent)
    {
        $o = [];
        $j = 0;
        $a = $inpDrop;
        $d1 = $this->dicOf_contentLines();
        $d2 = $this->dicOf_pageNoList($inpDrop);
        $d3 = $this->dicOf_rmkNoList($inpDrop, $inpRmk_OfOrd, $inpRmk_OfAdr);
        foreach ($inpDrop as $idx => $dr) {
            $ordDrop = $dr["ordDrop"];
            $ordAdr = $dr["ordAdr"];
            $ord = $dr["ord"];
            $ordDelvDte = $dr["ordDelvDte"];
            $ordNo = $dr["ordNo"];
            $cusCd = $dr["cusCd"];
            $engShtNm = $dr["engShtNm"];
            $chiShtNm = $dr["chiShtNm"];
            $ordBy = $dr["ordBy"];
            $adr = $dr["adr"];
            $adrContact = $dr["adrContact"];
            $adrPhone = $dr["adrPhone"];
            $nBox = $dr["nBox"];
            $nPallet = $dr["nPallet"];
            $nCBM = $dr["nCBM"];
            $nCage = $dr["nCage"];

            $m = [];
            // the order must follow!!!  because the xlsm read drop-NN.txt content in following order.
            $adrNo = 0;
            $m['ord'] = Fmt\fmt_dropKey($ordNo, $ordDelvDte, $tripDelvDte, $adrNo, $idx);
            $m['cus'] = $this->bld_cus($cusCd, $engShtNm, $chiShtNm);
            $m['adr'] = esc_lf($adr);
            $m['contentLines'] = @$d1[$ordDrop];
            $m['qty'] = $this->bld_qty($nBox, $nPallet, $nCBM, $nCage);
            $m['pagNoList'] = @$d2[$ordDrop];
            $m['ordBy'] = esc_lf($ordBy);
            $m['adrContact'] = $adrContact;
            $m['adrPhone'] = $adrPhone;
            $m['rmkNoList'] = @$d3[$ordDrop];

            $o[$j++] = $m;
        }
        $this->dta = $o;
    }

    function dicOf_contentLines()
    {
        return [];
    }

    function dicOf_pageNoList($inpDrop)
    {
        return [];
    }

    function dicOf_rmkNoList // return ordDrop => [*nn, @nn], where *nn is {OrdRmkIdx + 1},  @nn is {AdrRmkIdx + 1}
    ($inpDrop, // [ordDrop ordAdr ord ..]
     $inpRmkAy_OfOrd, // ord instTxt
     $inpRmkAy_OfAdr) // ordAdr instTxt
    {
        return [];
    }

    private function bld_qty($nBox, $nPallet, $nCBM, $nCage)
    {
        $o = [];
        if (trim($nBox) !== '') array_push($o, $nBox . ' 箱');
        if (trim($nPallet) !== '') array_push($o, $nPallet . '板');
        if (trim($nCBM) !== '') array_push($o, $nCBM . ' CBM');
        if (trim($nCage) !== '') array_push($o, $nCage . ' 籠');
        return join(' ', $o);
    }

    function gen($oupPth)
    {
        foreach ($this->dropDta as $idx => $dr) {
            $f = $this->dropFfn($idx + 1, $oupPth);
            ay_write_dic($dr, $f);
        }
    }

    function dropFfn($dropIdx, $outPth)
    {
        $n = sprintf("%02d", $dropIdx + 1);
        return $outPth . "drop-$n.txt";
    }
}

class Trip
{
    public
        $no,
        $nm,
        $pth;

    function __construct($tripDelvDte, $tripNo)
    {
        $this->no = $tripNo;
        $this->nm = $this->nm($tripDelvDte, $tripNo);
        $this->pth = QUEUE_PTH . $this->nm . "\\";
    }

    private function nm($tripDelvDte, $tripNo)
    {
        $i = $tripDelvDte;
        $a = date_parse($i);
        $y = $a['year'];
        $m = $a['month'];
        $d = $a['day'];
        return sprintf("Trip-%d-%02d-%02d#%03d", $y, $m, $d, $tripNo);
    }
}

class Inp
{
    public
        $content,  // ordContent | ord ordDelvDte ordNo cusCd contentRmk withImg
        $drop,  // ordDrop |  ordAdr ord ordDelvDte ordNo cusCd engShtNm chiShtNm ordBy adr adrContact adrPhone ordContentLvc nBox nPallet nCBM nCage trip
        $rmk_OfAdr, // ordAdr instTxt
        $rmk_OfOrd, // ord instTxt
        $trip, // trip | dte tripNo driver driverTy leader member truckCd plateNo
        $ord, // ord | cusCd engShtNm chiShtNm
        $ordAdr; // ordAdr | cusCd engShtNm chiShtNm adrCd adrNm

    function __construct($tripId, $con)
    {
        $dta = function ($nm, $trip, $con) {
            return \runsp_dta($con, "call loadsheet_$nm($trip)");
        };
        $dro = function ($nm, $trip, $con) {
            return \runsp_dro($con, "call loadsheet_$nm($trip)");
        };

        $this->rmk_OfAdr = $dta("rmk_OfAdr", $tripId, $con);
        $this->rmk_OfOrd = $dta("rmk_OfOrd", $tripId, $con);
        $this->drop = $dta("drop", $tripId, $con);
        $this->content = $dta("content", $tripId, $con);
        $this->trip = $dro("trip", $tripId, $con);
        $this->ord = $dta("ord", $tripId, $con);
        $this->ordAdr = $dta("ordAdr", $tripId, $con);
    }
}

class AttDta
{
    function attDta(
        $inpDrop, // ordDrop | .. ord contentNoLvc ordDelvDte
        $inpContent) // ord contentNo |  contentRmk withImg
        // return $ord+contentNo => attNo ordNo ordDelvDte contentNo || for only those withImg
    {
        // brw_dtaAy("inpDrop inpContent", $inpDrop, $inpContent);
        $withImgDic = [];        // $ord+$contentNo => 'withImg'
        foreach ($inpContent as $i) {
            list($ord, $contentNo, $withImg) = ay_extract($i, "ord contentNo withImg");
            if ($withImg === '1') {
                $withImgDic["$ord+$contentNo"] = 'withImg';
            }
        }

        $o = [];
        $attNo = 0;
        foreach ($inpDrop as $dr) {
            list($ord, $contentNoLvc, $ordDelvDte, $ordNo) = ay_extract($dr, "ord contentNoLvc ordDelvDte ordNo");
            if (trim($contentNoLvc) === '') continue;

            $contentNo_ay = preg_split("/,/", $contentNoLvc);
            foreach ($contentNo_ay as $contentNo) {
                $key = "$ord+$contentNo";
                if (array_key_exists($key, $withImgDic)) {
                    if (array_key_exists($key, $o)) {
                        $m = $o[$key];
                    } else {
                        $m = [];
                        $m['ordNo'] = $ordNo;
                        $m['contentNo'] = $contentNo;
                        $m['attNo'] = ++$attNo;
                        $m['ordDelvDte'] = $ordDelvDte;
                    }
                    $o[$key] = $m;
                }
            }
        }
        return $o;
    }

    function ordContent__contentLines__dic
    ($inpContent) // ord, contentNo, contentRmk;
    {
        $o = [];
        $a = $inpContent;
        $k_last = null;
        $ay = [];
        foreach ($a as $i) {
            list($ord, $contentNo, $contentRmk) = array_values($i);
            $k = "$ord+$contentNo";
            if (is_null($k_last)) {
                $ay = [$contentRmk];
                $k_last = $k;
                continue;
            }
            if ($k_last !== $k) {
                $o[$k_last] = join("\n", $ay);
                $k_last = $k;
                $ay = [$contentRmk];
                continue;
            }
            array_push($ay, $contentRmk);
        }
        if (!is_null($k_last))
            $o[$k_last] = join("\n", $ay);
        return $o;
    }

    function ordDrop_contentLines_dic(
        $inpDrop,  // ordDrop | ..ordContentLvc ord ..
        $ord_n_contentNo__contentLines__dic)
    {
        $o = [];
        $dic = $ord_n_contentNo__contentLines__dic;
        foreach ($inpDrop as $i) {
            list($ordDrop, $contentNoLvc, $ord) = ay_extract($i, "ordDrop contentNoLvc ord");
            if (!is_null($contentNoLvc)) {
                $linAy = [];
                $noAy = preg_split('/,/', $contentNoLvc);
                foreach ($noAy as $contentNo) {
                    $key = "$ord+$contentNo";
                    if (array_key_exists($key, $dic))
                        array_push($linAy, $dic[$key]);
                }
                $contentLines = join("\\n", $linAy);
                $o[$ordDrop] = $contentLines;
            }
        }
        return $o;
    }

    function ordDrop_pagNo($inpDrop, // ordDrop | .. ord contentNoLvc
                           $ord_n_contentNo__pagNo__dic)
    {
        $o = [];  // ordDrop pagNo |
        foreach ($inpDrop as $i) {
            list($ordDrop, $ord, $contentNoLvc) = ay_extract($i, ["ordDrop", "ord", "contentNoLvc"]);
            $contentNo_ay = preg_split('/,/', $contentNoLvc);
            foreach ($contentNo_ay as $contentNo) {
                $key = "$ord+$contentNo";
                $pagNo = @$ord_n_contentNo__pagNo__dic[$key];
                if (!is_null($pagNo))
                    array_push($o, [$ordDrop, $pagNo]);
            }
        }
        return $o;
    }

    function ordDrop_pagNoList_dic($ordDrop_pagNo)  // ordDrop pagNo_ay
    {
        if (false) {
            echo "inp: ordDrop pagNo |<br > ";
            var_dump($ordDrop_pagNo);
        }

        $o = [];
        foreach ($ordDrop_pagNo as $i) {
            list($ordDrop, $pagNo) = $i;
            $o[$ordDrop] =
                array_key_exists($ordDrop, $o)
                    ? $o[$ordDrop] . ", $pagNo"
                    : $pagNo;
        }
        return $o;
    }

    function ord_n_contentNo__pagNo__dic(
        $inpDrop, // ordDrop | .. ord contentNoLvc
        $inpContent)  // ord contentNo | contentRmk withImg
        // return only those ord+contactNo with image will be put in the dic
    {
        $o = [];
        $dic = []; // $dic = ord+contentNo => 1 for those ord+contentNo withImg.
        foreach ($inpContent as $i) {
            list($ord, $contentNo, $withImg) = ay_extract($i, "ord contentNo withImg");
            if ($withImg === '1')
                $dic["$ord+$contentNo"] = 1;
        }
        $pagNo = 0;
        foreach ($inpDrop as $i) {
            list($ord, $contentNoLvc) = ay_extract($i, ["ord", "contentNoLvc"]);
            $ay = preg_split('/,/', $contentNoLvc);
            foreach ($ay as $contentNo) {
                $key = "$ord+$contentNo";
                if (array_key_exists($key, $dic)) {  // $dic = ord+contentNo => 1 for those ord+contentNo withImg.
                    if (!array_key_exists($key, $o)) {
                        $o[$key] = ++$pagNo;
                    }
                }
            }
        }
        return $o;
    }
}

/** return ordAdr=>rmkNo by $inpDrop (ordDrop | .. ordAdr .. */
function rmkNo_OfOrdAdr($inpDrop)
{
    $o = [];
    $n = 0;
    foreach ($inpDrop as $dr) {
        $ordAdr = $dr['ordAdr'];
        if (!array_key_exists($ordAdr, $o)) {
            $o[$ordAdr] = ++$n;
        }
    }
    return $o;
}

/** return ord=>rmkNo by $inpDrop (ordDrop | .. ord .. */
function rmkNo_OfOrd($inpDrop)
{
    $o = [];
    $n = 0;
    foreach ($inpDrop as $dr) {
        $ord = $dr['ord'];
        if (!array_key_exists($ord, $o)) {
            $o[$ord] = ++$n;
        }
    }
    return $o;
}

function bld_cus($cusCd, $chiShtNm, $engShtNm)
{
    return trim($chiShtNm) !== '' ? $chiShtNm
        : (trim($engShtNm) !== '' ? $engShtNm
            : $cusCd);
}

function bld_adr($adrCd, $adrNm)
{
    $o = [];
    if ($adrCd !== '' && !is_null($adrCd)) array_push($o, $adrCd);
    if ($adrNm !== '' && !is_null($adrNm)) array_push($o, $adrNm);
    return join($o, "-");
}

function pngFile($ordContentId, $ordNo, $ordDelvDte)
{
    $_pth = function ($ordNoFmt, $ordDelvDte, $hom) {
        $a = date_parse($ordDelvDte);
        $y = $a['year'];
        $m = $a['month'];
        $d = $a['day'];
        $mm = sprintf("%2d", $m);
        $dd = sprintf("%2d", $d);

        return "{$hom}ordContent\\$y\\$mm\\$dd\\$ordNoFmt\\";
    };

    $_fn = function ($ordNoFmt, $ordDelvDte, $ordContentId) {
        return "Ord-$ordDelvDte #$ordNoFmt content-$ordContentId.png";
    };
    //$sql = "select ordNo, ordDelvDte from ord where ord in (select ord from ordcontent where ordContent=$ordContentId);";
    //list($ordNo, $ordDelvDte) = runsql_dr($con, $sql, MYSQLI_NUM);
    $ordNoFmt = sprintf("%04d", $ordNo);
    $hom = pth_norm(__DIR__ . "\\..\\ ..\\ ..\\ ..\\");
    $pth = $_pth($ordNoFmt, $ordDelvDte, $hom);
    $fn = $_fn($ordNoFmt, $ordDelvDte, $ordContentId);
    $ffn = $pth . $fn;

    $o = [];
    $o['hom'] = $hom;
    $o['pth'] = $pth;
    $o['fn'] = $fn;
    $o['ffn'] = $ffn;

    return $o;
}