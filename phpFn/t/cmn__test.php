<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23/6/2015
 * Time: 16:12
 */
include_once '/../cmn.php';
run();
function run() {
    run_str();
    run_pth();
    run_db();
}
function run_str()
{
    if (true) {
        is_intstr__tst();
    } else {
        is_intstr__tst();
        strbrk__tst();
        tim_stmp__tst();
    }
}

function pass($s)
{
    echo "pass : " . $s;
}

function is_intStr__tst()
{
    assert(is_intStr("1"));
    assert(!is_intStr("1.1"));
    assert(!is_intStr("1a"));
    assert(!is_intStr(" 1"));
    pass(__FUNCTION__);
}

function strbrk__tst()
{
    $a = "aa_bb";
    $exp = ["aa", "bb"];
    $act = strbrk($a, "_");
    assert($act === $exp);
}

function tim_stmp__tst()
{
    $act = tim_stmp();
    $act1 = tim_stmp();
    assert($act!==$act1);
}

function run_db()
{
    if(true) {
        runsql_isAny__tst();
        runsql_rs__tst();
        runsql_isAny__tst();
        runsp_dta__tst();
        runsp_rs__tst();

    } else {
        runsql_rs__tst();
        runsql_isAny__tst();
        runsp_dta__tst();
        runsp_rs__tst();
    }
}

function runsql_rs__tst()
{
    $con = db_con();
    $rs = runsql_rs($con, "select * from ordAdr");
    $a = 1;
}

function runsql_isAny__tst()
{
    $con = db_con();
    $sql = "select * from trip where trip=1";
    assert(runsql_isAny($con, $sql));

    $sql = "select * from trip where trip=1234567";
    assert(!runsql_isAny($con, $sql));
}

function runsp_dta__tst()
{
    $con = db_con();
    $dta = runsp_dta($con, "call loadsheet_trip(1)");
    $a = 1;
}

function runsp_rs__tst()
{
    $con = db_con();
    $rs = runsp_rs($con, "call loadsheet_trip(1)");
    $a = 1;
}
function run_pth()
{
    if (true) {
        pth_fnAy__tst();
    } else {
        pth_norm__tst();
    }
}

function pth_fnAy__tst()
{
    $pth = "c:\\CBETA\\";
    $a1 = pth_fnAy($pth);

    $pth = "c:\\CBETA";
    $a2 = pth_fnAy($pth);

    assert($a1 === $a2);
}


function pth_norm__tst()
{
    $a = "c:\\aa\\bb\\..\\a.txt";
    $act = pth_norm($a);
    $exp = "c:\\aa\\a.txt";
    assert($act === $exp);
}

function pth_tmp__tst()
{
    $seg = "aa";
    $act = pth_tmp("aa");
    $exp = "c:\\temp\\aa\\2015-";
    assert(is_pfx($act, $exp));
    assert(is_pth($act));
}


