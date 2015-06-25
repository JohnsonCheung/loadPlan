<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23/6/2015
 * Time: 16:08
 */
const eUniq = 0;
const eYYYY = "Y";
const eYYYYMM = "Y-m";
const eYYYYMMDD = "Y-m-d";
const eYYYYMMDDHH = "Y-m-d H";
const eYYYYMMDDHHMM = "Y-m-d Hi";
const eYYYYMMDDHHMMSS = "Y-m-d His";
const PTH_TMP_HOM = "c:\\Temp\\";
require_once "System\Launcher.php";
function brw_csvPth($csvPth)
{
    $fm = pth_norm(__DIR__ . "\\..\\xlsm\\OpnCsvPth.xlsm");
    $to = $csvPth . "OpnCsvPth.xlsm";
    copy($fm, $to);
    $a = new System_Launcher;
    $a->Launch($to);
    return $csvPth;
}

function pth_tmp($seg = null)
{
    $o = is_null($seg)
        ? PTH_TMP_HOM
        : PTH_TMP_HOM . $seg . "\\" . tim_stmp() . "\\";
    pth_create_if_not_exist($o);
    return $o;
}

function is_pth($pth)
{
    return is_dir($pth);
}

function rmv_end_backSlash($pth)
{
    if (is_sfx($pth, "\\"))
        return rmv_lastchr($pth);
    return $pth;
}

function pth_create_if_not_exist($pth)
{
    $a = rmv_end_backSlash($pth);
    if (!is_dir($a)) {
        mkdir($a, 0777, true);
    }
}

function pth_clear_files($pth)
{
    $fn_ay = pth_fnAy($pth);
    foreach ($fn_ay as $fn) {
        unlink($pth . $fn);
    }
}

function add_backSlash($pth)
{
    return right($pth, 1) === '\\' ? $pth : $pth . '\\';
}

function pth_opn($pth)
{
    $cmd = "explorer " . '"' . $pth . '"';
    exec($cmd);
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

function split_lvs($lvs)
{
    if (is_array($lvs)) return $lvs;
    if (!is_string($lvs)) {
        $ty = gettype($lvs);
        throw new Exception("\$lvs should be string or array, but now[$ty]");
    }
    $a = rmv_dbl_spc($lvs);
    if ($a === "")
        return [];
    return explode(" ", $a);
}


function split_lvc($lvc)
{
    if (is_null($lvc)) return [];
    if (is_array($lvc)) return $lvc;
    if (!is_string($lvc)) {
        $ty = gettype($lvc);
        throw new Exception("\$lvs should be string or array, but now[$ty]");
    }
    if ($lvc === "")
        return [];
    $o = explode(",", $lvc);
    foreach ($o as $i => $k)
        $o[$i] = trim($k);
    return $o;
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

const Q = "'";
function db_cvStr(mysqli $con, $s)
{
    if (is_null($s))
        return 'NULL';
    if (is_string($s))
        return Q . $con->real_escape_string(($s)) . Q;
    $a = gettype($s);
    throw new Exception("dta is not string nor NULL, but [$a]");
}

function logFt($varNm, $val, $ft)
{
    $fd = fopen("c:/temp/$ft", "a");
    fwrite($fd, "\r\n-------------\r\n");
    fwrite($fd, $varNm . "\r\n");
    $o = print_r($val, true);
    fwrite($fd, $o);
    fclose($fd);
}

function runsql_dataObj(mysqli $con, $sql)
{
    $o = [];
    $res = runsql($con, $sql);
    for ($j = 0; $j < $res->num_rows; $j++) {
        $res->data_seek($j);
        $row = $res->fetch_object();
        array_push($o, $row);
    }
    $res->free();
    $con->next_result();
    return $o;
}

function runsql_dro(mysqli $con, $sql)
{
    $res = runsql($con, $sql);
    $o = $res->fetch_object();
    $res->free();
    $con->next_result();
    return $o;
}

function runsql_exec(mysqli $con, $sql)
{
    runsql($con, $sql);
    $o = $con->affected_rows;
    $con->next_result();
    return $o;
}

function runsql_pk(mysqli $con, $sql, $pk, $resulttype = MYSQLI_ASSOC)
{
    return ay_pk(runsql_dta($con, $sql, $resulttype), $pk);
}

function runsql_keyVal(mysqli $con, $sql)
{
    // return all records from $sql as Key=>Val.  Assuming $sql return 2 columns: first column is unique
    $o = [];
    $res = $con->query($sql);
    if ($res === false) throw new Exception("{$con->error}\nSql: [$sql]\n\n");
    for ($j = 0; $j < $res->num_rows; $j++) {
        $res->data_seek($j);
        $row = $res->fetch_array(MYSQLI_NUM);
        $o[$row[0]] = $row[1];
    }
    $res->free();
    $con->next_result();
    return $o;
}

function runsp_dro(mysqli $con, $sql)
{
    /** @var  $res mysqli_result */
    $res = runsql($con, $sql);
    if ($res->num_rows !== 1) throw Exception("sql return 0 rows.  Sql=[$sql]");
    $o = $res->fetch_object();
    $res->free();
    $con->next_result();
    return $o;
}

function runsp_rs(mysqli $con, $sql, $resulttype = MYSQLI_ASSOC)
{
    /** @var  $res string */
    $res = runsql($con, $sql);
    if ($res->num_rows === 0) return [];
    $dta = $res->fetch_all($resulttype);
    $res->free();
    $con->next_result();
    return dta_rs($dta);
}

function runsp_dic(mysqli $con, $sp_ofTwoCol_first_isPk)
{
    $res = runsql($con, $sp_ofTwoCol_first_isPk);
    $dta = $res->fetch_all();
    $res->free();
    $con->next_result();
    $o = [];
    foreach ($dta as $dr) {
        $pk = $dr[0];
        $o[$pk] = $dr[1];
    }
    return $o;
}

function runsp_dta(mysqli $con, $sql, $resulttype = MYSQLI_ASSOC)
{
    $res = runsql($con, $sql);
    $dta = $res->fetch_all($resulttype);
    $res->free();
    $con->next_result();
    return $dta;
}

function dta_rs(array $dta)
{
    if (count($dta) === 0) return [];
    $row0 = $dta[0];
    reset($row0);
    $pkNm = each($row0)["key"];
    $o = [];
    foreach ($dta as $dr) {
        $pk = $dr[$pkNm];
        if (in_array($pk, $o))
            throw new Exception("dup pk: $pkNm[$pk]");
        $o[$pk] = $dr;
    }
    return $o;
}

/** @return mysqli_result */
function runsql(mysqli $con, $sql)
{
    $res = $con->query($sql);
    if ($res === false) throw new Exception("\nMsg: [{$con->error}]\nSql: [$sql]\n\n");
    return $res;
}

function runsp(mysqli $con, $sql)
{
    $res = $con->real_query($sql);
    if ($res === false) throw new Exception("{$con->error}\nSql: [$sql]\n\n");
    return $con->store_result();
}

function runsql_dta(mysqli $con, $sql, $resulttype = MYSQLI_ASSOC)
{
    /** @var $res mysqli_result */
    $res = runsql($con, $sql);
    $o = $res->fetch_all($resulttype);
    $res->free();
    $con->next_result();
    return $o;
}

function runsql_dicDic(mysqli $con, $sql)
{
    // use first 2 fields of $sql as key and 3 fields as value to return a named-array, $o, so that,
    // $v = $o[$k1][$k2], $v will be the 3rd-fields value and $k1, $k2 are the 1st and 2nd field value
    $dta = runsql_dta($con, $sql, MYSQLI_NUM);
    foreach ($dta as $dr) {
        list($d1, $d2, $d3) = $dr;
        $o[$d1][$d2] = $d3;
    }
    return $o;
}

function runsql_isAny(mysqli $con, $sql)
{
    $res = runsql($con, $sql);
    return $res->num_rows > 0;
}

function runsql_datetime(mysqli $con, $sql)
{
    return new DateTime(runsql_val($con, $sql));
}

/**
 * reutrn first column for the result as an array
 */
function runsql_dc(mysqli $con, $sql)
{
    $res = runsql($con, $sql);
    $dta = $res->fetch_all();
    $res->free();
    $con->next_result();
    $o = [];
    foreach ($dta as $dr) {
        array_push($o, $dr[0]);
    }
    return $o;
}

function runsql_bool(mysqli $con, $sql)
{
    return boolval(runsql_val($con, $sql));
}

function runsql_val(mysqli $con, $sql)
{
    $res = runsql($con, $sql);
    if ($res->num_rows == 0) {
        $msg = $con->error;
        throw new Exception("no rec return sql[$sql]  sqlMsg=[$msg]");
    }
    $o = $res->fetch_row()[0];
    $res->free();
    $con->next_result();
    return $o;
}

function runsql_drObj(mysqli $con, $sql)
{
    $res = runsql($con, $sql);
    if ($res->num_rows == 0) {
        return [];
    }
    $res->data_seek(0);
    $o = $res->fetch_object();
    $res->free();
    $con->next_result();
    return $o;
}

function runsql_dic(mysqli $con, $sql_ofTwoCol_first_isPk)
{
    $res = runsql($con, $sql_ofTwoCol_first_isPk);
    $dta = $res->fetch_all();
    $res->free();
    $con->next_result();
    $o = [];
    foreach ($dta as $dr) {
        $pk = $dr[0];
        $o[$pk] = $dr[1];
    }
    return $o;
}

function runsql_rs(mysqli $con, $sql)
{
    $res = runsql($con, $sql);
    $dta = $res->fetch_all(MYSQLI_ASSOC);
    $res->free();
    $con->next_result();
    return dta_rs($dta);
}

function runsql_list(mysqli $con, $sql)
{
    return runsql_dr($con, $sql, MYSQLI_NUM);
}

/** @return array */
function runsql_dr(mysqli $con, $sql, $resulttype = MYSQLI_ASSOC)
{
    $res = runsql($con, $sql);
    if ($res->num_rows == 0) return [];
    $res->data_seek(0);
    switch ($resulttype) {
        case MYSQLI_NUM:
        case MYSQLI_ASSOC:
        case MYSQLI_BOTH:
            return $res->fetch_array($resulttype);
        default:
            throw new Exception("invalid \$resulttype=[$resulttype]");
    }
}

function runsql_int(mysqli $con, $sql)
{
    return intval(runsql_val($con, $sql));
}

function db_con($dbNm = "loadplan")
{
    $o = new mysqli("localhost", "root", "ritachan", $dbNm);
    if ($o->connect_errno)
        die("Failed to connect to MySQL: " . $o->connect_error);
    mysqli_set_charset($o, 'utf8');
    return $o;
}

function pass($s)
{
    echo "pass: " . $s;
}

function db_isPkValExist($con, $tbl, $pkVal)
{

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

function obj_newByLpAp($lp, ...$ap)
{
    $o = new stdClass();
    $kAy = preg_split('/\s+/', $lp);
    foreach ($kAy as $k) {
        $o->$k = current($ap);
        next($ap);
    }
    return $o;
}

/** return a dicDic of [fldNm][msgNm] to $msg */
function msg_fldNm_msgNm($con, $pgmNm, $secNm, $lang)
{
    // table-lblPgmFldMsg = pgmNm, secNm, fldNm, msgNm, lang, msg
    return runsql_dicDic($con, "select fldNm, msgNm, msg from lblPgmFldMsg where lang='$lang' and pgmNm='$pgmNm' and $secNm='$secNm';");
}

?>