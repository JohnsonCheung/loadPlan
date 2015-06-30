<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 0:16
 */
$regCd = @$HTTP_RAW_POST_DATA;
if (is_null($regCd)) exit();
echo "regCd=" . $regCd;
include_once '/../phpFn/db.php';
$con = db_con();
runsql_exec($con, "delete from region where regCd= '$regCd'; ");
$con->close();

