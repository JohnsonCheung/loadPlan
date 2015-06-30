<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 21/5/2015
 * Time: 1:15
 */
function run()
{
    if (true) {
        fmtAy__tst();
        fmt__tst();
    } else {
        fmt__tst();
        is_intstr__tst();
        strbrk__tst();
        tim_stmp__tst();
    }
}

function fmtAy__tst()
{
    $act = fmtAy('a=[$a] b=[$b] a=[$a]', [1, 2]);
    assert($act === 'a=[1] b=[2] a=[1]');
    pass(__FUNCTION__);
}

function fmt__tst()
{
    $act = fmt('a=[$a] b=[$b] a=[$a]', 1, 2);
    assert($act === 'a=[1] b=[2] a=[1]');
    pass(__FUNCTION__);
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
    assert($act !== $act1);
}

require_once '..\str.php';
run();