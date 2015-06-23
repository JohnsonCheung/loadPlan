<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23/6/2015
 * Time: 8:16
 */
include '/../phpFn/db.php';
$con = db_con();
$dta = runsql_dta($con, "SELECT regCd,nearBy FROM nearBy;");
foreach ($dta as $dr) {
    list($regCd, $nearBy) = ay_extract($dr, "regCd nearBy");
    if (!runsql_isAny($con, "select regCd from nearBy where regCd='$nearBy';")) {
        runsql($con, "insert into nearBy (regCd, nearBy) values('$nearBy', '$regCd');");
        echo $regCd," ", $nearBy;
    }
}
$con->close();