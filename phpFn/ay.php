<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 14/5/2015
 * Time: 10:45
 */
require_once "pth.php";
require_once "System\Launcher.php";
function ay_newByLpAp($lp, ...$ap)
{
    $kAy = preg_split('/ / ', $lp);
    $o = [];
    reset($ap);
    foreach ($kAy as $k) {
        $o[$k] = current($ap);
        next($ap);
    }
    return $o;
}

function push_noDup(array &$ay, $i)
{
    ay_push_noDup($ay, $i);
}

function ay_minus($new, $old)
{
    $o = [];
    foreach ($new as $v) {
        if (!in_array($v, $old))
            array_push($o, $v);
    }
    return $o;
}

function ay_quote($ay, $q = "'")
{
    $o = [];
    foreach ($ay as $k => $v) {
        $o[$k] = quote($v, $q);
    }
    return $o;
}

function ay_to_dta(array $ay)
{
    $o = [];
    foreach ($ay as $k => $i) {
        array_push($o, ["idx" => $k, "val" => $i]);
    }
    return $o;
}

/** return a dic by using first column as key and 2nd column as val */
function dta_dic($twoColDta)
{
    $o = [];
    if (count($twoColDta) === 0) return $o;
    $a = array_keys($twoColDta[0]);
    $k0 = $a[0];
    $k1 = $a[1];
    foreach ($twoColDta as $i) {
        $key = $i[$k0];
        $val = $i[$k1];
        $o[$key] = $val;
    }
    return $o;
}

function ay_extract($ay, $names)
{
    $o = [];
    $nm_ay = split_lvs($names);
    foreach ($nm_ay as $nm) {
        assert_key_exists($nm, $ay);
        array_push($o, $ay[$nm]);
    }
    return $o;
}

function assert_key_exists($key, array $ay)
{
    if (!array_key_exists($key, $ay)) {
        $keys = join(' ', array_keys($ay));
        throw new Exception("key[$key] not found\nin array_keys=[$keys]");
    }
}

function dta_extract($_dta, $_nm_lvs)
{
    $nm_ay = split_lvs($_nm_lvs);
    $o = [];
    foreach ($_dta as $dr) {
        $m = [];
        foreach ($nm_ay as $nm) {
            assert_key_exists($nm, $dr);
            $m[$nm] = $dr[$nm];
        }
        array_push($o, $m);
    }
    return $o;
}

function ay_trim($Ay)
{
    $o = [];
    foreach ($Ay as $i)
        array_push($o, trim($i));
    return $o;
}

function ay_firstKey($Ay)
{
    reset($Ay);
    return each($Ay)["key"];
}

function ay_write_file(array $ay, $file)
{
    if ($ay == null) return;
    $fd = fopen($file, "c");
    foreach ($ay as $line) {
        fwrite($fd, $line . "\r\n");
    }
    fclose($fd);
}

function ay_push_noDup(array &$ay, $itm)
{
    if (!(in_array($itm, $ay))) {
        array_push($ay, $itm);
    }
}

class AyGluer
{
    private $glue;

    function __construct($Glue)
    {
        $this->glue = $Glue;
    }

    function glueFn()
    {
        return function ($Ay, $Dr) {
            $m = join($this->glue, $Dr);
            array_push($Ay, $m);
            return $Ay;
        };
    }
}

function ayGluer($Glue)
{
    return (new AyGluer($Glue))->glueFn();
}

function dta_join($Glue, $Dta)
{
    $a = ayGluer($Glue);
    return array_reduce($Dta, $a, []);
}

function ay_pk($assoc_ay, $pk)
{
    $o = [];
    foreach ($assoc_ay as $rec) {
        $pkVal = $rec[$pk];
        $o[$pkVal] = $rec;
    }
    return $o;
}

function brw_ft($ft)
{
    $a = new System_Launcher;
    $a->Launch($ft);
}

function brw_dtaAy($nm_lvs, ...$dtaAy)
{
    $p = dtaAy_tmpPth_array($nm_lvs, $dtaAy);
    return brw_csvPth($p);
}

/** return the tmpPth which has each $dtaAy written as a csv file */
function dtaAy_tmpPth_array($nm_lvs, array $dtaAy)
{
    $p = pth_tmp("dtaAy");
    $nmAy = split_lvs($nm_lvs);
    $j = 0;
    foreach ($dtaAy as $dta) {
        $file = $p . $nmAy[$j++] . ".csv";
        $f = fopen($file, "c");

        if (count($dta) === 0) {
            $f = fopen($file, "c");
            fclose($f);
            continue;
        };
        $dta1 = dta_convert_encoding($dta);
        $fldNmAy = array_keys($dta1[0]);   // use for row as fldNmAy
        fputcsv($f, $fldNmAy);
        foreach ($dta1 as $dr) {
            $fields = [];
            foreach ($fldNmAy as $nm)
                array_push($fields, @$dr[$nm]);
            fputcsv($f, $fields);
        }
        fclose($f);
    }
    return $p;
}

function ay_convert_encoding(array $ay, $encoding = "BIG-5")
{
    $o = [];
    foreach ($ay as $k => $v) {
        $o[$k] = mb_convert_encoding($v, $encoding);
    }
    return $o;
}

function ay_convert_decoding(array $ay, $encoding = "BIG-5")
{
    $o = [];
    foreach ($ay as $k => $v) {
        $o[$k] = mb_convert_encoding($v, "UTF-8", $encoding);
    }
    return $o;
}

function dta_convert_encoding($dta, $encoding = "BIG-5")
{
    $o = [];
    foreach ($dta as $k => $dr) {
        $o[$k] = ay_convert_encoding($dr, $encoding);
    }
    return $o;
}

function dtaAy_tmpPth($nm_lvs, ...$dtaAy)
{
    return dtaAy_tmpPth_array($nm_lvs, $dtaAy);
}