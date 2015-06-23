<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/6/2015
 * Time: 20:07
 */
$p = "/../phpFn/";
include_once $p . 'db.php';
include_once $p . 'Cipher.php';
$x = new Cipher("1234567890");

$a = @$_COOKIE['a'];
/*
if(is_null($ab)) {
    echo 'a';
    exit();
}
$usrId = $x->decrypt($a);
*/
$pgmNm = $_REQUEST['pgmNm'];
$usrId = 'johnson';
$con = db_con();
//$sql = "select auth from auth where pgmNm='region' and usrId='$usrId';";
$sql = "select auth from auth where pgmNm='$pgmNm' and usrId='$usrId';";
echo runsql_val($con, $sql);
