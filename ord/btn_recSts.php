<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/6/2015
 * Time: 16:23
 */
include_once('../phpFn/db.php');
if (isset($_SERVER['HTTP_HOST'])) {
    $cusCd = @$_GET['cusCd'];
    if(is_null($cusCd)) {
        echo "{}";
    } else {
        //logg("cusCd", $cusCd, "btn_recSts.txt");
        $o = db_recSts($cusCd);
        echo json_encode($o);
    }
} else {
    var_dump(db_recSts('cus1'));
}

function db_recSts($cusCd)
{
    $con = db_con();
    $sql = "SELECT isDea FROM cus where cusCd='$cusCd';";
    if(!runsql_isAny($con, $sql)) return new stdclass;
    $isDea = runsql_val($con, $sql) ? true : false;
    $isRef = isRef($con, $cusCd);
    $con->close();
    return ['isDea' => $isDea, 'isRef' => $isRef];
}

function isRef($con, $cusCd)
{
    $sql = "select cusCd from ord where cusCd='$cusCd'";
    $a = runsql_isAny($con, $sql);
    if ($a) return true;

    $sql = "select cusCd from cusadr where cusCd='$cusCd'";
    $a = runsql_isAny($con, $sql);
    if ($a) return true;
    return false;
}
