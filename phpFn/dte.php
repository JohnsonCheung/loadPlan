<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-02
 * Time: 14:33
 */
function is_dteStr($yyyy_mm_dd)
{
    return chk_dteStr($yyyy_mm_dd) === null;
}

function chk_dteStr($yyyy_mm_dd)
{
    $a = $yyyy_mm_dd;
    if (!is_string($a)) return "not a string";
    if (strlen($a) != 10) return "len <>10";
    if (substr($a, 4, 1) != "-") return "5th is not [-]";
    if (substr($a, 7, 1) != "-") return "8th is not [-]";
    try {
        new DateTime($a);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function is_pastDte($yyyy_mm_dd)
{
    $d = new DateTime($yyyy_mm_dd);
    $diff = (new DateTime(today()))->diff($d);
    return $diff->invert !== 0 ;
}

function today()
{
    $D = getdate();
    $y = $D['year'];
    $m = $D['mon'];
    $d = $D['mday'];
    return sprintf("%d-%'02d-%'02d", $y, $m, $d);
}