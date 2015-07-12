<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 31/5/2015
 * Time: 4:22
 */
namespace {
    require_once 'db.php';
    require_once 'barCd.php';
    require_once "pth.php";
    require_once "ay.php";
    require_once "fmtkey.php";
    use LoadSheet\FmtKey as Fmt;
    const QUEUE_PTH = "c:/xampp/htdocs/loadplan/pgm/loadsheet/queue/";

    $trip = getTrip();
    if (is_null($trip))
        echo "trip is not give\n";
    else {
        LoadSheet\gen($trip);
        echo "trip=" . $trip . "\n";
    }

    function getTrip()
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

namespace LoadSheet {

    function gen($trip)
    {
        $con = db_con();
        $inpRmk_OfAdr = Gen\runsp_dta("rmk_OfAdr", $trip, $con); // ordAdr instTxt
        $inpRmk_OfOrd = Gen\runsp_dta("rmk_OfOrd", $trip, $con); // ord instTxt
        $inpDrop = Gen\runsp_dta("drop", $trip, $con); // ordDrop |  ordAdr ord ordDelvDte ordNo cusCd engShtNm chiShtNm ordBy adr adrContact adrPhone ordContentLvc nBox nPallet nCBM nCage trip
        $inpContent = Gen\runsp_dta("content", $trip, $con); // ordContent | ord ordDelvDte ordNo cusCd contentRmk withImg
        $inpTrip = Gen\runsp_dro("trip", $trip, $con); // trip | dte tripNo driver driverTy leader member truckCd plateNo

        $tripDelvDte = $inpTrip->dte;
        $tripNo = $inpTrip->tripNo;
        $tripNm = Gen\tripNm($tripDelvDte, $tripNo);
        $tripPth = Gen\tripPth($tripNm);

        $f1 = $tripPth . "hdr.txt";
        $f2 = $tripPth . "rmk_OfAdr.txt";
        $f3 = $tripPth . "rmk_OfOrd.txt";
        $f4 = $tripPth . "barCd.png";

        pth_create_if_not_exist($tripPth);
        pth_clear_files($tripPth);

        Hdr\wrt_hdrFile($f1, $inpTrip);
        Rmk\wrt_rmkFile_OfAdr($f2, $inpRmk_OfAdr, $inpDrop);
        Rmk\wrt_rmkFile_OfOrd($f3, $inpRmk_OfOrd, $inpDrop);
        BarCd\wrt_barCdFile($f4, $tripDelvDte, $tripNo);
        Drop\wrt_dropFile($inpDrop, $inpRmk_OfOrd, $inpRmk_OfAdr, $tripDelvDte, $inpContent, $tripPth);
        Png\cpy_pngFile($con, $inpContent, $tripPth);
    }
}
namespace LoadSheet\Gen {
    function tripPth($tripNm)
    {
        return QUEUE_PTH . $tripNm . "\\";
    }

    function tripNm($tripDelvDte, $tripNo)
    {
        $i = $tripDelvDte;
        $a = date_parse($i);
        $y = $a['year'];
        $m = $a['month'];
        $d = $a['day'];
        return sprintf("Trip-%d-%02d-%02d#%03d", $y, $m, $d, $tripNo);
    }

    function runsp_dta($nm, $trip, $con)
    {
        return \runsp_dta($con, "call loadsheet_$nm($trip)");
    }

    function runsp_dro($nm, $trip, $con)
    {
        return \runsp_dro($con, "call loadsheet_$nm($trip)");
    }
}
namespace LoadSheet\Fn {
    function wrt_ay(array $rmkAy, $file)
    {
        $a = ay_convert_encoding($rmkAy);
        ay_write_file($a, $file);
    }
}
namespace LoadSheet\BarCd {
    function wrt_barCdFile($file, $tripDelvDte, $tripNo)
    {
        $s = $tripDelvDte . ' ' . sprintf("%3d", $tripNo);
        $m = new \BarCd($s);
        $m->save_file($file);
    }
}
namespace LoadSheet\Png {
    function cpy_pngFile($con, $inpContent, $tripPth)
    {
        foreach ($inpContent as $i) {
            list($ordContent, $withImg) = ay_extract($i, "ordContent withImg");
            if ($withImg === '0') continue;
            $fm = \LoadSheet\OrdContent\ffn($con, $ordContent);
            if (!is_file($fm))
                echo "'Fm - file not exist, cannot copy [$fm]\n";
            else {
                $to = $tripPth . $ordContent . '.png';
                copy($fm, $to);
            }
        }
    }
}
namespace LoadSheet\Png\X {

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
}

namespace LoadSheet\OrdContent {
    function ffn($con, $ordContent)
    {
        $sql = "select ordNo, ordDelvDte from ord where ord in (select ord from ordcontent where ordContent=$ordContent);";
        list($ordNo, $ordDelvDte) = runsql_dr($con, $sql, MYSQLI_NUM);
        $ordNoFmt = sprintf("%04d", $ordNo);
        $tripPth = "";
        $pth = pth($ordNoFmt, $ordDelvDte, $tripPth);
        $fn = fn($ordNoFmt, $ordDelvDte, $ordContent);
        return $pth . $fn;
    }

    function pth($ordNoFmt, $ordDelvDte, $tripPth)
    {
        $a = date_parse($ordDelvDte);
        $y = $a['year'];
        $m = $a['month'];
        $d = $a['day'];
        $mm = sprintf("%2d", $m);
        $dd = sprintf("%2d", $d);
        $hom = pth_norm("{$tripPth}..\\ ..\\ ..\\ ..\\");
        return "{$hom}ordContent\\$y\\$mm\\$dd\\$ordNoFmt\\";
    }

    function fn($ordNoFmt, $ordDelvDte, $ordContent)
    {
        return "Ord-$ordDelvDte #$ordNoFmt content-$ordContent.png";
    }

}
namespace LoadSheet\Hdr {
    function wrt_hdrFile($file, $inpTrip)
    {
        $a = $inpTrip;
        if ($a->driverTy = 'INT')
            $driverTy = '司機';
        else
            $driverTy = '街車';
        $ay = [
            "tripDelvDte" => $a->dte . ' 行程#' . $a->tripNo,
            "driverTy" => $driverTy,
            "driver" => $a->driver,
            "leader" => $a->leader,
            "member" => $a->member];
        \LoadSheet\Fn\wrt_ay($ay, $file);
    }
}

namespace LoadSheet\Rmk {
    use LoadSheet\FmtKey as Fmt;

    function wrt_rmkFile_OfOrd()
    {

    }

    function wrt_rmkFile_OfAdr()
    {

    }

    function rmkAy_ofOrd  // return [ ord instTxt ]
    ($inpRmk_OfOrd /* [ ord instTxt ] */)
    {
        return dta_joinLine_byKey($inpRmk_OfOrd, "ord", "instTxt", "(*) ");
    }

    function rmkAy_ofAdr  // return [ ordAdr instTxt ]
    ($inpRmk_OfAdr /* [ ordAdr instTxt ] */)
    {
        return dta_joinLine_byKey($inpRmk_OfAdr, "ordAdr", "instTxt", "(*) ");
    }
}

namespace LoadSheet\Drop {
    use LoadSheet\Fn as Fn;

    function wrt_dropFile($inpDrop, $inpRmkAy_OfOrd, $inpRmkAy_OfAdr, $tripDelvDte, $inpContent, $tripPth)
    {
        $drop_file = function ($dropIdx, $tripPth) {
            $n = sprintf("%02d", $dropIdx + 1);
            return $tripPth . "drop-$n.txt";
        };

        $dropDta = Dta\dropDta($inpDrop, $inpRmkAy_OfOrd, $inpRmkAy_OfAdr, $tripDelvDte, $inpContent);
        foreach ($dropDta as $idx => $dr) {
            $f = $drop_file($idx, $tripPth);
            Fn\wrt_ay($dr, $f);
        }
    }
}
namespace LoadSheet\Drop\Dta {
    use LoadSheet\FmtKey as Fmt;

    function dropDta
// return dropDta [
//    ord
//    cus
//    adr
//    contentLines
//    qty
//    pagNoList
//    ordBy
//    adrContact
//    rmkNoList]
    ($inpDrop,
     $inpRmkAy_OfOrd,
     $inpRmkAy_OfAdr,
     $tripDelvDte)
    {
        $o = [];
        $j = 0;
        $a = $inpDrop;
        $d1 = Dic\dicOf_contentLines();
        $d2 = Dic\dicOf_pageNoList($inpDrop);
        $d3 = Dic\dicOf_rmkNoList($inpDrop, $inpRmkAy_OfOrd, $inpRmkAy_OfAdr);
        foreach ($inpDrop as $idx => $dr) {
            list($ordDrop, $ordAdr, $ord, $ordDelvDte, $ordNo,
                $cusCd, $engShtNm, $chiShtNm, $ordBy,
                $adr, $adrContact, $adrPhone,
                $nBox, $nPallet, $nCBM, $nCage) = ay_extract($dr,
                "ordDrop ordAdr ord ordDelvDte ordNo"
                . " cusCd engShtNm chiShtNm ordBy"
                . " adr adrContact adrPhone"
                . " nBox nPallet nCBM nCage");
            $m = [];
            // the order must follow!!!  because the xlsm read drop-NN.txt content in following order.
            $adrNo = 0;
            $m['ord'] = Fmt\fmt_dropKey($ordNo, $ordDelvDte, $tripDelvDte, $adrNo, $idx);
            $m['cus'] = Bld\bld_cus($cusCd, $engShtNm, $chiShtNm);
            $m['adr'] = esc_lf($adr);
            $m['contentLines'] = @$d1[$ordDrop];
            $m['qty'] = Bld\bld_qty($nBox, $nPallet, $nCBM, $nCage);
            $m['pagNoList'] = @$d2[$ordDrop];
            $m['ordBy'] = esc_lf($ordBy);
            $m['adrContact'] = $adrContact . '/' . $adrPhone;
            $m['rmkNoList'] = @$d3[$ordDrop];

            $o[$j++] = $m;
        }
        return $o;
    }
}
namespace LoadSheet\Drop\Dta\Dic {
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
}
namespace LoadSheet\Drop\Dta\Bld {
    function bld_qty($nBox, $nPallet, $nCBM, $nCage)
    {
        $o = [];
        if (trim($nBox) !== '') array_push($o, $nBox . ' 箱');
        if (trim($nPallet) !== '') array_push($o, $nPallet . '板');
        if (trim($nCBM) !== '') array_push($o, $nCBM . ' CBM');
        if (trim($nCage) !== '') array_push($o, $nCage . ' 籠');

        return join(' ', $o);
    }

    function bld_cus($cusCd, $chiShtNm, $engShtNm)
    {
        return trim($chiShtNm) !== '' ? $chiShtNm
            : trim($engShtNm) !== '' ? $engShtNm
                : $cusCd;
    }
}