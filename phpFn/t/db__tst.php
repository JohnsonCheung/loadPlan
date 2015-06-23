<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23/5/2015
 * Time: 3:00
 */
function run()
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

include_once '..\db.php';
run();
