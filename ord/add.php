<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 17/6/2015
 * Time: 21:05
 */
require_once '/../phpFn/sys.php';
require_once '/../phpFn/db.php';
require_once '../phpFn/lbl.php';
require_once '../phpFn/dte.php';
$msg = [];
$lang = null;
$con = null;
function msg()
{
    global $lang, $con, $msg;
    if (sizeof($msg) == 0) {
        $msg = lblMsg("ord", "add", $lang, $con);
    }
    return $msg;
}

/** return addMsg {isOk:true} or {erMsg} */
function add($cusCd, $ordDelvDte, $_lang)
{
    global $con, $lang;
    $lang = $_lang;

    $con = db_con();
    if (!$cusCd) $erMsg['cusCd'] = msg()["req"];

    if (!$lang) {
        $erMsg['lang'] = msg()["req"];
    } else if ($lang != 'en' && $lang != 'zh') {
        $erMsg['lang'] = 'lang not en or zh';
    }

    $erMsg = [];
    if (!$ordDelvDte)
        $erMsg['ordDelvDte'] = msg()['req'];
    else if (!is_dteStr($ordDelvDte))
        $erMsg['ordDelvDte'] = msg()['notDte'];
    elseif (is_pastDte($ordDelvDte))
        $erMsg['ordDelvDte'] = msg()['pastDte'];
    elseif (date_diff(new DateTime($ordDelvDte), new DateTime)->days > 30)
        $erMsg['ordDelvDte'] = msg()['over30days'];

    if (sizeof($erMsg) > 0) {
        $con->close();
        return $erMsg;
    }

    $sql = "select cusCd from cus where cusCd='$cusCd';";
    if (!runsql_isAny($con, $sql)) {
        $erMsg['cusCd'] = msg()['notExist'];
        $con->close();
        return $erMsg;
    };
    runsql_exec($con, "insert into ord (cusCd,ordDelvDte) values ('$cusCd', '$ordDelvDte');");
    $ord = $con->insert_id;
    $con->close();
    return ['isOk' => true, 'ord' => $ord];
}

if (isset($_SERVER['REQUEST_METHOD'])) {
    if (!$_SERVER['REQUEST_METHOD']) {
        echo('need post');
        return;
    }
    $d = @json_decode(@$HTTP_RAW_POST_DATA);
    if (!$d) {
        echo('need post data');
        return;
    }
    $cusCd = @$d->cusCd;
    $lang = @$d->lang;
    $ordDelvDte = @$d->ordDelvDte;
    if (!$lang) {
        echo('need lang');
        return;
    }
    $a = add($cusCd, $ordDelvDte, $lang);
    echo(json_encode($a));
    exit();
}

//ob_start();
add("ASC", "2015-07-03", "zh");
$act = ob_get_clean();
$exp = '{"erMsg":"\u5df2\u6709\u5ba2\u6236[x]"}';
assert($act === $exp);
if (false) {
    echo "\n";
    var_export($act);
    echo "\n";
    var_export($exp);;
}
pass(__FILE__);
