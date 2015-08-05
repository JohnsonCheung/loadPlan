<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-08-05
 * Time: 11:54
 */
include_once 'PHPUnit.php';
include_once 'Ds.php';
include_once 'str.php';
include_once 'test.php';
class Ds__tst extends PHPUnit_TestCase
{
    function test_brw_dtaAy()
    {
        $dta1 = [[1, 2, 3, 91], [3, 4, 5, 61], [3, 4, 6, 21]];
        $dta2 = [[1, 2, 3, 92], [3, 4, 5, 62], [3, 4, 6, 22]];
        $dta3 = [[1, 2, 3, 93], [3, 4, 5, 63], [3, 4, 6, 23]];
        $pth = brw_dtaAy("dta1 dta2 dta3", $dta1, $dta2, $dta3);
        $a = 1;

    }

    function test_byTxt()
    {
        $txt = <<<TXT
*Ds dsNm

*Dt AA
A | B | C
A1 | B1 | C1
A2 | B2 | C2

*Dt BB
AA | BB | CC | DD
A1 | B1 | C1 | D1
A2 | B2 | C2 | D2
A3 | B3 | C3 | D3
TXT;

        $act = Ds::byTxt($txt);
        $this->assertEquals(2, $act->nDt(), "nDt");
        $this->assertEquals("dsNm", $act->dsNm, "dsNm");
        $this->assertEquals(['AA', 'BB'], $act->dtAn(), "dtAn()");
        $dtAy = $act->dtAy;
        $this->assertEquals(2, sizeof($dtAy));
        /** @var Dt $dt0 */
        $dt0 = $dtAy["AA"];
        $dt1 = $dtAy["BB"];

        $this->assertEquals('AA', $dt0->dtNm);
        $this->assertEquals(['A' => 'A1', 'B' => 'B1', 'C' => 'C1'], $dt0->rec0());
        $this->assertEquals(2, $dt0->nRec());
        $this->assertEquals(3, $dt0->nFld());
        $this->assertEquals('A B C', $dt0->fldNmLvs());
        $this->assertEquals(["A" => "A1", "B" => "B1", "C" => "C1"], $dt0->rec(0));
        $this->assertEquals(["A" => "A2", "B" => "B2", "C" => "C2"], $dt0->rec(1));

        $this->assertEquals('BB', $dt1->dtNm);
        $this->assertEquals(['AA' => 'A1', 'BB' => 'B1', 'CC' => 'C1', 'DD' => 'D1'], $dt1->rec0());
        $this->assertEquals(3, $dt1->nRec());
        $this->assertEquals(4, $dt1->nFld());
        $this->assertEquals('AA BB CC DD', $dt1->fldNmLvs());
        $this->assertEquals(["AA" => "A1", "BB" => "B1", "CC" => "C1", 'DD' => 'D1'], $dt1->rec(0));
        $this->assertEquals(["AA" => "A2", "BB" => "B2", "CC" => "C2", 'DD' => 'D2'], $dt1->rec(1));
        $this->assertEquals(["AA" => "A3", "BB" => "B3", "CC" => "C3", 'DD' => 'D3'], $dt1->rec(2));
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
}
test("Ds__tst");
