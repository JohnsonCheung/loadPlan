<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/6/2015
 * Time: 18:47
 */
include_once "/../phpFn/cmn.php";
include_once "/../phpFn/lbl.php";
include_once '/../dbTools/addMissingNearBy.php';

class Upd
{
    private
        $con,
        $nearByAy,
        $lang,
        $regDro,
        $regCd,
        $inpCd,
        $majRegCd;

    function __construct($regDs)
    {
        $this->con = db_con();
        $this->nearByAy = $regDs->nearByAy;
        $this->lang = $regDs->lang;
        $this->regDro = $regDs->regDro;
        $this->lblMsg = lblMsg("region", "upd", $this->lang, $this->con);
    }

    function updRegDs()
    {
        $m1 = $this->chkReq($this->regDro->inpCd);
        $m2 = $this->chkReq($this->regDro->majRegCd);
        $m3Ay = $this->m3Ay();
        if ($m1 || $m2 || $m3Ay) {
            if ($m1) $o["erMsg"]["regDro"]["inpCd"] = $m1;
            if ($m2) $o["erMsg"]["regDro"]["majRegCd"] = $m2;
            if ($m3Ay) $o["erMsg"]["nearByDt"] = $m3Ay;
            echo json_encode($o);
            return;
        }
        $this->updRegDro();
        $this->updNearByAy();
        $o["isOk"] = true;
        echo json_encode($o);
    }

    function chkReq($s)
    {
        if ($s === '') return $this->lblMsg['req'];
    }

    function m3($nearBy)
    {
        if ($nearBy === '') return $this->msg_cannotBlank();
        if (!runsql_isAny($this->con, "select regCd from region where regCd='$nearBy'; ")) return $this->msg_notFound();
    }

    function m3Ay()
    {
        $nearByAy = $this->nearByAy;
        $m3Ay = [];
        $isEr = false;
        foreach ($nearByAy as $nearBy) {
            $m3 = $this->m3($nearBy);
            array_push($m3Ay, $m3);
            if ($m3) $isEr = true;
        }
        if ($isEr) return $m3Ay;
    }

    function updRegDro()
    {
        $o = $this->regDro;
        $regCd = $o->regCd;
        $inpCd = $o->inpCd;
        $chiNm = $o->chiNm;
        $engNm = $o->engNm;
        $majRegCd = $o->majRegCd;

        $sql = "update region set
inpCd = '$inpCd',
chiNm = '$chiNm',
engNm = '$engNm',
majRegCd = '$majRegCd'
where regCd='$regCd'";
        runsql_exec($this->con, $sql);
    }

    function updNearByAy()
    {
        $regCd = $this->regDro->regCd;
        $sql = "delete from nearBy where regCd='$regCd' or nearBy='$regCd'; ";
        runsql_exec($this->con, $sql);
        foreach ($this->nearByAy as $nearBy) {
            $sql = "insert into nearBy (regCd, nearBy) values ('$regCd', '$nearBy'); ";
            runsql_exec($this->con, $sql);
        }
        addMissingNearBy();
    }
}

if (is_server()) {
    if (isset($debug)) logFt("HTTP_RAW_POST_DATA", $HTTP_RAW_POST_DATA, "region_upd_php.txt");
    $a = @$HTTP_RAW_POST_DATA;
    if (is_null($a)) {
        echo 'No post data';
        exit();
    }

    $regDs = json_decode($a);
    (new Upd($regDs))->updRegDs();
    exit();
}
$regDs = '{"regDro":{"regCd":"aaa1","inpCd":"sdf","chiNm":"sdf","engNm":"sdf","isDea":"0","majRegCd":"北區"},"nearByAy":[],"lang":"en"}';
//$regDs = '{"regDro":{"regCd":"aaa1","inpCd":"sdf","chiNm":"sdf","engNm":"sdf","isDea":"0","majRegCd":"北區"},"nearByAy":["aaa","aaa1"],"lang":"zh"}';
$regDs = json_decode($regDs);
(new Upd($regDs))->updRegDs();
?>

