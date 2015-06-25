<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/6/2015
 * Time: 18:47
 */
include_once "/../phpFn/cmn.php";
include_once '/../dbTools/addMissingNearBy.php';

class Upd
{
    private
        $msg_cannotBlank,
        $msg_notFind_InTblMajReg,
        $con,
        $nearByAy,
        $lang,
        $regCd,
        $inpCd,
        $majRegCd,
        $msg;


    function __construction($regDs)
    {
        $this->con = db_con();
        $this->nearByAy = $regDs->nearByAy;
        $this->lang = $regDs->lang;
        $a = $regDs->regDro;
        $this->regCd = $a->regCd;
        $this->inpCd = $a->inpCd;
        $this->majRegCd = $a->majRegCd;

        $pgmNm = "region";
        $secNm = "upd";
        $lang = $this->lang;
        $con = $this->con;
        $this->msg = msg_fldNm_msgNm($con, $pgmNm, $secNm, $lang);
    }

    function updRegDs()
    {
        $m1 = $this->m1();
        $m2 = $this->m2();
        list($m3Ay, $e3) = $this->m3Ay_e3();
        $e1 = !is_null($m1);
        $e2 = !is_null($m2);
        $e = $e1 || $e2 || $e3;
        $o["isEr"] = $e;
        if ($e1) $o["erMsg"]["regDro"]["inpCd"] = $m1;
        if ($e2) $o["erMsg"]["regDro"]["majRegCd"] = $m2;
        if ($e3) $o["erMsg"]["nearByAy"] = $m3Ay;
        if ($e) {
            echo json_decode($e);
            exit();
        }
    }

    function m1()
    {

        msg($con, $lang, "");
        return null;
    }

    function m2()
    {
        msg($con, $lang, "");
        return null;
    }

    function m3($nearBy)
    {
        if ($nearBy === '') return $this->msg_cannotBlank();
        if (!runsql_isAny($this->con, "select regCd from region where regCd='$nearBy'; ")) return $this->msg_notFound();
    }

    function m3Ay_e3()
    {
        $m3Ay = $this->m3Ay();
        $e3 = e3($m3Ay);
        return [$m3Ay, $e3];
    }

    function m3Ay()
    {
        $nearByAy = $this->nearByAy;

        $m3Ay = [];
        foreach ($nearByAy as $nearBy) {
            $m3 = m3($nearBy);
            array_push($m3Ay, $m3);
        }
        return $m3Ay;
    }

    function e3(array $m3Ay)
    {
        foreach ($m3Ay as $m3) {
            if (!is_null($m3)) return true;
        }
        return false;
    }

    function updRegDro($con, $regDro)
    {
        $o = $regDro;
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
        runsql_exec($con, $sql);
    }

    function updNearByAy($con, $regCd, $nearByAy)
    {
        $sql = "delete from nearBy where regCd='$regCd'";
        runsql_exec($con, $sql);
        foreach ($nearByAy as $nearBy) {
            $sql = "insert into nearBy (regCd, nearBy) values ('$regCd', '$nearBy'); ";
            runsql_exec($con, $sql);
        }
        addMissingNearBy();

    }
}

if (!isset($_SERVER['HTTP_HOST']))
    return;
if (isset($debug)) logFt("HTTP_RAW_POST_DATA", $HTTP_RAW_POST_DATA, "region_upd_php.txt");
$a = @$HTTP_RAW_POST_DATA;
if (is_null($a)) {
    echo 'No HTTP_RAW_POST_DATA';
    exit();
}
$con = db_con();
$regDro = json_decode($a);
updRegDs($con, $regDs);
?>

