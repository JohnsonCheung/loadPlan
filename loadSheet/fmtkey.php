<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/5/2015
 * Time: 10:26
 */

namespace LoadSheet\FmtKey;
function keyOf_ord($ordNo, $nDaysBef, $ordDelvDte)
{
    $ordNo = sprintf("%d", $ordNo);
    $negDay = "";
    if ($nDaysBef == 0) {
    } elseif (abs($nDaysBef) <= 7) {
        if ($nDaysBef > 0) {
            $a = "前";
            $b = $nDaysBef;
        } else {
            //throw new \Exception("tripDelvDte should not < ordDelvDte");
            $a = "aft";
            $b = -$nDaysBef;
        }
        $negDay = "{$b}日$a ";
    } else
        $negDay = "{$ordDelvDte} ";
    return $negDay . "柯打#$ordNo";
}

function keyOf_adr($ordNo, $nDaysBef, $adrNo, $ordDelvDte)
{
    $ord = keyOf_ord($ordNo, $nDaysBef, $ordDelvDte);
    return "$ord 地址#$adrNo";
}

function keyOf_drop($ordNo, $nDaysBef, $adrNo, $dropNo, $ordDelvDte)
{
    if ($dropNo == 1) {
        return keyOf_adr($ordNo, $nDaysBef, $adrNo, $ordDelvDte);
    }
    return keyOf_adr($ordNo, $nDaysBef, $adrNo, $ordDelvDte) . " 交貨#$dropNo";
}

function fmt_ordKey($ordNo, $ordDelvDte, $tripDelvDte)
{
    $nDaysBef = nDays($tripDelvDte, $ordDelvDte);
    return keyOf_ord($ordNo, $nDaysBef, $ordDelvDte);
}

function fmt_adrKey($ordNo, $ordDelvDte, $tripDelvDte, $adrNo)
{
    $nDaysBef = nDays($tripDelvDte, $ordDelvDte);
    return keyOf_adr($ordNo, $nDaysBef, $adrNo, $ordDelvDte);
}

function fmt_dropKey($ordNo, $ordDelvDte, $tripDelvDte, $adrNo, $dropNo)
{
    $nDaysBef = nDays($tripDelvDte, $ordDelvDte);
    return keyOf_drop($ordNo, $nDaysBef, $adrNo, $dropNo, $ordDelvDte);
}