<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 8:07
 */
include_once 'upd.php';
include_once '/../phpFn/db.php';
include_once '/../phpFn/ay.php';
include_once '/../objFn/obj.php';
test_updNearByAy();
test_updRegDro();
test_updRegDs();
function test_updRegDs() {
    pass(__FUNCTION__);
}
function test_updRegDro() {
    $con = db_con();
    $regDro =  obj_newByLpAp("regCd inpCd chiNm engNm majRegCd", 'reg1', 'inpCd1', 'chiNm1', 'engNm1', '路環 + 氹仔');

    runsql_exec($con, "Delete from nearBy where regCd='reg1' or nearBy='reg1'; ");
    runsql_exec($con, "Delete from region where regCd='reg1' limit 1; ");
    runsql_exec($con, "Insert into region (regCd) values ('reg1');");
    updRegDro($con, $regDro);
    $act = runsql_dr($con, "select * from region where regCd='reg1'; ");
    $exp = ay_newByLpAp('regCd engNm chiNm majRegCd', 'reg11','engNm','chiNm','路環 + 氹仔');
    assert($act === $exp);
    $con->close();
    pass(__FUNCTION__);
}
function test_updNearByAy() {
    $con = db_con();
    runsql_exec($con, "delete from nearBy where (regCd in ('reg1', 'reg2', 'reg3', 'reg4')) or (nearBy in ('reg1', 'reg2', 'reg3', 'reg4'));");
    runsql_exec($con, "delete from region where regCd in ('reg1', 'reg2', 'reg3', 'reg4');");

    runsql_exec($con, "insert into region (regCd) values ('reg1')");
    runsql_exec($con, "insert into region (regCd) values ('reg2')");
    runsql_exec($con, "insert into region (regCd) values ('reg3')");
    runsql_exec($con, "insert into region (regCd) values ('reg4')");

    $regCd = 'reg1';
    $nearByAy  =['reg2', 'reg3'];

    updNearByAy($con, $regCd, $nearByAy);

    assert(runsql_isAny($con, "select regCd from nearBy where regCd='reg1' and nearBy='reg2'; "));
    assert(runsql_isAny($con, "select regCd from nearBy where regCd='reg1' and nearBy='reg3'; "));

    assert(runsql_isAny($con, "select regCd from nearBy where regCd='reg2' and nearBy='reg1'; "));
    assert(runsql_isAny($con, "select regCd from nearBy where regCd='reg3' and nearBy='reg1'; "));

    assert(!runsql_isAny($con, "select regCd from nearBy where regCd='reg1' and nearBy='reg4'; "));
    assert(!runsql_isAny($con, "select regCd from nearBy where regCd='reg4' and nearBy='reg1'; "));

    pass(__FUNCTION__);
}

