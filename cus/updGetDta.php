<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 20:34
 */
include_once "/../phpFn/db.php";
if (isset($_SERVER['HTTP_HOST'])) {
    $cusCd = @$_REQUEST['cusCd'];
//logg($cusCd);
    $o = oneCus($cusCd);
    echo json_encode($o);
}

function oneCus($cusCd)
{
    $con = db_con();
    $sql = "SELECT * FROM cus where cusCd='$cusCd';";
    $cus = runsql_dro($con, $sql);
    $adr = oneCus_adr($con, $cusCd);
    $con->close();
    return ['cusDro' => $cus, 'adrDt' => $adr];
}


function oneCus_adr($con, $cusCd)
{
    $sql = "SELECT * from cusadr WHERE cusCd='$cusCd';";
    $dta = runsql_dta($con, $sql);
    return oneCus_adr_isRef($con, $dta);
}

function oneCus_adr_isRef($con, $dta)
{
    foreach ($dta as $idx => $dr) {
        $cusAdr = $dr['cusAdr'];
        $dta[$idx]['isRef'] = isRef($con, $cusAdr);
    }
    return $dta;
}

function isRef($con, $cusAdr)
{
    return runsql_isAny($con, "select cusAdr from ordAdr where cusAdr=$cusAdr limit 1");
}