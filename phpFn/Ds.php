<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-08-05
 * Time: 11:52
 */
require_once 'Dt.php';

class Ds
{
    public $dsNm = null;
    /** @var  array $dtAy */
    public $dtAy;

    function __construct($dsNm, $dtAy)
    {
        $this->dsNm = $dsNm;
        $this->dtAy = $dtAy;
    }


    static function byTxt($ds_txt)
    {
        $txt1 = null;
        $txtRest = null;
        {
            $ay = split_lines($ds_txt);
            $txt1 = array_shift($ay);
            $txtRest = "\n" . join("\n", $ay);  // the "\n" in front is required due to make sure each *Dt begins with 2 \n.
        }
        $dsNm = null;
        {
            if (!is_pfx($txt1, "*Ds ")) {
                throw new Exception("first line of \$txt must be [*Ds XXX]");
            }
            $dsNm = rmv_pfx($txt1, "*Ds ");
        }

        $dtTxtAy = null; // ($txtRest)
        {
            $ay = preg_split("/\n\n\*Dt /", $txtRest);
            array_shift($ay);
            $dtTxtAy = ay_addPfx($ay, "*Dt ");
        }
        $dtAy = [];
        {
            foreach ($dtTxtAy as $dtTxt) {
                $dt = Dt::byTxt($dtTxt);
                $dtNm = $dt->dtNm;
                $dtAy[$dtNm] = $dt;
            }
        }
        return new Ds($dsNm, $dtAy);
    }


    function dt($dtNm)
    {
        return $this->dtAy[$dtNm];
    }

    function dtAn()
    {
        return array_keys($this->dtAy);
    }

    function nDt()
    {
        return sizeof($this->dtAy);
    }

    function toFmt($alignR_Lvs = null, $alignC_Lvs = null)
    {
        $o = [];
        $i = 0;
        $dsNm = $this->dsNm;
        $o[$i++] = "*Ds $dsNm";
        /** @var Dt $dt */
        foreach ($this->dtAy as $dt) {
            $o[$i++] = "";
            $o[$i++] = $dt->toFmt($alignR_Lvs, $alignC_Lvs);
        }
        return join("\n", $o);
    }

    function toTxt()
    {
        $o = [];
        $i = 0;
        $dsNm = $this->dsNm;
        $o[$i++] = "*Ds $dsNm";
        /** @var Dt $dt */
        foreach ($this->dtAy as $dt) {
            $o[$i++] = "";
            $o[$i++] = $dt->toTxt();
        }
        return join("\n", $o);
    }
}
