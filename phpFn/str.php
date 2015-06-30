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

function str2macroAy($s)
{
    $ay = preg_match_all('/\$[0-9a-zA-Z_]+/', $s, $matches);
    return ay_rmvDup_rev($matches[0]);
}

function pass($s)
{
    echo 'pass: ' . $s . "\n";
}

function esc_lf($s)
{
    return str_replace("\n", '\n', $s);
}

function is_intStr($s)
{
    $a = strval(intval($s));
    $b = ($s === $a);
    return $b;
}

function nDays($d1, $d2)
{
    $a = date_create($d1);
    $b = date_create($d2);
    $c = date_diff($a, $b);
    $aa = $a->format("Ymd");
    $bb = $b->format("Ymd");
    if ($aa > $bb) {
        return $c->days;
    } else {
        return -$c->days;
    }
}

function push_noNull(&$_ay, $_i)
{
    if (is_null($_i)) return;
    array_push($_ay, $_i);
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

function norm_nm($nm)
{
    $a = '\\\'"*?<>/:#@';
    $o = $nm;
    for ($j = 1; j <= strlen($a); $j++) {
        $o = repl($o, substr($a, $j, 1), "_");
    }
    return $o;
}

function repl_sfx($_s, $fmSfx, $toSfx)
{
    if (is_sfx($_s, $fmSfx)) {
        return rmv_sfx($_s, $fmSfx) & $toSfx;
    }
    return $_s;

}

function rmv_ffx($_s, $pfx)
{
    if (is_pfx($_s, $pfx)) {
        return substr($_s, strlen($pfx) + 1);
    }
    return $_s;
}

function is_pfx($_s, $pfx)
{
    return substr($_s, 0, strlen($pfx)) == $pfx;
}

function is_lik_ay($_s, $_lik_ay)
{
    if (count($_lik_ay) == 0) {
        return false;
    }
    foreach ($_lik_ay as $lik) {
        if (fnmatch($lik, $_s)) {
            return true;
        }
    }
    return false;
}

function right($s, $len)
{
    return substr($s, -$len);
}

function rmv_lastChr($s)
{
    return left($s, strlen($s) - 1);
}

function is_sfx($s, $sfx)
{
    return right($s, strlen($sfx)) === $sfx;
}

function left($_s, $len)
{
    return substr($_s, 0, $len);
}

function rmv_sfx($_s, $_sfx)
{
    if (is_sfx($_s, $_sfx)) {
        return left($_s, strlen($_s) - strlen($_sfx));
    }
    return $_s;

}

function brk_quote($_q)
{
    $p = strpos($_q, "*");
    if ($p > 0)
        return [
            left($_q, $p - 1),
            substr($_q, $p + 1)
        ];

    $len = strlen($_q);
    if ($len == 1) return [$_q, $_q];
    if ($len == 2)
        return [
            left($_q, 1),
            right($_q, 1)
        ];
    return ['', ''];
}


function quote($_s, $_q)
{
    list($q1, $q2) = brk_quote($_q);
    return $q1 . $_s . $q2;
}

function tak_befchr($_s, $_chr, $_keepchr = false)
{
    $p = strpos($_s, $_chr);
    if ($p === false) {
        return "";
    }
    if ($_keepchr) {
        return left($_s, $p - 1 + strlen($_chr));
    }
    return left($_s, $p - 1);
}

function tak_aftchr($_s, $_chr, $_keepchr = false)
{
    $p = strpos($_s, $_chr);
    if ($p === false) {
        return "";
    }
    if ($_keepchr) {
        return substr($_s, $p);
    }
    return substr($_s, $p + strlen($_chr));
}

function quote_lvs($_lvs)
{
    $ay = split_lvs($_lvs);
    return join(",", ay_quote($ay));
}

function tak_befchr_rev($_s, $_chr, $_keepchr = false)
{
    $p = strrpos($_s, $_chr);
    if ($p === false)
        return "";

    if ($_keepchr)
        return left($_s, $p + strlen($_chr));

    return left($_s, $p);
}

function tak_aftchr_rev($_s, $_chr, $_keepchr = false)
{
    $p = strrpos($_s, $_chr);
    if ($p === false)
        return "";

    if ($_keepchr)
        return substr($_s, $p);

    return substr($_s, $p + strlen($_chr));
}

function cut_lastchr($s)
{
    return left($s, strlen($s) - 1);
}

function str_brw($_s)
{
    $ft = tmpFt();
    str_wrt($_s, $ft);
    ftBrw($ft);
}

function str_wrt($_s, $_ft, $_is_append = false)
{
    if ($_is_append) {
        $a = "a";
    } else {
        $a = "c";
    }
    $f = fopen($_ft, $a);
    fputs($f, $_s);
    fclose($f);
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

/**  cut the first occurance of $_chr in $_s if any else return $_s */
function cut_chr_and_aft($s, $chr)
{
    $p = strpos($s, $chr);
    if ($p > 0) {
        return substr($s, $p - 1);
    }
    return $s;
}

function str_wrap_star($s, $chr = "*")
{
    $len = strlen($s) + 6;
    $a = strrepeat($len, $chr);
    return "$a\r\n{$chr}$chr $s {$chr}$chr\r\n$a";
}

function str_is_blank($s)
{
    return trim($s) === '';
}

function strSplitIntoChrAy($_s)
{
//forFor J = 0 To strlen($_s) - 1
//O(J) = substr($_s, J + 1, 1)
//}
//StrSplitIntoChrAy = O
}

function str_nz($_s, $_blank_val)
{
    if (str_is_blank($_s)) {
        return $_blank_val;
    }
    return $_s;
}

function padR($_s, $_len)
{
    $len = strlen($_s);
    if ($len > $_len) {
        return $_s;
    }
    return $_s & space($_len - $len);
}

function space($len)
{
    return str_repeat(" ", $len);
}

function repl_vbar_tab($vbar_tab_str)
{
    return rep_vbar(repl_tab($vbar_tab_str));
}

function esc_tab($s)
{
    return str_replace("\t", "\\t", $s);
}

function rmv_dbl_spc($_s)
{
    $o = trim($_s);
    $p = strpos($_s, "  ");
    while ($p > 0):
        $o = str_replace("  ", " ", $o);
        $p = strpos($o, "  ");
    endwhile;
    return $o;
}

function strbrk2($_s, $_brkchr)
{
//Aim: if BrkChr not found assign to o2 and clear o1
    $p = strpos($_s, $_brkchr);
    if ($p === 0) {
        return ['', trim($_s)];
    }
    $len = strlen($_brkchr);
    $o1 = trim(substr($_s, $p - 1));
    $o2 = trim(substr($_s, $p + $len));
    return [$o1, $o2];
}

function strbrk1($_s, $_brkchr)
{
//Aim: if BrkChr not found assign to o2 and clear o1
    $p = strpos($_s, $_brkchr);
    if ($p === false) {
        return [trim($_s), ""];
    }
    $len = strlen($_brkchr);
    $O1 = trim(left($_s, $p - 1));
    $O2 = trim(substr($_s, $p + $len));
    return [$O1, $O2];
}

function strbrk2_rev($_s, $_brkchr)
{
    //Aim: if BrkChr not found assign to o2 and clear o1
    $p = InStrRev($_s, $_brkchr);
    if ($p === 0) {
        return ["", trim($_s)];
    }
    $len = strlen($_brkchr);
    $O1 = trim(left($_s, $p - 1));
    $O2 = trim(substr($_s, $p + $len));
}

function strbrk1_rev($_s, $_brkchr)
{
//Aim: if $_brkchr not found assign to o2 and clear o1
    $p = strrpos($_s, $_brkchr);
    if ($p === false) {
        return [trim($_s), ""];
    }
    $len = strlen($_brkchr);
    $O1 = trim(left($_s, $p - 1));
    $O2 = trim(substr($_s, $p + $len));
    return [$O1, $O2];
}

function strbrk_both($_s, $_brkchr)
{
    $p = strpos($_s, $_brkchr);
    if ($p === 0) {
        return [$_s, $_s];
    }
    return strbrk($_s, $_brkchr);
}

function strrbrk($_s, $_brkchr, $_notrim = false)
{
    $p = strrpos($_s, $_brkchr);
    if ($p === false) {
        throw new Exception("strBrk: \$_s[$_s] does contains \$_brkchr[$_brkchr].  Cannot break into 2.");
    }
    $len = strlen($_brkchr);
    $o1 = left($_s, $p);
    $o2 = substr($_s, $p + $len);
    if (!$_notrim) {
        $o1 = trim($o1);
        $o2 = trim($o2);
    }
    return [$o1, $o2];
}

function strbrk($_s, $_brkchr, $_notrim = false)
{
    $p = strpos($_s, $_brkchr);
    if ($p === false) {
        throw new Exception("strBrk: \$_s[$_s] does contains \$_brkchr[$_brkchr].  Cannot break into 2.");
    }
    $len = strlen($_brkchr);
    $o1 = left($_s, $p);
    $o2 = substr($_s, $p + $len);
    if (!$_notrim) {
        $o1 = trim($o1);
        $o2 = trim($o2);
    }
    return [$o1, $o2];
}