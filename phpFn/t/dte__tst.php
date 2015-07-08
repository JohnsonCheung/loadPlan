<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-02
 * Time: 14:56
 */
include '/../dte.php';
include '/../pass.php';
function tst__isPastDte() {
    $act = is_pastDte("2014-10-01");
    assert($act===true);

    $act = is_pastDte(today());
    assert($act===false);

    $act =is_pastDte("2022-01-01");
    assert($act===false);
    pass(__FUNCTION__);
}

function tst__today() {
    $a = getdate((new DateTime(today()))->getTimestamp());
    $b = getdate();
    assert($a['year']===$b['year']);
    assert($a['mon']===$b['mon']);
    assert($a['mday']===$b['mday']);
    pass(__FUNCTION__);
}

tst__today();
tst__isPastDte();