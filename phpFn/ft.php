<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-29
 * Time: 15:17
 */
require_once 'pth.php';
require_once "str.php";
require_once "System\\Launcher.php";

function tmpFt($seg = "")
{
    $f = tim_stmp() . ".txt";
    if ($seg === "") return tmpPth() . $f;
    $p = tmpPth() . "seg\\";
    pth_create_if_not_exist($p);
    return $p . $f;
}

function ft_ay($ft)
{
    $a = file_get_contents($ft);
    $b = str_replace("\r\n", "\n", $a);
    $c = preg_split('/\n/', $b);
    return $c;
}

function ft_brw($ft)
{
    $a = new System_Launcher;
    $a->Launch($ft);
}
