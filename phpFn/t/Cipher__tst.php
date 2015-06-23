<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 9/6/2015
 * Time: 18:43
 */
include_once '/../str.php';
include_once '/../cipher.php';
run();
function run() {
    if(true) {
        test_encryt();
    }
}
function test_encryt()
{
    $a = "johnson";
    $b = encrypt($a);
    $c = decrypt($b);
    var_dump($b);
    assert($a === $c, "[after encrypt and decryt, the string should be same]");

    $a = "132";
    $b = encrypt($a);
    $c = decrypt($b);
    var_dump($b);
    assert($a === $c, "[after encrypt and decryt, the string should be same]");

    $a = "2";
    $b = encrypt($a);
    $c = decrypt($b);
    var_dump($b);
    assert($a === $c, "[after encrypt and decryt, the string should be same]");

    echo "pass ". __FUNCTION__;
}