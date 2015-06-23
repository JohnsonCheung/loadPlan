<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 17/6/2015
 * Time: 21:05
 */
include_once  "/../phpFn/db.php";
$con = db_con();
$cusCd = @$_REQUEST['cusCd'];
if (is_null($cusCd)) exit();
$sql = "select cusCd from cus where cusCd='$cusCd';";
if (runsql_isAny($con, $sql)) {
    header('HTTP/1.1 202');
    exit();
}
runsql($con, "insert into cus (cusCd) values ('$cusCd');");
if($con->error) {
    header('HTTP/1.1 203');
    echo $con->error;
    exit();
}
header('HTTP/1.1 201');