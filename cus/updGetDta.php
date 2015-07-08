<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 20:34
 */
include_once "/../phpFn/cmn.php";
if (isset($_SERVER['HTTP_HOST'])) {
    $cusCd = @$_REQUEST['cusCd'];
    $o = oneCus($cusCd);
    echo json_encode($o);
}

function oneCus($cusCd)
{
    $con = db_con();
    $sql = "SELECT * FROM cus WHERE cusCd='$cusCd';";
    $cusDr = runsql_dr($con, $sql);
    $isRef = runsql_isAny($con, "select cusCd from ord where cusCd='$cusCd' limit 1; ");
    $cusDr['isRef'] = $isRef;
    $cusDr['shwDlt'] = !$isRef;
    $adrDt = oneCus_adr($con, $cusCd);
    $con->close();
    return ['cusDro' => $cusDr, 'adrDt' => $adrDt];
}

function oneCus_adr($con, $cusCd)
{
    $sql = "SELECT * FROM cusadr WHERE cusCd='$cusCd';";
    $dta = runsql_dta($con, $sql);
    $boolLvs = "truckCold truckFlat truckVan truckClose truckTail truckUpstair truckDispatchAtDoor truckByBox truckByPallet truckLoc";
    $timLvs = "delvTimFm delvTimTo delvLasTim";
    foreach ($dta as $idx => $dr) {
        $cusAdr = $dr['cusAdr'];
        $dta[$idx] = ay_convert_bool($dta[$idx], $boolLvs);
        $dta[$idx] = ay_convert_tim($dta[$idx], $timLvs);
        $isRef = runsql_isAny($con, "select cusAdr from ordadr where cusAdr=$cusAdr limit 1");
        $dta[$idx]['isRef'] = $isRef;
        $dta[$idx]['shwDlt'] = !$isRef;
    }
    return $dta;
}
