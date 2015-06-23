<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 8:25
 */
function logg($varNm, $val, $ft)
{
    $fd = fopen("c:/temp/$ft", "a");
    fwrite($fd, "\r\n-------------\r\n");
    fwrite($fd, $varNm . "\r\n");
    $o = print_r($val, true);
    fwrite($fd, $o);
    fclose($fd);
}

function updCus($cusDta)
{
    $con = db_con();
    $cus = $cusDta->cus;
    $adr = $cusDta->adr;
    logg("cusDta", $cusDta, "updCusDta.txt");
    updCus_cus($con, $cus);
    updCus_adr($con, $cus->cusCd, $adr);
}
function updCus_cus($con, $cus) {
    $o = $cus;
    $cusCd = $o->cusCd;
    $inpCd = $o->inpCd;
    $chiNm = $o->chiNm;
    $engNm = $o->engNm;
    $chiShtNm = $o->chiShtNm;
    $engShtNm = $o->engShtNm;

    $sql = "update cus set
inpCd = '$inpCd',
chiNm = '$chiNm',
engNm = '$engNm',
chiShtNm = '$chiShtNm',
engShtNm = '$engShtNm',
where cusCd='$cusCd'";
    runsql_exec($con, $sql);

    $adrAy = $cusDro->adr;
    updCusAdr($cusCd, $adrAy, $con);
}

function ayMinus(array $ay, $itm)
{
    $o = [];
    foreach ($ay as $i) {
        if ($i !== $itm) array_push($o, $i);
    };
    return $o;
}

function updCus_adr($cusCd, $adrAy, $con)
{
    $newDta = $adrAy;
    $oldDta = runsql_exec($con, "select * from cusAdr where cusCd='$cusCd';");

    $newAy = keyAy($newDta);
    $oldAy = keyAy($oldDta);
    $insAy = ayMinus($newAy, $oldAy);
    $dltAy = ayMinus($oldAy, $newAy);
    ins($con, $insAy, $newDta);
    dlt($con, $dltAy);
    upd($con, $oldAy, $newAy);
    return;
    function keyAy($dta)
    {
        $o = [];
        foreach ($dta as $i) {
            array_push($o, $i['cusCd']);
        }
        return $o;
    }

    function ins($con, $insAy, $newAy)
    {
        foreach ($insAy as $i) {
            $cusAdr = $i['cusAdr'];
            $cusCd = $i['cusCd'];
            $adrCd = $i['adrCd'];
            $inpCd = $i['inpCd'];
            $adrNm = $i['adrNm'];
            $adr = $i['adr'];
            $contact = $i['contact'];
            $phone = $i['phone'];
            $regCd = $i['regCd'];
            $gpsX = $i['gpsX'];
            $gpsY = $i['gpsY'];
            $delvTimFm = $i['delvTimFm'];
            $delvTimTo = $i['delvTimTo'];
            $delvLasTim = $i['delvLasTim'];
            $truckTones = $i['truckTones'];
            $truckCold = $i['truckCold'];
            $truckFlat = $i['truckFlat'];
            $truckVan = $i['truckVan'];
            $truckClose = $i['truckClose'];
            $truckTail = $i['truckTail'];
            $truckUpstair = $i['truckUpstair'];
            $trickDispatchAtDoor = $i['trickDispatchAtDoor'];
            $truckByBox = $i['truckByBox'];
            $truckByPallet = $i['truckByPallet'];
            $truckLock = $i['truckLock'];
            $pickAdrCd = $i['pickAdrCd'];
            $rmk = $i['pickAdrCd'];

            $sql = "insert into cusAdr (cusCd, adrCd, inpCd, adrNm, adr, contact, phone, regCd, gpsX, gpsY,
delvTimFm, delvTimTo, delvLasTim,
truckTones, truckCold, truckFlat, truckVan, truckClose, truckTail, truckUpstair, trickDispatchAtDoor,
truckByBox, truckByPallet, truckLock, pickAdrCd, rmk)
values ('$cusCd', '$adrCd', '$inpCd', '$adrNm', '$adr', '$contact', '$phone', '$regCd', $gpsX, $gpsY,
$truckTones, b'$truckCold', b'$truckFlat', b'$truckVan', b'$truckClose', b'$truckTail', b'$truckUpstair', b'$trickDispatchAtDoor',
b'$truckByBox', b'$truckByPallet', b'$truckLock', '$pickAdrCd', '$rmk')";
            runsql_exec($con, $sql);
        }

    }

    function dlt($con, $dltAy)
    {
        foreach ($dltAy as $i) {
            $sql = "delete from cusAdr where cusAdr=$i limit 1;";
            runsql_exec($con, $sql);
        }
    }

    function upd($con, $updAy, $newAy)
    {
        foreach ($updAy as $i) {
            $sql = "update cusAdr set a where cusAdr=$i";
            runsql_exec($con, $sql);
        }

    }
}
