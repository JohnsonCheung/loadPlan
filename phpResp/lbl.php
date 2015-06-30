<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/6/2015
 * Time: 18:43
 */
include_once '/../phpFn/db.php';
include_once '/../phpFn/str.php';
include_once '/../phpFn/lbl.php';

if (is_server()) {
    $pgmNm = $_GET['pgmNm'];
    $secNm = $_GET['secNm'];
    $lang = @$_GET['lang'];
    if (!$lang) $lang = "zh";
    lbl($pgmNm, $secNm, $lang);
    exit();
}
ob_start();
lbl("region", "upd", "zh");
$act = ob_get_clean();
echo $act;
?>
