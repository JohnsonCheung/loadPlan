<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 0:16
 */
$cusCd = @$HTTP_RAW_POST_DATA;
if (is_null($cusCd)) exit();
include_once '/../phpFn/db.php';
$con = db_con();
runsql_exec($con, "update cus set isDea=b'0' where cusCd= '$cusCd'; ");
$con->close();
