<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27/5/2015
 * Time: 11:33
 */
class Wbx
{
    public $wb;

    function __construct($wb_or_fx)
    {
        if (gettype($wb_or_fx) === 'string')
            $this->wb = (new Xls())->Workbooks->Open($wb_or_fx);
        else
            $this->wb = $wb_or_fx;
    }

    function dlt_sheet123()
    {
        for ($j = 1; $j <= 3; $j++) {
            $this->dlt_ws("Sheet$j");
        }
        return $this;
    }

    function app()
    {
        return $this->wb->application;
    }

    function is_ws($wsNm_or_idx)
    {
        if (is_wholeNbr($wsNm_or_idx)) {
            $i = $wsNm_or_idx;
            return (1 <= $i && $i <= $this->nWs());
        }
        foreach ($this->wb->Sheets as $ws) {
            if ($ws->Name = $wsNm_or_idx) return true;
        }
        return false;
    }

    function dlt_ws($wsNm_or_idx)
    {
        $app = $this->app();
        $a = $app->DisplayAlerts;
        $app->DisplayAlerts = false;
        if ($this->is_ws($wsNm_or_idx))
            $this->ws(wsNm_or_idx)->delete();
        $app->DisplayAlerts = $a;
        return $this;
    }

    function newWs($wsNm = null, $aftWsNm = null)
    {
        if (is_null($aftWsNm)) {
            $o = $this->wb->Worksheets->Add(null, $this->wsLast);
        } else {
            $o = $this->wb->Worksheets->Add(null, $this->wb->Sheets($aftWsNm));
        }
        if ($wsNm <> "") {
            return $o->Name = $wsNm;
        }
    }

    function newWsx($wsNm = null, $aft_wsNm)
    {
        return new Wsx($this->newWs($wsNm, $aft_wsNm))
}

    function  nWs()
    {
        return $this->wb->sheets->count;
    }

    function opnFt($ft, $wsNm = null)
    {
        // Open Ft in $this->wb using WsNm or its file name
        $AftWs; // copy after this AftWs

        $this->wbs()->OpenText($ft);
        $fmWs = Xlsx . WbxLast . WsLast    ' the just openned Ft is the LastWb of current workbook
if WsNm <> "" Then $fmWs . Name = WsNm

$AftWs = WsLast
$fmWs . Copy After:=AftWs
$opnFt = WsxLast

Xlsx . WbLast . Close SaveChanges:=False
}

function opnFtPth(FtPth$) {
    Dim FtAy$(), J %, P As Pthx
$P = NewPthx(FtPth)
FtAy = P . FtAy
For J = 0 To UB(FtAy)
opnFt FtAy(J)
Next
$opnFtPth = Me
}

function reorderWs($wsNmLvs) {
    Dim A$(), J %, B$()
A = SplitSsv(WsNmLvs)
B = WsNmAx . ReorderByAy(A) . AyCpy
For J = UB(B) To 0 Step - 1
    'Ws(B(J)) . Index = J + 1
Next
}

    function wbs()
    {
        return app()->Workbooks;
    }

    function ws($wsNm_or_idx)
    {
        return $this->wb->sheets($wsNm_or_idx);
    }

    function wsFirst()
    {
        $WsFirst = Ws(1)
}

    function wsLast()
    {
        $WsLast = $this->wb . Sheets(NWs)
}

    function wsNmAy()
    {

        $o = [];
        foreach ($this->wb->Sheets as $ws) {
            array_push($o, $ws->Name;
        }
    }

    function wsx($wsNm_or_idx)
    {
        return $this->newWsx($this->ws($wsNm_or_idx));
    }

    function wsxFirst()
    {
        $this->wsx(1);
    }

    function wsxLast()
    {
        return $this->newWsx($this->wsLast());
    }

    function xls()
    {
        return $this->wb->Application;
    }

    function xlsx()
    {
        return new Xlsx($this->xls());
    }
}