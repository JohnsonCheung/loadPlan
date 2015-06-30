<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 0:16
 */
$regCd = @$HTTP_RAW_POST_DATA;
if (is_null($regCd)) exit();
include_once '/../phpFn/db.php';
$con = db_con();
runsql_exec($con, "update region set isDea=b'1' where regCd= '$regCd'; ");
$con->close();
