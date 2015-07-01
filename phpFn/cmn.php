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
const PTH_TMP_HOM = "c:/Temp/";
const Q = "'";
require_once "System/Launcher.php";

class BldSql
{
    private
        $dro,           // data row object.
        $dra,           // data row associated array.  Becomes from $dro
        $pk,            // fld first in $fld
        $fld = [],      // all field support in $dro.  In $dro may have more or less field in $fld.
        $req = [],      // fld name ay wich is required.  If not given in $dro, throw exception
        $tim = [],      // fld name ay which is time-format so that to build a fldStrVal good put into sql as time
        $dte = [],      // fld name ay which is date-format so that to build a fldStrVal good put into sql as time
        $bool = [],     // fld name ay  which is boolean-format so that to build a fldStrVal good put into sql as time
        $fldNm2valStr,  // it is array build from $this->dro. Each field in $this->dro will be the key and valStr() its value as the value
        $o = [];


    function __construct($dro, $tblNm, $fldLvs, $reqLvs = null, $boolLvs = null, $timLvs = null, $dteLvs = null)
    {
        $this->dro = $dro;
        $this->dra = get_object_vars($dro);
        $this->drFld = array_keys($this->dra);
        $this->fld = split_lvs($fldLvs);
        $this->req = split_lvs($reqLvs);
        $this->bool = split_lvs($boolLvs);
        $this->tim = split_lvs($timLvs);
        $this->dte = split_lvs($dteLvs);
        $this->pk = $this->fld[0];
        $this->tblNm = $tblNm;
        $this->fldNm2valStr = $this->fldNm2valStr();
        $this->o = [];
    }

    private function fldNm2valStr()
    {
        $dra = $this->dra;
        $ay = $this->drFld;
        $ay1 = array_intersect($ay, $this->fld);
        $o = [];
        $j = 0;
        foreach ($ay1 as $fld) {
            $o[$fld] = $this->fldValStr($fld, $dra[$fld]);
        }
        return $o;
    }

    private function fldValStr($fldNm, $v)
    {
        if (in_array($fldNm, $this->bool)) {
            return $v ? "b'1'" : "b'0'";
        } elseif (in_array($fldNm, $this->tim)) {
            return "'$v'";
        } elseif (in_array($fldNm, $this->dte)) {
            return "'$v'";
        }
        return "'$v'";
    }

    function insStmt()
    {
        $a = $this->tblNm;
        $b = $this->fldNm2valStr();
        $pk = $this->pk;
        ay_splice_assoc($b, $pk, 1);
        $c = array_keys($b);
        $c1 = join(",", $c);
        $d = array_values($b);
        $d1 = join(",", $d);
        return "insert into $a ($c1) values($d1)";
    }

    function updStmt()
    {
        $this->chkMissing();
        $a = $this->tblNm;
        $pk = $this->pk;
        $b = $this->fldNm2valStr;
        ay_splice_assoc($b, $pk, 1);
        $c = join(", ", ay_join_kv("=", $b));
        $pkVal = $this->dro->$pk;
        return "update $a set $c where $pk='$pkVal';";
    }

    /** each field in $this->req should have value in $this->dro */
    private function chkMissing()
    {
        $reqAy = $this->req;
        $drFld = $this->drFld;
        $ay = ay_minus($reqAy, $drFld);
        if (sizeof($ay) > 0) {
            $a = join(" ", $ay);
            $b = join(' ', $this->req);
            $c = join(' ', $drFld);
            echo "There are required field not found in given [dro]\n";
            echo "They are = [$a]\n";
            echo "All required fields = [$b]\n";
            echo "Given fields in [dro] = [$c]\n";
            die();
        }
    }
}

class AyGluer
{

    private $glue;

    function __construct($Glue)
    {
        $this->glue = $Glue;
    }

    function ayGluer($Glue)
    {
        return (new AyGluer($Glue))->glueFn();
    }

}

function add_backSlash($pth)
{
    return right($pth, 1) === '\\' ? $pth : $pth . '\\';
}

function assert_key_exists($key, array $ay)
{
    if (!array_key_exists($key, $ay)) {
        $keys = join(' ', array_keys($ay));
        throw new Exception("key[$key] not found\nin array_keys=[$keys]");
    }
}

/** return an array from assoc-$ay with fields as specified in [$boolLvs] converted from '0-1' to true-false  */
function ay_convert_bool(array $ay, $boolLvs)
{
    $a = split_lvs($boolLvs);
    $o = $ay;
    foreach ($a as $fld) {
        if (!array_key_exists($fld, $ay)) {
            $o[$fld] = false;
        } else {
            switch ($o[$fld]) {
                case null:
                case '1':
                    $o[$fld] = true;
                    break;
                case '0':
                    $o[$fld] = false;
                    break;
                default:
                    echo "bool-Fld[$fld] has value <>1 or 0 or null\n";
                    echo "boolLvs=[$boolLvs]\n";
                    echo "the value=\n";
                    var_dump($o[$fld]);
                    echo "ay=\n";
                    var_dump($ay);
                    throw new Exception('see above');
            }
        }
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

/** return an array by joining the $k and $v by $sep of given $ay */
function ay_join_kv($sep, $ay)
{
    $o = [];
    foreach ($ay as $k => $v) {
        array_push($o, $k . $sep . $v);
    }
    return $o;
}

/** return the idx of the key in $ay */
function ay_key_idx($ay, $key)
{
    $j = 0;
    foreach ($ay as $k => $v) {
        if ($key === $k) return $j;
        $j++;
    }
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

/** $offset can be key or long, $length can be $key or length */
function ay_splice_assoc(&$ay, $offset, $length = null, $replacement = null)
{
    if (is_string($offset) || is_string($length)) {
        $key_indices = array_flip(array_keys($ay));
        if (is_string($offset)) {
            if (!isset($ay[$offset])) return [];    //<-- just return []; if $offset is a string and not found $ay;
            $offset = $key_indices[$offset];
        }
        if (is_string($length)) {
            if (!isset($ay[$length])) throw new Exception('[length] is a string, but not found in [ay]');
            $length = $key_indices[$length] - $offset + 1;
        }
    }

    $o = array_slice($ay, $offset, $length, true);

    $ay = array_slice($ay, 0, $offset, TRUE)
        + (array)$replacement
        + array_slice($ay, $offset + $length, NULL, TRUE);

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

function ay_trim($Ay)
{
    $o = [];
    foreach ($Ay as $i)
        array_push($o, trim($i));
    return $o;
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

/** each element in $ayAp is an array of same size.  Return an array by adding each corresponding element in $ayAp using $sep */
function ay_zip($sep, ...$ayAp)
{
    $o = [];
    $nRec = sizeof($ayAp[0]);
    $nAy = sizeof($ayAp);
    for ($j = 0; $j < $nRec; $j++) {
        $m = [];
        for ($i = 0; $i < $nAy; $i++) {
            array_push($m, $ayAp[$i][$j]);
        }
        array_push($o, join($sep, $m));
    }
    return $o;
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

function brw_csvPth($csvPth)
{
    $fm = pth_norm(__DIR__ . "/../xlsm/OpnCsvPth.xlsm");
    $to = $csvPth . "OpnCsvPth.xlsm";
    copy($fm, $to);
    $a = new System_Launcher;
    $a->Launch($to);
    return $csvPth;
}

function brw_dtaAy($nm_lvs, ...$dtaAy)
{
    $p = dtaAy_tmpPth_array($nm_lvs, $dtaAy);
    return brw_csvPth($p);
}

function brw_ft($ft)
{
    $a = new System_Launcher;
    $a->Launch($ft);
}

/**  cut the first occurance of $_chr in $s if any else return $s */
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

function db_con($dbNm = "loadplan")
{
    $o = new mysqli("localhost", "root", "ritachan", $dbNm);
    if ($o->connect_errno)
        die("Failed to connect to MySQL: " . $o->connect_error);
    mysqli_set_charset($o, 'utf8');
    return $o;
}

function db_cvStr(mysqli $con, $s)
{
    if (is_null($s))
        return 'NULL';
    if (is_string($s))
        return Q . $con->real_escape_string(($s)) . Q;
    $a = gettype($s);
    throw new Exception("dta is not string nor NULL, but [$a]");
}

function db_isPkValExist($con, $tbl, $pkVal)
{

}

function dtaAy_tmpPth($nm_lvs, ...$dtaAy)
{
    return dtaAy_tmpPth_array($nm_lvs, $dtaAy);
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

function dta_convert_encoding($dta, $encoding = "BIG-5")
{
    $o = [];
    foreach ($dta as $k => $dr) {
        $o[$k] = ay_convert_encoding($dr, $encoding);
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

function dta_join($Glue, $Dta)
{
    $a = ayGluer($Glue);
    return array_reduce($Dta, $a, []);
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

function esc_lf($s)
{
    return str_replace("\n", '\n', $s);
}

function esc_tab($s)
{
    return str_replace("\t", "\\t", $s);
}

function glueFn()
{
    return function ($Ay, $Dr) {
        $m = join($this->glue, $Dr);
        array_push($Ay, $m);
        return $Ay;
    };
}

function is_intStr($s)
{
    $a = strval(intval($s));
    $b = ($s === $a);
    return $b;
}

function is_lik_ay($s, $_lik_ay)
{
    if (count($_lik_ay) == 0) {
        return false;
    }
    foreach ($_lik_ay as $lik) {
        if (fnmatch($lik, $s)) {
            return true;
        }
    }
    return false;
}

function is_main()
{
    if ($_SERVER['argv'][0] !== $_SERVER['SCRIPT_FILENAME']) exit();
}

function is_pfx($s, $pfx)
{
    return substr($s, 0, strlen($pfx)) == $pfx;
}

function is_pth($pth)
{
    return is_dir($pth);
}

function is_server()
{
    return isset($_SERVER['HTTP_HOST']);
}

function is_sfx($s, $sfx)
{
    return right($s, strlen($sfx)) === $sfx;
}

function left($s, $len)
{
    return substr($s, 0, $len);
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

/** return a msgDic of msgNm2Lbl from lblPgm->msgNmLvs ==> lblMsg */
function msgDic($con, $pgmNm, $secNm, $lang)
{
    $msgNmLvs = runsql_val($con, "select msgNmLvs from lblPgm where pgmNm='$pgmNm' and $secNm='$secNm';");
    return lblDic("msg", $msgNmLvs, $lang, $con);
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

function norm_nm($nm)
{
    $a = '\\\'"*?<>/:#@';
    $o = $nm;
    for ($j = 1; j <= strlen($a); $j++) {
        $o = repl($o, substr($a, $j, 1), "_");
    }
    return $o;
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

function padR($s, $_len)
{
    $len = strlen($s);
    if ($len > $_len) {
        return $s;
    }
    return $s & space($_len - $len);
}

function pass($s)
{
    echo "pass: " . $s . "\n";
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

function push_noDup(array &$ay, $i)
{
    ay_push_noDup($ay, $i);
}

function push_noNull(&$_ay, $_i)
{
    if (is_null($_i)) return;
    array_push($_ay, $_i);
}

function quote($s, $_q)
{
    list($q1, $q2) = brk_quote($_q);
    return $q1 . $s . $q2;
}

function quote_lvs($_lvs)
{
    $ay = split_lvs($_lvs);
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

function rmv_end_backSlash($pth)
{
    if (is_sfx($pth, "\\"))
        return rmv_lastchr($pth);
    return $pth;
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

function runsp(mysqli $con, $sql)
{
    $res = $con->real_query($sql);
    if ($res === false) throw new Exception("{$con->error}\nSql: [$sql]\n\n");
    return $con->store_result();
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

function runsp_dta(mysqli $con, $sql, $resulttype = MYSQLI_ASSOC)
{
    $res = runsql($con, $sql);
    $dta = $res->fetch_all($resulttype);
    $res->free();
    $con->next_result();
    return $dta;
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

/** @return mysqli_result */
function runsql(mysqli $con, $sql)
{
    $res = $con->query($sql);
    if ($res === false) throw new Exception("\nMsg: [{$con->error}]<br>\nSql: [$sql]\n\n");
    return $res;
}

function runsql_bool(mysqli $con, $sql)
{
    return boolval(runsql_val($con, $sql));
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

function runsql_dro(mysqli $con, $sql)
{
    $res = runsql($con, $sql);
    $o = $res->fetch_object();
    $res->free();
    $con->next_result();
    return $o;
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

function runsql_exec(mysqli $con, $sql)
{
    runsql($con, $sql);
    if ($con->error) throw new Exception($con->error);
    $o = $con->affected_rows;
    $con->next_result();
    return $o;
}

function runsql_int(mysqli $con, $sql)
{
    return intval(runsql_val($con, $sql));
}

function runsql_isAny(mysqli $con, $sql)
{
    $res = runsql($con, $sql);
    return $res->num_rows > 0;
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

function runsql_list(mysqli $con, $sql)
{
    return runsql_dr($con, $sql, MYSQLI_NUM);
}

function runsql_pk(mysqli $con, $sql, $pk, $resulttype = MYSQLI_ASSOC)
{
    return ay_pk(runsql_dta($con, $sql, $resulttype), $pk);
}

function runsql_rs(mysqli $con, $sql)
{
    $res = runsql($con, $sql);
    $dta = $res->fetch_all(MYSQLI_ASSOC);
    $res->free();
    $con->next_result();
    return dta_rs($dta);
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

function space($len)
{
    return str_repeat(" ", $len);
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

function split_lvs($lvs)
{
    if (is_null($lvs)) return [];
    if (is_array($lvs)) return $lvs;
    if (!is_string($lvs)) {
        $ty = gettype($lvs);
        throw new Exception("\$lvs should be string or array or null, but now[$ty]");
    }
    return preg_split("/\s+/", trim($lvs));
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
    ftBrw($ft);
}

function str_is_blank($s)
{
    return trim($s) === '';
}

function str_nz($s, $_blank_val)
{
    if (str_is_blank($s)) {
        return $_blank_val;
    }
    return $s;
}

function str_wrap_star($s, $chr = "*")
{
    $len = strlen($s) + 6;
    $a = strrepeat($len, $chr);
    return "$a\r\n{$chr}$chr $s {$chr}$chr\r\n$a";
}

function str_wrt($s, $_ft, $_is_append = false)
{
    if ($_is_append) {
        $a = "a";
    } else {
        $a = "c";
    }
    $f = fopen($_ft, $a);
    fputs($f, $s);
    fclose($f);
}

function strbrk($s, $_brkchr, $_notrim = false)
{
    $p = strpos($s, $_brkchr);
    if ($p === false) {
        throw new Exception("strBrk: \$s[$s] does contains \$_brkchr[$_brkchr].  Cannot break into 2.");
    }
    $len = strlen($_brkchr);
    $o1 = left($s, $p);
    $o2 = substr($s, $p + $len);
    if (!$_notrim) {
        $o1 = trim($o1);
        $o2 = trim($o2);
    }
    return [$o1, $o2];
}

function strbrk1($s, $_brkchr)
{
//Aim: if BrkChr not found assign to o2 and clear o1
    $p = strpos($s, $_brkchr);
    if ($p === false) {
        return [trim($s), ""];
    }
    $len = strlen($_brkchr);
    $O1 = trim(left($s, $p - 1));
    $O2 = trim(substr($s, $p + $len));
    return [$O1, $O2];
}

function strbrk1_rev($s, $_brkchr)
{
//Aim: if $_brkchr not found assign to o2 and clear o1
    $p = strrpos($s, $_brkchr);
    if ($p === false) {
        return [trim($s), ""];
    }
    $len = strlen($_brkchr);
    $O1 = trim(left($s, $p - 1));
    $O2 = trim(substr($s, $p + $len));
    return [$O1, $O2];
}

function strbrk2($s, $_brkchr)
{
//Aim: if BrkChr not found assign to o2 and clear o1
    $p = strpos($s, $_brkchr);
    if ($p === 0) {
        return ['', trim($s)];
    }
    $len = strlen($_brkchr);
    $o1 = trim(substr($s, $p - 1));
    $o2 = trim(substr($s, $p + $len));
    return [$o1, $o2];
}

function strbrk2_rev($s, $_brkchr)
{
    //Aim: if BrkChr not found assign to o2 and clear o1
    $p = InStrRev($s, $_brkchr);
    if ($p === 0) {
        return ["", trim($s)];
    }
    $len = strlen($_brkchr);
    $O1 = trim(left($s, $p - 1));
    $O2 = trim(substr($s, $p + $len));
}

function strbrk_both($s, $_brkchr)
{
    $p = strpos($s, $_brkchr);
    if ($p === 0) {
        return [$s, $s];
    }
    return strbrk($s, $_brkchr);
}

function strrbrk($s, $_brkchr, $_notrim = false)
{
    $p = strrpos($s, $_brkchr);
    if ($p === false) {
        throw new Exception("strBrk: \$s[$s] does contains \$_brkchr[$_brkchr].  Cannot break into 2.");
    }
    $len = strlen($_brkchr);
    $o1 = left($s, $p);
    $o2 = substr($s, $p + $len);
    if (!$_notrim) {
        $o1 = trim($o1);
        $o2 = trim($o2);
    }
    return [$o1, $o2];
}

function tak_aftchr($s, $_chr, $_keepchr = false)
{
    $p = strpos($s, $_chr);
    if ($p === false) {
        return "";
    }
    if ($_keepchr) {
        return substr($s, $p);
    }
    return substr($s, $p + strlen($_chr));
}

function tak_aftchr_rev($s, $_chr, $_keepchr = false)
{
    $p = strrpos($s, $_chr);
    if ($p === false)
        return "";

    if ($_keepchr)
        return substr($s, $p);

    return substr($s, $p + strlen($_chr));
}

function tak_befchr($s, $_chr, $_keepchr = false)
{
    $p = strpos($s, $_chr);
    if ($p === false) {
        return "";
    }
    if ($_keepchr) {
        return left($s, $p - 1 + strlen($_chr));
    }
    return left($s, $p - 1);
}

function tak_befchr_rev($s, $_chr, $_keepchr = false)
{
    $p = strrpos($s, $_chr);
    if ($p === false)
        return "";

    if ($_keepchr)
        return left($s, $p + strlen($_chr));

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

?>