<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 14/5/2015
 * Time: 8:55
 */
include_once "str.php";
include_once "System/Launcher.php";
const PTH_TMP_HOM = "c:\\Temp\\";
function add_backSlash($pth)
{
    return right($pth, 1) === '\\' ? $pth : $pth . '\\';
}

function is_pth($pth)
{
    return is_dir($pth);
}

function pth_clear_files($pth)
{
    $fn_ay = pth_fnAy($pth);
    foreach ($fn_ay as $fn) {
        unlink($pth . $fn);
    }
}

function pth_create_if_not_exist($pth)
{
    $a = rmv_end_backSlash($pth);
    if (!is_dir($a)) {
        mkdir($a, 0777, true);
    }
}

function pth_fnAy($pth)
{
    $o = [];
    $p = add_backSlash($pth);
    if (!is_dir($pth)) throw new Exception("pth[$pth] is not found");
    $fnAy = scandir($pth);
    foreach ($fnAy as $fn) {
        if (is_file($p . $fn))
            array_push($o, $fn);
    }
    return $o;
}

/**
 * normailze $pth by set
 * Where 'Set'  is If prefix of Pth .\ or ..\, add NonBlank(PthBase,CurDbPth) to Pth
 * Where 'Norm' is to replace \.\ to \ and \..\ to remove 1 lvl in Pth
 *
 * @param        $pth
 * @param string $pthBase
 *
 * @return mixed|string
 */
function pth_norm($pth, $pthBase = "")
{
    $o = $pth;
    if ((substr($o, 0, 2) == '.\\') || (substr($o, 0, 3) == '..\\')) {
        if ($pthBase == '') {
            $o = __DIR__ . $o;
        }
    } else {
        $o = $pthBase . $o;
    }
    $o = str_replace('\\.\\', '\\', $o);
    $p = strpos($o, '\\..\\');
    while ($p !== false) {
        $brk = strbrk($o, '\\..\\');
        $a = $brk[0];
        $b = $brk[1];
        $o = tak_befchr_rev($a, "\\", $keepChr = true) . $b;
        $p = strpos($o, '\\..\\');
    }
    return $o;
}

function pth_opn($pth)
{
    $cmd = "explorer " . '"' . $pth . '"';
    exec($cmd);
}

function pth_tmp($seg = null)
{
    $o = is_null($seg)
        ? PTH_TMP_HOM
        : PTH_TMP_HOM . $seg . "\\" . tim_stmp() . "\\";
    pth_create_if_not_exist($o);
    return $o;
}

function rmv_end_backSlash($pth)
{
    if (is_sfx($pth, "\\"))
        return rmv_lastchr($pth);
    return $pth;
}

function tmpPth()
{
    return "c:\\temp\\";
}
