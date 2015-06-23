<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 17/6/2015
 * Time: 21:05
 */
include_once "/../phpFn/db.php";
$con = db_con();
$regCd =@$HTTP_RAW_POST_DATA;
if (is_null($regCd)) exit();

$majRegCd = runsql_val($con, "select majRegCd from majreg limit 1");

$sql = "select regCd from region where regCd='$regCd';";
if (runsql_isAny($con, $sql)) {
    header('HTTP/1.1 202'); // already added
    exit();
}
runsql($con, "insert into region (regCd) values ('$regCd');");
if ($con->error) {
    header('HTTP/1.1 203'); // sql statement error
    echo $con->error;
    exit();
}
header('HTTP/1.1 201');
