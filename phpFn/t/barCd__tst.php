<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 30/5/2015
 * Time: 21:14
 */
include_once 'PHPUnit.php';
include_once '..\\barCd.php';

class BarCd__tst extends PHPUnit_TestCase
{
    function test_save_file()
    {
        $barCd = new BarCd("johnson");
        define("F", "c:\\temp\\barCode-Johnson.png");
        @unlink(F);
        assert(!is_file(F));
        $barCd->save_file(F);
        assert(is_file(F));
    }
}

$suite = new PHPUnit_TestSuite("BarCd__tst");
$result = PHPUnit::run($suite);
echo $result->toString();