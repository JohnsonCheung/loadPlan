<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 20/5/2015
 * Time: 16:04
 */
function run()
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

include "..\\pth.php";
run();
