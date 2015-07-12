<?php
namespace {
    include_once 'LoadSheet.php';
}
namespace LoadSheet {
    class Gen
    {
        public
            $inpAdrRmkKey,
            $inpAdrRmkTxt,
            $inpOrdRmkKey,
            $inpOrdRmkTxt,
            $inpDrop,
            $inpContent,
            $inpTrip,
            $tripDelvDte,
            $tripNo,
            $tripNm,
            $tripPth;
        public
            $rmkFile,
            $hdrFile,
            $barCdFile;
        private
            $trip,
            $con;

        function __construct($trip)
        {
            $this->trip = $trip;
            $this->con = \db_con();
            $con = $this->con;
            $inpAdrRmkKey = z_runsp_dta("adrRmkKey", $trip, $con);
            $inpAdrRmkTxt = z_runsp_dta("adrRmkTxt", $trip, $con);
            $inpOrdRmkKey = z_runsp_dta("ordRmkKey", $trip, $con);
            $inpOrdRmkTxt = z_runsp_dta("ordRmkTxt", $trip, $con);
            $inpDrop = z_runsp_dta("drop", $trip, $con); // ordDrop | ord ordAdr cusCd shtNm ordBy adr adrContact contentNoLvc box pallet cbm
            $inpContent = z_runsp_dta("content", $trip, $con); // ord contentNo contentRmk withImg
            $inpTrip = z_runsp_dro("trip", $trip, $con);

            $tripDelvDte = $inpTrip->dte;
            $tripNo = $inpTrip->tripNo;
            $tripNm = tripNm($tripDelvDte, $tripNo);
            $tripPth = tripPth($tripNm);
            $rmkFile = $tripPth . "rmk.txt";
            $hdrFile = $tripPth . "hdr.txt";
            $barCdFile = $tripPth . "barCd.png";

            $this->inpAdrRmkKey = $inpAdrRmkKey;
            $this->inpAdrRmkTxt = $inpAdrRmkTxt;
            $this->inpOrdRmkKey = $inpOrdRmkKey;
            $this->inpOrdRmkTxt = $inpOrdRmkTxt;
            $this->inpDrop = $inpDrop;
            $this->inpContent = $inpContent;
            $this->inpTrip = $inpTrip;
            $this->tripDelvDte = $tripDelvDte;
            $this->tripNo = $tripNo;
            $this->tripNm = $tripNm;
            $this->tripPth = $tripPth;
            $this->rmkFile = $rmkFile;
            $this->hdrFile = $hdrFile;
            $this->barCdFile = $barCdFile;
        }

        function brwAllData()
        {
            brw_dtaSet([
                'inpAdrRmkKey' => $this->inpAdrRmkKey,
                'inpAdrRmkTxt' => $this->inpAdrRmkTxt,
                'inpContent' => $this->inpContent,
                'inpDrop' => $this->inpDrop,
                'inpOrdRmkKey' => $this->inpOrdRmkKey,
                'inpOrdRmkTxt' => $this->inpOrdRmkTxt,
                'inpAdrRmkKey' => $this->inpAdrRmkKey,

            ]);
        }

        function gen()
        {
            $this->genPth();
            $this->genHdr();
            $this->genRmk();
            $this->genAtt();
            $this->genBarCd();
            $this->genDrop();
        }

        function genPth()
        {
            genPth($this->tripPth);
        }

        function genHdr()
        {
            genHdr($this->hdrAy(), $this->hdrFile);
        }

        function hdrAy()
        {
            return Hdr\ay($this->inpTrip);
        }

        function genRmk()
        {
            genRmk($this->rmkAy(), $this->rmkFile);
        }

        function rmkAy()
        {
            return Rmk\rmkAy(
                $this->ord_rmkNo_dic(),
                $this->ord_rmkKey_dic(),
                $this->ord_rmkTxt_dic(),
                $this->ordAdr_rmkNo_dic(),
                $this->ordAdr_rmkKey_dic(),
                $this->ordAdr_rmkTxt_dic());
        }

        function ord_rmkNo_dic()
        {
            return $this->ord_rmkNo_dic__and__ordAdr_rmkNo_dic()[0];
        }

        function ord_rmkNo_dic__and__ordAdr_rmkNo_dic()
        {
            return Rmk\ord_rmkNo_dic__and__ordAdr_rmkNo_dic($this->inpDrop, $this->ord_rmkTxt_dic(), $this->ordAdr_rmkTxt_dic());
        }

        function ord_rmkTxt_dic()
        {
            return Rmk\ord_RmkTxt_dic($this->inpOrdRmkTxt);
        }

        function ordAdr_rmkTxt_dic()
        {
            return Rmk\ordAdr_rmkTxt_dic($this->inpAdrRmkTxt);
        }

        function ord_rmkKey_dic()
        {
            return Rmk\ord_rmkKey_dic($this->inpOrdRmkKey, $this->tripDelvDte);
        }

        function ordAdr_rmkNo_dic()
        {
            return $this->ord_rmkNo_dic__and__ordAdr_rmkNo_dic()[1];
        }

        function ordAdr_rmkKey_dic()
        {
            return Rmk\ordAdr_rmkKey_dic($this->inpAdrRmkKey, $this->ord_rmkKey_dic());
        }

        function genAtt()
        {
            genAtt($this->fmtoDta());
        }

        function fmtoDta()
        {
            return Att\fmtoDta($this->attDta(), $this->tripPth, $this->tripNm);
        }

        function attDta()
        {
            return Att\attDta($this->inpDrop, $this->inpContent);
        }

        function genBarCd()
        {
            genBarCd($this->tripDelvDte, $this->tripNo, $this->barCdFile);
        }

        function genDrop()
        {
            genDrop($this->dropDta(), $this->tripPth);
        }

        function dropDta()
        {
            return Drop\dropDta($this->inpDrop, $this->tripDelvDte,
                $this->ordDrop_contentLines_dic(),
                $this->ordDrop_pagNoList_dic(),
                $this->ordDrop_rmkNoList_dic());
        }

        function ordDrop_contentLines_dic()
        {
            return Att\ordDrop_contentLines_dic($this->inpDrop, $this->ord_n_contentNo__contentLines__dic());
        }

        function ord_n_contentNo__contentLines__dic()
        {
            return Att\ord_n_contentNo__contentLines__dic($this->inpContent);
        }

        function ordDrop_pagNoList_dic()
        {
            return Att\ordDrop_pagNoList_dic($this->ordDrop_pagNo());
        }

        function ordDrop_pagNo()
        {
            return Att\ordDrop_pagNo($this->inpDrop, $this->ord_n_contentNo__pagNo__dic());
        }

        function ord_n_contentNo__pagNo__dic()
        {
            return Att\ord_n_contentNo__pagNo__dic($this->inpDrop, $this->inpContent);
        }

        function ordDrop_rmkNoList_dic()
        {
            return Rmk\ordDrop_rmkNoList_dic($this->inpDrop, $this->ord_rmkNo_dic(), $this->ordAdr_rmkNo_dic());
        }
    }
}