<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-08
 * Time: 17:54
 */
include_once '/../phpFn/cmn.php';
if (is_main()) {
    $HTTP_RAW_POST_DATA = 1;
}
$ordId = @$HTTP_RAW_POST_DATA;
if (is_null($ordId)) exit();
echo getOrd_json($ordId);
exit();

function getOrd_json($ordId)
{
    $con = db_con();
    $ord = _ord($con, $ordId);
    $inst = _inst($con, $ordId);
    $adr = _adr($con, $ordId);
    $content = _content($con, $ordId);
    $o = [
        'ord' => $ord,
        'inst' => $inst,
        'adr' => $adr,
        'content' => $content];
    return json_encode($o);
}

function _ord($con, $ordId)
{
    $o = runsql_dr($con, "Select * from ord where ord=$ordId;");
    list($a, $b, $c, $d) = runsql_dr($con,
        "select sum(nPallet), sum(nBox), sum(nCage), sum(nCBM) from ordadr where ord = $ordId; ",
        MYSQLI_NUM);
    $o['nPallet'] = $a;
    $o['nBox'] = $b;
    $o['nCage'] = $c;
    $o['nCBM'] = $d;
    return $o;
}

function _inst($con, $ordId)
{
    return runsql_rs($con, "select a.*, b.instCd from ordInst a left join inst b on a.inst=b.inst where ord=$ordId order by sno, inst;");
}

function _adr($con, $ordId)
{
    return runsql_rs($con, "select * from ordadr where ord=$ordId order by sno, ordadr;");
}

function _content($con, $ordId)
{
    $sql = "SELECT * FROM ordcontent where ord=$ordId;";
    return runsql_rs($con, $sql);
}
