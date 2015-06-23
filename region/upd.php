<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/6/2015
 * Time: 18:47
 */
include_once "/../phpFn/cmn.php";
include_once '/../dbTools/addMissingNearBy.php';
function updRegDs($con, $regDs)
{
    $regDro = $regDs->regDro;
    $regCd = $regDro->regCd;
    $nearByDt = $regDs->nearByDt;
    updRegDro($con, $regDro);
    updNearByAy($con, $regCd, $nearByDt);
}

function updRegDro($con, $regDro)
{
    $o = $regDro;
    $regCd = $o->regCd;
    $inpCd = $o->inpCd;
    $chiNm = $o->chiNm;
    $engNm = $o->engNm;
    $majRegCd = $o->majRegCd;

    $sql = "update region set
inpCd = '$inpCd',
chiNm = '$chiNm',
engNm = '$engNm',
majRegCd = '$majRegCd'
where regCd='$regCd'";
    runsql_exec($con, $sql);
}

function updNearByAy($con, $regCd, $nearByAy)
{
    $sql = "delete from nearBy where regCd='$regCd'";
    runsql_exec($con, $sql);
    foreach ($nearByAy as $nearBy) {
        $sql = "insert into nearBy (regCd, nearBy) values ('$regCd', '$nearBy'); ";
        runsql_exec($con, $sql);
    }
    addMissingNearBy();

}

if (!isset($_SERVER['HTTP_HOST']))
    return;
if (isset($debug)) logFt("HTTP_RAW_POST_DATA", $HTTP_RAW_POST_DATA, "region_upd_php.txt");
$a = @$HTTP_RAW_POST_DATA;
if (is_null($a)) {
    echo 'No HTTP_RAW_POST_DATA';
    exit();
}
$con = db_con();
$regDro = json_decode($a);
updRegDs($con, $regDs);

?>

