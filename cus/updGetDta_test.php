<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19/6/2015
 * Time: 20:44
 */
include_once 'updGetDta.php';
run();
function run()
{
    test_oneCus();
}

function test_oneCus()
{
    $cusCd = 'cus1';
    $act = oneCus($cusCd);
    $exp = array(
        'cusDro' =>
            array(
                'cusCd' => 'cus1',
                'chiNm' => 'chiNm',
                'engNm' => 'engNm',
                'inpCd' => 'inpCd',
                'cusTy' => 'cusTy',
                'chiShtNm' => 'shtNm-cus1',
                'engShtNm' => NULL,
                'isDea' => '0',
                'cusRmk' => NULL,
                'isRef' => true,
            ),
        'adrDt' =>
            array(
                0 =>
                    array(
                        'cusAdr' => '1',
                        'cusCd' => 'cus1',
                        'adrCd' => '11',
                        'inpCd' => NULL,
                        'adrNm' => '101',
                        'adr' => 'sdlfksdfdf',
                        'contact' => NULL,
                        'phone' => NULL,
                        'regCd' => NULL,
                        'gpsX' => NULL,
                        'gpsY' => NULL,
                        'delvTimFm' => NULL,
                        'delvTimTo' => NULL,
                        'delvLasTim' => NULL,
                        'truckTones' => NULL,
                        'truckCold' => NULL,
                        'truckFlat' => '0',
                        'truckVan' => '0',
                        'truckClose' => '0',
                        'truckTail' => '0',
                        'truckUpstair' => '0',
                        'truckDispatchAtDoor' => '0',
                        'truckByBox' => '0',
                        'truckByPallet' => '0',
                        'truckLock' => '0',
                        'pickAdrCd' => NULL,
                        'rmk' => NULL,
                        'isRef' => true,
                        'shwDlt' => false
                    ),
            ),
    );
    var_dump($act);
    assert($act === $exp);
}