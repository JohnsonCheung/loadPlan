<?php
include_once  "/../phpFn/db.php";
$con = db_con();
$cusCd = @$_REQUEST['cusCd'];
$o = db_cus($con, $cusCd);
echo json_encode($o);

function db_cus($con, $cusCd)
{
    $sql = "SELECT * FROM cus where cusCd='$cusCd';";
    $cus = runsql_dr($con, $sql);

    $sql = "SELECT * from cusAdr WHERE cusCd='$cusCd';";
    $adr = runsql_dta($con, $sql);
    return ['cus' => $cus, 'adr' => $adr];
}
