<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 28/6/2015
 * Time: 18:59
 */
include_once 'db.php';
include_once 'ay.php';
/** return a json named as lbl so that
 * lbl.fld.XXX will return a label for fldNm-XXX
 * lbl.txt.XXX will return a label for txtNm-XXX
 * lbl.btn.XXX will return a label for btnNm-XXX
 * lbl.msg.XXX will return a label for msgNm-XXX
 */
function lbl($pgmNm, $secNm, $lang)
{
    $con = db_con();
    $sql = "select btnNmLvs, txtNmLvs, fldNmLvs, msgNmLvs from lblpgm where pgmNm='$pgmNm' and secNm='$secNm'";
    list($btnNmLvs, $txtNmLvs, $fldNmLvs, $msgNmLvs) = runsql_dr($con, $sql, MYSQLI_NUM);
    $o = [];
    $a = 'btn';
    $b = 'fld';
    $c = 'msg';
    $d = 'txt';
    $a1 = lblDic('btn', $btnNmLvs, $lang, $con);
    $b1 = lblDic('fld', $fldNmLvs, $lang, $con);
    $c1 = lblDic('msg', $msgNmLvs, $lang, $con);
    $d1 = lblDic('txt', $txtNmLvs, $lang, $con);
    if ($a1) $o[$a] = $a1;
    if ($b1) $o[$b] = $b1;
    if ($c1) $o[$c] = $c1;
    if ($d1) $o[$d] = $d1;
    $con->close();
    echo json_encode($o);
}

function lblMsg($pgmNm, $secNm, $lang, $con) // to be called by region/upd.php
{
    $sql = "select msgNmLvs from lblpgm where pgmNm='$pgmNm' and secNm='$secNm'";
    $msgNmLvs = runsql_val($con, $sql);
    return lblDic('msg', $msgNmLvs, $lang, $con);
}

function lblDic($XXX, $nmLvs, $lang, $con)
{ // $XXX can be (txt btn fld msg)
    // return [$nm => $txt,
    //           $nm1 => $txt1     if $txt is format of [aaa~bbb], then, add addition element $nm1=>$txt, where $txt1 will be aaa
    //           $nm2 => $txt2]
    // from table-lblXXX
    if (!$nmLvs) return;
    $o = [];
    $ay = split_lvs($nmLvs);
    if (count($ay) === 0) return [];
    $ay1 = ay_quote($ay, "'");
    $a = join(",", $ay1);
    $sql = "select {$XXX}Nm, lbl from lbl$XXX where {$XXX}Nm in ($a) and lang='$lang'; ";
    $dic = runsql_dic($con, $sql);
    foreach ($ay as $xxxNm) {
        if (array_key_exists($xxxNm, $dic)) {
            $o[$xxxNm] = $dic[$xxxNm];
        } else {
            $o[$xxxNm] = $xxxNm;
        }
    }
    foreach ($o as $k => $v) {
        $ay = preg_split('/\~/', $v);   // if $k is aaa~bbb~ccc, return an array of [aaa bbb ccc] else return null;
        if (sizeof($ay) > 0) {
            $o[$k] = str_replace("~", "", $v);
            foreach ($ay as $i => $v1) {
                $o[$k . ($i + 1)] = $v1;
            }
        }
    }
    return $o;
}


function pgmMsg($con, $lang, $msgNm, ...$ay)
{
    $sql = "select lbl from lblMsg where msgNm='$msgNm' and lang='$lang'; ";
    $isAny = runsql_isAny($con, $sql);
    if (!$isAny) {
        runsql_exec($con, "insert into lblMsg (lang, msgNm, lbl) values ('$lang', '$msgNm', '$msgNm');");
        return $msgNm + ": " + print_r($ay, true);
    }
    $msgTxt = runsql_val($con, $sql);
    return fmtAy($msgTxt, $ay);
}

if (@$_SERVER['argv'][0] === __FILE__) {
    echo 'testing';
}
