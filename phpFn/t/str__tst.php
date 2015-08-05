<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 21/5/2015
 * Time: 1:15
 */

require_once 'PHPUnit.php';
require_once 'test.php';
require_once 'str.php';

class str__tst extends PHPUnit_TestCase
{
    function test_esc_dblQuote()
    {
        $a = "a";
        $act = esc_dblQuote($a);
        $exp = '"a"';
        $this->assertEquals($exp, $act);

        $a = 'a"b';
        $act = esc_dblQuote($a);
        $exp = '"a\'b"';
        $this->assertEquals($exp, $act);


    }

    function test_fmt()
    {
        $act = fmt('a=[$a] b=[$b] a=[$a]', 1, 2);
        assert($act === 'a=[1] b=[2] a=[1]');
    }

    function test_fmtAy()
    {
        $act = fmtAy('a=[$a] b=[$b] a=[$a]', [1, 2]);
        assert($act === 'a=[1] b=[2] a=[1]');
    }

    function test_is_intStr()
    {
        assert(is_intStr("1"));
        assert(!is_intStr("1.1"));
        assert(!is_intStr("1a"));
        assert(!is_intStr(" 1"));
    }

    function test_rmv_pfx()
    {
        $act = rmv_pfx("aa bb", "aa ");
        $this->assertEquals("bb", $act);
    }

    function test_rmv_sfx()
    {
        $act = rmv_sfx("aa bb", " bb");
        $exp = "aa";
        $this->assertEquals($exp, $act);

        $act = rmv_sfx("aa bb", "bb");
        $exp = "aa ";
        $this->assertEquals($exp, $act);

    }

    function test_space()
    {
        $this->assertEquals('  ', space(2));
        $this->assertEquals('', space(0));
    }

    function test_split_lines()
    {
        $lines = "aaa\r\nbbb";
        $act = split_lines($lines);
        $exp = ['aaa', 'bbb'];
        $this->assertEquals($exp, $act);

        $lines = "aaa\nbbb";
        $act = split_lines($lines);
        $exp = ['aaa', 'bbb'];
        $this->assertEquals($exp, $act);

        $lines = "aaa\rbbb";
        $act = split_lines($lines);
        $exp = ["aaa\rbbb"];
        $this->assertEquals($exp, $act);
    }

    function test_split_vbar()
    {
        $s = "a | b | c ||";
        $act = split_vbar($s);
        $exp = ["a", "b", "c", "", ""];
        $this->assertEquals($exp, $act);
    }

    function test_str_align()
    {
        $s = "aa";
        $wdt = 4;
        $align = "r";
        $act = str_align($s, $wdt, $align);
        $exp = "  aa";
        $this->assertEquals($exp, $act);
    }

    function test_strbrk()
    {
        $a = "aa_bb";
        $exp = ["aa", "bb"];
        $act = strbrk($a, "_");
        assert($act === $exp);
    }

    function test_strbrk2()
    {
        $s = "a:1";
        $act = strbrk2($s, ":");
        $exp = ["a", "1"];
        $this->assertEquals($exp, $act);

        $s = "a";
        $act = strbrk2($s, ":");
        $exp = ["", "a"];
        $this->assertEquals($exp, $act);
    }

    function test_tim_stmp()
    {
        $act = tim_stmp();
        $act1 = tim_stmp();
        assert($act !== $act1);
    }
}

test("str__tst");