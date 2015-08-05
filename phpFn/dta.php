<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-30
 * Time: 9:33
 */

require_once 'str.php';

class Dta
{
    public $dta;

    function __construct(array $dta)
    {
        $this->dta = $dta;
    }

    static function byTxt($dta_txt)
    {
        $o = [];
        $ay = split_lines($dta_txt);
        $a = array_shift($ay);
        $fldAn = split_vbar($a);
        foreach ($ay as $lin) {
            $dr = split_vbar($lin);
            $dr = ay_trim($dr);
            $m = [];
            foreach ($fldAn as $idx => $fldNm) {
                $m[$fldNm] = @$dr[$idx];
            }

            array_push($o, $m);
        }
        return new Dta($o);
    }

    static function toHtml__dr($dr, array &$fldAn)
    {
        $o = [];
        $i = 0;
        $o[$i++] = "<tr>";
        foreach ($dr as $k => $v) {

        }
        return join("", $o);
    }

    function dr($idx)
    {
        return $this->dta[$idx];
    }

    function fldNmLvs()
    {
        return join($this->fldAn(), ' ');
    }

    function fldAn()
    {
        $o = [];
        foreach ($this->dta as $dr) {
            foreach ($dr as $fld => $v) {
                ay_push_noDup($o, $fld);
            }
        }
        return $o;
    }

    function nFld()
    {
        return sizeof($this->fldAn());
    }

    function toCsvAy()
    {
        return $this->toCsvAy1();
    }

    function toCsvAy1()
    {
        $fldAn = $this->fldAn();
        $o = [];
        $i = 0;
        $o[$i++] = join(",", $fldAn);
        foreach ($this->dta as $i => $dr) {
            $o[$i++] = ay_csvStr($dr);
        }
        return $o;
    }

    function toCsvAy2()
    {
        $fldAn = $this->fldAn();
        $f = fopen("php://temp", "w+");
        fputcsv($f, $this->fldAn());
        fputs($f, CRLF);
        $ub = $this->nRec() - 1;
        foreach ($this->dta as $i => $dr) {
            $ay = ay_subSet($dr, $fldAn);
            $ay1 = array_values($ay);
            fputcsv($f, $ay1);
            if ($i !== $ub) {
                fputs($f, LF);
            }
        }
        $o = $this->toCsv_getTxtAy($f);
        fclose($f);
        return $o;
    }

    function nRec()
    {
        return sizeof($this->dta);
    }

    function toFmt($alignR_Lvs = null, $alignC_Lvs = null)
    {
        $o = [];
        $fldAn = $this->fldAn();
        $wdtAy = $this->wdtAy($fldAn);
        $alignAy = Dta::toFmt__alignAy($alignR_Lvs, $alignC_Lvs, $fldAn);
        $o = [];
        $i = 0;
        $o[$i++] = Dta::toFmt__hdrLin($fldAn, $wdtAy, $alignAy);
        foreach ($this->dta as $dr) {
            $o[$i++] = Dta::toFmt__dr($dr, $fldAn, $wdtAy, $alignAy);
        }
        return join($o, "\n");
    }

    function wdtAy(array $fldAn)
    {
        $w2 = [];
        foreach ($fldAn as $fldNm) {
            $w2[$fldNm] = mb_strwidth($fldNm);
        }
        $w1 = $this->wdtAy__dtaOnly();
        return ay_maxEle($w1, $w2);
    }

    function wdtAy__dtaOnly()
    {
        $o = [];
        foreach ($this->dta as $dr) {
            $w = ay_eleWdt($dr);
            $o = ay_maxEle($o, $w);
        }
        return $o;
    }

    static function toFmt__alignAy($alignR_Lvs, $alignC_Lvs, $fldAn)
    {
        $o = [];
        $i = 0;
        $R = split_lvs($alignR_Lvs);
        $C = split_lvs($alignC_Lvs);
        foreach ($fldAn as $nm) {
            $o[$nm] =
                in_array($nm, $R) ? "R"
                    : (in_array($nm, $C) ? "C"
                    : "L");
        }
        return $o;
    }

    static function toFmt__hdrLin(array $fldAn, array $wdtAy, array $alignAy)
    {
        $i = 0;
        $o = [];
        foreach ($fldAn as $fldNm) {
            $a = $alignAy[$fldNm];  // assume $alignAy always contains all keys as specified in $fldAn else err
            $w = $wdtAy[$fldNm];    // assume $wdtAy   always contains all keys as specified in $fldAn else err
            $l = strlen($fldNm);
            if ($w < $l) {
                throw new Exception("w must > len(fldNm). w=[$w] fldNm=[$fldNm] len(fldNm)=[$l]");
            }
            $o[$i++] = str_align($fldNm, $w, $a);
        }
        return rtrim(join(" | ", $o));
    }

    static function toFmt__dr(array $dr, array $fldAn, array $wdtAy, array $alignAy)
    {
        $o = [];
        $i = 0;
        foreach ($fldAn as $fldNm) {
            $v = @$dr[$fldNm];
            $w = $wdtAy[$fldNm];        // assume $wdtAy   contains all key as in $dr else exception
            $a = $alignAy[$fldNm];      // assume $alignAy contains all key as in $dr else exception
            $v1 = str_align($v, $w, $a);
            $o[$i++] = $v1;
        }
        return rtrim(join(" | ", $o));
    }

    function toHtml($caption = null)
    {
        $o = [];
        $i = 0;
        $o[$i++] = "<table>";
        if (!is_null($caption)) $o[$i++] = "<caption>$caption</caption>";
        $o[$i++] = "";
        $fldAn = $this->fldAn();
        foreach ($this->dta as $dr) {
            $o[$i++] = ay_valAy($dr, $fldAn);
        }
        $o[$i++] = "</table>";
        $o[1] = Dta::toHtml__hdr($fldAn);
        return join($o, "\n");
    }

    static function toHtml__hdr($fldAn)
    {
        $o = [];
        return join("", $o);
    }

    function toTxt()
    {
        $o = [];
        $i = 0;
        $fldAn = $this->fldAn();
        $o[$i++] = join("|", $fldAn);
        foreach ($this->dta as $dr) {
            $o[$i++] = Dta::toTxt__dr($dr, $fldAn);
        }
        return join($o, "\n");
    }

    static function toTxt__dr($dr, array $fldAn)
    {
        $o = [];
        $i = 0;
        foreach ($fldAn as $fldNm) {
            $o[$i++] = @$dr[$fldNm];
        }
        return join("|", $o);
    }
}

//class dta_fn
{
    function brw_dtaAy($nm_lvs, ...$dtaAy)
    {
        $csvPth = dtaAy_tmpPth_array($nm_lvs, $dtaAy);
        $fm = pth_norm(__DIR__ . "/../xlsm/OpnCsvPth.xlsm");
        $to = $csvPth . "OpnCsvPth.xlsm";
        copy($fm, $to);
        $a = new System_Launcher;
        $a->Launch($to);
    }

    function dtaAy_tmpPth($nm_lvs, ...$dtaAy)
    {
        return dtaAy_tmpPth_array($nm_lvs, $dtaAy);
    }

    /** return the tmpPth which has each $dtaAy written as a csv file */
    function dtaAy_tmpPth_array($nm_lvs, array $dtaAy)
    {
        $p = pth_tmp("dtaAy");
        $nmAy = split_lvs($nm_lvs);
        $j = 0;
        foreach ($dtaAy as $dta) {
            $file = $p . $nmAy[$j++] . ".csv";
            $f = fopen($file, "c");

            if (count($dta) === 0) {
                fclose($f);
                continue;
            };
            $dta1 = dta_convert_encoding($dta);
            $fldNmAy = array_keys($dta1[0]);   // use for row as fldNmAy
            fputcsv($f, $fldNmAy);
            foreach ($dta1 as $dr) {
                $fields = [];
                foreach ($fldNmAy as $nm)
                    array_push($fields, @$dr[$nm]);
                fputcsv($f, $fields);
            }
            fclose($f);
        }
        return $p;
    }

    function dtaPk(array $dta, $pkFldNm)
    {
        return (new Dta($dta))->pk(pkFldNm);
    }

    function dta_convert_encoding($dta, $encoding = "BIG-5")
    {
        $o = [];
        foreach ($dta as $k => $dr) {
            $o[$k] = ay_convert_encoding($dr, $encoding);
        }
        return $o;
    }

    /** return a dic by using first column as key and 2nd column as val */
    function dta_dic($twoColDta)
    {
        $o = [];
        if (count($twoColDta) === 0) return $o;
        $a = array_keys($twoColDta[0]);
        $k0 = $a[0];
        $k1 = $a[1];
        foreach ($twoColDta as $i) {
            $key = $i[$k0];
            $val = $i[$k1];
            $o[$key] = $val;
        }
        return $o;
    }

    function dta_extract($dta, $lvs)
    {
        $nm_ay = split_lvs($lvs);
        $o = [];
        foreach ($dta as $dr) {
            $m = [];
            foreach ($nm_ay as $nm) {
                assert_key_exists($nm, $dr);
                $m[$nm] = $dr[$nm];
            }
            array_push($o, $m);
        }
        return $o;
    }

    function dta_join($Glue, $Dta)
    {
        $a = ayGluer($Glue);
        return array_reduce($Dta, $a, []);
    }

    /**
     * return a new [dta] with [key] is unique [line] with joined with \n and with optional $pfx
     *  from $dta which 2 columns of $keyFldNm & $linFldNm
     */
    function dta_joinLine_byKey(
        $dta, // $dta has 2 columns: $keyFldNm &  $linFldNm.  $key is not unique.
        $keyFldNm, // the key-field of $dta
        $linFldNm, // the line-field of $dta to join
        $pfx = "", // what pfx to be added to each line
        $jnChr = '\n')
    {
        $f1 = $keyFldNm;
        $f2 = $linFldNm;

        $o = [];
        if (sizeof($dta) === 0) return [];
        $k = $dta[0][$f1];      // first line in $dta
        $l = $dta[0][$f2];      // first line in $dta
        $m = [];
        $m[$f1] = $k;
        $m[$f2] = $pfx . $l;     // first record
        $first = true;
        foreach ($dta as $dr) {
            if ($first) {
                $first = false;
                continue;
            }
            $k1 = $dr[$f1];
            $l1 = $dr[$f2];
            if ($k1 === $k) {
                $m[$f2] .= $jnChr . $l1;
            } else {
                array_push($o, $m);
                $m[$f1] = $k1;
                $m[$f2] = $pfx . $l1;
                $k = $k1;
            }
        }
        array_push($o, $m);
        return $o;
    }

    function dta_toFmt($dta, $alignR_Lvs = null, $alignC_Lvs = null)
    {
        return (new Dta($dta))->toFmt($alignR_Lvs, $alignC_Lvs);
    }

    function dta_toTxt($dta)
    {
        return (new Dta($dta))->toTxt();
    }

    function dta_byTxt($dta_txt)
    {
        $dta = Dta::byTxt($dta_txt);
        return $dta->dta;
    }
} // dta_fn endClass
