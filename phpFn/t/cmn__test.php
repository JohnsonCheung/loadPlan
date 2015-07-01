<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23/6/2015
 * Time: 16:12
 */
include_once '/../cmn.php';
A_run();
function A_run()
{
    if (true) {
        A_run_ay();
    } else {
        A_run_BldSql();
        A_run_ay();
        A_run_str();
        A_run_pth();
        A_run_db();
    }
}

function A_run_BldSql()
{
    if (true) {
        BldSql_updStmt__tst();
    } else {
        BldSql_updStmt__tst();
    }
}

function A_run_ay()
{
    if (true) {
        ay_convert_bool__tst();

    } else {
        ay_splice_assoc__tst();
        ay_key_idx__tst();
        ay_join_kv__tst();
        ay_zip__tst();
    }
}

function A_run_db()
{
    if (true) {
        runsql_dicDic__tst();
    } else {
        runsql_dicDic__tst();
        runsql_rs__tst();
        runsql_isAny__tst();
        runsp_dta__tst();
        runsp_rs__tst();
    }
}

function A_run_pth()
{
    if (true) {
        pth_fnAy__tst();
    } else {
        pth_norm__tst();
    }
}

function A_run_str()
{
    if (true) {
        is_intstr__tst();
    } else {
        is_intstr__tst();
        strbrk__tst();
        tim_stmp__tst();
    }
}

function BldSql_updStmt__tst()
{
    $dro = new stdClass();
    $dro->cusCd = "aa";
    $dro->chiNm = "aa";
    $dro->engNm = "aa";
    $dro->chiShtNm = "aa";
    $dro->engShtNm = "aa";
    $dro->cusTy = "aa";

    $m = new BldSql($dro, "cus", "cusCd chiNm engNm chiShtNm engShtNm cusTy rmk", "cusTy");
    $act = $m->updStmt();
    $exp = "update cus set chiNm='aa', engNm='aa', chiShtNm='aa', engShtNm='aa', cusTy='aa' where cusCd='aa';";
    assert($act === $exp);
    pass(__FUNCTION__);
}

function ay_join_kv__tst()
{
    $a = ['a' => 1, 'b' => 2];
    $act = ay_join_kv("=", $a);
    $exp = ["a=1", "b=2"];
    assert($act === $exp);
    pass(__FUNCTION__);
}

function ay_convert_bool__tst()
{
    $a = [
        'a' => 1,
        'b' => 0,
        'c' => '1',
        'd' => '0',
        'e' => 'aa',
        'f' => 'aa'];
    $act = ay_convert_bool($a, "a b c d");
    $exp = ['a' => true,
        'b' => false,
        'c' => true,
        'd' => false,
        'e' => 'aa',
        'f' => 'aa'];
    assert($act === $exp);
    pass(__FUNCTION__);
}

function ay_key_idx__tst()
{
    $a = ['a' => 1, 'b' => 2];
    $act = ay_key_idx($a, "a");
    $exp = 0;
    assert($act === $exp);

    $act = ay_key_idx($a, "b");
    $exp = 1;
    assert($act === $exp);

    $act = ay_key_idx($a, "c");
    $exp = null;
    assert($act === $exp);

    pass(__FUNCTION__);
}

function ay_splice_assoc__tst()
{
    $a = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];

    $m = $a;
    $act = ay_splice_assoc($m, 'b', 1);
    $exp = ['b' => 2];
    $exp1 = ['a' => 1, 'c' => 3, 'd' => 4, 'e' => 5];
    assert($act === $exp);
    assert($m === $exp1);

    $m = $a;
    $act = ay_splice_assoc($m, 'b', 2);
    $exp = ['b' => 2, 'c' => 3];
    $exp1 = ['a' => 1, 'd' => 4, 'e' => 5];
    assert($act === $exp);
    assert($m === $exp1);

    $m = $a;
    $act = ay_splice_assoc($m, 'b', 'b');
    $exp = ['b' => 2];
    $exp1 = ['a' => 1, 'c' => 3, 'd' => 4, 'e' => 5];
    assert($act === $exp);
    assert($m === $exp1);

    $m = $a;
    $act = ay_splice_assoc($m, 'b', 'c');
    $exp = ['b' => 2, 'c' => 3];
    $exp1 = ['a' => 1, 'd' => 4, 'e' => 5];
    assert($act === $exp);
    assert($m === $exp1);

    $m = $a;
    $act = ay_splice_assoc($m, 1, 1);
    $exp = ['b' => 2];
    $exp1 = ['a' => 1, 'c' => 3, 'd' => 4, 'e' => 5];
    assert($act === $exp);
    assert($m === $exp1);

    $m = $a;
    $act = ay_splice_assoc($m, 1, 2);
    $exp = ['b' => 2, 'c' => 3];
    $exp1 = ['a' => 1, 'd' => 4, 'e' => 5];
    assert($act === $exp);
    assert($m === $exp1);

    $m = $a;
    $act = ay_splice_assoc($m, 1, 'b');
    $exp = ['b' => 2];
    $exp1 = ['a' => 1, 'c' => 3, 'd' => 4, 'e' => 5];
    assert($act === $exp);
    assert($m === $exp1);

    $m = $a;
    $act = ay_splice_assoc($m, 1, 'c');
    $exp = ['b' => 2, 'c' => 3];
    $exp1 = ['a' => 1, 'd' => 4, 'e' => 5];
    assert($act === $exp);
    assert($m === $exp1);
}

function ay_zip__tst()
{
    $a1 = [1, 2, 3];
    $a2 = [7, 8, 9];
    $a3 = ['a', 'b', 'c'];

    $act = ay_zip(" . ", $a1, $a2);
    $exp = ['1.7', '2.8', '3.9'];
    assert($act === $exp);

    $act = ay_zip(" . ", $a1, $a2, $a3);
    $exp = ['1.7.a', '2.8.b', '3.9.c'];
    assert($act === $exp);

    $act = ay_zip(" = ", $a1, $a2, $a3);
    $exp = ['1= 7= a', '2= 8= b', '3= 9= c'];
    assert($act === $exp);

}

function is_intStr__tst()
{
    assert(is_intStr("1"));
    assert(!is_intStr("1.1"));
    assert(!is_intStr("1a"));
    assert(!is_intStr(" 1"));
    pass(__FUNCTION__);
}

function msg_fldNm_msgNm__tst()
{
    $con = db_con();
    $act = msg_fldNm_msgNm($con, "region", "upd", "en");

    $con->close();
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
    $a = "c:\\aa\\bb\\ ..\\a . txt";
    $act = pth_norm($a);
    $exp = "c:\\aa\\a . txt";
    assert($act === $exp);
}

function pth_tmp__tst()
{
    $seg = "aa";
    $act = pth_tmp("aa");
    $exp = "c:\\temp\\aa\\2015 - ";
    assert(is_pfx($act, $exp));
    assert(is_pth($act));
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

function runsql_dicDic__tst()
{
    $con = db_con();
    runsql_exec($con, "DROP TABLE IF EXISTS dicDic;");
    runsql_exec($con, "CREATE TABLE dicDic(k1 VARCHAR(10), k2 VARCHAR(10), v VARCHAR(10));");
    runsql_exec($con, "INSERT INTO dicDic(k1, k2, v) VALUES('a', 'a', 'a.a-value')");
    runsql_exec($con, "INSERT INTO dicDic(k1, k2, v) VALUES('a', 'b', 'a.b-value')");
    runsql_exec($con, "INSERT INTO dicDic(k1, k2, v) VALUES('b', '1', 'b.1-value')");
    runsql_exec($con, "INSERT INTO dicDic(k1, k2, v) VALUES('b', '2', 'b.2-value')");
    $act = runsql_dicDic($con, "SELECT k1,k2,v FROM dicDic;");
    runsql_exec($con, "DROP TABLE dicDic;");

    $exp['a']['a'] = 'a.a-value';
    $exp['a']['b'] = 'a.b-value';
    $exp['b']['1'] = 'b.1-value';
    $exp['b']['2'] = 'b.2-value';
    assert($act === $exp);
    pass(__FUNCTION__);
}

function runsql_isAny__tst()
{
    $con = db_con();
    $sql = "SELECT * FROM trip WHERE trip = 1";
    assert(runsql_isAny($con, $sql));

    $sql = "SELECT * FROM trip WHERE trip = 1234567";
    assert(!runsql_isAny($con, $sql));
}

function runsql_rs__tst()
{
    $con = db_con();
    $rs = runsql_rs($con, "SELECT * FROM ordAdr");
    $a = 1;
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
    assert($act !== $act1);
}
