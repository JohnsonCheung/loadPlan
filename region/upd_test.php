<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 8:07
 */
include_once 'upd.php';
include_once '/../phpFn/cmn.php';
test_updNearByAy();
test_updRegDro();
test_updRegDs();
function test_updRegDs()
{
    pass(__FUNCTION__);
}

function test_updRegDro()
{
    $con = db_con();
    $regDro = obj_newByLpAp("regCd inpCd chiNm engNm majRegCd", 'reg1', 'inpCd1', 'chiNm1', 'engNm1', '路環 + 氹仔');

    runsql_exec($con, "DELETE FROM nearBy WHERE regCd='reg1' OR nearBy='reg1'; ");
    runsql_exec($con, "DELETE FROM region WHERE regCd='reg1' LIMIT 1; ");
    runsql_exec($con, "INSERT INTO region (regCd) VALUES ('reg1');");
    updRegDro($con, $regDro);
    $act = runsql_dr($con, "SELECT * FROM region WHERE regCd='reg1'; ");

    $exp = ay_newByLpAp('regCd inpCd chiNm engNm majRegCd isDea deaBy deaOn deaRmk', 'reg1', 'inpCd1', 'chiNm1', 'engNm1', '路環 + 氹仔', '0', null, null, null);
    var_dump($act, $exp);
    assert($act === $exp);
    $con->close();
    pass(__FUNCTION__);
}

function test_updNearByAy()
{
    $con = db_con();
    runsql_exec($con, "DELETE FROM nearBy WHERE (regCd IN ('reg1', 'reg2', 'reg3', 'reg4')) OR (nearBy IN ('reg1', 'reg2', 'reg3', 'reg4'));");
    runsql_exec($con, "DELETE FROM region WHERE regCd IN ('reg1', 'reg2', 'reg3', 'reg4');");

    runsql_exec($con, "INSERT INTO region (regCd) VALUES ('reg1')");
    runsql_exec($con, "INSERT INTO region (regCd) VALUES ('reg2')");
    runsql_exec($con, "INSERT INTO region (regCd) VALUES ('reg3')");
    runsql_exec($con, "INSERT INTO region (regCd) VALUES ('reg4')");

    $regCd = 'reg1';
    $nearByAy = ['reg2', 'reg3'];

    updNearByAy($con, $regCd, $nearByAy);

    assert(runsql_isAny($con, "SELECT regCd FROM nearBy WHERE regCd='reg1' AND nearBy='reg2'; "));
    assert(runsql_isAny($con, "SELECT regCd FROM nearBy WHERE regCd='reg1' AND nearBy='reg3'; "));

    assert(runsql_isAny($con, "SELECT regCd FROM nearBy WHERE regCd='reg2' AND nearBy='reg1'; "));
    assert(runsql_isAny($con, "SELECT regCd FROM nearBy WHERE regCd='reg3' AND nearBy='reg1'; "));

    assert(!runsql_isAny($con, "SELECT regCd FROM nearBy WHERE regCd='reg1' AND nearBy='reg4'; "));
    assert(!runsql_isAny($con, "SELECT regCd FROM nearBy WHERE regCd='reg4' AND nearBy='reg1'; "));

    pass(__FUNCTION__);
}

