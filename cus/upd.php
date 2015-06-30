<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/6/2015
 * Time: 18:47
 */
include_once "/../phpFn/cmn.php";
include_once "/../phpFn/lbl.php";
include_once '/../dbTools/addMissingNearBy.php';

class Upd
{
    const adrReqLvs = "cusAdr cusCd adrCd inpCd adrNm adr contact phone regCd gpsX gpsY delvTimFm delvTimTo delvLasTim" .
    " truckTones truckCode truckFlat truckVan truckClose truckTail truckUpstair truckDispatchAtDoor truckByBox truckByPallet truckLock" .
    " pckAdrCd rmk";
    const adrFldLvs = 1;
    const adrBoolLvs = 1;
    const adrTimLvs = 1;
    private
        $con,
        $adrDt,
        $lang,
        $cusDro;

    function __construct($cusDs)
    {
        $this->con = db_con();
        $this->cusDro = $cusDs->cusDro;
        $this->adrDt = $cusDs->adrDt;
        $this->lang = $cusDs->lang;
        $this->lblMsg = lblMsg("cus", "upd", $this->lang, $this->con);
    }

    function updCusDs()
    {
        $m1 = $this->chkReq($this->cusDro->inpCd);
        $m2Ay = $this->m2Ay();
        if ($m1 || $m2Ay) {
            if ($m1) $o["erMsg"]["cusDro"]["inpCd"] = $m1;
            if ($m2Ay) $o["erMsg"]["adrDt"] = $m2Ay;
            echo json_encode($o);
            return;
        }
        $this->updCusDro();
        $this->updAdrDt();
        $o["isOk"] = true;
        echo json_encode($o);
    }

    function chkReq($s)
    {
        if ($s === '') return $this->lblMsg['req'];
    }

    function m2Ay()
    {
        $adrDt = $this->adrDt;
        $m2Ay = [];
        $isEr = false;
        foreach ($adrDt as $adrDro) {
            $m2 = $this->m2($adrDro);
            array_push($m2Ay, $m2);
            if ($m2) $isEr = true;
        }
        if ($isEr) return $m2Ay;
    }

    function m2($adrDro)
    {
        $i = $adrDro;
        $regCd = trim($i->regCd);
        $adrCd = trim($i->adrCd);
        $o = [];
        if ($adrCd === '') $o['adrCd'] = $this->lblMsg['req'];
        if ($regCd === '')
            $o['regCd'] = $this->lblMsg['req'];
        elseif (!runsql_isAny($this->con, "select regCd from region where regCd='$regCd';")) {
            $o['regCd'] = $this->lblMsg['notFound'];
        }
    }

    function updCusDro()
    {
        $a = new BldSql($this->cusDro, "cus", "cusCd inpCd chiNm engNm chiShtNm engShtNm cusTy cusRmk");
        $b = $a->updStmt();
        runsql_exec($this->con, $b);
    }

    private function updAdrDt()
    {
        $this->updAdrDt_rmv();
        $this->updAdrDt_ins();
        $this->updAdrDt_upd();
    }

    private function updAdrDt_rmv()
    {
        $cusCd = $this->cusDro->cusCd;
        $old = runsql_dc($this->con, "select adrCd from cusAdr where cusCd = '$cusCd'; ");
        $new = [];
        foreach ($this->adrDt as $adrDro) {
            array_push($new, $adrDro->adrCd);
        }
        $rmv = ay_minus($new, $old);
        foreach ($rmv as $adrCd) {
            $sql = "delete from cusAdr where cusCd='$cusCd' and adrCd='$adrCd' limit 1";
            runsql_exec($this->con, $sql);
        }
    }

    function updAdrDt_ins()
    {
        $a = Upd::$adrFldLvs;
        $b = Upd::$adrReqLvs;
        $c = Upd::$adrBoolLvs;
        $d = Upd::$adrTimLvs;
        foreach ($this->adrDt as $i) {
            if (!$i->newRec) {
                $m = new BldSql($i, 'cusadr', $a, $b, $c, $d);
                runsql_exec($this->con, $m->insStmt());
            }
        } // for
    }

    private function updAdrDt_upd()
    {
        $a = Upd::$adrFldLvs;
        $b = Upd::$adrReqLvs;
        $c = Upd::$adrBoolLvs;
        $d = Upd::$adrTimLvs;
        foreach ($this->adrDt as $i) {
            if (!$i->newRec) {
                $m = new BldSql($i, 'cusadr', $a, $b, $c, $d);
                runsql_exec($this->con, $m->updStmt());
            }
        } // for
    }
}

if (is_server()) {
    if (isset($debug)) logFt("HTTP_RAW_POST_DATA", $HTTP_RAW_POST_DATA, "region_upd_php.txt");
    $a = @$HTTP_RAW_POST_DATA;
    if (is_null($a)) {
        echo 'No post data';
        exit();
    }

    $cusDs = json_decode($a);
    (new Upd($cusDs))->updCusDs();
    exit();
}
(new Upd(cusDs1()))->updCusDs();
function cusDs3()
{
    $a = '{
"lang": "zh",
"cusDro":{
        "cusCd":"AMWAY","chiNm":"","engNm":"","inpCd":"am","cusTy":"","chiShtNm":"AMWAY","engShtNm":null,"isDea":"0","cusRmk":null},
"adrDt":[
    {"cusAdr":"4","cusCd":"AMWAY","adrCd":"001","inpCd":null,"adrNm":"澳門中心","adr":"澳門高地烏街52號地下","contact":"ERIC","phone":"28527888","regCd":"新口岸","gpsX":null,"gpsY":null,"delvTimFm":"11:30:00","delvTimTo":"12:30:00","delvLasTim":"12:30:01","truckTones":null,"truckCold":null,"truckFlat":"0","truckVan":"0","truckClose":"0","truckTail":"0","truckUpstair":"0","truckDispatchAtDoor":"0","truckByBox":"0","truckByPallet":"0","truckLock":"0","pickAdrCd":null,"rmk":null,"isRef":false},
    {"shwDlt":true,"newRec":true,"adrCd":"sdf","regCd":"sdf","truckCold":true,"truckFlat":true, inpCd:"aa"},
    {"shwDlt":true,"newRec":true,"adrCd":"kk","regCd":"reg3", "inpCd:"aa"}
]
}';
    return json_decode($a);
}

function cusDs2()
{
    $a = '{
        "cusDro": {
        "cusCd": "Cus1",
        "chiNm": "",
        "engNm": "",
        "inpCd": "am",
        "cusTy": "",
        "chiShtNm": "AMWAY",
        "engShtNm": null,
        "isDea": "0",
        "cusRmk": null
    },
    "adrDt": [{
        "cusAdr": "4",
        "cusCd": "AMWAY",
        "adrCd": "001",
        "inpCd": null,
        "adrNm": "澳門中心",
        "adr": "澳門高地烏街52號地下",
        "contact": "ERIC",
        "phone": "28527888",
        "regCd": "reg1",
        "gpsX": null,
        "gpsY": null,
        "delvTimFm": "11:30:00",
        "delvTimTo": "12:30:00",
        "delvLasTim": "12:30:01",
        "truckTones": null,
        "truckCold": null,
        "truckFlat": "0",
        "truckVan": "0",
        "truckClose": "0",
        "truckTail": "0",
        "truckUpstair": "0",
        "truckDispatchAtDoor": "0",
        "truckByBox": "0",
        "truckByPallet": "0",
        "truckLock": "0",
        "pickAdrCd": null,
        "rmk": null,
        "isRef": false
    }, {
            "shwDlt": true, "newRec": true, "adrCd": "df", "regCd": "ASDF1", "adr": "ss", "inpCd": "xx"
    }], "lang": "zh"
}';
    return json_decode($a);
}

function cusDs1()
{
    $a = '{
        "cusDro": {
        "cusCd": "Cus1",
        "chiNm": "",
        "engNm": "",
        "inpCd": "am",
        "cusTy": "",
        "chiShtNm": "AMWAY",
        "engShtNm": null,
        "isDea": "0",
        "cusRmk": null
    },
    "adrDt": [{
        "cusAdr": "4",
        "cusCd": "AMWAY",
        "adrCd": "001",
        "inpCd": null,
        "adrNm": "澳門中心",
        "adr": "澳門高地烏街52號地下",
        "contact": "ERIC",
        "phone": "28527888",
        "regCd": "reg1",
        "gpsX": null,
        "gpsY": null,
        "delvTimFm": "11:30:00",
        "delvTimTo": "12:30:00",
        "delvLasTim": "12:30:01",
        "truckTones": null,
        "truckCold": null,
        "truckFlat": "0",
        "truckVan": "0",
        "truckClose": "0",
        "truckTail": "0",
        "truckUpstair": "0",
        "truckDispatchAtDoor": "0",
        "truckByBox": "0",
        "truckByPallet": "0",
        "truckLock": "0",
        "pickAdrCd": null,
        "rmk": null,
        "isRef": false
    }, {
            "shwDlt": true, "newRec": true, "adrCd": "df", "regCd": "ASDF1", "adr": "ss", "inpCd": "xx"
    }], "lang": "zh"
}';
    return json_decode($a);
}

?>

*/