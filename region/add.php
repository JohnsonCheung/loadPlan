<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 17/6/2015
 * Time: 21:05
 */
require_once '/../phpFn/sys.php';
require_once '/../phpFn/db.php';
function add($regCd, $lang)
{
    if (!$regCd) {
        $con = db_con();
        $erMsg = pgmMsg($con, $lang, "req");
        echo(json_encode(['erMsg' => $erMsg]));
        return $con->close();
    }
    if (!$lang) {
        echo(json_encode(['erMsg' => "In post data, 'lang' cannot be blank"]));
        return;
    }

    $con = db_con();
    $sql = "select regCd from region where regCd='$regCd';";
    if (runsql_isAny($con, $sql)) {
        $erMsg = pgmMsg($con, $lang, "regCdExist", $regCd);
        echo(json_encode(['erMsg' => $erMsg]));
        return $con->close();
    }
    runsql($con, "insert into region (regCd) values ('$regCd');");
    if ($con->error) {
        $erMsg = pgmMsg($con, $lang, "dbEr", $con->error);
        echo(json_encode(['erMsg' => $erMsg]));
        return $con->close();
    }
    echo(json_encode(['isOk' => true]));
    $con->close();
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
    $regCd = @$d->regCd;
    $lang = @$d->lang;
    if (!$lang) {
        echo('need lang');
        return;
    }
    add($regCd, $lang);
    exit();
}

ob_start();
add("x", "zh");
$act = ob_get_clean();
$exp = '{"erMsg":"\u5df2\u6709\u5730\u5340[x]"}';
assert($act === $exp);
if(false) {
    echo "\n";
    var_export($act);
    echo "\n";
    var_export($exp);;

}
pass(__FILE__);

