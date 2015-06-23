<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 8:25
 */
function logg($s)
{
    $fd = fopen("c:/temp/aa.txt", "a");
    fwrite($fd, "\r\n-------------\r\n");
    $o = print_r($s, true);
    fwrite($fd, $o);
    fclose($fd);
}

function upd($regDro)
{
    $con = db_con();
    logg($regDro);
    $o = $regDro->region;
    $regCd = $o->regCd;
    $inpCd = $o->inpCd;
    $chiNm = $o->chiNm;
    $engNm = $o->engNm;
    $majRegCd = $o->majRegCd;

    $sql = "update region set
inpCd = '$inpCd',
chiNm = '$chiNm',
engNm = '$engNm',
majRegCd = '$majRegCd'
where regCd='$regCd'";
    runsql_exec($con, $sql);

    $nearByAy = $regDro->nearByAy;
    updNearby($regCd, $nearByAy, $con);
}

function ayMinus(array $ay, $itm)
{
    $o = [];
    foreach ($ay as $i) {
        if ($i !== $itm) array_push($o, $i);
    };
    return $o;
}

function updNearBy($regCd, $nearByAy, $con)
{
    $sql = "delete from nearBy where regCd='$regCd'";
    runsql_exec($con, $sql);
    foreach ($nearByAy as $nearBy) {
        $sql = "insert into nearBy (regCd, nearBy) values ('$regCd', '$nearBy'); ";
        runsql_exec($con, $sql);
    }

    $dta = runsql_dta($con, "SELECT * FROM nearBy");
    $set = $nearByAy;
    array_push($set, $regCd);
    foreach ($dta as $dr) {
        $a = $dr['regCd'];
        $b = $dr['nearBy'];
        $fnd = false;
        if (in_array($a, $set)) {
            if (in_array($b, $set)) {
                continue;
            }
        }
        runsql_exec($con, "delete from nearBy where regCd='$a' and nearBy='$b'; ");
        runsql_exec($con, "delete from nearBy where regCd='$b' and nearBy='$a'; ");
    }

    foreach ($nearByAy as $nearBy) {
        if (!runsql_isAny($con, "select regCd from nearBy where regCd='$nearBy' and nearBy='$regCd'; ")) {
            runsql_exec($con, "insert into nearBy (regCd, nearBy) value ('$nearBy', '$regCd');");
        }
    }
    logg($con->error);
}
