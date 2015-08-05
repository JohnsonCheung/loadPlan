<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-08-05
 * Time: 11:52
 */
require_once 'Dta.php';

class Dt
{
    /** @var  array */
    public $dta;
    public $dtNm;

    function __construct($dtNm, array $dta)
    {
        $this->dtNm = $dtNm;
        $this->dta = $dta;
    }

    static function byTxt($dt_txt)
    {
        $txt1 = null;
        $txtRest = null;
        {
            $ay = split_lines($dt_txt);
            $txt1 = array_shift($ay);
            $txtRest = join($ay, "\n");
        }
        $nmDt = null; // <- $txt1
        {
            if (!is_pfx($txt1, "*Dt ")) throw new Exception("first line of \$txt must be *Dt XXX");
            $nmDt = rmv_pfx($txt1, "*Dt ");
        }
        $a = Dta::byTxt($txtRest);
        $dta = $a->dta;

        return new Dt($nmDt, $dta);
    }


    function fldNmLvs()
    {
        return join(' ', $this->fldAn());
    }

    function fldAn()
    {
        return $this->Dta()->fldAn();
    }

    function Dta()
    {
        return new Dta($this->dta);
    }

    function nFld()
    {
        return sizeof($this->Dta()->fldAn());
    }

    function nRec()
    {
        return sizeof($this->dta);
    }

    /** return pkVal=>dr */
    function pk($pkFldNm)
    {
        $o = [];
        foreach ($this->dta as $i => $dr) {
            $pkVal = @$dr[$pkFldNm];
            if (!isset($pkVal)) {
                throw new Exception("row[$i] does not have pkFld[$pkFldNm]");
            }
            $o[$pkVal] = $dr;
        }
        return $o;
    }

    function rec($recIdx)
    {
        return $this->dta[$recIdx];
    }


    function toCsvAy()
    {
        return $this->Dta()->tsCsvAy();
    }


    function toFmt($alignR_Lvs = null, $alignC_Lvs = null)
    {
        $dtNm = $this->dtNm;
        return "*Dt $dtNm\n" . $this->Dta()->toFmt($alignR_Lvs, $alignC_Lvs);
    }

    function toHtml()
    {
        return $this->Dta()->toHtml($this->dtNm);
    }

    function toTxt()
    {
        $dtNm = $this->dtNm;
        return "*Dt $dtNm\n" . $this->Dta()->toTxt();
    }

    function toTxt_byKey()
    {

    }



    private function toCsv_getTxtAy($f)
    {
        $o = [];
        $i = 0;
        fseek($f, 0);
        $m = fgets($f);
        if ($m !== false) {
            $o[$i++] = $m;
            $m = fgets($f);
        }
        return $o;
    }
}
