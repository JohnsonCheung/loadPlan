<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23/5/2015
 * Time: 9:31
 */
require_once '..\ay.php';
require_once '..\pth.php';
require_once '..\str.php';
function run()
{
    if (true) {
        test_ay_newByLpAp();


    } else {
        test_ay_newByLpAp();
        test_brw_ft();
        test_dtaAy_tmpPth1();
        test_dtaAy_tmpPth();
        test_push_noNull();
        test_dta_join();
        test_ay_extract();
        test_dta_dic();
        test_ay_pk();
        test_ay_firstKey();
    }
}

function test_ay_newByLpAp()
{
    $act = ay_newByLpAp("a b c", 1, 2, 3);
    $exp = [
        "a" => 1,
        "b" => 2,
        "c" => 3,
    ];
    assert($act === $exp);
    pass(__FUNCTION__);
}

function test_brw_ft()
{
    $ft = "c:/temp/a.txt";
    file_put_contents($ft, "test\r\nsdfsdf\r\n交貨\r\n");
    brw_ft($ft);
}

function test_brw_dtaAy()
{
    $dta1 = [[1, 2, 3, 91], [3, 4, 5, 61], [3, 4, 6, 21]];
    $dta2 = [[1, 2, 3, 92], [3, 4, 5, 62], [3, 4, 6, 22]];
    $dta3 = [[1, 2, 3, 93], [3, 4, 5, 63], [3, 4, 6, 23]];
    $pth = brw_dtaAy("dta1 dta2 dta3", $dta1, $dta2, $dta3);
    $a = 1;

}

function test_ay_pk()
{
    $o = [];
    array_push($o, ["aa" => 1, 'bb' => 1]);
    array_push($o, ["aa" => 2, 'bb' => 2]);
    array_push($o, ["aa" => 3, 'bb' => 3]);
    $act = ay_pk($o, "aa");
    assert(sizeof($act) == 3);
    assert(array_key_exists("1", $act));
    assert(array_key_exists("2", $act));
    assert(array_key_exists("3", $act));
    assert(json_encode($act['1']) == '{"aa":1,"bb":1}');
}

function test_dtaAy_tmpPth()
{
    $dta1 = [[1, 2, 3, 91], [3, 4, 5, 61], [3, 4, 6, 21]];
    $dta2 = [[1, 2, 3, 92], [3, 4, 5, 62], [3, 4, 6, 22]];
    $dta3 = [[1, 2, 3, 93], [3, 4, 5, 63], [3, 4, 6, 23]];
    $pth = dtaAy_tmpPth("dta1 dta2 dta3", $dta1, $dta2, $dta3);
    $a = 1;
}

function test_dtaAy_tmpPth1()
{
    $dta1 = [];
    $pth = dtaAy_tmpPth("dta1", $dta1);
    pth_opn($pth);
}

function test_dta_dic()
{
    $a = [
        ["a", "a1", "a2"],
        ["b", "b1", "b2"],
        ["c", "c1", "c2"]
    ];
    $act = dta_dic($a);
    $exp = ["a" => "a1", "b" => "b1", "c" => "c1"];
    assert($act === $exp);

    $a = [
        ["a" => "a1", "b" => "b1", "c" => "c1"],
        ["a" => "a2", "b" => "b2", "c" => "c2"],
        ["a" => "a3", "b" => "b3", "c" => "c3"]
    ];
    $act = dta_dic($a);
    $exp = ["a1" => "b1", "a2" => "b2", "a3" => "b3"];
    assert($act === $exp);
}

function test_ay_firstKey()
{
    $ay = ["k1" => 1, "k2" => 2];
    $act = ay_firstKey($ay);
    assert($act == "k1");

    $ay = [1, 2, 3];
    $act = ay_firstKey($ay);
    assert($act == "0");
}

function test_ay_extract()
{
    $ay = ["a" => "a1", "b" => "b1", "c" => "c11"];

    $act = ay_extract($ay, "a b");
    $exp = ["a1", "b1"];
    assert($act === $exp);

    $act = ay_extract($ay, "b a");
    $exp = ["b1", "a1"];
    assert($act === $exp);

    try {
        $act = ay_extract($ay, "x a");
        assert(false, "dta_extract");
    } catch (Exception $e) {
        // throw exception is expected
    }
}

function test_dta_join()
{
    $ay = [
        ["a", "b", "c"],
        ["1", "2", "3"]];
    $act = dta_join(",", $ay);
    $exp = ["a,b,c", "1,2,3"];
    assert($act === $exp);

    $act = dta_join(", ", $ay);
    $exp = ["a, b, c", "1, 2, 3"];
    assert($act === $exp);
}

function test_ay_push_noDup()
{
    $a = [];
    ay_push_noDup($a, 1);
    ay_push_noDup($a, 1);
    ay_push_noDup($a, 2);
    ay_push_noDup($a, 3);
    if (count($a) != 3) {
        throw new Exception();
    };
}

function test_push_noNull()
{
    $a = [1, 2, 3];
    push_noNull($a, null);
    push_noNull($a, 4);
    $exp = [1, 2, 3, 4];
    assert($exp === $a);

}

run();