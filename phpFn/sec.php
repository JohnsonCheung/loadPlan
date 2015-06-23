<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 9/6/2015
 * Time: 14:46
 */
require_once 'db.php';
require_once 'Cipher.php';
function chkPgm($pgmNm)
{
    $a = new Sec;
    $a->chkPgm($pgmNm);
}


/**
 * Class Sec
 * This class provides (chkPgm) to check cookie a,b,c (usrId, sessId, actId)
 * if they are invalid.
 * if valid
 * when a php page is called by browser (case#1),
 * or,  a php-ajax-response-page is get/post by browser (case#2),
 *         the php should have a statement of "(new Sec).chk();".
 * By assuming,
 *      #1. the client (both case#1 & #2), has 3 cookies, a, b, c set
 *      #2. there is $_HTTP_RAW_POST_DATA of json {pgmNm, actNm, key, dta}, where key and dta are optional
 * This Sec::chk() will:
 * #1 assume decrypt the 3 cookies a,b,c by function-decrypt()) into usrId, sess, lastAct;
 * #2 If fail in checking, response the #CHK_XXX string and exit() the page.
 * #3 if all checking are OK,
 *      #1, create one-record in table-act
 *      #2, update the sess-record -> lastAct as the just created-record-of-act
 *      #3, setcookie(a,b,c) <== userId, sess, lastAct with good expiry time
 */
class Sec
{
    const chk_has_http_host = 1;
    const chk_has_a_which_is_usrId = 2;
    const chk_has_b_which_is_sess = 3;
    const chk_has_c_which_is_lastAct = 4;
    const chk_b_is_intStr = 5;
    const chk_c_is_which_is_lastAct = 6;
    const chk_usrId_exist = 7;
    const chk_usrId_enable = 8;
    const chk_sess_exist = 9;
    const chk_sess_match_usrId = 10;
    const chk_sess_match_lastAct = 11;
    const chk_lastAct_exist = 12;
    const chk_lastAct_match_sess = 13;
    const chk_lastAct_match_usrId = 14;
    const chk_lastAct_timIn = 15;
    private
        $con,
        $usrId,
        $sess,
        $lastAct;
    public
        $sessTimOutMin;

    public function __construct()
    {
        // usr = usrId | usrShtNm usrNm pwd crtDte isDisable
        // sess = sess | usrId loginDte logoutDte logoutBy (USR SYS TIMEOUT) ipAdr
        // act = act | sess pgmNm actNm tim key dta
        // prm = sessTimOutMin

        $this->con = db_con("sec");
        $this->sessTimOutMin = runsql_int($this->con, "SELECT prmVal FROM prm WHERE prmNm = 'sessTimOutMin'; ");

        $this->usrId = $this->__construct__cookie("a");
        $this->sess = $this->__construct__cookie("b");
        $this->lastAct = $this->__construct__cookie("c");
        $this->chk();
    }

    private function __construct__cookie($a)
    {
        return isset($_COOKIE[$a])
            ? decrypt(@$_COOKIE[$a])
            : '';
    }

    /** check if usr is authority to pgm */
    function chkPgm($pgmNm)
    {
        $con = $this->con;
        $usrId = $this->usrId;
        $sql = "select usr from usrPgm where usrId=$usrId and pgmNm='$pgmNm'; ";
        runsql_isAny($con, $sql)
            ? chkPgm__log_auth($pgmNm)
            : chkPgm__log_notAuth($pgmNm);
    }

    function chkPgm__log_auth($pgmNm)
    {

    }

    function chkPgm__log_notAuth($pgmNm)
    {

    }

    function chk()
    {
        $usrId = $this->usrId;
        $sess = intval($this->sess);
        $lastAct = intval($this->lastAct);

        if ($this->chk_has_HTTP_HOST()) $this->resp(Sec::chk_has_http_host);
        if ($this->chk_has_a_which_is_usrId()) $this->resp(Sec::chk_has_a_which_is_usrId);
        if ($this->chk_has_b_which_is_sess()) $this->resp(Sec::chk_has_b_which_is_sess);
        if ($this->chk_has_c_which_is_lastAct()) $this->resp(Sec::chk_has_c_which_is_lastAct);
        if ($this->chk_b_is_intStr()) $this->resp(Sec::chk_b_is_intStr);
        if ($this->chk_c_is_intStr()) $this->resp(Sec::chk_c_is_which_is_lastAct);
        if ($this->chk_usrId_exist()) $this->resp(Sec::chk_usrId_exist);
        if ($this->chk_usrId_enable()) $this->resp(Sec::chk_usrId_enable);
        if ($this->chk_sess_exist()) $this->resp(Sec::chk_sess_exist);
        if ($this->chk_sess_match_usrId()) $this->resp(Sec::chk_sess_match_usrId);
        if ($this->chk_lastAct_exist()) $this->resp(Sec::chk_lastAct_exist);
        if ($this->chk_lastAct_match_usrId()) $this->resp(Sec::chk_lastAct_match_usrId);
        if ($this->chk_lastAct_timeIn()) $this->resp(Sec::chk_lastAct_timIn);
    }

    function chk_b_is_intStr()
    {
        return !is_intStr($this->sess);
    }

    function chk_c_is_intStr()
    {
        return !is_intStr($this->lastAct);
    }

    function setcookie_c()
    {
        $sessTimOutMin = $this->sessTimOutMin;
        $v = encrypt($this->lastAct);;
        $t = time() + ($sessTimOutMin * 60);
        setcookie("c", $v, $t);
    }

    function __destruct()
    {
        //echo 'Sec::__desctruct is called';
        @$this->con->close();
    }

    function resp($erNo)
    {
        setcookie("a", null, 0);
        setcookie("b", null, 0);
        setcookie("c", null, 0);
        switch ($erNo) {
            case Sec::chk_has_http_host:
                $a = "chk_has_http_host";
                break;
            case Sec::chk_has_a_which_is_usrId:
                $a = "chk_has_a_which_is_usrId";
                break;
            case Sec::chk_has_b_which_is_sess:
                $a = "chk_has_b_which_is_sess";
                break;
            case Sec::chk_has_c_which_is_lastAct:
                $a = "chk_has_c_which_is_lastAct";
                break;
            case Sec::chk_b_is_intStr:
                $a = "chk_b_is_intStr";
                break;
            case Sec::chk_c_is_which_is_lastAct:
                $a = "chk_c_is_which_is_lastAct";
                break;
            case Sec::chk_usrId_exist:
                $a = "chk_usrId_exist";
                break;
            case Sec::chk_usrId_enable:
                $a = "chk_usrId_enable";
                break;
            case Sec::chk_sess_exist:
                $a = "chk_sess_exist";
                break;
            case Sec::chk_sess_match_usrId:
                $a = "chk_sess_match_usrId";
                break;
            case Sec::chk_sess_match_lastAct:
                $a = "chk_sess_match_lastAct";
                break;
            case Sec::chk_lastAct_exist:
                $a = "chk_lastAct_exist";
                break;
            case Sec::chk_lastAct_match_sess:
                $a = "chk_lastAct_match_sess";
                break;
            case Sec::chk_lastAct_match_usrId:
                $a = "chk_lastAct_match_usrId";
                break;
            case Sec::chk_lastAct_timIn:
                $a = "chk_lastAct_timIn";
                break;
            default:
                throw new Exception("unexpect erNo[$erNo]");
        }
        echo '#' . $erNo . ':' . $a;
        exit($erNo);
    }

    function chk_has_a_which_is_usrId()
    {
        return $this->usrId === '';
    }

    function chk_has_b_which_is_sess()
    {
        return $this->sess === '';
    }

    function chk_has_c_which_is_lastAct()
    {
        return $this->lastAct === '';
    }

    function chk_lastAct_timeIn()
    {
        $lastAct = $this->lastAct;
        $t1 = runsql_datetime($this->con, "select tim from act where act = $lastAct"); // lastActTim;
        $t2 = new DateTime(); // cur

        $minPassed = date_diff($t2, $t1)->i;  // # of minute passed
        $sessTimOutMin = $this->sessTimOutMin;
        return $minPassed >= $sessTimOutMin;
    }

    function chk_lastAct_match_sess()
    {
        $lastAct = $this->lastAct;
        $sess = $this->sess;
        $sess1 = runsql_val($this->con, "select sess from act where act=$lastAct;");
        return $sess1 !== $sess;
    }

    function chk_sess_match_lastAct()
    {

    }

    function chk_lastAct_match_usrId()
    {
        $lastAct = $this->lastAct;
        $usrId = $this->usrId;
        $sess = runsql_val($this->con, "select sess from act where act=$lastAct;");
        $usrId1 = runsql_val($this->con, "select usrId from sess where sess=$sess;");
        return $usrId1 !== $usrId;
    }

    function chk_lastAct_exist()
    {
        $lastAct = $this->lastAct;
        return !runsql_isAny($this->con, "select act from act where act=$lastAct");
    }

    function chk_sess_match_usrId()
    {
        $sess = $this->sess;
        $usrId = $this->usrId;
        $usrId1 = runsql_val($this->con, "select usrId from sess where sess = $sess");
        return $usrId1 !== $usrId;
    }

    function chk_usrId_enable()
    {
        $usrId = $this->usrId;
        return runsql_bool($this->con, "select isDisable from usr where usrId='$usrId';");
    }

    function chk_sess_exist()
    {
        $sess = $this->sess;
        return !runsql_isAny($this->con, "select sess from sess where sess=$sess");
    }

    function chk_usrId_exist()
    {
        $usrId = $this->usrId;
        return !runsql_isAny($this->con, "select usrId from usr where usrId='$usrId'");
    }

    function logActivity($pgmNm, $actNm, $key = null, $dta = null)
    {
        //Effects:-
        //      Create one rec in table-act
        //      Update table-sess -> lastAct
        // copied
        // usr = usrId | usrShtNm usrNm pwd crtDte isDisable
        // sess = sess | usrId loginDte logoutDte logoutBy (USR SYS TIMEOUT) ipAdr
        // act = act | sess actNm tim key dta
        // prm = sessTimOutMin
        $sess = $this->sess;
        $lastAct = $this->lastAct;
        $dta = db_cvStr($this->con, $dta);
        $key = db_cvStr($this->con, $key);

        $sql = "insert into act (sess, pgmNm, actNm, `key`, dta) values($sess, '$pgmNm', '$actNm', $key, $dta)";
        runsql_exec($this->con, $sql);
        $lastAct_cur = $this->con->insert_id;

        $sql = "update sess set lastAct = $lastAct_cur where sess = $sess; ";
        runsql_exec($this->con, $sql);
        return $lastAct_cur;
    }

    function chk_has_HTTP_HOST()
    {
        return !isset($_SERVER['HTTP_HOST']);
    }
}

?>
