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
    require_once "fmtkey.php";
    use LoadSheet\FmtKey as Fmt;

    $trip = getTrip();
    if (is_null($trip))
        echo "trip is not give\n";
    else {
        LoadSheet\gen($trip);
        echo "trip=" . $trip . "\n";
    }

    function getTrip()
    {
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
    function isVdt_trip($trip)
    {
        $con = db_con();
        return runsql_isAny($con, "select trip from trip where trip=$trip");
    }

    function gen($trip)
    {
        $con = db_con();
        $inpAdrRmkKey = z_runsp_dta("adrRmkKey", $trip, $con); // ordAdr ord adrNo
        $inpAdrRmkTxt = z_runsp_dta("adrRmkTxt", $trip, $con); // ordAdr rmkTxt
        $inpOrdRmkKey = z_runsp_dta("ordRmkKey", $trip, $con);  // ord ordDelvDte ordNo
        $inpOrdRmkTxt = z_runsp_dta("ordRmkTxt", $trip, $con); // ord inst
        $inpDrop = z_runsp_dta("drop", $trip, $con); // ordDrop | ord ordAdr cusCd shtNm ordBy adr adrContact contentNoLvc box pallet cbm
        $inpContent = z_runsp_dta("content", $trip, $con); // ord contentNo contentRmk withImg
        $inpTrip = z_runsp_dro("trip", $trip, $con); // trip dte tripNo driver driverTy leader member

        $tripDelvDte = $inpTrip->dte;
        $tripNo = $inpTrip->tripNo;
        $tripNm = tripNm($tripDelvDte, $tripNo);
        $tripPth = tripPth($tripNm);

        $hdrFile = $tripPth . "hdr.txt";
        $rmkFile = $tripPth . "rmk.txt";
        $barCdFile = $tripPth . "barCd.png";

        $hdrAy = Hdr\ay($inpTrip);

        $ord_rmkTxt_dic = Rmk\ord_RmkTxt_dic($inpOrdRmkTxt);
        $ord_rmkKey_dic = Rmk\ord_rmkKey_dic($inpOrdRmkKey, $tripDelvDte);
        $ordAdr_rmkKey_dic = Rmk\ordAdr_rmkKey_dic($inpAdrRmkKey, $ord_rmkKey_dic);
        $ordAdr_rmkTxt_dic = Rmk\ordAdr_rmkTxt_dic($inpAdrRmkTxt);

        $a = Rmk\ord_rmkNo_dic__and__ordAdr_rmkNo_dic($inpDrop, $ord_rmkTxt_dic, $ordAdr_rmkTxt_dic);
        $ord_rmkNo_dic = $a[0];
        $ordAdr_rmkNo_dic = $a[1];

        $rmkAy = Rmk\rmkAy(
            $ord_rmkNo_dic,
            $ord_rmkKey_dic,
            $ord_rmkTxt_dic,
            $ordAdr_rmkNo_dic,
            $ordAdr_rmkKey_dic,
            $ordAdr_rmkTxt_dic);

        $attDta = Att\attDta($inpDrop, $inpContent);
        $fmtoDta = Att\fmtoDta($attDta, $tripPth, $tripNm);

        $ord_n_contentNo__contentLines__dic = Att\ord_n_contentNo__contentLines__dic($inpContent);
        $ord_n_contentNo__pagNo__dic = Att\ord_n_contentNo__pagNo__dic($inpDrop, $inpContent);

        $ordDrop_contentLines_dic = Att\ordDrop_contentLines_dic($inpDrop, $ord_n_contentNo__contentLines__dic);
        $ordDrop_pagNo = Att\ordDrop_pagNo($inpDrop, $ord_n_contentNo__pagNo__dic);
        $ordDrop_pagNoList_dic = Att\ordDrop_pagNoList_dic($ordDrop_pagNo);
        $ordDrop_rmkNoList_dic = Rmk\ordDrop_rmkNoList_dic($inpDrop, $ord_rmkNo_dic, $ordAdr_rmkNo_dic);
        $dropDta = Drop\dropDta($inpDrop, $tripDelvDte,
            $ordDrop_contentLines_dic,
            $ordDrop_pagNoList_dic,
            $ordDrop_rmkNoList_dic);

        genPth($tripPth);
        genHdr($hdrAy, $hdrFile);
        genRmk($rmkAy, $rmkFile);
        genAtt($fmtoDta);
        genDrop($dropDta, $tripPth);
        genBarCd($tripDelvDte, $tripNo, $barCdFile);
    }

    function genBarCd($tripDelvDte, $tripNo, $file)
    {
        $s = $tripDelvDte . ' ' . sprintf("%3d", $tripNo);
        $m = new \BarCd($s);
        $m->save_file($file);
    }

    function genHdr(array $hdrAy, $file)
    {
        $a = ay_convert_encoding($hdrAy);
        ay_write_file($a, $file);
    }

    function genRmk(array $rmkAy, $file)
    {
        $a = ay_convert_encoding($rmkAy);
        ay_write_file($a, $file);
    }

    function genDrop(array $dropDta, $tripPth)
    {
        foreach ($dropDta as $dropIdx => $drop_dr) {
            $f = Drop\z_drop_file($dropIdx, $tripPth);
            $a = ay_convert_encoding($drop_dr);
            ay_write_file($a, $f);
        }
    }

    function tripPth($tripNm)
    {
        return queuePth() . $tripNm . "\\";
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

    function z_runsp_dta($nm, $trip, $con)
    {
        return \runsp_dta($con, "call loadsheet_$nm($trip)");
    }

    function z_runsp_dro($nm, $trip, $con)
    {
        return \runsp_dro($con, "call loadsheet_$nm($trip)");
    }

    function queuePth()
    {
        return "c:\\xampp\\htdocs\\loadplan\\pgm\\loadsheet\\queue\\";
    }

    function genAtt($fmtoDta)
    {
        foreach ($fmtoDta as $i) {
            list($fm, $to) = $i;
            if (!is_file($fm))
                echo "'Fm - file not exist, cannot copy [$fm]\n";
            else
                copy($fm, $to);
        }
    }

    function genPth($tripPth)
    {
        pth_create_if_not_exist($tripPth);
        pth_clear_files($tripPth);
    }

    class Gen
    {
        public
            $inpAdrRmkKey,
            $inpAdrRmkTxt,
            $inpOrdRmkKey,
            $inpOrdRmkTxt,
            $inpDrop,
            $inpContent,
            $inpTrip,
            $tripDelvDte,
            $tripNo,
            $tripNm,
            $tripPth;
        public
            $rmkFile,
            $hdrFile,
            $barCdFile;
        private
            $trip,
            $con;

        function __construct($trip)
        {
            $this->trip = $trip;
            $this->con = \db_con();
            $con = $this->con;
            $inpAdrRmkKey = z_runsp_dta("adrRmkKey", $trip, $con);
            $inpAdrRmkTxt = z_runsp_dta("adrRmkTxt", $trip, $con);
            $inpOrdRmkKey = z_runsp_dta("ordRmkKey", $trip, $con);
            $inpOrdRmkTxt = z_runsp_dta("ordRmkTxt", $trip, $con);
            $inpDrop = z_runsp_dta("drop", $trip, $con); // ordDrop | ord ordAdr cusCd shtNm ordBy adr adrContact contentNoLvc box pallet cbm
            $inpContent = z_runsp_dta("content", $trip, $con); // ord contentNo contentRmk withImg
            $inpTrip = z_runsp_dro("trip", $trip, $con);
            $tripDelvDte = $inpTrip->dte;
            $tripNo = $inpTrip->tripNo;
            $tripNm = tripNm($tripDelvDte, $tripNo);
            $tripPth = tripPth($tripNm);
            $rmkFile = $tripPth . "rmk.txt";
            $hdrFile = $tripPth . "hdr.txt";
            $barCdFile = $tripPth . "barCd.png";

            $this->inpAdrRmkKey = $inpAdrRmkKey;
            $this->inpAdrRmkTxt = $inpAdrRmkTxt;
            $this->inpOrdRmkKey = $inpOrdRmkKey;
            $this->inpOrdRmkTxt = $inpOrdRmkTxt;
            $this->inpDrop = $inpDrop;
            $this->inpContent = $inpContent;
            $this->inpTrip = $inpTrip;
            $this->tripDelvDte = $tripDelvDte;
            $this->tripNo = $tripNo;
            $this->tripNm = $tripNm;
            $this->tripPth = $tripPth;
            $this->rmkFile = $rmkFile;
            $this->hdrFile = $hdrFile;
            $this->barCdFile = $barCdFile;
        }

        function gen()
        {
            $this->genPth();
            $this->genHdr();
            $this->genRmk();
            $this->genAtt();
            $this->genBarCd();
            $this->genDrop();
        }

        function genPth()
        {
            genPth($this->tripPth);
        }

        function genHdr()
        {
            genHdr($this->hdrAy(), $this->hdrFile);
        }

        function hdrAy()
        {
            return Hdr\ay($this->inpTrip);
        }

        function genRmk()
        {
            genRmk($this->rmkAy(), $this->rmkFile);
        }

        function rmkAy()
        {
            return Rmk\rmkAy(
                $this->ord_rmkNo_dic(),
                $this->ord_rmkKey_dic(),
                $this->ord_rmkTxt_dic(),
                $this->ordAdr_rmkNo_dic(),
                $this->ordAdr_rmkKey_dic(),
                $this->ordAdr_rmkTxt_dic());
        }

        function ord_rmkNo_dic()
        {
            return $this->ord_rmkNo_dic__and__ordAdr_rmkNo_dic()[0];
        }

        function ord_rmkNo_dic__and__ordAdr_rmkNo_dic()
        {
            return Rmk\ord_rmkNo_dic__and__ordAdr_rmkNo_dic($this->inpDrop, $this->ord_rmkTxt_dic(), $this->ordAdr_rmkTxt_dic());
        }

        function ord_rmkTxt_dic()
        {
            return Rmk\ord_RmkTxt_dic($this->inpOrdRmkTxt);
        }

        function ordAdr_rmkTxt_dic()
        {
            return Rmk\ordAdr_rmkTxt_dic($this->inpAdrRmkTxt);
        }

        function ord_rmkKey_dic()
        {
            return Rmk\ord_rmkKey_dic($this->inpOrdRmkKey, $this->tripDelvDte);
        }

        function ordAdr_rmkNo_dic()
        {
            return $this->ord_rmkNo_dic__and__ordAdr_rmkNo_dic()[1];
        }

        function ordAdr_rmkKey_dic()
        {
            return Rmk\ordAdr_rmkKey_dic($this->inpAdrRmkKey, $this->ord_rmkKey_dic());
        }

        function genAtt()
        {
            genAtt($this->fmtoDta());
        }

        function fmtoDta()
        {
            return Att\fmtoDta($this->attDta(), $this->tripPth, $this->tripNm);
        }

        function attDta()
        {
            return Att\attDta($this->inpDrop, $this->inpContent);
        }

        function genBarCd()
        {
            genBarCd($this->tripDelvDte, $this->tripNo, $this->barCdFile);
        }

        function genDrop()
        {
            genDrop($this->dropDta(), $this->tripPth);
        }

        function dropDta()
        {
            return Drop\dropDta($this->inpDrop, $this->tripDelvDte,
                $this->ordDrop_contentLines_dic(),
                $this->ordDrop_pagNoList_dic(),
                $this->ordDrop_rmkNoList_dic());
        }

        function ordDrop_contentLines_dic()
        {
            return Att\ordDrop_contentLines_dic($this->inpDrop, $this->ord_n_contentNo__contentLines__dic());
        }

        function ord_n_contentNo__contentLines__dic()
        {
            return Att\ord_n_contentNo__contentLines__dic($this->inpContent);
        }

        function ordDrop_pagNoList_dic()
        {
            return Att\ordDrop_pagNoList_dic($this->ordDrop_pagNo());
        }

        function ordDrop_pagNo()
        {
            return Att\ordDrop_pagNo($this->inpDrop, $this->ord_n_contentNo__pagNo__dic());
        }

        function ord_n_contentNo__pagNo__dic()
        {
            return Att\ord_n_contentNo__pagNo__dic($this->inpDrop, $this->inpContent);
        }

        function ordDrop_rmkNoList_dic()
        {
            return Rmk\ordDrop_rmkNoList_dic($this->inpDrop, $this->ord_rmkNo_dic(), $this->ordAdr_rmkNo_dic());
        }
    }
}
namespace LoadSheet\Att {
    function fmtoDta(
        $attDta,  // $ord+$contentNo => [attNo ordNo ordDelvDte contentNo]
        $tripPth, $tripNm)
    {
        $o = [];
        foreach ($attDta as $i) {
            list($attNo, $ordNo, $ordDelvDte, $contentNo) = ay_extract($i, "attNo ordNo ordDelvDte contentNo");
            $m = z_fm_to($attNo, $ordNo, $ordDelvDte, $contentNo, $tripPth, $tripNm);
            array_push($o, $m);
        }
        return $o;
    }

    function ordDrop_contentLines_dic(
        $inpDrop,  // ordDrop | .. contentNoLvc ord ..
        $ord_n_contentNo__contentLines__dic)
    {
        $o = [];
        $dic = $ord_n_contentNo__contentLines__dic;
        foreach ($inpDrop as $i) {
            list($ordDrop, $contentNoLvc, $ord) = ay_extract($i, ["ordDrop", "contentNoLvc", "ord"]);
            if (!is_null($contentNoLvc)) {
                $linAy = [];
                $noAy = split_lvc($contentNoLvc);
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

    function ord_n_contentNo__contentLines__dic($inpContent) // ord, contentNo, contentRmk;
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
            $ay = split_lvc($contentNoLvc);
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
        //brw_dtaAy("inpDrop inpContent", $inpDrop, $inpContent);
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

            $contentNo_ay = split_lvc($contentNoLvc);
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
            $contentNo_ay = split_lvc($contentNoLvc);
            foreach ($contentNo_ay as $contentNo) {
                $key = "$ord+$contentNo";
                $pagNo = @$ord_n_contentNo__pagNo__dic[$key];
                if (!is_null($pagNo))
                    array_push($o, [$ordDrop, $pagNo]);
            }
        }
        return $o;
    }

    function z_fm_to($_attNo, $_ordNo, $_ordDelvDte, $_contentNo, $tripPth, $tripNm)
    {
        $a = date_parse($_ordDelvDte);
        $y = $a['year'];
        $m = $a['month'];
        $d = $a['day'];
        $mFmt = sprintf("%2d", $m);
        $dFmt = sprintf("%2d", $d);

        $ordDelvDteFmt = sprintf('%d-%02d-%02d', $y, $m, $d);
        $ordNoFmt = sprintf('%04d', $_ordNo);
        $contentNoFmt = sprintf('%02d', $_contentNo);
        $attNoFmt = sprintf('%02d', $_attNo);
        $hom = pth_norm("{$tripPth}..\\ ..\\ ..\\ ..\\");

        $fmPth = "{$hom}ordContent\\$y\\$mFmt\\$dFmt\\";
        $fmFnn = "Ord-$ordDelvDteFmt#$ordNoFmt content-$contentNoFmt";
        $fmFn = "$fmFnn.png";
        $fm = "$fmPth{$fmFn}";

        $toPth = $tripPth;
        $fmFnn = strtolower($fmFnn);
        $toFn = "$tripNm att-$attNoFmt ($fmFnn).png";
        $to = "$toPth{$toFn}";
        return [$fm, $to];
    }
}

namespace LoadSheet\Hdr {
    function file($tripPth)
    {
        return $tripPth . "hdr . txt";
    }

    function ay($inpTrip)
    {
        $a = $inpTrip;
        if ($a->driverTy = 'INT')
            $driverTy = '司機';
        else
            $driverTy = '街車';
        return [
            "tripDelvDte" => $a->dte . ' 行程#' . $a->tripNo,
            "driverTy" => $driverTy,
            "driver" => $a->driver,
            "leader" => $a->leader,
            "member" => $a->member];
    }
}

namespace LoadSheet\Rmk {
    use LoadSheet\FmtKey as Fmt;

    function file($tripPth)
    {
        return $tripPth . "rmk . txt";
    }

    function rmkAy(
        $ord_rmkNo_dic,
        $ord_rmkKey_dic,
        $ord_rmkTxt_dic,
        $ordAdr_rmkNo_dic,
        $ordAdr_rmkKey_dic,
        $ordAdr_rmkTxt_dic)
        // return $rmkKey,$rmkTxt
        /*
        $rmkKey which is either [ord-Key] or [ordAdr-Key].<br>
            [ord-Key] is
                ord-NNN,    if M is zero
                ord-NNN(-M) if M is >0
                NNN is ord->ordNo,
                M is # of days diff between order's delivery and trip's delivery date.
                trip's delivery date is always >= order's delivery</li>
            [ordAdr-Key] is
                adr-NNN(-M)-AA
                AA is ordAdr->adrNo</li>

        $rmkTxt are lines separated by \n
            if is ordRmk, it is coming from ordInst
            if it is adrRmk, it is coming from ordAdr->delvRmk

         $ordDrop_rmkNo_dic</b> each drop line should have 0-2 rmkNo.  1st is ordRmk and 2nd is adrRmk.  It
         will be used to be printed in the drop-list.
         */
    {
        $dta = []; // rmkNo rmkTxt rmkKey
        foreach ($ord_rmkNo_dic as $ord => $rmkNo) {
            $rmkTxt = $ord_rmkTxt_dic[$ord];
            $rmkKey = $ord_rmkKey_dic[$ord];
            $dta[$rmkNo] = ['rmkNo' => $rmkNo, 'rmkTxt' => $rmkTxt, 'rmkKey' => $rmkKey];
        }

        foreach ($ordAdr_rmkNo_dic as $ordAdr => $rmkNo) {
            $rmkTxt = $ordAdr_rmkTxt_dic[$ordAdr];
            $rmkKey = $ordAdr_rmkKey_dic[$ordAdr];
            $dta[$rmkNo] = ['rmkNo' => $rmkNo, 'rmkTxt' => $rmkTxt, 'rmkKey' => $rmkKey];
        }
        ksort($dta, SORT_NUMERIC);
        $o = [];
        foreach ($dta as $i) {
            list(, $rmkTxt, $rmkKey) = array_values($i);
            array_push($o, "$rmkKey,$rmkTxt");
        }
        return $o; // $rmkKey,$rmkTxt
    }

    function ord_rmkTxt_dic($inpOrdRmkTxt)
    {
        $lastOrd = null;
        $o = [];
        $rmk = [];
        foreach ($inpOrdRmkTxt as $i) {
            list($ord, $inst) = array_values($i);
            if ($lastOrd == $ord)
                array_push($rmk, $inst);
            else {
                if ($lastOrd == null)
                    array_push($rmk, $inst);
                else
                    $o[$lastOrd] = join('\n', $rmk);
                $lastOrd = $ord;
            }
        }
        if ($lastOrd != null)
            $o[$lastOrd] = join('\n', $rmk);
        return $o;
    }

    function ord_rmkKey_dic(
        $inpOrdRmkKey, // ord ordDelvDte ordNo
        $tripDelvDte)
    {
        $o = [];
        foreach ($inpOrdRmkKey as $i) {
            list($ord, $ordDelvDte, $ordNo) = array_values($i);
            $rmkKey = Fmt\fmt_ordKey($ordNo, $ordDelvDte, $tripDelvDte);
            $o[$ord] = $rmkKey;
        }
        return $o;
    }

    function ordAdr_rmkKey_dic(
        $inpAdrRmkKey,   // ordAdr ord adrNo
        $ord_rmkKey_dic)
    {
        $o = [];
        foreach ($inpAdrRmkKey as $i) {
            list($ordAdr, $ord, $adrNo) = array_values($i);
            $adrNo = sprintf("%d", $adrNo);
            $rmkKey = $ord_rmkKey_dic[$ord] . " 地址#$adrNo";
            $o[$ordAdr] = $rmkKey;
        }
        return $o;
    }

    function ordAdr_rmkTxt_dic(
        $inpAdrRmkTxt) // ordAdr rmkTxt
    {
        $p = $inpAdrRmkTxt;
        $o = [];
        foreach ($p as $i) {
            list($ordAdr, $rmkTxt) = ay_extract($i, "ordAdr rmkTxt");
            $o[$ordAdr] = $rmkTxt;
        }
        return $o;
    }

    function ord_rmkNo_dic__and__ordAdr_rmkNo_dic(
        $inpDrop,
        $ord_rmkTxt_dic,
        $ordAdr_rmkTxt_dic)
    {
        $nm_ay = split_lvs("ord ordAdr");
        $ord_rmkNo_dic = [];
        $ordAdr_rmkNo_dic = [];
        $rmkNo = 0;
        foreach ($inpDrop as $i) {
            list($ord, $ordAdr) = ay_extract($i, $nm_ay);

            if (array_key_exists($ord, $ord_rmkTxt_dic))
                if (!array_key_exists($ord, $ord_rmkNo_dic))
                    $ord_rmkNo_dic[$ord] = ++$rmkNo;

            if (array_key_exists($ordAdr, $ordAdr_rmkTxt_dic))
                if (!array_key_exists($ordAdr, $ordAdr_rmkNo_dic))
                    $ordAdr_rmkNo_dic[$ordAdr] = ++$rmkNo;
        }
        return [$ord_rmkNo_dic, $ordAdr_rmkNo_dic];
    }

    function ordDrop_rmkNoList_dic(
        $inpDrop,  // ordDrop | ord ordAdr ...
        $ord_rmkNo_dic,
        $ordAdr_rmkNo_dic)
    {
        $o = [];
        $nm_ay = split_lvs("ordDrop ordAdr ord");
        foreach ($inpDrop as $i) {
            list($ordDrop, $ordAdr, $ord) = ay_extract($i, $nm_ay);
            $rmkNo_ay = [];
            if (array_key_exists($ord, $ord_rmkNo_dic))
                array_push($rmkNo_ay, $ord_rmkNo_dic[$ord]);
            if (array_key_exists($ordAdr, $ordAdr_rmkNo_dic))
                array_push($rmkNo_ay, $ordAdr_rmkNo_dic[$ordAdr]);
            $o[$ordDrop] = join(", ", $rmkNo_ay);
        }
        return $o;
    }
}

namespace LoadSheet\Drop {
    use LoadSheet\FmtKey as Fmt;

    function z_drop_file($dropIdx, $tripPth)
    {
        $n = sprintf("%02d", $dropIdx + 1);
        return $tripPth . "drop-$n.txt";
    }

    function dropDta(
        $inpDrop,
        $tripDelvDte,
        $ordDrop_contentLines_dic,
        $ordDrop_pagNoList_dic,
        $ordDrop_rmkNoList_dic)
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
    {
        $dropNo = 0;
        $o = [];
        foreach ($inpDrop as $drop_dr) {
            $m = z_drop_dr(++$dropNo, $drop_dr, $tripDelvDte,
                $ordDrop_contentLines_dic,
                $ordDrop_pagNoList_dic,
                $ordDrop_rmkNoList_dic);
            array_push($o, $m);
        }
        return $o;
    }

    function z_bld_qty($box, $pallet, $cbm)
    {
        $o = [];
        if (trim($box) !== '') array_push($o, $box . ' 箱');
        if (trim($pallet) !== '') array_push($o, $pallet . '板');
        if (trim($cbm) !== '') array_push($o, $cbm . ' CBM');
        return join(' ', $o);
    }

    function z_bld_cus($cusCd, $shtNm)
    {
        if ($shtNm !== '')
            return $shtNm;
        return $cusCd;
    }

    function z_drop_dr(
        $dropNo, $drop_dr, $tripDelvDte,
        $ordDrop_contentLines_dic,
        $ordDrop_pagNoList_dic,
        $ordDrop_rmkNoList_dic)
    {
        list($ordDrop,
            $adrNo, $ordNo,
            $cusCd, $shtNm,
            $adr, $adrContact,
            $box, $pallet, $cbm,
            $ordBy, $ordDelvDte) = ay_extract($drop_dr,
            "ordDrop"
            . "  adrNo ordNo"
            . " cusCd shtNm"
            . " adr adrContact"
            . " box pallet cbm"
            . " ordBy ordDelvDte");
        $o = [];
        // the order must follow!!!
        $o['ord'] = Fmt\fmt_dropKey($ordNo, $ordDelvDte, $tripDelvDte, $adrNo, $dropNo);
        $o['cus'] = z_bld_cus($cusCd, $shtNm);
        $o['adr'] = esc_lf($adr);
        $o['contentLines'] = @$ordDrop_contentLines_dic[$ordDrop];
        $o['qty'] = z_bld_qty($box, $pallet, $cbm);
        $o['pagNoList'] = @$ordDrop_pagNoList_dic[$ordDrop];
        $o['ordBy'] = esc_lf($ordBy);
        $o['adrContact'] = esc_lf($adrContact);
        $o['rmkNoList'] = @$ordDrop_rmkNoList_dic[$ordDrop];
        return $o;
    }
}

