<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/5/2015
 * Time: 6:47
 */
require_once "ay.php";
require_once "pth.php";
require_once "db.php";
require_once "/../LoadSheet.php";

LoadSheet\gen(1);

function test_gen()
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

function test_genAtt()
{
    $t = $this->tar;
    $t->genPth();
    $t->genAtt();

    //      $this->assertEquals($exp, $act);
}

function test_genBarCd()
{
    $t = $this->tar;
    $t->genPth();
    $f = $t->barCdFile;

    $this->assertFalse(is_file($f));
    $t->genBarCd();
    $this->assertTrue(is_file($f));
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
    $act = mb_convert_encoding($s, "UTF-8", "BIG-5");
    $exp = "2013-12-25 柯打#1,1\\n2\\n3
2013-12-25 柯打#1 地址#0,ordAdr->delvRmk....1
2013-12-25 柯打#1 地址#0,ordAdr->delvRmk....2
2013-12-25 柯打#1 地址#0,ordAdr->delvRmk....3
2013-12-25 柯打#1 地址#0,ordAdr->delvRmk....3
2013-12-25 柯打#1 地址#0,ordAdr->delvRmk....4
2013-12-25 柯打#1 地址#0,ordAdr->delvRmk....5
2013-12-25 柯打#1 地址#0,ordAdr->delvRmk....6
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
    $exp = "c:\\xampp\\htdocs\\loadplan\\pgm\\loadsheet\\queue\\Trip-2015-01-01#001\\hdr.txt";
    $this->assertEquals($exp, $act);
}

function test_inpContent()
{
    $act = $this->tar->inpContent;
    $exp = [0 => ['ord' => '1', 'contentNo' => '1', 'contentRmk' => 'sdf content Rmk 1', 'withImg' => '1'],
        1 => ['ord' => '1', 'contentNo' => '2', 'contentRmk' => 'sdf content Rmk 2', 'withImg' => '1'],
        2 => ['ord' => '1', 'contentNo' => '3', 'contentRmk' => 'sdf content Rmk 3', 'withImg' => '1'],
        3 => ['ord' => '1', 'contentNo' => '4', 'contentRmk' => 'sdf content Rmk 4', 'withImg' => '1'],
        4 => ['ord' => '1', 'contentNo' => '5', 'contentRmk' => 'sdf content Rmk 5', 'withImg' => '1'],
        5 => ['ord' => '1', 'contentNo' => '7', 'contentRmk' => 'sdf content Rmk 6', 'withImg' => '0']
    ];
    $this->assertEquals($exp, $act);
}

function test_inpDrop()
{
    $act = $this->tar->inpDrop;
    //echo "inpDrop\n";
    $exp = [0 => ['ordDrop' => '1', 'ordAdr' => '1', 'ord' => '1', 'ordDelvDte' => '2013-12-25', 'ordNo' => '0001', 'adrNo' => '1',
        'cusCd' => 'cus1', 'shtNm' => 'shtNm-cus1', 'ordBy' => 'ordBy - ABC', 'adr' => '載貨計劃系統地址1', 'adrContact' => 'adr 1Contact',
        'contentNoLvc' => '1,2,3', 'box' => '1', 'pallet' => '2', 'cbm' => '1.0', 'trip' => '1',],
        1 => ['ordDrop' => '2', 'ordAdr' => '2', 'ord' => '1', 'ordDelvDte' => '2013-12-25',
            'ordNo' => '0001',
            'adrNo' => '2',
            'cusCd' => 'cus1',
            'shtNm' => 'shtNm-cus1',
            'ordBy' => 'ordBy - ABC',
            'adr' => '載貨計劃系統地址2',
            'adrContact' => 'adr 2 Contact',
            'contentNoLvc' => '2,3,4',
            'box' => '2',
            'pallet' => NULL,
            'cbm' => '3.0',
            'trip' => '1',
        ],
        2 =>
            array(
                'ordDrop' => '3',
                'ordAdr' => '3',
                'ord' => '1',
                'ordDelvDte' => '2013-12-25',
                'ordNo' => '0001',
                'adrNo' => '3',
                'cusCd' => 'cus1',
                'shtNm' => 'shtNm-cus1',
                'ordBy' => 'ordBy - ABC',
                'adr' => '載貨計劃系統地址3',
                'adrContact' => 'adr 3 Contact',
                'contentNoLvc' => '4,5,7',
                'box' => NULL,
                'pallet' => '3',
                'cbm' => '4.0',
                'trip' => '1',
            ),
        3 =>
            array(
                'ordDrop' => '4',
                'ordAdr' => '4',
                'ord' => '1',
                'ordDelvDte' => '2013-12-25',
                'ordNo' => '0001',
                'adrNo' => '4',
                'cusCd' => 'cus1',
                'shtNm' => 'shtNm-cus1',
                'ordBy' => 'ordBy - ABC',
                'adr' => '載貨計劃系統地址4',
                'adrContact' => 'adr 4 Contact',
                'contentNoLvc' => '8,9,10,1',
                'box' => NULL,
                'pallet' => NULL,
                'cbm' => '4.0',
                'trip' => '1',
            ),
        4 =>
            array(
                'ordDrop' => '8',
                'ordAdr' => '5',
                'ord' => '1',
                'ordDelvDte' => '2013-12-25',
                'ordNo' => '0001',
                'adrNo' => '5',
                'cusCd' => 'cus1',
                'shtNm' => 'shtNm-cus1',
                'ordBy' => 'ordBy - ABC',
                'adr' => '載貨計劃系統地址5',
                'adrContact' => 'adr 5 Contact',
                'contentNoLvc' => NULL,
                'box' => '6',
                'pallet' => NULL,
                'cbm' => NULL,
                'trip' => '1',
            ),
        5 =>
            array(
                'ordDrop' => '9',
                'ordAdr' => '6',
                'ord' => '1',
                'ordDelvDte' => '2013-12-25',
                'ordNo' => '0001',
                'adrNo' => '6',
                'cusCd' => 'cus1',
                'shtNm' => 'shtNm-cus1',
                'ordBy' => 'ordBy - ABC',
                'adr' => '載貨計劃系統地址6',
                'adrContact' => 'adr 6 Contact',
                'contentNoLvc' => NULL,
                'box' => NULL,
                'pallet' => '7',
                'cbm' => NULL,
                'trip' => '1',
            ),
        6 =>
            array(
                'ordDrop' => '7',
                'ordAdr' => '7',
                'ord' => '1',
                'ordDelvDte' => '2013-12-25',
                'ordNo' => '0001',
                'adrNo' => '7',
                'cusCd' => 'cus1',
                'shtNm' => 'shtNm-cus1',
                'ordBy' => 'ordBy - ABC',
                'adr' => '載貨計劃系統地址7',
                'adrContact' => 'adr 7 Contact',
                'contentNoLvc' => NULL,
                'box' => '4',
                'pallet' => '5',
                'cbm' => NULL,
                'trip' => '1',
            ),
    ];
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

function test_z_drop_file()
{
    $dropIdx = 0;
    $tripPth = "c:\\xampp\\htdocs\\loadplan\\pgm\\loadsheet\\queue\\Trip-2015-01-01#001\\";
    $act = LoadSheet\Drop\z_drop_file($dropIdx, $tripPth);
    $exp = 'c:\\xampp\\htdocs\\loadplan\\pgm\\loadsheet\\queue\\Trip-2015-01-01#001\\drop-01.txt';
    $this->assertEquals($exp, $act);
}

function test_z_fm_to()
{
    $attNo = 2;
    $ordNo = 1;
    $ordDelvDte = "2015/12/31";
    $contentNo = 3;
    $tripPth = "c:\\xampp\\htdocs\\loadplan\\ordContent\\2015\\12\\31\\";
    $tripNm = "Trip-2015-01-01#001";
    $act = \LoadSheet\Att\z_fm_to($attNo, $ordNo, $ordDelvDte, $contentNo, $tripPth, $tripNm);
    //var_export($act);
    $exp = array(
        0 => 'c:\\xampp\\htdocs\\loadplan\\ordContent\\2015\\12\\31\\Ord-2015-12-31#0001 content-03.png',
        1 => 'c:\\xampp\\htdocs\\loadplan\\ordContent\\2015\\12\\31\\Trip-2015-01-01#001 att-02 (ord-2015-12-31#0001 content-03).png',
    );
    $this->assertEquals($exp, $act);
}

