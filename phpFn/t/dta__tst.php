<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-30
 * Time: 10:07
 */
include_once 'PHPUnit.php';
include_once 'Dta.php';
include_once 'str.php';
include_once 'test.php';

class Dta__tst extends PHPUnit_TestCase_ext
{
    /** @var  Dta $tar */
    public $tar;

    function setup()
    {
        $dta = [
            ['a' => 'a1', 'b' => 'b1', 'c' => 'c1...', 'd' => 'd1'],
            ['a' => 'a2', 'b' => 'b2', 'd' => 'd2'],
            ['a' => 'a3', 'b' => 'b3..', 'c' => 'c3'],
            ['a' => 'a4.', 'b' => 'b4', 'e' => 'e4'],
        ];
        $this->tar = new Dta($dta);
    }

    function te1st_toCsvAy()
    {
        $a = "a|b|c
a1|b1|c1";
        $m = Dta::byTxt($a);
        $act = $m->toCsvAy();
        $exp = ["a,b,c",
            "a1,b1,c1"
        ];
        $this->assertEquals($exp, $act);

        $a = "a|b|c
a1|b1
|a2|b2";

        $m = Dta::byTxt($a);
        $act = $m->toCsvAy();
        $exp = [
            "a,b,c",
            "a1,b1,",
            ",a2,b2"
        ];
        $this->assertEquals($exp, $act);
    }

    function test_byTxt()
    {
        $a = <<<DtaTxt
A  | B  | C
A1 | B1 | C1
A2 | B2 | C2
DtaTxt;

        $m = Dta::byTxt($a);
        $this->assertEquals(3, $m->nFld());
        $this->assertEquals(2, $m->nRec());
        $this->assertEquals(['A' => 'A1', 'B' => 'B1', 'C' => 'C1'], $m->dr(0));
        $this->assertEquals(['A' => 'A2', 'B' => 'B2', 'C' => 'C2'], $m->dr(1));
        $this->assertEquals(ay_ByLvs('A:A1 B:B1 C:C1'), $m->dr(0));
        $this->assertEquals(ay_ByLvs('A:A2 B:B2 C:C2'), $m->dr(1));

    }

    function test_dta_joinLine_byKey()
    {
        $dta = [
            ["ord" => 1, "instTxt" => '1 Line1'],
            ["ord" => 1, "instTxt" => '1 Line2'],
            ["ord" => 1, "instTxt" => '1 Line3'],
            ["ord" => 1, "instTxt" => '1 Line4'],
            ["ord" => 2, "instTxt" => '2 Line1'],
            ["ord" => 2, "instTxt" => '2 Line2'],
            ["ord" => 9, "instTxt" => '9 Line1'],
        ];
        $pfx = "(*) ";
        $exp = [
            ["ord" => 1, 'instTxt' =>
                '(*) 1 Line1\n' .
                '1 Line2\n' .
                '1 Line3\n' .
                '1 Line4'],
            ["ord" => 2, 'instTxt' =>
                '(*) 2 Line1\n' .
                '2 Line2'],
            ["ord" => 9, 'instTxt' => '(*) 9 Line1'],
        ];

        $act = dta_joinLine_byKey($dta, "ord", "instTxt", "(*) ");
        $this->assertEquals($exp[0], $act[0], "idx-0");
        $this->assertEquals($exp[1], $act[1], "idx-1");
        $this->assertEquals($exp[2], $act[2], "idx-2");
    }

    function test_fldAn()
    {
        $a = $this->tar;
        $act = $a->fldAn();
        $exp = ["a", "b", "c", "d", "e"];
        $this->assertEquals($exp, $act);

    }

    function test_fldNmLvs()
    {
        $a = $this->tar;
        $act = $a->fldNmLvs();
        $exp = "a b c d e";
        $this->assertEquals($exp, $act);
    }

    function test_join()
    {

    }

    function test_nFld()
    {
        $a = $this->tar;
        $act = $a->nFld();
        $exp = 5;
        $this->assertEquals($exp, $act);

    }

    function test_nRec()
    {
        $a = $this->tar;
        $act = $a->nRec();
        $exp = 4;
        $this->assertEquals($exp, $act);
    }

    function test_new()
    {
        $a = [
            0 =>
                array(
                    'ordContent' => '1',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'contentRmk' => "sdf content Rmk 1
dkf sf
sdfsdf",
                    'withImg' => '1',
                ),
            1 =>
                array(
                    'ordContent' => '2',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'contentRmk' => 'sdf content Rmk 2',
                    'withImg' => '1',
                ),
            2 =>
                array(
                    'ordContent' => '3',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'contentRmk' => 'sdf content Rmk 3',
                    'withImg' => '0',
                ),
            3 =>
                array(
                    'ordContent' => '4',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'contentRmk' => 'sdf content Rmk 4',
                    'withImg' => '1',
                ),
            4 =>
                array(
                    'ordContent' => '5',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'contentRmk' => 'sdf content Rmk 5',
                    'withImg' => '1',
                ),
            5 =>
                array(
                    'ordContent' => '6',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'contentRmk' => 'sdf content Rmk 6',
                    'withImg' => '0',
                ),
        ];
        $m = new Dta($a);
        $act = $m->fldNmLvs();
        $exp = "ordContent ord ordDelvDte ordNo cusCd contentRmk withImg";
        $this->assertEquals($exp, $act);
    }

    function test_toFmt()
    {
        $a = $this->tar;
        $act = $a->toFmt();
        $exp = <<<EXP
a   | b    | c     | d  | e
a1  | b1   | c1... | d1 |
a2  | b2   |       | d2 |
a3  | b3.. | c3    |    |
a4. | b4   |       |    | e4
EXP;
        $exp = str_replace("\r\n", "\n", $exp);
        $this->assertEquals($exp, $act);
        $this->assert_lines($exp, $act);;
    }

    function test_toFmt__alignAy()
    {
        $fldAn = split_lvs("aa bb cc dd ee");
        $act = Dta::toFmt__alignAy("aa bb", "dd cc", $fldAn);
        $exp = [
            "aa" => "R",
            "bb" => "R",
            "cc" => "C",
            "dd" => "C",
            "ee" => "L"
        ];
        $this->assertEquals($exp, $act);


        $act = Dta::toFmt__alignAy("cc aa bb", "dd cc", $fldAn);
        $exp = [
            "cc" => "R",
            "aa" => "R",
            "bb" => "R",
            "dd" => "C",
            "ee" => "L"
        ];
        $this->assertEquals($exp, $act);
    }


    function test_toFmt__dr()
    {
        $dr = ay_ByLvs("a:1 b:2 c:3");
        $fldAn = ay_ByLvs("a b c d");
        $wdtAy = ay_ByLvs("a:3 b:3 c:3 d:3");
        $alignAy = ay_ByLvs("a:R b:C c:R d:L");
        $act = Dta::toFmt__dr($dr, $fldAn, $wdtAy, $alignAy);;
        $exp = "  1 |  2   |   3 |";
        $this->assertEquals(strlen($exp), strlen($act));
        $this->assertEquals($exp, $act);
    }

    function test_toFmt__hdrLin()
    {
        $fldAn = split_lvs("aaaa bbbb cccc dddd");
        $wdtAy = ay_byLvs("aaaa:5 bbbb:4 cccc:6 dddd:8");
        $alignAy = ay_byLvs("aaaa:L bbbb:L cccc:R dddd:C");
        $act = Dta::toFmt__hdrLin($fldAn, $wdtAy, $alignAy);
        $exp = "aaaa  | bbbb |   cccc |   dddd";
        $this->assertEquals($exp, $act);
    }

    function test_toHtml()
    {

    }

    function test_toTxt()
    {
        $a = $this->tar;
        $act = $a->toTxt();
        $exp = "a|b|c|d|e
a1|b1|c1...|d1|
a2|b2||d2|
a3|b3..|c3||
a4.|b4|||e4";

        $exp = str_replace("\r\n", "\n", $exp);
//        ['a' => 'a1', 'b' => 'b1', 'c' => 'c1...', 'd' => 'd1'],
//            ['a' => 'a2', 'b' => 'b2', 'd' => 'd2'],
//            ['a' => 'a3', 'b' => 'b3..', 'c' => 'c3'],
//            ['a' => 'a4.', 'b' => 'b4', 'e' => 'e4'],

        $expAy = preg_split("/\n/", $exp);
        foreach (preg_split("/\n/", $act) as $idx => $a_val) {
            $e_val = $expAy[$idx];
            $this->assertEquals($e_val, $a_val, "idx=$idx");
        }
        $this->assertEquals(strlen($exp), strlen($act), "length");
        $this->assertEquals($exp, $act);
    }

    function test_wdtAy()
    {
        $a = $this->tar;
        $fldAn = $this->tar->fldAn();
        $act = $a->wdtAy($fldAn);
        $exp = ["a" => 3, "b" => 4, "c" => 5, "d" => 2, "e" => 2];
        $this->assertEquals($exp, $act);
    }

    function test_wdtAy__dtaOnly()
    {
        $a = $this->tar;
        $fldAn = $this->tar->fldAn();
        $act = $a->wdtAy__dtaOnly($fldAn);
        $exp = ["a" => 3, "b" => 4, "c" => 5, "d" => 2, "e" => 2];
        $this->assertEquals($exp, $act);
    }
}

$a = [
    0 =>
        array(
            'ordContent' => '1',
            'ord' => '1',
            'ordDelvDte' => '2013-12-25',
            'ordNo' => '0001',
            'cusCd' => 'cus1',
            'contentRmk' => "sdf content Rmk 1
dkf sf
sdfsdf",
            'withImg' => '1',
        ),
    1 =>
        array(
            'ordContent' => '2',
            'ord' => '1',
            'ordDelvDte' => '2013-12-25',
            'ordNo' => '0001',
            'cusCd' => 'cus1',
            'contentRmk' => 'sdf content Rmk 2',
            'withImg' => '1',
        ),
    2 =>
        array(
            'ordContent' => '3',
            'ord' => '1',
            'ordDelvDte' => '2013-12-25',
            'ordNo' => '0001',
            'cusCd' => 'cus1',
            'contentRmk' => 'sdf content Rmk 3',
            'withImg' => '0',
        ),
    3 =>
        array(
            'ordContent' => '4',
            'ord' => '1',
            'ordDelvDte' => '2013-12-25',
            'ordNo' => '0001',
            'cusCd' => 'cus1',
            'contentRmk' => 'sdf content Rmk 4',
            'withImg' => '1',
        ),
    4 =>
        array(
            'ordContent' => '5',
            'ord' => '1',
            'ordDelvDte' => '2013-12-25',
            'ordNo' => '0001',
            'cusCd' => 'cus1',
            'contentRmk' => 'sdf content Rmk 5',
            'withImg' => '1',
        ),
    5 =>
        array(
            'ordContent' => '6',
            'ord' => '1',
            'ordDelvDte' => '2013-12-25',
            'ordNo' => '0001',
            'cusCd' => 'cus1',
            'contentRmk' => 'sdf content Rmk 6',
            'withImg' => '0',
        ),
];
//$m = new Dta($a);
//echo $m->toFmt();
//echo LF;
test("Dta__tst");

