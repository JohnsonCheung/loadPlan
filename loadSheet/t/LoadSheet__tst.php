<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/5/2015
 * Time: 6:47
 */
const p = __DIR__ . "/../";
require_once "PHPUnit.php";
require_once "dta.php";
require_once "ay.php";
require_once "pth.php";
require_once "ffn.php";
require_once "ft.php";
require_once "db.php";
require_once "obj.php";
require_once "test.php";
require_once p . "LoadSheet.php";

class SampleInp
{
    static function content()
    {
        return [
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
    }
}

class GenBarCd__tst extends PHPUnit_TestCase
{
    function test_gen()
    {
        $t = $this->tar;
        $t->genPth();
        $f = $t->barCdFile;

        $this->assertFalse(is_file($f));
        $t->genBarCd();
        $this->assertTrue(is_file($f));
    }
}

class GenDrop__tst extends PHPUnit_TestCase
{
    /** @var GenDrop */
    private $tar;

    function test_dropDta()
    {
        $act = $this->tar->dropDta();
        //echo "dropDta\n";
        $exp = array(
            0 => ['ord' => '2013-12-25 柯打#1 地址#1', 'cus' => 'shtNm-cus1', 'adr' => '載貨計劃系統地址1', 'contentLines' => 'sdf content Rmk 1\\nsdf content Rmk 2\\nsdf content Rmk 3', 'qty' => '1 箱 2板 1.0 CBM', 'pagNoList' => '1, 2, 3', 'ordBy' => 'ordBy - ABC', 'adrContact' => 'adr 1Contact', 'rmkNoList' => '1, 2',],
            1 => ['ord' => '2013-12-25 柯打#1 地址#2 交貨#2', 'cus' => 'shtNm-cus1', 'adr' => '載貨計劃系統地址2', 'contentLines' => 'sdf content Rmk 2\\nsdf content Rmk 3\\nsdf content Rmk 4', 'qty' => '2 箱 3.0 CBM', 'pagNoList' => '2, 3, 4', 'ordBy' => 'ordBy - ABC', 'adrContact' => 'adr 2 Contact', 'rmkNoList' => '1, 3',],
            2 => ['ord' => '2013-12-25 柯打#1 地址#3 交貨#3', 'cus' => 'shtNm-cus1', 'adr' => '載貨計劃系統地址3', 'contentLines' => 'sdf content Rmk 4\\nsdf content Rmk 5\\nsdf content Rmk 6', 'qty' => '3板 4.0 CBM', 'pagNoList' => '4, 5', 'ordBy' => 'ordBy - ABC', 'adrContact' => 'adr 3 Contact', 'rmkNoList' => '1, 4',],
            3 => array(
                'ord' => '2013-12-25 柯打#1 地址#4 交貨#4',
                'cus' => 'shtNm-cus1',
                'adr' => '載貨計劃系統地址4',
                'contentLines' => 'sdf content Rmk 1',
                'qty' => '4.0 CBM',
                'pagNoList' => 1,
                'ordBy' => 'ordBy - ABC',
                'adrContact' => 'adr 4 Contact',
                'rmkNoList' => '1, 5',
            ),
            4 => array(
                'ord' => '2013-12-25 柯打#1 地址#5 交貨#5',
                'cus' => 'shtNm-cus1',
                'adr' => '載貨計劃系統地址5',
                'contentLines' => NULL,
                'qty' => '6 箱',
                'pagNoList' => NULL,
                'ordBy' => 'ordBy - ABC',
                'adrContact' => 'adr 5 Contact',
                'rmkNoList' => '1, 6',
            ),
            5 => array(
                'ord' => '2013-12-25 柯打#1 地址#6 交貨#6',
                'cus' => 'shtNm-cus1',
                'adr' => '載貨計劃系統地址6',
                'contentLines' => NULL,
                'qty' => '7板',
                'pagNoList' => NULL,
                'ordBy' => 'ordBy - ABC',
                'adrContact' => 'adr 6 Contact',
                'rmkNoList' => '1, 7',
            ),
            6 => array(
                'ord' => '2013-12-25 柯打#1 地址#7 交貨#7',
                'cus' => 'shtNm-cus1',
                'adr' => '載貨計劃系統地址7',
                'contentLines' => NULL,
                'qty' => '4 箱 5板',
                'pagNoList' => NULL,
                'ordBy' => 'ordBy - ABC',
                'adrContact' => 'adr 7 Contact',
                'rmkNoList' => '1, 8',
            ),
        );
        $this->assertEquals($exp, $act);
    }

    function test_dropFfn()
    {
        $act = $this->tar->dropFfn(0, 'c:\\');
        $exp = 'c:\drop-01.txt';
        $this->assertEquals($exp, $act);
    }

    function setup()
    {
        $a = new Inp(1, db_con());
        $inpDrop = $a->drop;
        $inpContent = $a->content;
        $inpRmk_OfAdr = $a->rmk_OfAdr;
        $inpRmk_OfOrd = $a->rmk_OfOrd;
        $tripDelvDte = $a->trip->dte;
        $this->tar = new GenDrop($inpDrop, $inpRmk_OfOrd, $inpRmk_OfAdr, $tripDelvDte, $inpContent);
    }
}

class GenPng__tst extends PHPUnit_TestCase
{
    private $tar;

    function test_fmAy()
    {
        $a_fmAy = $this->tar->fmAy;
        $e_fmAy = [];
        $this->assertEquals($e_fmAy, $a_fmAy);
    }

    function test_toAy()
    {
        $a_toAy = $this->tar->toAy;
        $e_toAy = [];
        $this->assertEquals($e_toAy, $a_toAy);

    }

    function setup()
    {
        $inpContent = sample_inpContent();
        $tripPth = "c:\\temp\\";
        $this->tar = new GenPng($inpContent, $tripPth);
    }
}

class GenRmkOfAdr__tst extends PHPUnit_TestCase
{
    /** @var GenRmkOfAdr tar */
    public $tar;

    function test_gen()
    {
        $tmpPth = $f = null;
        {
            $tmpPth = tmpPth();
            $f = $tmpPth . "rmk_OfAdr.txt";
            ffn_dlt($f);
        }
        $this->tar->gen($tmpPth);
        $act = ft_ay($f);
        $exp = array(
            0 => 'chiShtNm1,adrCd1-adrNm1,(*) A-Line1\nA-Line2',
            1 => 'chiShtNm1,adrCd1-adrNm1,(*) B-Line1\nB-Line2\nB-Line3');
        $this->assertEquals($exp, $act);
        ffn_dlt($f);
    }

    function test_ordAdr_cus_adr()
    {
        $a = <<<inpOrdAd
ordAdr   | cusCd  | engShtNm  | chiShtNm  | adrCd  | adrNm
ordAdr-A | cusCd1 | engShtNm1 | chiShtNm1 | adrCd1 | adrNm1
ordAdr-B | cusCd2 | engShtNm2 | chiShtNm2 | adrCd2 | adrNm2
inpOrdAd;

        $inpOrdAdr = newDtaByTxt($a);
        $act = GenRmkOfAdr::ordAdr_cus_adr($inpOrdAdr);
        $exp = [
            'ordAdr-A' => ['cus' => 'chiShtNm1', 'adr' => 'adrCd1-adrNm1'],
            'ordAdr-B' => ['cus' => 'chiShtNm2', 'adr' => 'adrCd2-adrNm2'],
        ];
        $this->assertEquals($exp, $act);
    }

    function test_oupAy()
    {
        $act = $this->tar->oupAy;
        $exp = array(
            0 => 'chiShtNm1,adrCd1-adrNm1,(*) A-Line1\nA-Line2',
            1 => 'chiShtNm1,adrCd1-adrNm1,(*) B-Line1\nB-Line2\nB-Line3',
        );
        $this->assertEquals($exp, $act);
    }

    function setup()
    {
        $a = <<<Ds
*Ds DataSet

*Dt inpDrop
ordAdr
ordAdr-A
ordAdr-A
ordAdr-B
ordAdr-C

*Dt inpRmk_OfAdr
ordAdr   | instTxt
ordAdr-A | A-Line1
ordAdr-A | A-Line2
ordAdr-B | B-Line1
ordAdr-B | B-Line2
ordAdr-B | B-Line3

*Dt inpOrdAdr
ordAdr   | cusCd  | engShtNm  | chiShtNm  | adrCd  | adrNm
ordAdr-A | cusCd1 | engShtNm1 | chiShtNm1 | adrCd1 | adrNm1
ordAdr-B | cusCd1 | engShtNm1 | chiShtNm1 | adrCd1 | adrNm1
Ds;
        $ds = Ds::byTxt($a);

        $inpDrop = $ds->dt('inpDrop')->dta;
        $inpRmk_OfAdr = $ds->dt('inpRmk_OfAdr')->dta;
        $inpOrdAdr = $ds->dt('inpOrdAdr')->dta;
        $this->tar = new GenRmkOfAdr($inpRmk_OfAdr, $inpDrop, $inpOrdAdr);
    }
}

class GenRmkOfOrd__tst extends PHPUnit_TestCase
{
    /** @var GenRmkOfOrd tar */
    public $tar;

    function test_gen()
    {
        $tmpPth = $f = null;
        {
            $tmpPth = tmpPth();
            $f = $tmpPth . "rmk_OfOrd.txt";
            ffn_dlt($f);
        }
        $this->tar->gen($tmpPth);
        $act = ft_ay($f);
        $exp = [
            0 => 'chiShtNm1,(*) A-Line1\nA-Line2',
            1 => 'chiShtNm2,(*) B-Line1\nB-Line2\nB-Line3',
        ];
        $this->assertEquals($exp, $act);
        ffn_dlt($f);
    }

    function test_ord_cus()
    {
        $a = <<<inpOrd
ord   | cusCd  | engShtNm  | chiShtNm
ord-A | cusCd1 | engShtNm1 | chiShtNm1
ord-B | cusCd2 | engShtNm2 | chiShtNm2
inpOrd;

        $inpOrd = newDtaByTxt($a);
        $act = GenRmkOfOrd::ord_cus($inpOrd);
        $exp = [
            'ord-A' => 'chiShtNm1',
            'ord-B' => 'chiShtNm2',
        ];
        $this->assertEquals($exp, $act);
    }

    function test_oupAy()
    {
        $act = $this->tar->oupAy;
        $exp = array(
            0 => 'chiShtNm1,(*) A-Line1\nA-Line2',
            1 => 'chiShtNm2,(*) B-Line1\nB-Line2\nB-Line3',
        );
        $this->assertEquals($exp, $act);
    }

    function setup()
    {
        $a = <<<Ds
*Ds DataSet

*Dt inpDrop
ord
ord-A
ord-A
ord-B
ord-C

*Dt inpRmk_OfOrd
ord   | instTxt
ord-A | A-Line1
ord-A | A-Line2
ord-B | B-Line1
ord-B | B-Line2
ord-B | B-Line3

*Dt inpOrd
ord   | cusCd  | engShtNm  | chiShtNm
ord-A | cusCd1 | engShtNm1 | chiShtNm1
ord-B | cusCd2 | engShtNm2 | chiShtNm2
ord-C | cusCd3 | engShtNm3 | chiShtNm3
Ds;
        $ds = Ds::byTxt($a);

        $inpDrop = $ds->dt('inpDrop')->dta;
        $inpRmk_OfOrd = $ds->dt('inpRmk_OfOrd')->dta;
        $inpOrd = $ds->dt('inpOrd')->dta;
        $this->tar = new GenRmkOfOrd($inpRmk_OfOrd, $inpDrop, $inpOrd);
    }
}

class Inp__tst extends PHPUnit_TestCase_ext
{
    public $tar;

    function setUp()
    {
        $this->tar = new Inp(1, db_con());
    }

    function test_inpContent()
    {
        $act = $this->tar->content;
        echo dta_toFmt($act);
        echo LF;

        $exp = <<<'Txt'
ordContent | ord | ordDelvDte | ordNo | cusCd | contentRmk                        | withImg
1          | 1   | 2013-12-25 | 0001  | cus1  | sdf content Rmk 1\ndkf sf\nsdfsdf | 1
2          | 1   | 2013-12-25 | 0001  | cus1  | sdf content Rmk 2                 | 1
3          | 1   | 2013-12-25 | 0001  | cus1  | sdf content Rmk 3                 | 0
4          | 1   | 2013-12-25 | 0001  | cus1  | sdf content Rmk 4                 | 1
5          | 1   | 2013-12-25 | 0001  | cus1  | sdf content Rmk 5                 | 1
6          | 1   | 2013-12-25 | 0001  | cus1  | sdf content Rmk 6                 | 0
Txt;
        $exp = dta_byTxt($exp);
        $this->assertEquals(sizeof($exp), sizeof($act), "SIZEOF");
        for ($i = 0; $i < sizeof($exp); $i++) {
            $e = $exp[$i];
            $a = $act[$i];
            $this->assertEquals($e, $a, "REC[$i]");
            $this->assert_str($e['contentRmk'], $a['contentRmk'], "REC[$i]");
        }
        $this->assertEquals($exp, $act);
    }

    function test_inpDrop()
    {
        $act = $this->tar->drop;
        $exp = array(
            0 =>
                array(
                    'ordDrop' => '1',
                    'ordAdr' => '1',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'ordBy' => 'ordBy - ABC',
                    'adr' => '載貨計劃系統地址1',
                    'adrContact' => 'adr 1Contact',
                    'adrPhone' => 'adr1 phone',
                    'ordContentLvc' => '1,2,3',
                    'nBox' => '1',
                    'nPallet' => '2',
                    'nCBM' => '1.0',
                    'nCage' => NULL,
                    'trip' => '1',
                ),
            1 =>
                array(
                    'ordDrop' => '2',
                    'ordAdr' => '2',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'ordBy' => 'ordBy - ABC',
                    'adr' => '載貨計劃系統地址2',
                    'adrContact' => 'adr 2 Contact',
                    'adrPhone' => 'adr2 phone',
                    'ordContentLvc' => '2,3,4',
                    'nBox' => '2',
                    'nPallet' => NULL,
                    'nCBM' => '3.0',
                    'nCage' => NULL,
                    'trip' => '1',
                ),
            2 =>
                array(
                    'ordDrop' => '3',
                    'ordAdr' => '3',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'ordBy' => 'ordBy - ABC',
                    'adr' => '載貨計劃系統地址3',
                    'adrContact' => 'adr 3 Contact',
                    'adrPhone' => 'adr3 phone',
                    'ordContentLvc' => '4,5,7',
                    'nBox' => NULL,
                    'nPallet' => '3',
                    'nCBM' => '4.0',
                    'nCage' => NULL,
                    'trip' => '1',
                ),
            3 =>
                array(
                    'ordDrop' => '4',
                    'ordAdr' => '4',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'ordBy' => 'ordBy - ABC',
                    'adr' => '載貨計劃系統地址4',
                    'adrContact' => 'adr 4 Contact',
                    'adrPhone' => 'adr4 phone',
                    'ordContentLvc' => '8,9,10,1',
                    'nBox' => NULL,
                    'nPallet' => NULL,
                    'nCBM' => '4.0',
                    'nCage' => NULL,
                    'trip' => '1',
                ),
            4 =>
                array(
                    'ordDrop' => '8',
                    'ordAdr' => '5',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'ordBy' => 'ordBy - ABC',
                    'adr' => '載貨計劃系統地址5',
                    'adrContact' => 'adr 5 Contact',
                    'adrPhone' => 'adr5 phone',
                    'ordContentLvc' => NULL,
                    'nBox' => '6',
                    'nPallet' => NULL,
                    'nCBM' => NULL,
                    'nCage' => NULL,
                    'trip' => '1',
                ),
            5 =>
                array(
                    'ordDrop' => '9',
                    'ordAdr' => '6',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'ordBy' => 'ordBy - ABC',
                    'adr' => '載貨計劃系統地址6',
                    'adrContact' => 'adr 6 Contact',
                    'adrPhone' => 'adr6 phone',
                    'ordContentLvc' => NULL,
                    'nBox' => NULL,
                    'nPallet' => '7',
                    'nCBM' => NULL,
                    'nCage' => NULL,
                    'trip' => '1',
                ),
            6 =>
                array(
                    'ordDrop' => '7',
                    'ordAdr' => '7',
                    'ord' => '1',
                    'ordDelvDte' => '2013-12-25',
                    'ordNo' => '0001',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'ordBy' => 'ordBy - ABC',
                    'adr' => '載貨計劃系統地址7',
                    'adrContact' => 'adr 7 Contact',
                    'adrPhone' => 'adr7 phone',
                    'ordContentLvc' => NULL,
                    'nBox' => '4',
                    'nPallet' => '5',
                    'nCBM' => NULL,
                    'nCage' => NULL,
                    'trip' => '1',
                ),
        );
        echo dta_toFmt($exp);
        echo LF;
        $this->assertEquals($exp, $act);
    }

    function test_inpOrd()
    {
        $act = $this->tar->ord;
        $exp = array(
            0 =>
                array(
                    'ord' => '1',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                )
        );
        $this->assertEquals($exp, $act);

    }

    function test_inpOrdAdr()
    {
        $act = $this->tar->ordAdr;
        $exp = array(
            0 =>
                array(
                    'ordAdr' => '1',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'adrCd' => 'RMU9',
                    'adrNm' => '威尼斯',
                ),
            1 =>
                array(
                    'ordAdr' => '2',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'adrCd' => 'RMUA',
                    'adrNm' => '金沙城',
                ),
            2 =>
                array(
                    'ordAdr' => '3',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'adrCd' => NULL,
                    'adrNm' => NULL,
                ),
            3 =>
                array(
                    'ordAdr' => '4',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'adrCd' => NULL,
                    'adrNm' => NULL,
                ),
            4 =>
                array(
                    'ordAdr' => '5',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'adrCd' => NULL,
                    'adrNm' => NULL,
                ),
            5 =>
                array(
                    'ordAdr' => '6',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'adrCd' => NULL,
                    'adrNm' => NULL,
                ),
            6 =>
                array(
                    'ordAdr' => '7',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'adrCd' => NULL,
                    'adrNm' => NULL,
                ),
            7 =>
                array(
                    'ordAdr' => '8',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'adrCd' => NULL,
                    'adrNm' => NULL,
                ),
            8 =>
                array(
                    'ordAdr' => '9',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'adrCd' => NULL,
                    'adrNm' => NULL,
                ),
            9 =>
                array(
                    'ordAdr' => '10',
                    'cusCd' => 'cus1',
                    'engShtNm' => '',
                    'chiShtNm' => 'AMWAY',
                    'adrCd' => NULL,
                    'adrNm' => NULL,
                ),
        );
        $this->assertEquals($exp, $act);

    }

    function test_inpRmk_OfAdr()
    {
        $act = $this->tar->rmk_OfAdr;
        $exp = array(
            0 =>
                array(
                    'ordAdr' => '1',
                    'instTxt' => 'sdfsdfsdfsdf',
                ),
            1 =>
                array(
                    'ordAdr' => '1',
                    'instTxt' => 'sdfsdf sdfs df',
                ),
            2 =>
                array(
                    'ordAdr' => '1',
                    'instTxt' => 'sdfsdfsdf sdfsdfsdf',
                ),
        );
        $this->assertEquals($exp, $act);
    }

    function test_inpRmk_OfOrd()
    {
        $act = $this->tar->rmk_OfOrd;
        $exp = array(
            0 =>
                array(
                    'ord' => '1',
                    'instTxt' => 'sdfsdfsdfsdf',
                ),
            1 =>
                array(
                    'ord' => '1',
                    'instTxt' => 'sdfsdf sdfs df',
                ),
            2 =>
                array(
                    'ord' => '1',
                    'instTxt' => 'sdfsdfsdf sdfsdfsdf',
                ),
        );
        $this->assertEquals($exp, $act);
    }

    function test_inpTrip()
    {
        $act = $this->tar->trip;
        $exp = new stdClass;
        obj_setPrp($exp, [
            'trip' => '1',
            'dte' => '2015-01-01',
            'tripNo' => '1',
            'driver' => 'aa',
            'driverTy' => 'INT',
            'leader' => 'leader-trip1',
            'member' => 'member-trip1',
            'truckCd' => 'aa',
            'plateNo' => NULL,
        ]);
        $this->assertEquals($exp, $act);
    }
}

class LoadSheet__tst extends PHPUnit_TestCase
{
    /** @var  LoadSheet\Gen */
    public $tar;

    function test_attDta()
    {
        $t = $this->tar;
        $act = $t->attDta();
        $exp = ['1+1' => ['ordNo' => '0001', 'contentNo' => '1', 'attNo' => 1, 'ordDelvDte' => '2013-12-25'],
            '1+2' => ['ordNo' => '0001', 'contentNo' => '2', 'attNo' => 2, 'ordDelvDte' => '2013-12-25'],
            '1+3' => ['ordNo' => '0001', 'contentNo' => '3', 'attNo' => 3, 'ordDelvDte' => '2013-12-25'],
            '1+4' => ['ordNo' => '0001', 'contentNo' => '4', 'attNo' => 4, 'ordDelvDte' => '2013-12-25'],
            '1+5' => ['ordNo' => '0001', 'contentNo' => '5', 'attNo' => 5, 'ordDelvDte' => '2013-12-25'],
        ];
        $this->assertEquals($exp['1+1'], $act['1+1']);
        // $this->assertEquals($exp, $act);
    }

    function test_fmtoDta()
    {
        $act = $this->tar->fmtoDta();
        //echo "fmToDat\n";
        //var_export($act);
        $exp = array(
            0 =>
                array(
                    0 => 'c:\\xampp\\htdocs\\loadplan\\ordContent\\2013\\12\\25\\Ord-2013-12-25#0001 content-01.png',
                    1 => 'c:\\xampp\\htdocs\\loadplan\\pgm\\loadsheet\\queue\\Trip-2015-01-01#001\\Trip-2015-01-01#001 att-01 (ord-2013-12-25#0001 content-01).png',
                ),
            1 =>
                array(
                    0 => 'c:\\xampp\\htdocs\\loadplan\\ordContent\\2013\\12\\25\\Ord-2013-12-25#0001 content-02.png',
                    1 => 'c:\\xampp\\htdocs\\loadplan\\pgm\\loadsheet\\queue\\Trip-2015-01-01#001\\Trip-2015-01-01#001 att-02 (ord-2013-12-25#0001 content-02).png',
                ),
            2 =>
                array(
                    0 => 'c:\\xampp\\htdocs\\loadplan\\ordContent\\2013\\12\\25\\Ord-2013-12-25#0001 content-03.png',
                    1 => 'c:\\xampp\\htdocs\\loadplan\\pgm\\loadsheet\\queue\\Trip-2015-01-01#001\\Trip-2015-01-01#001 att-03 (ord-2013-12-25#0001 content-03).png',
                ),
            3 =>
                array(
                    0 => 'c:\\xampp\\htdocs\\loadplan\\ordContent\\2013\\12\\25\\Ord-2013-12-25#0001 content-04.png',
                    1 => 'c:\\xampp\\htdocs\\loadplan\\pgm\\loadsheet\\queue\\Trip-2015-01-01#001\\Trip-2015-01-01#001 att-04 (ord-2013-12-25#0001 content-04).png',
                ),
            4 =>
                array(
                    0 => 'c:\\xampp\\htdocs\\loadplan\\ordContent\\2013\\12\\25\\Ord-2013-12-25#0001 content-05.png',
                    1 => 'c:\\xampp\\htdocs\\loadplan\\pgm\\loadsheet\\queue\\Trip-2015-01-01#001\\Trip-2015-01-01#001 att-05 (ord-2013-12-25#0001 content-05).png',
                ),
        );
        $this->assertEquals($exp, $act);
    }

    function test_gen()
    {
        $this->tar->gen();
    }

    function test_genAtt()
    {
        $t = $this->tar;
        $t->genPth();
        $t->genAtt();

        //      $this->assertEquals($exp, $act);
    }

    function test_genHdr()
    {
        $t = $this->tar;
        $f = $t->hdrFile;

        $this->tar->genPth();
        assert(!is_file($f));

        $this->tar->genHdr();
        assert(is_file($f));
    }

    function test_genPth()
    {
        /** @var LoadSheet\Gen $tar */
        $t = $this->tar;
        $pth = $t->tripPth;
        pth_clear_files($pth);
        if (is_dir($pth))
            rmdir($pth);
        $this->assertFalse(is_dir($pth));

        $t->genPth();
        $this->assertTrue(is_dir($pth));
    }

    function test_genRmk()
    {
        $t = $this->tar;
        $t->genPth();
        $f = $t->rmkFile;

        $this->assertFalse(is_file($f));
        $t->genRmk();
        $this->assertTrue(is_file($f));

        $s = file_get_contents($f);
        $act = mb_convert_encoding($s, "UTF - 8", "BIG - 5");
        $exp = "2013 - 12 - 25 柯打#1,1\\n2\\n3
2013 - 12 - 25 柯打#1 地址#0,ordAdr->delvRmk....1
2013 - 12 - 25 柯打#1 地址#0,ordAdr->delvRmk....2
2013 - 12 - 25 柯打#1 地址#0,ordAdr->delvRmk....3
2013 - 12 - 25 柯打#1 地址#0,ordAdr->delvRmk....3
2013 - 12 - 25 柯打#1 地址#0,ordAdr->delvRmk....4
2013 - 12 - 25 柯打#1 地址#0,ordAdr->delvRmk....5
2013 - 12 - 25 柯打#1 地址#0,ordAdr->delvRmk....6
";
        $this->assertEquals($exp, $act);
    }

    function test_getTrip()
    {
        $_SERVER['argv'] = ['--trip', '1'];
        $act = getTrip();
        assert($act === '1');

        $_SERVER['HTTP_HOST'] = 'localhost';
        $_REQUEST['trip'] = '2';
        $act = getTrip();
        assert($act === '2');
    }

    function test_hdrAy()
    {
        $act = $this->tar->hdrAy();
        $exp = array(
            'tripDelvDte' => '2015-01-01 行程#1',
            'driverTy' => '司機',
            'driver' => 'aa',
            'leader' => 'leader-trip1',
            'member' => 'member-trip1',
        );
        $this->assertEquals($exp, $act);
    }

    function test_hdrFile()
    {
        $act = $this->tar->hdrFile;
        $exp = "c:\\xampp\\htdocs\\loadplan\\pgm\\loadsheet\\queue\\Trip - 2015 - 01 - 01#001\\hdr.txt";
        $this->assertEquals($exp, $act);
    }

    function test_ord_n_contentNo__pagNo__dic()
    {
        $act = $this->tar->ord_n_contentNo__pagNo__dic();
        $exp = [
            '1+1' => 1,
            '1+2' => 2,
            '1+3' => 3,
            '1+4' => 4,
            '1+5' => 5];
        $this->assertEquals($exp, $act);
    }

    function test_rmkAy()
    {
        $act = $this->tar->rmkAy();
        $exp = array(
            0 => '2013-12-25 柯打#1,Line 1 - sdf\\nLine 2 - sdf\\nLine 3 -sdfsdfsdf',
            1 => '2013-12-25 柯打#1 地址#1,ordAdr->delvRmk....1',
            2 => '2013-12-25 柯打#1 地址#2,ordAdr->delvRmk....2',
            3 => '2013-12-25 柯打#1 地址#3,ordAdr->delvRmk....3',
            4 => '2013-12-25 柯打#1 地址#4,ordAdr->delvRmk....3',
            5 => '2013-12-25 柯打#1 地址#5,ordAdr->delvRmk....4',
            6 => '2013-12-25 柯打#1 地址#6,ordAdr->delvRmk....5',
            7 => '2013-12-25 柯打#1 地址#7,ordAdr->delvRmk....6',
        );
        $this->assertEquals($exp, $act);
    }
}

class functions__tst extends PHPUnit_TestCase
{
    public $inpDrop;

    function test_pngFile()
    {
        $e_pth = "C:\\xampp\\ordContent\\2012\\11\\11\\0001\\";
        $e_hom = "C:\\xampp\\";
        $e_fn = "Ord-2012-11-11 #0001 content-1.png";
        $e_ffn = "C:\\xampp\ordContent\\2012\\11\\11\\0001\\Ord-2012-11-11 #0001 content-1.png";

        $ordContentId = 1;
        $ordNo = 1;
        $ordDelvDte = "2012-11-11";
        $a = pngFile($ordContentId, $ordNo, $ordDelvDte);

        $this->assertEquals($e_pth, $a['pth']);
        $this->assertEquals($e_hom, $a['hom']);
        $this->assertEquals($e_fn, $a['fn']);
        $this->assertEquals($e_ffn, $a['ffn']);
    }

    function test_rmkNo_OfAdr()
    {
        $act = rmkNo_OfOrd($this->inpDrop);
        $exp = [
            'ord-A' => 1,
            'ord-B' => 2,
            'ord-C' => 3,
            'ord-D' => 4];
        $this->assertEquals($exp, $act);
    }

    function test_rmkNo_OfOrdAdr()
    {
        $act = rmkNo_OfOrdAdr($this->inpDrop);
        $exp = [
            'ordAdr-A' => 1,
            'ordAdr-B' => 2,
            'ordAdr-C' => 3,
            'ordAdr-D' => 4];
        $this->assertEquals($exp, $act);
    }

    function setup()
    {
        $this->inpDrop = [
            ['ord' => 'ord-A', 'ordAdr' => 'ordAdr-A'],
            ['ord' => 'ord-B', 'ordAdr' => 'ordAdr-B'],
            ['ord' => 'ord-A', 'ordAdr' => 'ordAdr-A'],
            ['ord' => 'ord-C', 'ordAdr' => 'ordAdr-C'],
            ['ord' => 'ord-D', 'ordAdr' => 'ordAdr-D']];
    }
}

function main()
{
//test("functions__tst");
    test("Inp__tst");
//test("GenDrop__tst");
//test("GenRmkOfAdr__tst");
//test("GenRmkOfOrd__tst");
}

$l = mb_strwidth("載貨");
echo "strlen=$l";
echo LF;

main();