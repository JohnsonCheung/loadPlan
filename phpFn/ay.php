<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 14/5/2015
 * Time: 10:45
 */
require_once "pth.php";
require_once 'str.php';

//class ay_fn
//{
function assert_key_exists($key, array $ay)
{
    if (!array_key_exists($key, $ay)) {
        $keys = join(' ', array_keys($ay));
        throw new Exception("key[$key] not found\nin array_keys=[$keys]");
    }
}

/** return new array by lvs.  Each element of $lvs may be $v or $k:$v format */
function ay_ByLvs($lvs)
{
    $eleAy = split_lvs($lvs);
    $o = [];
    foreach ($eleAy as $ele) {
        $a = strbrk2($ele, ":");
        $k = $a[0];
        if ($k === '')
            array_push($o, $a[1]);
        else
            $o[$k] = $a[1];
    }
    return $o;
}

function ay_addPfx(array $ay, $pfx)
{
    $o = [];
    foreach ($ay as $k => $v) {
        $o[$k] = $pfx . $v;
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

function ay_convert_encoding(array $ay, $encoding = "BIG-5")
{
    $o = [];
    foreach ($ay as $k => $v) {
        $o[$k] = mb_convert_encoding($v, $encoding);
    }
    return $o;
}

function ay_csvStr(array $ay)
{
    $o = [];
    $i = 0;
    foreach ($ay as $v) {
        if (is_numeric($v) || is_null($v))
            $o[$i++] = $v;
        elseif (is_string($v))
            $o[$i++] = esc_dblQuote($v);
        else
            throw new Exception("ay has element of type none of (str num null)");
    }
    return join(",", $o);
}

/** return new array by deleting the element of $key if any else return $ay */
function ay_dltEle(array $ay, $key)
{
    $idx = ay_keyIdx($ay, $key);
    if ($idx === -1) return $ay;
    array_splice($ay, $idx, 1);
    return $ay;
}

/** return new array of same key but the value is mb_strwidth */
function ay_eleWdt(array $ay)
{
    $o = [];
    foreach ($ay as $k => $v) {
        $o[$k] = (is_object($v) || is_array($v)) ? 0 : mb_strwidth($v);
    }
    return $o;
}

function ay_escLF(array $ay)
{
    $o = [];
    foreach ($ay as $k => $v) {
        $o[$k] = esc_lf($v);
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

function ay_firstKey($Ay)
{
    reset($Ay);
    return each($Ay)["key"];
}

/** return the first index of $key in $ay if any else return -1 */
function ay_keyIdx(array $ay, $key)
{
    $o = 0;
    foreach ($ay as $k => $v) {
        if ($k === $key) return $o;
        $o++;
    }
    return -1;
}

/** return new array of merging keys from $ay1 & $ay2 with element which is greater */
function ay_maxEle(array $ay1, array $ay2)
{
    $o = $ay1;
    foreach ($ay2 as $i => $a2) {
        $a1 = @$o[$i];
        $a = max($a1, $a2);
        $o[$i] = $a;
    }
    return $o;
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

function ay_pk($assoc_ay, $pk)
{
    $o = [];
    foreach ($assoc_ay as $rec) {
        $pkVal = $rec[$pk];
        $o[$pkVal] = $rec;
    }
    return $o;
}

function ay_push_array(array &$o, array $ay)
{
    foreach ($ay as $i => $v) {
        array_push($o, $v);
    }
}

function ay_push_noDup(array &$ay, $itm)
{
    if (!(in_array($itm, $ay))) {
        array_push($ay, $itm);
    }
}

function ay_quote($ay, $q = "'")
{
    $o = [];
    foreach ($ay as $k => $v) {
        $o[$k] = quote($v, $q);
    }
    return $o;
}

function ay_rmvDup(array $ay)
{
    $o = [];
    $j = 0;
    $i = 0;
    foreach ($ay as $v) {
        $in_ay = in_ay($v, $ay, ++$j);
        if (!$in_ay)
            $o[$i++] = $v;
    }
    return $o;
}

function ay_rmvDup_rev(array $ay)
{
    $o = [];
    $j = 0;
    $i = 0;
    for ($j = sizeof($ay) - 1; $j >= 0; $j--) {
        $v = $ay[$j];
        $in_ay = in_ay($v, $ay, 0, $j - 1);
        if (!$in_ay)
            $o[$i++] = $v;
    }
    return array_reverse($o);
}

/** return new array by splice the element of $key and all next $len element if any else return $ay.  If $len is specified, all element will be deleted */
function ay_splice(array $ay, $key, $len = 0)
{
    $idx = ay_keyIdx($ay, $key);
    if ($idx === -1) return $ay;
    array_splice($ay, $idx, $len);
    return $ay;
}

/** return new array which is subset of $ay by using given $keyAy */
function ay_subSet(array $ay, array $keyAy)
{
    $o = [];
    foreach ($keyAy as $k) {
        $o[$k] = @$ay[$k];
    }
    return $o;
}

/** return an new by the key of $ay become value and value of $ay become key */
function ay_swapKV(array $ay)
{
    $o = [];
    foreach ($ay as $k => $v) {
        $o[$v] = $k;
    }
    return $o;
}

function ay_trim(array $ay)
{
    $o = [];
    foreach ($ay as $i)
        array_push($o, trim($i));
    return $o;
}

/** return the first $key of given $val in given $ay if such $val exist in $ay else throw exception */
function ay_valFirstKey(array $ay, $val)
{
    foreach ($ay as $k => $v) {
        if ($v === $val) return $k;
    }
    throw new Exception("no such val[$val] in given array");
}

/** return the first index of $val in $ay if any else return -1 */
function ay_valIdx(array $ay, $val)
{
    $o = 0;
    foreach ($ay as $v) {
        if ($v === $val) return $o;
        $o++;
    }
    return -1;
}

/** return the first $key of given $val in given $ay if such $val exist in $ay else push $val to $ay */
function ay_valKey(&$ay, $val)
{
    if (in_array($val, $ay)) return ay_valFirstKey($ay, $val);
    array_push($ay, $val);
}

function ay_write_dic(array $ay, $file)
{
    $o = [];
    foreach ($ay as $k => $v) {
        array_push($o, $k . ' = ' . $v);
    }
    ay_write_file($ay, $file);
}

function ay_write_file(array $ay, $file)
{
    $a = ay_escLF($ay);
    $b = ay_convert_encoding($ay);
    $c = join($b, "\n");
    file_put_contents($file, $c);
}

function in_ay($needle, array $haystack, $fmIdx = null, $toIdx = null)
{
    $fmIdx = is_null($fmIdx) ? 0 : $fmIdx;
    $toIdx = is_null($toIdx) ? sizeof($haystack) - 1 : $toIdx;
    for ($j = $fmIdx; $j <= $toIdx; $j++) {
        if ($haystack[$j] === $needle)
            return true;
    }
    return false;
}

function push_noDup(array &$ay, $i)
{
    ay_push_noDup($ay, $i);
}

function push_noNull(&$ay, $i)
{
    if (is_null($i)) return;
    array_push($ay, $i);
}
//} // ay_fn endClass
