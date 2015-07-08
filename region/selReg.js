/**
 * touch point:
 *      $scope.$on('shwSelReg, tarEle_ev, regCdPar, regCdFldNm)
 */
angular.module('app').controller('selReg', ['$scope', '$http', '$document', '$ay', '$app', '$obj', '$dta', function ($scope, $http, $doc, $ay, $app, $obj, $dta) {
    var $tar;
    var $cb;
    $scope.$on("shwSelReg", function ($ev, tarEle_ev, tarObj, tarPrpNm, cb) {
        $tar = {scope: tarObj, model: tarPrpNm};
        $cb = cb;
        var tarEle = tarEle_ev.currentTarget;
        $scope.pos = tarEle_to_pos(tarEle);
        $scope.shwSelReg = true;
    })
    $app.getLbl("selReg", "sel", $scope.lang, $scope, function () {
        $http.get("/loadplan/pgm/region/selReg.php").success(success);
    });
    $scope.$watch("lang", function (lang) {
        $app.getLbl("selReg", "sel", lang, $scope)
    })
    $scope.$watch('filter', bld_and_set_tar_data);
    $scope.do_sel_row = do_sel_row;
    $scope.do_tgl_btn = do_tgl_btn;
    $scope.do_can = do_can;
    $scope.do_selReg = do_selReg;
    $scope.btn0Nm = "reg";
    $scope.btn1Nm = "inp";
    $scope.btn2Nm = "chi";
    $scope.btn3Nm = "eng";
    $scope.btn_selected = $obj.getOptObj("inp reg chi eng");
    $scope.rno = 1;

    angular.jj = angular.jj || {};
    angular.jj.ondrop = ondrop;
    angular.jj.ondragstart = ondragstart;
    angular.jj.ondragover = ondragover;

    function success(data) {
        $scope.src = {data: data};
        $scope.tar = {};
        $scope.btn_selected = {reg: true, inp: true, chi: true, eng: true};
        bld_and_set_tar_data();
        bld_and_set_tar_hdrAy();
        $scope.rno = 1;
    }

    /**
     * The change of $scope.tar.data are trigger @ 3 points: #1 filter, #2 toggle-btn, #3 swap-button
     * They should all call this function to set $scope.tar.data.
     * To build $scope.tar.data, these are the input:
     *      filter
     *      btn_selected        : {inp reg eng chi} where each prp in the obj-btn_selected is boolean
     *      btn1Nm, 2, 3, 4
     * Class Dta should be used to build the tar.data
     */
    function bld_and_set_tar_data() {
        if ($scope.src === undefined) return;
        if ($scope.src.data === undefined) return;
        var filter = $scope.filter;
        var dta = $scope.src.data;
        var bs = $scope.btn_selected;
        var b0 = $scope.btn0Nm;
        var b1 = $scope.btn1Nm;
        var b2 = $scope.btn2Nm;
        var b3 = $scope.btn3Nm;

        var selColNmLvs = _selColNmLvs(bs, b0, b1, b2, b3);
        var d = new $dta.Dta(dta);
        var o = d.filter_and_selCol(filter, selColNmLvs, " isDea regCd")
        $scope.tar = $scope.tar || {};
        $scope.tar.data = o;
        function _selColNmLvs(bs, b0, b1, b2, b3) {
            // input:
            //      bs-stru = {chi eng inp reg} where all prp are boolean
            //      b0      = btn0Nm        which is string of value one of these 4: (chi eng inp reg)
            //      b1 2 3  = btn1Nm 2 3    ^-- similar
            // output:
            //      selColNmLvs = Lvs-string: "inp reg eng chi" with each element as optional.
            var a = [];
            if (bs[b0]) a.push(_btnNm_to_fldNm(b0));
            if (bs[b1]) a.push(_btnNm_to_fldNm(b1));
            if (bs[b2]) a.push(_btnNm_to_fldNm(b2));
            if (bs[b3]) a.push(_btnNm_to_fldNm(b3));
            var selColNmLvs = a.join(' ');
            return selColNmLvs;
            function _btnNm_to_fldNm(btnNm) {
                switch (btnNm) {
                    case 'reg' :
                        return "regCd";
                    case 'inp':
                        return "inpCd";
                    case 'chi':
                        return "chiNm";
                    case 'eng':
                        return "engNm";
                    default:
                        throw new Error('unexpect btnNm');
                }
            }
        }
    }

    function bld_and_set_tar_hdrAy() {
        var bs = $scope.btn_selected;
        var b0 = $scope.btn0Nm;
        var b1 = $scope.btn1Nm;
        var b2 = $scope.btn2Nm;
        var b3 = $scope.btn3Nm;
        if ($scope.lbl === undefined)return;
        var lblDic = $scope.lbl.btn;

        var a = [];
        if (bs[b0]) a.push(lblDic[b0]);
        if (bs[b1]) a.push(lblDic[b1]);
        if (bs[b2]) a.push(lblDic[b2]);
        if (bs[b3]) a.push(lblDic[b3]);
        $scope.tar = $scope.tar || {};
        $scope.tar.hdrAy = a;
    }

    function ondrop(ev) {
        ev.preventDefault();
        var src = ev.dataTransfer.getData("text");
        var tar = ev.target.name;
        var b0 = $scope.btn0Nm;
        var b1 = $scope.btn1Nm;
        var b2 = $scope.btn2Nm;
        var b3 = $scope.btn3Nm;
        var ay = [b0, b1, b2, b3];
        var a = $ay.swapEle(ay, src, tar);
        $scope.btn0Nm = a[0];
        $scope.btn1Nm = a[1];
        $scope.btn2Nm = a[2];
        $scope.btn3Nm = a[3];
        bld_and_set_tar_data();
        bld_and_set_tar_hdrAy();
        $scope.$digest();
    }

    function ondragover(ev) {
        ev.preventDefault();
    }

    function ondragstart(ev) {
        var name = ev.target.name;
        ev.dataTransfer.setData("text", name) // id = btn_chi btn_eng btn_code btn_inp
    }

    function tarEle_to_pos(t) {
        if (t instanceof Element) {
            var tarRect = t.getBoundingClientRect();
            var tarTop = tarRect.top
            var tarLeft = tarRect.left;
            return {top: tarTop + 15, left: tarLeft + 15};
        }
        return {top: 0, left: 0}
    }

    function do_sel_row() {
        $scope.rno = this.$index + 1;
    }

    function do_tgl_btn(btnNm) {
        var a = $scope.btn_selected;
        var s0 = a.chi;	// s for switch
        var s1 = a.eng;
        var s2 = a.reg;
        var s3 = a.inp;
        switch (btnNm) {
            case 'chi':
                if (s0 && !s1 && !s2 && !s3) return;
                break
            case 'eng':
                if (!s0 && s1 && !s2 && !s3) return;
                break
            case 'cod':
                if (!s0 && !s1 && s2 && !s3) return;
                break
            case 'inp':
                if (!s0 && !s1 && !s2 && s3) return;
                break
        }
        $scope.btn_selected[btnNm] = !$scope.btn_selected[btnNm];
        bld_and_set_tar_data();
        bld_and_set_tar_hdrAy();
    }

    function do_selReg() {
        var regCd = this.rec.regCd;
        $scope.shwSelReg = false;
        $tar.scope[$tar.model] = regCd; //<=====
        if (typeof $cb === 'function') $cb($tar.scope); //<==== call back if cb is given.
    }

    function do_can() {
        $scope.shwSelReg = false;
    }
}]);