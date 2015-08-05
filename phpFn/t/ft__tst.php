<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-29
 * Time: 15:49
 */
const p1 = __DIR__ . "/../../phpFn/";
require_once "PHPUnit.php";
require_once p1 . "test.php";
require_once p1 . "ft.php";

class ft__tst extends PHPUnit_TestCase
{
    function test_ft_brw()
    {
        $ft = "c:/temp/a.txt";
        file_put_contents($ft, "test\r\nsdfsdf\r\n交貨\r\n");
        ft_brw($ft);
    }

    function test_ft_ay()
    {
        $ft = tmpFt();
        $ay = ['aa', '街車'];
        ay_write_file($ay, $ft);
        $act = ft_ay($ft);
        $this->assertEquals($ay, $act);
        delete($ft);
    }
}

test("ft__tst");