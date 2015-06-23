<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 9:36
 */
include_once '..\phpFn\db.php';
$con = db_con();
$sql = "SELECT regCd, inpCd, chiNm, engNm, isDea FROM region WHERE NOT isDea ORDER BY inpCd,regCd,engNm,chiNm";
$data = runsql_dta($con, $sql);
echo json_encode($data);
?>
