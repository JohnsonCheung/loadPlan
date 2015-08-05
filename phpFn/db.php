<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-08
 * Time: 15:38
 */

require_once 'ay.php';
require_once 'str.php';
const Q = "'";

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

function nz($a, $nz)
{
    return $a ? $a : $nz;
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
    if ($res === false) throw new Exception("\nMsg: [{$con->error}]\nSql: [$sql]\n\n");
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
