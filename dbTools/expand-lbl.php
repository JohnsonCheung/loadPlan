<?php
/*
  inp: lblPgm = pgmNm secNm | txtNmLvs btnNmLvs fldNmLvs
      : lang = lang
  oup: lblTxt = txtNm lang | lbl
       lblFld = fldNm lang | lbl
       lblBtn = btnNm lang | lbl
  des: 2 things: for each lang in table-lang
         #1 add missing to lbl*(Txt Fld Btn)
         #2 rmv excess
 */
$p = '/../phpFn/';
require_once $p . 'db.php';
require_once $p . 'ay.php';
$con = db_con();
$langAy = runsql_dc($con, "SELECT lang FROM lang");
const TXT = 'Txt';
const BTN = 'Btn';
const FLD = 'Fld';
const MSG = 'Msg';
foreach ($langAy as $lang) {
    $new = lblAy_new($con, TXT);
    $old = lblAy_old($con, $lang, TXT);
    $rmv = ay_minus($old, $new, $lang);
    $ins = ay_minus($new, $old, $lang);
    rmv_Lbl($con, $rmv, $lang, TXT);
    ins_Lbl($con, $ins, $lang, TXT);

    $new = lblAy_new($con, BTN);
    $old = lblAy_old($con, $lang, BTN);
    $rmv = ay_minus($old, $new, $lang);
    $ins = ay_minus($new, $old, $lang);
    rmv_Lbl($con, $rmv, $lang, BTN);
    ins_Lbl($con, $ins, $lang, BTN);

    $new = lblAy_new($con, FLD);
    $old = lblAy_old($con, $lang, FLD);
    $rmv = ay_minus($old, $new, $lang);
    $ins = ay_minus($new, $old, $lang);
    rmv_Lbl($con, $rmv, $lang, FLD);
    ins_Lbl($con, $ins, $lang, FLD);

    $new = lblAy_new($con, MSG);
    $old = lblAy_old($con, $lang, MSG);
    $rmv = ay_minus($old, $new, $lang);
    $ins = ay_minus($new, $old, $lang);
    rmv_Lbl($con, $rmv, $lang, MSG);
    ins_Lbl($con, $ins, $lang, MSG);
}

function lblAy_new($con, $what)
{
    $sql = "select {$what}NmLvs from lblPgm";
    $dc = runsql_dc($con, $sql);
    $o = [];
    foreach ($dc as $i) {
        $ay = split_lvs($i);
        foreach ($ay as $v) {
            push_noDup($o, $v);
        }
    }
    return $o;
}

function lblAy_old($con, $lang, $what)
{
    $sql = "select {$what}Nm from lbl{$what} where lang='$lang';";
    return runsql_dc($con, $sql);
}

function rmv_lbl($con, $ay, $lang, $what)
{
    if (count($ay) === 0) return;
    $ay1 = ay_quote($ay, "'");
    $w = join(", ", $ay1);
    $sql = "delete from lbl$what where {$what}Nm in ($w) and lang='$lang';";
    runsql_exec($con, $sql);
}

function ins_lbl($con, $ay, $lang, $what)
{
    if (count($ay) === 0) return;
    foreach ($ay as $i) {
        $sql = "insert into lbl{$what} ({$what}Nm,lang,lbl) values ('$i', '$lang', '$i');";
        runsql_exec($con, $sql);
    }
}

?>

