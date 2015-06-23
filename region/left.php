<?php
include_once '..\phpFn\db.php';
$con = db_con();
$sql = "SELECT regCd, inpCd, chiNm, engNm, isDea FROM region ORDER BY inpCd,regCd,engNm,chiNm";
$data = runsql_dta($con, $sql);
echo json_encode($data);
?>
