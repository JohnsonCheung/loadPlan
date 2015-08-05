<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-29
 * Time: 15:51
 */
require_once 'PHPUnit.php';
function test($tstClsNm)
{
    $suite = new PHPUnit_TestSuite($tstClsNm);
    $result = PHPUnit::run($suite);
    echo $result->toString();
}

class PHPUnit_TestCase_ext extends PHPUnit_TestCase
{

    function assert_lines($lines1, $lines2)
    {
        $ay1 = split_lines($lines1);
        $ay2 = split_lines($lines2);
        $this->assert_ay($ay1, $ay2);
    }

    function assert_ay(array $ay1, array $ay2, $msg = "")
    {
        $n1 = sizeof($ay1);
        $n2 = sizeof($ay2);
        $this->assertEquals($n1, $n2, "size is diff: [$n1] / [$n2]");
        $i = 0;
        foreach ($ay1 as $k => $v) {
            $hasKey = array_key_exists($k, $ay2);
            $this->assertTrue($hasKey, "key[$k] in index[$i] of \$ay1 not found in \$ay2");
            $a1 = $ay1[$k];
            $a2 = $ay2[$k];
            $this->assertEquals($a1, $a2, "key[$k] idx[$i] are different");
            if (is_string($a1) && is_string($a2)) {
                $this->assertEquals(strlen($a1), strlen($a2), "length of item of key[$k] idx[$i] are different");
            }
            $i++;
        }
    }

    function assert_str($s1, $s2, $msg = null)
    {
        if (!is_null($msg)) $msg = "  msg=[$msg]";
        $n1 = strlen($s1);
        $n2 = strlen($s2);
        $this->assertEquals($n1, $n2, "strlen is diff: [$n1] / [$n2].");
        $i = 0;
        $this->assertEquals(rawurlencode($s1), rawurlencode($s2));
        if (false) {
            for ($i = 0; $i < $n1; $i++) {
                $a1 = $s1[$i];
                $a2 = $s2[$i];
                $this->assertEquals($a1, $a2, "Chr[$i] is diff [$a1]/[$a2]");
            }
        }
    }
}