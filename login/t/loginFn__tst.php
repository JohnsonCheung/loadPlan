<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/6/2015
 * Time: 15:33
 */
include_once '/../loginFn.php';

run();
function run()
{
    if (true) {
        test__sessTimOutMin();
        test_has_http_host();
        test_has_usrId();
        test_has_pwd();
        test_usrId_exist();
        test_usrId_enable();
        test_new_login();
        test_pwd_ok();
        test_ipAdr_notBlock();
        test_log_loginOK();
    } else {
        test__sessTimOutMin();
        test_has_http_host();
        test_has_usrId();
        test_has_pwd();
        test_usrId_exist();
        test_usrId_enable();
        test_new_login();
        test_pwd_ok();
        test_ipAdr_notBlock();
        test_log_loginOK();
        echo 'end';
    }
    $con = db_con("sec");
    runsql_exec($con, "update usr set pwd='1234' where usrId='johnson'; ");
}

function test_log_loginOK()
{
    //--- prepare -------
    $usrId = "johnson";
    $pwd = "pwd";
    $con = db_con("sec");
    $ipAdr = "1:1:1:1";
    runsql_exec($con, "delete from ipBlocked where ipBlocked='$ipAdr'; ");
    runsql_exec($con, "update usr set pwd = '$pwd' where usrId = '$usrId'; ");
    set_ip($ipAdr);
    set_a($usrId);
    set_b($pwd);
    $max_sess1 = runsql_int($con, "SELECT max(sess) FROM sess");
    $max_act1 = runsql_int($con, "SELECT max(act) FROM act");
    //-- run --------------
    $m = new Login;
    list($act_sess, $act_lastAct) = $m->log_loginOk();
    //-- assert --------------------------
    $max_sess2 = runsql_int($con, "SELECT max(sess) FROM sess");
    $max_act2 = runsql_int($con, "SELECT max(act) FROM act");

    assert($max_sess1 + 1 === $max_sess2); // one reocrd added;
    assert($max_act1 + 1 === $max_act2); // one reocrd added;

    assert($act_sess === $max_sess2);
    assert($act_lastAct === $max_act2);

    //-- assert table-sess = sess | usrId lastAct loginDte logoutDte logoutBy ipAdr ----------------------
    list($t1_usrId, $t1_lastAct, $t1_loginDte, $t1_logoutDte, $t1_logoutBy, $t1_ipAdr) =
        runsql_list($con, "select usrId,  lastAct, loginDte, logoutDte, logoutBy, ipAdr from sess where sess=$act_sess;");

    assert($t1_usrId === $usrId);
    assert($t1_lastAct == $act_lastAct);

    $d1 = new DateTime($t1_loginDte);
    $d2 = new DateTime();
    $diff = date_diff($d1, $d2);
    $diff_min = $diff->i;
    assert($diff_min === 0);
    assert(is_null($t1_logoutDte));
    assert(is_null($t1_logoutBy));
    assert($t1_ipAdr === $ipAdr);

    //-- assert table-act = sess pgmNm actNm tim key dta  ----------------------
    list($t2_sess, $t2_pgmNm, $t2_actNm, $t2_key, $t2_dta) =
        runsql_list($con, "select sess,  pgmNm, actNm, `key`, dta from act where act=$act_lastAct;");

    assert($t2_sess == $act_sess);
    assert($t2_pgmNm === 'login');
    assert($t2_actNm === 'ok');
    assert($t2_key === $usrId);
    assert($t2_dta === $ipAdr);

    pass(__FUNCTION__);
}

function test_ipAdr_notBlock()
{
    $con = db_con("sec");
    $ipBlocked = "1:1:1:1";
    if (!runsql_isAny($con, "select ipBlocked from ipBlocked where ipBlocked='$ipBlocked'; "))
        runsql_exec($con, "insert into ipBlocked (ipBlocked) values('$ipBlocked'); ");
    set_ip($ipBlocked);
    $m = new Login;
    $act = $m->chk_ipAdr_notBlocked();
    assert($act == true);

    runsql_exec($con, "delete from ipBlocked where ipBlocked = '$ipBlocked'; ");
    $m = new Login;
    $act = $m->chk_ipAdr_notBlocked();
    assert($act == false);

    pass(__FUNCTION__);
}

function test_pwd_ok()
{
    $usrId = "johnson";
    $pwd = "pwd";
    set_a($usrId);
    set_b($pwd);
    $con = db_con("sec");

    //---------------
    $max_sess1 = runsql_int($con, "SELECT max(sess) FROM sess");
    $max_act1 = runsql_int($con, "SELECT max(act) FROM act;");
    runsql_exec($con, "update usr set pwd='xx' where usrId = '$usrId'; ");
    $m = new Login;
    $act = $m->chk_pwd_ok();


    runsql_exec($con, "update usr set pwd = '$pwd' where usrId = '$usrId'; ");
    $m = new Login;
    $act = $m->chk_pwd_ok();
    assert($act === false);

    pass(__FUNCTION__);

}

function test_new_login()
{

}

function test_has_http_host()
{
    $_SERVER['HTTP_HOST'] = 'a';
    $m = new Login;
    $act = $m->chk_has_HTTP_HOST();
    assert($act === false);

    unset($_SERVER['HTTP_HOST']);
    $m = new Login;
    $act = $m->chk_has_HTTP_HOST();
    assert($act === true);

    pass(__FUNCTION__);
}

function test_has_usrId()
{
    set__all_dlt();
    $m = new Login;
    $act = $m->chk_has_usrId();
    assert($act === true);

    set_a("johnson");
    $m = new Login;
    $act = $m->chk_has_usrId();
    assert($act === false);
    pass(__FUNCTION__);
}

function test_has_pwd()
{
    set__all_dlt();
    $m = new Login;
    $act = $m->chk_has_pwd();
    assert($act === true);

    set_b("1");
    $m = new Login;
    $act = $m->chk_has_pwd();
    assert($act === false);
    pass(__FUNCTION__);
}


function set_a($usrId)
{
    $_REQUEST['a'] = base64_encode($usrId);
}


function set_ip($ipAdr)
{
    $_SERVER['REMOTE_ADDR'] = $ipAdr;
}

function set__all_dlt()
{
    unset($_REQUEST['a']);
    unset($_REQUEST['b']);
}

function set_b($pwd)
{
    $_REQUEST['b'] = base64_encode($pwd);
}

function test_usrId_enable()
{
    $con = db_con("sec");
    set__all_dlt();
    set_a("johnson");
    runsql_exec($con, "UPDATE usr SET isDisable = TRUE WHERE usrId = 'johnson'; ");
    $m = new Login;
    $act = $m->chk_usrId_enable();
    assert($act == true);

    set_a("johnson");
    $m = new Login;
    runsql_exec($con, "UPDATE usr SET isDisable = FALSE WHERE usrId = 'johnson'; ");
    $act = $m->chk_usrId_enable();
    assert($act == false);
    pass(__FUNCTION__);
}

function test_usrId_exist()
{
    set__all_dlt();
    set_a("john1son");
    $m = new Login;
    assert($m->chk_usrId_exist() === true);

    set_a("johnson");
    $m = new Login;
    $act = $m->chk_usrId_exist();
    assert($act === false);
    pass(__FUNCTION__);
}

function test__sessTimOutMin()
{
    $m = new Login;
    assert($m->sessTimOutMin === 30);
    pass(__FUNCTION__);

}

function pass($s)
{
    echo 'pass: ' . $s . "<br>\n";
}
