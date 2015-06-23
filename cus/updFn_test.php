<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 8:07
 */
include_once 'updFn.php';
include_once '/../phpFn/db.php';
test_cus();
function test_cus() {
    $con = db_con();
    runsql_exec($con, "delete from nearBy where regCd='reg1' and nearBy='reg4'; ");
    runsql_exec($con, "delete from nearBy where regCd='reg4' and nearBy='reg1'; ");
    runsql_exec($con, "delete from region where regCd='reg4'; ");
    runsql_exec($con, "insert into region (regCd, inpCd, engNm, chiNm) values ('reg4', 'a', 'engNm', 'chiNm'); ");
    runsql_exec($con, "insert into nearBy (regCd, nearBy) values ('reg1', 'reg4'); ");
    runsql_exec($con, "insert into nearBy (regCd, nearBy) values ('reg4', 'reg1'); ");

    $regCd = 'reg1';
    $nearByAy  =['reg2', 'reg3'];

    updNearBy($regCd, $nearByAy, $con);

    assert(runsql_isAny($con, "select regCd from nearBy where regCd='reg1' and nearBy='reg2'; "));
    assert(runsql_isAny($con, "select regCd from nearBy where regCd='reg1' and nearBy='reg3'; "));

    assert(runsql_isAny($con, "select regCd from nearBy where regCd='reg2' and nearBy='reg1'; "));
    assert(runsql_isAny($con, "select regCd from nearBy where regCd='reg3' and nearBy='reg1'; "));

    assert(!runsql_isAny($con, "select regCd from nearBy where regCd='reg1' and nearBy='reg4'; "));
    assert(!runsql_isAny($con, "select regCd from nearBy where regCd='reg4' and nearBy='reg1'; "));

    pass(__FUNCTION__);

}

function pass($s) {
    echo "pass: " . $s;
}