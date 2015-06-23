<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/6/2015
 * Time: 18:43
 */
$p = __DIR__ . '/../phpFn/';
include_once $p . 'db.php';
include_once $p . 'str.php';
$pgmNm = $_GET['pgmNm'];
$secNm = $_GET['secNm'];
$lang = @$_GET['lang'];
if(is_null($lang)) $lang="zh";
if($lang==='undefined') $lang='zh';

$con = db_con();
$sql = "select btnNmLvs, txtNmLvs, fldNmLvs, msgNmLvs from lblpgm where pgmNm='$pgmNm' and secNm='$secNm'";
list($btnNmLvs, $txtNmLvs, $fldNmLvs, $msgNmLvs) = runsql_dr($con, $sql, MYSQLI_NUM);
$o = [];
$o['btn'] = fnd_btn($btnNmLvs, $lang, $con);
$o['txt'] = fnd_txt($txtNmLvs, $lang, $con);
$o['fld'] = fnd_fld($fldNmLvs, $lang, $con);
$o['msg'] = fnd_msg($msgNmLvs, $lang, $con);
$o['fldMsg'] = fnd_fldMsg($pgmNm, $secNm, $lang, $con);
echo json_encode($o);
function fnd_fldMsg($pgmNm, $secNm, $lang, $con) {
    $sql = "select fldNm, msgNm,msg from lblPgmFldMsg where pgmNm='$pgmNm' and secNm='$secNm' and lang='$lang';";
    $dta = runsql_dta($con, $sql);
    $o = new stdClass;
    foreach($dta as $dr) {
        list($fldNm, $msgNm, $msg) = ay_extract($dr,"fldNm msgNm msg");
        $o->$fldNm = new stdClass;
        $o->$fldNm->$msgNm = $msg;
    }
    return $o;
}
function fnd_msg($s, $lang, $con)
{
    $o = [];
    $ay = split_lvs($s);
    if (count($ay) === 0) return new stdClass;
    $ay1 = ay_quote($ay, "'");
    $a = join(",", $ay1);
    $sql = "select msgNm, lbl from lblmsg where msgNm in ($a) and lang='$lang'; ";
    $dic = runsql_dic($con, $sql);
    foreach ($ay as $msgNm) {
        if (array_key_exists($msgNm, $dic)) {
            $o[$msgNm] = $dic[$msgNm];
        } else {
            $o[$msgNm] = $msgNm;
        }
    }
    return $o;
}

function fnd_btn($s, $lang, $con)
{
    $o = [];
    $ay = split_lvs($s);
    if (count($ay) === 0) return new stdClass;
    $ay1 = ay_quote($ay, "'");
    $a = join(",", $ay1);
    $sql = "select btnNm, lbl from lblBtn where btnNm in ($a) and lang='$lang'; ";
    $dic = runsql_dic($con, $sql);
    foreach ($ay as $btnNm) {
        if (array_key_exists($btnNm, $dic)) {
            $o[$btnNm] = $dic[$btnNm];
        } else {
            $o[$btnNm] = $btnNm;
        }
    }
    return $o;
}

function fnd_fld($s, $lang, $con)
{
    $o = [];
    $ay = split_lvs($s);
    if (count($ay) === 0) return new stdClass;
    $ay1 = ay_quote($ay, "'");
    $a = join(",", $ay1);
    $sql = "select fldNm, lbl from lblFld where fldNm in ($a) and lang='$lang';";
    $dic = runsql_dic($con, $sql);
    foreach ($ay as $fldNm) {
        if (array_key_exists($fldNm, $dic)) {
            $o[$fldNm] = $dic[$fldNm];
        } else {
            $o[$fldNm] = $fldNm;
        }
    }
    return $o;
}

function fnd_txt($s, $lang, $con)
{
    $o = [];
    $ay = split_lvs($s);
    if (count($ay) === 0) return new stdClass;
    $ay1 = ay_quote($ay, "'");
    $a = join(",", $ay1);
    $sql = "select txtNm, lbl from lblTxt where txtNm in ($a) and lang='$lang';";
    $dic = runsql_dic($con, $sql);
    foreach ($ay as $txtNm) {
        if (array_key_exists($txtNm, $dic)) {
            $o[$txtNm] = $dic[$txtNm];
        } else {
            $o[$txtNm] = $txtNm;
        }
    }
    return $o;
}

function logg($s)
{
    $fd = fopen("c:/temp/aa.txt", "a");
    fwrite($fd, "\r\n-------------\r\n");
    $o = print_r($s, true);
    fwrite($fd, $o);
    fclose($fd);
}
