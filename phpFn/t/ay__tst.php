<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23/5/2015
 * Time: 9:31
 */
include_once 'ay.php';
include_once 'pth.php';
include_once 'str.php';
include_once 'test.php';
include_once 'PHPUnit.php';
class ay__tst extends PHPUnit_TestCase
{
    function test_ay_csvStr()
    {
        $ay = [1, 2, "a"];
        $act = ay_csvStr($ay);
        $exp = '1,2,"a"';
        $this->assertEquals($exp, $act);
    }

    function test_ay_dltEle()
    {
        $ay = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];
        $act = ay_dltEle($ay, 'b');
        $exp = ['a' => 1, 'c' => 3, 'd' => 4];
        assert($ay === ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]);  // orignal $ay will be not changed
        assert($act === $exp);

        $act = ay_dltEle($ay, 'a');
        $exp = ['b' => 2, 'c' => 3, 'd' => 4];
        assert($ay === ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]);  // orignal $ay will be not changed
        assert($act === $exp);

        $act = ay_dltEle($ay, 'd');
        $exp = ['a' => 1, 'b' => 2, 'c' => 3];
        assert($act === $exp);

        $ay = ['a', 'b', 'c', 'd'];
        $act = ay_dltEle($ay, 1);
        $exp = ['a', 'c', 'd'];
        assert($ay === ['a', 'b', 'c', 'd']); // orignal $ay will be not changed
        assert($act === $exp);

        $ay = ['a', 'b', 'c', 'd'];
        $act = ay_dltEle($ay, 0);
        $exp = ['b', 'c', 'd'];
        assert($ay === ['a', 'b', 'c', 'd']); // orignal $ay will be not changed
        assert($act === $exp);

        $ay = ['a', 'b', 'c', 'd'];
        $act = ay_dltEle($ay, 3);
        $exp = ['a', 'b', 'c'];
        assert($act === $exp);
    }

    function test_ay_eleLen()
    {
        $ay = ["aa", "a", "1", null, 3, new stdClass()];
        $act = ay_eleWdt($ay);
        $exp = [2, 1, 1, 0, 1, 0];
        $this->assertEquals($exp, $act);
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

    function test_ay_firstKey()
    {
        $ay = ["k1" => 1, "k2" => 2];
        $act = ay_firstKey($ay);
        assert($act == "k1");

        $ay = [1, 2, 3];
        $act = ay_firstKey($ay);
        assert($act == "0");
    }

    function test_ay_keyIdx()
    {
        $ay = ['a' => 'x', 'b' => 'y', 'c' => 'z', null => '9'];
        $this->assertEquals(0, ay_keyIdx($ay, 'a'));
        $this->assertEquals(1, ay_keyIdx($ay, 'b'));
        $this->assertEquals(2, ay_keyIdx($ay, 'c'));
        $this->assertEquals(-1, ay_keyIdx($ay, null));
        $this->assertEquals(3, ay_keyIdx($ay, ''));
        $this->assertEquals(-1, ay_keyIdx($ay, 'd'));
    }

    function test_ay_maxEle()
    {
        $a1 = [1, 2, 3];
        $a2 = [3, 2, 1];
        $act = ay_maxEle($a1, $a2);
        $exp = [3, 2, 3];
        $this->assertEquals($exp, $act);

        $a1 = [1, 2, 3];
        $a2 = [3, 2, 1, 4];
        $act = ay_maxEle($a1, $a2);
        $exp = [3, 2, 3, 4];
        $this->assertEquals($exp, $act);

        $a1 = [1, 2, 3, 4];
        $a2 = [3, 2, 1];
        $act = ay_maxEle($a1, $a2);
        $exp = [3, 2, 3, 4];
        $this->assertEquals($exp, $act);
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
    }

    function test_ay_newByLvs()
    {
        $act = ay_ByLvs("a:1 b:2 c:3");
        $exp = ["a" => "1", "b" => "2", "c" => "3"];
        $this->assertEquals($exp, $act);

        $act = ay_ByLvs("a b c");
        $exp = ["a", "b", "c"];
        $this->assertEquals($exp, $act);

        $act = ay_ByLvs("a:1 b:2 3");
        $exp = ["a" => "1", "b" => "2", 0 => "3"];
        $this->assertEquals($exp, $act);

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
        $this->assertEquals([1, 2, 3], $a);
    }

    function test_ay_rmvDup()
    {
        $act = ay_rmvDup([1, 2, 3, 2, 1]);
        $this->assertEquals([3, 2, 1], $act);
    }

    function test_ay_rmvDup_rev()
    {
        $act = ay_rmvDup_rev([1, 2, 3, 2, 1]);
        $this->assertEquals([1, 2, 3], $act);
    }

    function test_ay_subSet()
    {
        $ay = [0, 1, 2, 3, 4];
        $act = ay_subSet($ay, [0, 1, 4]);
        $exp = [0 => 0, 1 => 1, 4 => 4];
        $this->assertEquals($exp, $act);
    }

    function test_ay_valIdx()
    {
        $ay = ['a', 'b', 'c'];
        $this->assertEquals(0, ay_valIdx($ay, 'a'));
        $this->assertEquals(1, ay_valIdx($ay, 'b'));
        $this->assertEquals(2, ay_valIdx($ay, 'c'));
        $this->assertEquals(-1, ay_valIdx($ay, 'd'));
    }

    function test_push_noNull()
    {
        $a = [1, 2, 3];
        push_noNull($a, null);
        push_noNull($a, 4);
        $exp = [1, 2, 3, 4];
        $this->assertEquals($exp, $a);
    }
}

test('ay__tst');
