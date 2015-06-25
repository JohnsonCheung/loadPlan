<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 17/6/2015
 * Time: 15:56
 */
$p = __DIR__ . '/../phpFn/';
include_once $p . 'db.php';
$t = $_REQUEST['t'];
$f = $_REQUEST['f'];
$con = db_con();
$sql = "select $f from $t order by $f";
echo json_encode(runsql_dc($con, $sql));
