<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18/6/2015
 * Time: 21:35
 */
require_once "/../phpFn/db.php";
require_once "/../phpFn/cipher.php";

/** This class provide one function Login->chk(), which,
 * will decrypt $_REQUEST['a', 'b'] as usrId and pwd to do 7 checking against table usr/ipBlocked/prm
 * if login_Fail:
 *      call $this->respEr(), which will response 401 (unauthority), clear cookies, a,b c and set cookie d to erNo
 *      call $this->log_loginFail() to write one record to table-act.
 *      exit();
 * if login_OK:
 *      call $this->log_loginOK() to write 1 record to each of table-act & sess and return them.
 *      use usrId together these 2 id to set cookies a,b,c with encryt() and expiry time as defined in table prm->sessTimOutMin
 *      response status 202 (accepted)
 * This class is used by login.php
 */
class Login
{
    const chk_has_http_post = 0;
    const chk_has_usrId = 1;
    const chk_has_pwd = 2;
    const chk_usrId_exist = 3;
    const chk_usrId_enable = 4;
    const chk_new_login = 5;
    const chk_pwd_ok = 6;
    const chk_ipAdr_notBlocked = 7;
    private $chkMsg = [
        'chk_has_http',
        'chk_has_usrId',
        'chk_has_pwd',
        'chk_usrId_exist',
        'chk_usrId_enable',
        'chk_new_login',
        'chk_pwd_ok',
        'chk_ipAdr_notBlocked'];
    private
        $con,
        $usrId,
        $pwd,
        $ipAdr,
        $usrId_b64,
        $pwd_b64,
        $erMsg;
    public
        $sessTimOutMin;

    function __construct()
    {
        ob_start();  //<-- without this, the output must in order!!
        // <-- with this, header() can be called after some echo??
        $this->usrId_b64 = @$_REQUEST['a'];
        $this->pwd_b64 = @$_REQUEST['b'];
        if (false) {
            echo $this->usrId_b64;
            echo '<br>';
            echo $this->pwd_b64;
            die();
        }
        $this->usrId = $this->__construct__request("a"); // a for usrId: base64
        $this->pwd = $this->__construct__request("b"); // a for pwd: base64
        if (false) {
            echo $this->usrId;
            echo '<br>';
            echo $this->pwd;
            die();
        }

        $this->ipAdr = @$_SERVER['REMOTE_ADDR'];
        $this->con = db_con("sec");
        $this->sessTimOutMin = runsql_int($this->con, "SELECT prmVal FROM prm WHERE prmNm='sessTimOutMin'");
        if (false) {
            echo $this->sessTimOutMin;
            exit();
        }
    }

    private function __construct__request($a)
    {
        if (!isset($_REQUEST[$a])) return '';
        return base64_decode($_REQUEST[$a]);
    }

    function chk()
    {
        if ($this->chk_has_http_host()) $this->respEr(Login::chk_has_usrId);
        if ($this->chk_has_usrId()) $this->respEr(Login::chk_has_usrId);
        if ($this->chk_has_pwd()) $this->respEr(Login::chk_has_pwd);
        if ($this->chk_usrId_exist()) $this->respEr(Login::chk_usrId_exist);
        if ($this->chk_usrId_enable()) $this->respEr(Login::chk_usrId_enable);
        if ($this->chk_new_login()) $this->respEr(Login::chk_new_login);
        if ($this->chk_pwd_ok()) $this->respEr(Login::chk_pwd_ok);
        if ($this->chk_ipAdr_notBlocked()) $this->respEr(Login::chk_ipAdr_notBlocked);
        list($sess, $lastAct) =
            $this->log_loginOk();
        $this->setOKCookie($sess, $lastAct);
        header('HTTP/1.1 202');
    }

    function __destruct()
    {
        @$this->con->close();
    }

    function respEr($erNo)
    {
        header("HTTP/1.1 401"); // Unauthorized
        setcookie("a", null, time() - 300);
        setcookie("b", null, time() - 300);
        setcookie("c", null, time() - 300);
        setcookie("d", $erNo, time() + 60);
        echo '#' . $this->chkMsg[$erNo];
        $this->erMsg = $this->chkMsg[$erNo];
        $this->log_loginFail();
        die();
    }

    function chk_has_http_host()
    {
        return !isset($_SERVER['HTTP_HOST']);
    }

    function chk_has_usrId()
    {
        if (false) {
            echo "usrId is blank: ";
            var_dump($this->usrId === '');
            echo $this->usrId === '';
            die();
        }
        return $this->usrId === '';
    }

    function chk_has_pwd()
    {
        return $this->pwd === '';
    }

    function chk_usrId_exist()
    {
        $usrId = $this->usrId;
        $ok = runsql_isAny($this->con, "select usrId from usr where usrId='$usrId'; ");
        return !$ok;
    }

    function chk_usrId_enable()
    {
        $usrId = $this->usrId;
        return runsql_bool($this->con, "select isDisable from usr where usrId='$usrId';");
    }

    function setOKCookie($sess, $lastAct)
    {
        $sessTimOutMin = $this->sessTimOutMin;
        $t = time() + ($sessTimOutMin * 60);  /* expire in n minutes */
        $usrId = $this->usrId;

        $a = encrypt($usrId);
        $b = encrypt($sess);
        $c = encrypt($lastAct);
        setcookie("a", $a, $t);
        setcookie("b", $b, $t);
        setcookie("c", $c, $t);
//        echo "header list\n";
//        var_dump(headers_list());
//        echo "header sent\n";
//        var_dump(headers_sent());
    }

    function log_loginOK__updSess_forLastAct($sess, $lastAct)
    {
        $con = $this->con;
        $sql = "update sess set lastAct=$lastAct where sess=$sess";
        runsql_exec($con, $sql);
    }

    function log_loginOK__sess()
    {
        // sess = sess | usrId lastAct loginDte logoutDte logoutBy ipAdr
        $con = $this->con;
        $usrId = $this->usrId;
        $ipAdr = $this->ipAdr;

        $sql = "insert into sess (usrId, ipAdr) values('$usrId', '$ipAdr'); ";
        runsql_exec($con, $sql);
        return $con->insert_id;
    }

    function log_loginOK__lastAct($sess)
    {
        // act = act | sess pgmNm actNm tim key dta
        $con = $this->con;
        $usrId = $this->usrId;
        $pwd = $this->ipAdr;
        $tim = (new DateTime())->format(DateTime::W3C);
        $sql = "INSERT INTO act (sess, pgmNm, actNm,tim, `key`, dta) VALUES ($sess, 'login', 'ok','$tim', '$usrId', '$pwd'); ";
        runsql_exec($con, $sql);
        return $con->insert_id;
    }

    function chk_ipAdr_notBlocked()
    {
        $ipAdr = $this->ipAdr;
        $sql = "select ipBlocked from ipBlocked where ipBlocked='$ipAdr'; ";
        return runsql_isAny($this->con, $sql);
    }

    function chk_new_login()
    {
        return false;
    }

    function chk_pwd_ok()
    {
        $usrId = $this->usrId;
        $sql = "select pwd from usr where usrId = '$usrId';  ";
        $p1 = runsql_val($this->con, $sql);
        $p2 = $this->pwd;
        return $p1 !== $p2;
    }

    function log_loginOk()
    {
        $usrId = $this->usrId;
        $sess = $this->log_loginOK__sess();
        $lastAct = $this->log_loginOK__lastAct($sess);
        $this->log_loginOK__updSess_forLastAct($sess, $lastAct);
        return [$sess, $lastAct];
    }

    function log_loginFail()
    {
        $con = $this->con;
        if (runsql_isAny($con, "SELECT sess FROM sess WHERE sess=0")) {
            runsql_exec($con, "INSERT INTO sess (sess, userId, lastAct, ipAdr) VALUES (0,'#NA#', 0,'0.0.0.0')");
        }
        $ipAdr = @$_SERVER['REMOTE_ADDR'];
        $a = $this->usrId;
        $b = $this->pwd;
        $c = $this->erMsg;
        $dta = db_cvStr($con, "usrId=[$a] pwd=[$b] er=[$c]");
        runsql_exec($con, "insert into act(sess, pgmNm, actNm, `key`, dta) value (0, 'login', 'fail','$ipAdr', $dta);");
    }
}
