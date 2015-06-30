<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/6/2015
 * Time: 20:07
 */
include_once '/../phpFn/db.php';
include_once '/../phpFn/Cipher.php';

/*
$a = @$_COOKIE['a'];
$x = new Cipher("1234567890");
if(is_null($ab)) {
    echo 'a';
    exit();
}
$usrId = $x->decrypt($a);
*/
$pgmNm = $_REQUEST['pgmNm'];
$usrId = 'johnson';
auth($pgmNm, $usrId);
function auth($pgmNm, $usrId)
{
    $con = db_con();
    $sql = "select auth from auth where pgmNm='$pgmNm' and usrId='$usrId';";
    echo runsql_val($con, $sql);
    $con->close();
}