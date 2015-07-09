<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 20/5/2015
 * Time: 9:42
 */
include_once 'ay.php';
const eUniq = 0;
const eYYYY = "Y";
const eYYYYMM = "Y-m";
const eYYYYMMDD = "Y-m-d";
const eYYYYMMDDHH = "Y-m-d H";
const eYYYYMMDDHHMM = "Y-m-d Hi";
const eYYYYMMDDHHMMSS = "Y-m-d His";
function brk_quote($q)
{
    $p = strpos($q, "*");
    if ($p > 0)
        return [
            left($q, $p - 1),
            substr($q, $p + 1)
        ];

    $len = strlen($q);
    if ($len == 1) return [$q, $q];
    if ($len == 2)
        return [
            left($q, 1),
            right($q, 1)
        ];
    return ['', ''];
}

/**  cut the first occurance of $chr in $s if any else return $s */
function cut_chr_and_aft($s, $chr)
{
    $p = strpos($s, $chr);
    if ($p > 0) {
        return substr($s, $p - 1);
    }
    return $s;
}

function cut_lastchr($s)
{
    return left($s, strlen($s) - 1);
}

function esc_lf($s)
{
    return str_replace("\n", '\n', $s);
}

function esc_tab($s)
{
    return str_replace("\t", "\\t", $s);
}

function fmt($s, ...$ay)
{
    return fmtAy($s, $ay);
}

function fmtAy($s, array $ay)
{
    $mAy = str2macroAy($s);
    $j = 0;
    foreach ($mAy as $m) {
        $s = str_replace($m, $ay[$j++], $s);
    }
    return $s;
}

function is_intStr($s)
{
    $a = strval(intval($s));
    $b = ($s === $a);
    return $b;
}

function is_lik_ay($s, $lik_ay)
{
    if (count($lik_ay) == 0) {
        return false;
    }
    foreach ($lik_ay as $lik) {
        if (fnmatch($lik, $s)) {
            return true;
        }
    }
    return false;
}

function is_pfx($s, $pfx)
{
    return substr($s, 0, strlen($pfx)) == $pfx;
}

function is_sfx($s, $sfx)
{
    return right($s, strlen($sfx)) === $sfx;
}

function left($s, $len)
{
    return substr($s, 0, $len);
}

function nDays($d1, $d2)
{
    $a = date_create($d1);
    $b = date_create($d2);
    $c = date_diff($a, $b);
    $aa = $a->format("Ymd");
    $bb = $b->format("Ymd");
    return ($aa > $bb)
        ? $c->days
        : -$c->days;
}

function norm_nm($nm)
{
    $a = '\\\'"*?<>/:#@';
    $o = $nm;
    for ($j = 1; j <= strlen($a); $j++) {
        $o = repl($o, substr($a, $j, 1), "_");
    }
    return $o;
}

function padR($s, $len)
{
    $len = strlen($s);
    if ($len > $len) {
        return $s;
    }
    return $s & space($len - $len);
}

function pass($s)
{
    echo 'pass: ' . $s . "\n";
}

function push_noNull(&$ay, $i)
{
    if (is_null($i)) return;
    array_push($ay, $i);
}

function quote($s, $q)
{
    list($q1, $q2) = brk_quote($q);
    return $q1 . $s . $q2;
}

function quote_lvs($lvs)
{
    $ay = split_lvs($lvs);
    return join(",", ay_quote($ay));
}

function repl_sfx($s, $fmSfx, $toSfx)
{
    if (is_sfx($s, $fmSfx)) {
        return rmv_sfx($s, $fmSfx) & $toSfx;
    }
    return $s;

}

function repl_vbar_tab($vbar_tab_str)
{
    return rep_vbar(repl_tab($vbar_tab_str));
}

function right($s, $len)
{
    return substr($s, -$len);
}

function rmv_dbl_spc($s)
{
    $o = trim($s);
    $p = strpos($s, "  ");
    while ($p > 0):
        $o = str_replace("  ", " ", $o);
        $p = strpos($o, "  ");
    endwhile;
    return $o;
}

function rmv_ffx($s, $pfx)
{
    if (is_pfx($s, $pfx)) {
        return substr($s, strlen($pfx) + 1);
    }
    return $s;
}

function rmv_lastChr($s)
{
    return left($s, strlen($s) - 1);
}

function rmv_sfx($s, $sfx)
{
    if (is_sfx($s, $sfx)) {
        return left($s, strlen($s) - strlen($sfx));
    }
    return $s;

}

function space($len)
{
    return str_repeat(" ", $len);
}

function split_lvs($lvs_or_array)
{
    $s = $lvs_or_array;
    if (is_null($s)) return [];
    if (is_array($s)) return $s;
    if (is_string($s)) return preg_split("/\\s+/", trim($s));
    $ty = gettype($s);
    throw new Exception("given \$lvs_or_array  should be string or array, but now[$ty]");
}

function str2macroAy($s)
{
    $ay = preg_match_all('/\$[0-9a-zA-Z_]+/', $s, $matches);
    return ay_rmvDup_rev($matches[0]);
}

function strSplitIntoChrAy($s)
{
//forFor J = 0 To strlen($s) - 1
//O(J) = substr($s, J + 1, 1)
//}
//StrSplitIntoChrAy = O
}

function str_brw($s)
{
    $ft = tmpFt();
    str_wrt($s, $ft);
    ft_brw($ft);
}

function str_is_blank($s)
{
    return trim($s) === '';
}

function str_nz($s, $blank_val)
{
    if (str_is_blank($s)) {
        return $blank_val;
    }
    return $s;
}

function str_wrap_star($s, $chr = "*")
{
    $len = strlen($s) + 6;
    $a = strrepeat($len, $chr);
    return "$a\r\n{$chr}$chr $s {$chr}$chr\r\n$a";
}

function str_wrt($s, $ft, $is_append = false)
{
    if ($is_append) {
        $a = "a";
    } else {
        $a = "c";
    }
    $f = fopen($ft, $a);
    fputs($f, $s);
    fclose($f);
}

function strbrk($s, $brkchr, $notrim = false)
{
    $p = strpos($s, $brkchr);
    if ($p === false) {
        throw new Exception("strBrk: \$s[$s] does contains \$brkchr[$brkchr].  Cannot break into 2.");
    }
    $len = strlen($brkchr);
    $o1 = left($s, $p);
    $o2 = substr($s, $p + $len);
    if (!$notrim) {
        $o1 = trim($o1);
        $o2 = trim($o2);
    }
    return [$o1, $o2];
}

function strbrk1($s, $brkchr)
{
//Aim: if BrkChr not found assign to o2 and clear o1
    $p = strpos($s, $brkchr);
    if ($p === false) {
        return [trim($s), ""];
    }
    $len = strlen($brkchr);
    $O1 = trim(left($s, $p - 1));
    $O2 = trim(substr($s, $p + $len));
    return [$O1, $O2];
}

function strbrk1_rev($s, $brkchr)
{
//Aim: if $brkchr not found assign to o2 and clear o1
    $p = strrpos($s, $brkchr);
    if ($p === false) {
        return [trim($s), ""];
    }
    $len = strlen($brkchr);
    $O1 = trim(left($s, $p - 1));
    $O2 = trim(substr($s, $p + $len));
    return [$O1, $O2];
}

function strbrk2($s, $brkchr)
{
//Aim: if BrkChr not found assign to o2 and clear o1
    $p = strpos($s, $brkchr);
    if ($p === 0) {
        return ['', trim($s)];
    }
    $len = strlen($brkchr);
    $o1 = trim(substr($s, $p - 1));
    $o2 = trim(substr($s, $p + $len));
    return [$o1, $o2];
}

function strbrk2_rev($s, $brkchr)
{
    //Aim: if BrkChr not found assign to o2 and clear o1
    $p = InStrRev($s, $brkchr);
    if ($p === 0) {
        return ["", trim($s)];
    }
    $len = strlen($brkchr);
    $O1 = trim(left($s, $p - 1));
    $O2 = trim(substr($s, $p + $len));
}

function strbrk_both($s, $brkchr)
{
    $p = strpos($s, $brkchr);
    if ($p === 0) {
        return [$s, $s];
    }
    return strbrk($s, $brkchr);
}

function strrbrk($s, $brkchr, $notrim = false)
{
    $p = strrpos($s, $brkchr);
    if ($p === false) {
        throw new Exception("strBrk: \$s[$s] does contains \$brkchr[$brkchr].  Cannot break into 2.");
    }
    $len = strlen($brkchr);
    $o1 = left($s, $p);
    $o2 = substr($s, $p + $len);
    if (!$notrim) {
        $o1 = trim($o1);
        $o2 = trim($o2);
    }
    return [$o1, $o2];
}

function tak_aftchr($s, $chr, $keepchr = false)
{
    $p = strpos($s, $chr);
    if ($p === false) {
        return "";
    }
    if ($keepchr) {
        return substr($s, $p);
    }
    return substr($s, $p + strlen($chr));
}

function tak_aftchr_rev($s, $chr, $keepchr = false)
{
    $p = strrpos($s, $chr);
    if ($p === false)
        return "";

    if ($keepchr)
        return substr($s, $p);

    return substr($s, $p + strlen($chr));
}

function tak_befchr($s, $chr, $keepchr = false)
{
    $p = strpos($s, $chr);
    if ($p === false) {
        return "";
    }
    if ($keepchr) {
        return left($s, $p - 1 + strlen($chr));
    }
    return left($s, $p - 1);
}

function tak_befchr_rev($s, $chr, $keepchr = false)
{
    $p = strrpos($s, $chr);
    if ($p === false)
        return "";

    if ($keepchr)
        return left($s, $p + strlen($chr));

    return left($s, $p);
}

function tim_stmp($fmt = 0)
{
    static $sno = 0;
    if ($fmt === 0) {
        $a = new DateTime();
        $b = $a->format("Y-m-d His");
        $c = $sno++;
        return "$b-$c";
    }
    return date_create()->format($fmt);
}
