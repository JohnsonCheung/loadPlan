/**
 * Created by USER on 2015-07-08.
 */

//************//
//*** $app ***//
//************//
angular.module('app').factory('dspH_hlp', function () {
    return {
        assign_lblBtn: assign_lblBtn,
        assign_hdr_lbl: assign_hdr_lbl,
        assign_hdr_dta: assign_hdr_dta,
        assign_inst_lbl: assign_inst_lbl,
        assign_inst_dta: assign_inst_dta,
        assign_adr_dta: assign_adr_dta,
        assign_adr_lbl: assign_adr_lbl,
        assign_content_dta: assign_content_dta,
        assign_content_lbl: assign_content_lbl
    }
    function assign_lblBtn($scope) {
        $scope.lbl = $scope.lbl || {};
        $scope.lbl.btn = $scope.lbl.btn || {};
        $scope.lbl.btn.inst = "instruction";
        $scope.lbl.btn.hdr = "header";
        $scope.lbl.btn.adr = "address";
        $scope.lbl.btn.content = "content";
        $scope.lbl.btn.back = "Back";
        $scope.lbl.btn.prt = "Print";
        $scope.lbl.btn.can = "Print";
        $scope.lbl.btn.upd = "Update";
    }

    function assign_hdr_dta($scope) {
        $scope.data = $scope.data || {};
        $scope.data.ord = $scope.data.ord || {};
        $scope.data.ord.ordNo = 'aa';
        $scope.data.ord.ordDelvDte = 'aa';
        $scope.data.ord.cusCd = 'aa';
        $scope.data.ord.engShtNm = 'aa';
        $scope.data.ord.chiShtNm = 'aa';
        $scope.data.ord.ordBy = 'aa';
        $scope.data.ord.ordByPhone = 'aa';
        $scope.data.ord.ordTy = 'aa';

        $scope.data.ord.crtBy = 'aa';
        $scope.data.ord.crtOn = 'aa';
        $scope.data.ord.isComplete = 'aa';

        $scope.data.ord.canBy = '';     // if no canBy, all can-* will not displayed
        $scope.data.ord.canOn = 'aa';
        $scope.data.ord.canRmk = 'aa';
        $scope.data.ord.cpyFmOrd = 'aa';

        $scope.data.ord.nCntr = 4;
        $scope.data.ord.nPallet = 10;
        $scope.data.ord.nBox = 4;
        $scope.data.ord.nCBM = 10;
        $scope.data.ord.nCage = 10;
        $scope.data.ord.pickAdrCd = 4;
        $scope.data.ord.nContent = 10;
        $scope.data.ord.pickTim = 4;
        $scope.data.ord.isRetWhs = 10;
        $scope.data.ord.isCold = 4;

        $scope.data.ord.nAdr = 10;
        $scope.data.ord.nContent = null;
    }

    function assign_hdr_lbl($scope) {
        $scope.lbl = $scope.lbl || {};
        $scope.lbl.fld = $scope.lbl.fld || {};
        $scope.lbl.fld.ordNo = "ordNo#";
        $scope.lbl.fld.ordDelvDte = "ordDelvDtee";
        $scope.lbl.fld.cusCd = 'aa';
        $scope.lbl.fld.engShtNm = 'aa';
        $scope.lbl.fld.chiShtNm = 'aa';
        $scope.lbl.fld.ordBy = "ordBy";
        $scope.lbl.fld.ordByPhone = "ordByPhone";
        $scope.lbl.fld.ordTy = "ordTye";
        $scope.lbl.fld.crtBy = "crtBy";
        $scope.lbl.fld.crtOn = "crtOn";
        $scope.lbl.fld.isComplete = "isComplete";
        $scope.lbl.fld.canBy = "canBy";
        $scope.lbl.fld.canOn = "canOn";
        $scope.lbl.fld.canRmk = "canRmk";
        $scope.lbl.fld.cpyFmOrd = "cpyFmOrd";
        $scope.lbl.fld.nCntr = "nCntr";
        $scope.lbl.fld.nPallet = "nPallet";
        $scope.lbl.fld.nBox = "nBox";
        $scope.lbl.fld.nCBM = "nCBM";
        $scope.lbl.fld.nCage = 'nCage';
        $scope.lbl.fld.pickAdrCd = "pickAdrCd";
        $scope.lbl.fld.pickTim = "pickTim";
        $scope.lbl.fld.isRetWhs = "isRetWhs";
        $scope.lbl.fld.isCold = "isCold";
        $scope.lbl.fld.nAdr = "nAdr";
        $scope.lbl.fld.nContent = "nContent";
    }

    function assign_inst_dta($scope) {
        $scope.data = $scope.data || {};
        $scope.data.inst = $scope.data.inst || [];
        $scope.data.inst[0] = {isStdInst: 'Y', instCd: 'XX', instTxt: 'xxxxxx x x xxxxx'};
        $scope.data.inst[1] = {isStdInst: 'Y', instCd: 'BB', instTxt: 'xxxxxx x x xxxxx'};
        $scope.data.inst[2] = {isStdInst: 'Y', instCd: 'XX', instTxt: 'xxxxxx x x xxxxx'};
        $scope.data.inst[3] = {instTxt: 'xxxxxx x x xxxxx'};
    }

    function assign_inst_lbl($scope) {
        $scope.lbl = $scope.lbl || {};
        $scope.lbl.fld = $scope.lbl.fld || {};
        $scope.lbl.fld.isStdInst1 = "isStdInst1 ";
        $scope.lbl.fld.isStdInst2 = "isStdInst2";
        $scope.lbl.fld.instCd1 = "instCd1 ";
        $scope.lbl.fld.instCd2 = "instCd2";
        $scope.lbl.fld.instTxt = "instTxt";
    }

    function assign_adr_dta($scope) {
        $scope.data = $scope.data || {};
        $scope.data.adr = $scope.data.adr || [];
        $scope.data.adr[0] = {
            isOneTim: 'Y',
            adrCd: 'adrCd',
            regCd: 'regCd',
            adrNm: 'xx',
            adr: 'xxx',
            contact: 'aaa',
            delvTim: 'xxx',
            nPallet: 'xxx',
            nBox: 'xxx',
            nCBM: 'xxx',
            nCage: 'xxx',
            delvContNoAy: [0, 2],
            rmk: 'xxx'
        };

        $scope.data.adr[1] = {
            isOneTim: 'Y',
            adrCd: 'XX',
            regCd: 'xxxxxx',
            adrNm: 'xx',
            adr: 'xxx',
            contact: 'aaa',
            delvTim: 'xxx',
            nPallet: 'xxx',
            nBox: 'xxx',
            nCBM: 'xxx',
            nCage: 'xxx',
            delvContNoAy: [1, 2],
            rmk: 'xxx'
        };

    }

    function assign_adr_lbl($scope) {
        $scope.lbl = $scope.lbl || {};
        $scope.lbl.fld = $scope.lbl.fld || {};
        $scope.lbl.fld.isOneTim = "isOneTim ";
        $scope.lbl.fld.adrCd = "adrCd";
        $scope.lbl.fld.regCd = "regCd ";
        $scope.lbl.fld.adrNm = "adrNm";
        $scope.lbl.fld.adr = "adr";
        $scope.lbl.fld.contact = "contact";
        $scope.lbl.fld.nPallet = "nPallet";
        $scope.lbl.fld.nBox = "nBox";
        $scope.lbl.fld.nCBM = "nCBM";
        $scope.lbl.fld.nCage = "nCage";
        $scope.lbl.fld.delvTim = "delvTim";
        $scope.lbl.fld.delvContNoAy = "delvContNoAy";
        $scope.lbl.fld.rmk = "rmk";
    }

    function assign_content_dta($scope) {
        $scope.data = $scope.data || {};
        $scope.data.content = $scope.data.content || [];
        $scope.data.content[0] = {
            idx: 0,
            contentRmk: 'aaaaa0',
            url: 'http://www.jetft.com/sites/default/files/slideshow/Photo.jpg'
        };
        $scope.data.content[1] = {
            idx: 1,
            contentRmk: 'aaaaa1',
            url: 'http://www.jetft.com/sites/default/files/styles/large/public/images/20121103141058.png'
        };
        $scope.data.content[2] = {
            idx: 2,
            contentRmk: 'aaaaa2',
            url: 'http://www.jetft.com/sites/default/files/styles/sidebar_image/public/page/20091029820402-org_2.jpg'
        };
    }

    function assign_content_lbl($scope) {
        $scope.lbl = $scope.lbl || {};
        $scope.lbl.fld = $scope.lbl.fld || {};
        $scope.lbl.fld.des = "description";
    }
});
