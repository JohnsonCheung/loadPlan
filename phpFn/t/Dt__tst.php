<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-08-05
 * Time: 11:54
 */
include_once 'PHPUnit.php';
include_once 'Dt.php';
include_once 'str.php';
include_once 'test.php';

class Dt__tst extends PHPUnit_TestCase
{
    function test_byTxt()
    {
        $a = <<<DtTxt
*Dt AA
A | B | C
A1 | B1 | C1
A2 | B2 | C2
DtTxt;

        $m = Dt::byTxt($a);
        $this->assertEquals("AA", $m->dtNm);
        $this->assertEquals(3, $m->nFld());
        $this->assertEquals(2, $m->nRec());
        $this->assertEquals(['A' => 'A1', 'B' => 'B1', 'C' => 'C1'], $m->rec(0));
    }
}
test("Dt__tst");
