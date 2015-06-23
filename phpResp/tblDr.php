<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 3:45
 */
$p = __DIR__ . '/../phpFn/';
include_once $p . 'db.php';
$t = $_GET['t'];
$f = $_GET['f'];
$v = $_GET['v'];
$con = db_con();
$sql = "select * from $t where $f='$v'";
echo json_encode(runsql_dro($con, $sql));
