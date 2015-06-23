<?php
include_once  "/../phpFn/db.php";
$con = db_con();
$regCd = isset($_SERVER['HTTP_HOST'])
    ? @$_GET['regCd']
    : 'reg1';
//logg($regCd);
$sql = "SELECT regCd, inpCd, chiNm, engNm, isDea, majRegCd FROM region where regCd='$regCd';";
$region = runsql_dro($con, $sql); // must use runsql_dro, cannot use runsql_dr

$sql = "SELECT a.nearBy, b.inpCd, b.chiNm, b.engNm, b.isDea, b.majRegCd
FROM nearBy a INNER JOIN region b on a.nearBy = b.regCd
WHERE a.regCd='$regCd';";
$nearBy = runsql_dta($con, $sql);
$o = ['region' => $region, 'nearBy' => $nearBy];
echo json_encode($o);

function logg($s)
{
    $fd = fopen("c:\\temp\\aa.txt", "a");
    fwrite($fd, "regCd=[$s]\r\n");
    fclose($fd);
}
