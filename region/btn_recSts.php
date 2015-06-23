<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/6/2015
 * Time: 16:23
 */
include_once('../phpFn/db.php');
if (isset($_SERVER['HTTP_HOST'])) {
    $regCd = @$_GET['regCd'];
    if(is_null($regCd)) {
        echo "{}";
    } else {
        //logg($regCd);
        $o = db_recSts($regCd);
        echo json_encode($o);
    }
} else {
    var_dump(db_recSts('內港碼頭'));
}
function logg($s)
{
    $fd = fopen("c:/temp/btn_recSts.txt", "a");
    fwrite($fd, "regCd=[$s]\r\n");
    fclose($fd);
}

function db_recSts($regCd)
{
    $con = db_con();
    $sql = "SELECT isDea FROM region where regCd='$regCd';";
    if(!runsql_isAny($con, $sql)) return new stdclass;
    $isDea = runsql_val($con, $sql) ? true : false;
    $isRef = isRef($con, $regCd);
    $con->close();
    return ['isDea' => $isDea, 'isRef' => $isRef];
}

function isRef($con, $regCd)
{
    $sql = "select regCd from nearBy where regCd='$regCd'";
    $a = runsql_isAny($con, $sql);
    if ($a) return true;

    $sql = "select nearBy from nearBy where nearBy='$regCd'";
    $a = runsql_isAny($con, $sql);
    if ($a) return true;

    $sql = "select regCd from cusadr where regCd='$regCd'";
    $a = runsql_isAny($con, $sql);
    if ($a) return true;

    $sql = "select nearBy from nearBy where regCd='$regCd'";
    $a = runsql_isAny($con, $sql);
    if ($a) return true;
    return false;
}
