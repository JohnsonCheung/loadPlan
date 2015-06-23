<?php
include_once  "/../phpFn/cmn.php";
$con = db_con();
$regCd = isset($_SERVER['HTTP_HOST'])
    ? @$_GET['regCd']
    : 'reg1';
$sql = "SELECT regCd, inpCd, chiNm, engNm, isDea, majRegCd FROM region where regCd='$regCd';";
$regDro = runsql_dro($con, $sql); // must use runsql_dro, cannot use runsql_dr

$sql = "SELECT a.nearBy, b.inpCd, b.chiNm, b.engNm, b.isDea, b.majRegCd
FROM nearBy a INNER JOIN region b on a.nearBy = b.regCd
WHERE a.regCd='$regCd';";
$nearByDt = runsql_dta($con, $sql);
$o = ['regDro' => $regDro, 'nearByDt' => $nearByDt];
echo json_encode($o);
