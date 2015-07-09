<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 9:36
 */
include_once '..\phpFn\db.php';
$con = db_con();
$sql = "SELECT cusCd, inpCd, chiShtNm, engShtNm, isDea FROM cus WHERE NOT isDea ORDER BY cusCd, inpCd, engShtNm,chiShtNm";
$data = runsql_dta($con, $sql);
echo json_encode($data);
?>
