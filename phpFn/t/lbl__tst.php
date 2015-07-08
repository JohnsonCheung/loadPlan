<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-07
 * Time: 18:23
 */
include_once '/../db.php';
include_once '/../lbl.php';
function tst__lblDic()
{
    $con = db_con();
    $act = lblDic('Fld', "isRetWhs nCntr", 'en', $con);
    $exp = [
        'isRetWhs' => 'Ret Whs',
        'nCntr' => 'N-Container',
        'isRetWhs1' => 'Ret',
        'isRetWhs2' => 'Whs',
        'nCntr1' => 'N-Con',
        'nCntr2' => 'tainer'];
    assert($act === $exp);
    pass(__FUNCTION__);
}

tst__lblDic();
