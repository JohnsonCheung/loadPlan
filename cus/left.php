<?php
include_once '..\phpFn\db.php';
$con = db_con();
$sql = "SELECT cusCd, inpCd, chiShtNm, engShtNm, isDea FROM cus ORDER BY inpCd,cusCd,engShtNm,chiShtNm";
$data = runsql_dta($con, $sql);
echo json_encode($data);
?>
