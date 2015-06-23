<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 9/6/2015
 * Time: 18:49
 */
include_once '/../sec.php';

run();
function run()
{
    if (true) {
        test__sessTimOutMin();
        test_usrId_exist();
        test_has_http_host();
        test_has_a_which_is_usrId();
        test_has_b_which_is_sess();
        test_has_c_which_is_lastAct();
        test_b_is_intStr();
        test_c_is_intStr();
        test_usrId_exist();
        test_usrId_enable();
        test_sess_exist();
        test_sess_match_usrId();
        test_lastAct_exist();
        test_lastAct_match_sess();
        test_lastAct_match_usrId();
        test_lastAct_timIn();
        test_logActivity();
        echo 'end';
    } else {
        test__sessTimOutMin();
        test_usrId_exist();
        test_has_http_host();
        test_has_a_which_is_usrId();
        test_has_b_which_is_sess();
        test_has_c_which_is_lastAct();
        test_b_is_intStr();
        test_c_is_intStr();
        test_usrId_exist();
        test_usrId_is_enable();
        test_sess_exist();
        test_sess_match_usrId();
        test_lastAct_exist();
        test_lastAct_match_sess();
        test_lastAct_match_usrId();
        test_lastAct_timIn();
        test_logActivity();
    }
}

function test_logActivity()
{
    $sess = '1';
    $lastAct = '1';
    set_a('johnson');
    set_b($sess);
    set_c($lastAct);
    $m = new Sec;
    $con = db_con("sec");

//-------------
    $pgmNm = "pgm-A";
    $actNm = "add";
    $lastAct = $m->logActivity($pgmNm, $actNm);

    $sql = "select act, sess, pgmNm, actNm, `key`, dta from act where act = $lastAct; ";
    $dro = runsql_dro($con, $sql);

    $sql = "select lastAct from sess where sess = $sess; ";
    $lastAct_act = runsql_val($con, $sql);

    assert($lastAct == $dro->act);
    assert($sess == $dro->sess);
    assert($pgmNm == $dro->pgmNm);
    assert($actNm == $dro->actNm);
    assert(is_null($dro->key));
    assert(is_null($dro->dta));
    assert($lastAct_act == $lastAct);

//-------------
    $pgmNm = "pgm-A";
    $actNm = "add";
    $key = 'abc';
    $dta = 'lskdf';
    $lastAct = $m->logActivity($pgmNm, $actNm, $key, $dta);

    $sql = "select act, sess, pgmNm, actNm, `key`, dta from act where act = $lastAct; ";
    $dro = runsql_dro($con, $sql);

    $sql = "select lastAct from sess where sess = $sess; ";
    $lastAct_act = runsql_val($con, $sql);

    assert($lastAct == $dro->act);
    assert($sess == $dro->sess);
    assert($pgmNm === $dro->pgmNm);
    assert($actNm === $dro->actNm);
    assert($key === $dro->key);
    assert($dta === $dro->dta);
    assert($lastAct_act == $lastAct);

    pass(__FUNCTION__);
}

function test_has_http_host()
{
    $_SERVER['HTTP_HOST'] = 'a';
    $m = new Sec;
    $act = $m->chk_has_HTTP_HOST();
    assert($act === false);

    unset($_SERVER['HTTP_HOST']);
    $m = new Sec;
    $act = $m->chk_has_HTTP_HOST();
    assert($act === true);

    pass(__FUNCTION__);
}

function test_has_a_which_is_usrId()
{
    set__all_dlt();
    $m = new Sec;
    $act = $m->chk_has_a_which_is_usrId();
    assert($act === true);

    set_a("johnson");
    $m = new Sec;
    $act = $m->chk_has_a_which_is_usrId();
    assert($act === false);
    pass(__FUNCTION__);
}

function test_has_c_which_is_lastAct()
{
    set__all_dlt();
    $m = new Sec;
    $act = $m->chk_has_c_which_is_lastAct();
    assert($act === true);

    set_c("1");
    $m = new Sec;
    $act = $m->chk_has_c_which_is_lastAct();
    assert($act === false);
    pass(__FUNCTION__);
}

function test_has_b_which_is_sess()
{
    set__all_dlt();
    $m = new Sec;
    $act = $m->chk_has_b_which_is_sess();
    assert($act === true);

    set_b("johnson");
    $m = new Sec;
    $act = $m->chk_has_b_which_is_sess();
    assert($act === false);
    pass(__FUNCTION__);
}


function test_b_is_intStr()
{
    set__all_dlt();
    set_b('b');
    $m = new Sec;
    $act = $m->chk_b_is_intStr();
    assert($act === true);

    set_b('1');
    $m = new Sec;
    $act = $m->chk_b_is_intStr();
    assert($act === false);

    pass(__FUNCTION__);
}

function test_c_is_intStr()
{
    set__all_dlt();
    set_c('c');
    $m = new Sec;
    $act = $m->chk_b_is_intStr();
    assert($act === true);

    set_c('1');
    $m = new Sec;
    $act = $m->chk_c_is_intStr();
    assert($act === false);

    pass(__FUNCTION__);

}

function set_a($s)
{
    $_COOKIE['a'] = encrypt($s);
}

function set__all_dlt()
{
    unset($_COOKIE['a']);
    unset($_COOKIE['b']);
    unset($_COOKIE['c']);
}

function set_b($s)
{
    $_COOKIE['b'] = encrypt($s);
}

function set_c($s)
{
    $_COOKIE['c'] = encrypt($s);
}

function test_usrId_enable()
{
    $con = db_con("sec");
    set__all_dlt();
    set_a("johnson");
    runsql_exec($con, "update usr set isDisable=true where usrId='johnson'; ");
    $m = new Sec;
    $act = $m->chk_usrId_enable();
    assert($act == true);

    set_a("johnson");
    $m = new Sec;
    runsql_exec($con, "update usr set isDisable=false where usrId='johnson'; ");
    $act = $m->chk_usrId_enable();
    assert($act == false);
    pass(__FUNCTION__);
}

function test_usrId_exist()
{
    set__all_dlt();
    set_a("john1son");
    $m = new Sec;
    assert($m->chk_usrId_exist() === true);

    set_a("johnson");
    $m = new Sec;
    assert($m->chk_usrId_exist() === false);
    pass(__FUNCTION__);
}

function test_sess_match_usrId()
{
    $con = db_con("sec");
    runsql_exec($con, "update sess set usrId='x' where sess=1; ");
    set__all_dlt();
    set_a('johnson');
    set_b('1');
    $m = new Sec;
    $act = $m->chk_sess_match_usrId();
    assert($act == true);

    runsql_exec($con, "update sess set usrId='johnson' where sess=1; ");
    $m = new Sec;
    $act = $m->chk_sess_exist();
    assert($act == false);

    pass(__FUNCTION__);
}

function test_sess_exist()
{
    set__all_dlt();
    set_b('-1');
    $m = new Sec;
    $act = $m->chk_sess_exist();
    assert($act == true);

    set_b('1');
    $m = new Sec;
    $act = $m->chk_sess_exist();
    assert($act == false);

    pass(__FUNCTION__);
}

function test__sessTimOutMin()
{
    $m = new Sec('act1');
    assert($m->sessTimOutMin === 30);
    pass(__FUNCTION__);

}

function test_lastAct_match_usrId()
{
    $con = db_con("sec");
    runsql_exec($con, "update act set sess=999 where act=1; ");
    set__all_dlt();
    set_b('1');
    set_c("1");
    $m = new Sec;
    $act = $m->chk_lastAct_match_sess();
    assert($act == true);

    runsql_exec($con, "update act set sess=1 where act=1; ");
    $m = new Sec;
    $act = $m->chk_lastAct_match_sess();
    assert($act == false);

    pass(__FUNCTION__);
}

function test_lastAct_match_sess()
{
    $con = db_con("sec");
    runsql_exec($con, "update act set sess=999 where act=1; ");
    set__all_dlt();
    set_b('1');
    set_c("1");
    $m = new Sec;
    $act = $m->chk_lastAct_match_sess();
    assert($act == true);

    runsql_exec($con, "update act set sess=1 where act=1; ");
    $m = new Sec;
    $act = $m->chk_lastAct_match_sess();
    assert($act == false);

    pass(__FUNCTION__);
}

function test_lastAct_timIn()
{
    $con = db_con("sec");
    $t = time() - 60 * 31;
    $t = (new DateTime())->setTimestamp($t)->format(DateTime::W3C);
    runsql($con, "update act set tim='$t' where act=1");
    set_a('johnson');
    set_b('1');
    set_c('1');
    $m = new Sec;
    $act = $m->chk_lastAct_timeIn();
    assert($act === true);

    $t = time() + 60 * 29;
    $t = (new DateTime())->setTimestamp($t)->format(DateTime::W3C);

    runsql($con, "update act set tim='$t' where act=1");
    $m = new Sec;
    $act = $m->chk_lastAct_timeIn();
    assert($act === false);

    pass(__FUNCTION__);
}

function test_lastAct_exist()
{
    set__all_dlt();
    set_c('-1');
    $m = new Sec;
    $act = $m->chk_lastAct_exist();
    assert($act == true);

    set_c('1');
    $m = new Sec;
    $act = $m->chk_lastAct_exist();
    assert($act == false);

    pass(__FUNCTION__);
}

function pass($s)
{
    echo 'pass: ' . $s . "<br>\n";
}
