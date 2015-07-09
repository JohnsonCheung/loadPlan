/**
 * touch point:
 *      $scope.$on('shwSelCus, tarEle_ev, scope, cusCdFldNm)
 */
angular.module('app').controller('selCus', ['$scope', '$http', '$document', '$ay', '$app', '$obj', '$dta', function ($scope, $http, $doc, $ay, $app, $obj, $dta) {
    var $tar;
    var $cb;
    var fldLvs = "inpCd cusCd engShtNm chiShtNm";
    var fldAn = fldLvs.split(' ');
    $scope.$on("shwSelCus", function ($ev, tarEle_ev, tarObj, tarPrpNm, cb) {
        $tar = {scope: tarObj, model: tarPrpNm};
        $cb = cb;
        var tarEle = tarEle_ev.currentTarget;
        $scope.pos = tarEle_to_pos(tarEle);
        $scope.shwSelCus = true;
    })
    $app.getLbl("selCus", "sel", $scope.lang, $scope, function () {
        $http.get("/loadplan/pgm/cus/selCus.php").success(success);
    });
    $scope.$watch("lang", function (lang) {
        $app.getLbl("selCus", "sel", lang, $scope)
    })
    $scope.$watch('filter', bld_and_set_tar_data);
    $scope.do_sel_row = do_sel_row;
    $scope.do_tgl_btn = do_tgl_btn;
    $scope.do_can = do_can;
    $scope.do_selCus = do_selCus;
    $scope.btnAn = fldAn;
    $scope.btn_selected = $obj.getOptObj(fldLvs);
    $scope.rno = 1;

    angular.jj = angular.jj || {};
    angular.jj.ondrop = ondrop;
    angular.jj.ondragstart = ondragstart;
    angular.jj.ondragover = ondragover;

    function success(data) {
        $scope.src = {data: data};
        $scope.tar = {};
        $scope.btn_selected = $obj.getOptObj(fldLvs);
        bld_and_set_tar_data();
        bld_and_set_tar_hdrAy();
        $scope.rno = 1;
    }

    /**
     * The change of $scope.tar.data are trigger @ 3 points: #1 filter, #2 toggle-btn, #3 swap-button
     * They should all call this function to set $scope.tar.data.
     * To build $scope.tar.data, these are the input:
     *      filter
     *      btn_selected        : {inp Cus eng chi} where each prp in the obj-btn_selected is boolean
     *      btn1Nm, 2, 3, 4
     * Class Dta should be used to build the tar.data
     */
    function bld_and_set_tar_data() {
        if ($scope.src === undefined) return;
        if ($scope.src.data === undefined) return;
        var filter = $scope.filter;
        var dta = $scope.src.data;
        var bs = $scope.btn_selected;
        var selColNmLvs = _selColNmLvs(bs, $scope.btnAn);
        var d = new $dta.Dta(dta);
        var o = d.filter_and_selCol(filter, selColNmLvs, "cusCd")
        $scope.tar = $scope.tar || {};
        $scope.tar.data = o;
        function _selColNmLvs(bs, btnAn) {
            // input:
            //      bs-stru = {chi eng inp Cus} where all prp are boolean
            //      b0      = btn0Nm        which is string of value one of these 4: (chi eng inp Cus)
            //      b1 2 3  = btn1Nm 2 3    ^-- similar
            // output:
            //      selColNmLvs = Lvs-string: "inp Cus eng chi" with each element as optional.
            var a = [];
            btnAn.forEach(function (btnNm) {
                if (bs[btnNm]) a.push(btnNm);
            });
            var selColNmLvs = a.join(' ');
            return selColNmLvs;
        }
    }

    function bld_and_set_tar_hdrAy() {
        var bs = $scope.btn_selected;
        var btnAn = $scope.btnAn;
        if ($scope.lbl === undefined)return;
        var lblDic = $scope.lbl.btn;

        var a = [];
        btnAn.forEach(function(btnNm) {
            if(bs[btnNm]) {
                a.push(lblDic[btnNm]);
            }
        })
        $scope.tar = $scope.tar || {};
        $scope.tar.hdrAy = a;
    }

    function ondrop(ev) {
        ev.preventDefault();
        var src = ev.dataTransfer.getData("text");
        var tar = ev.target.name;
        var btnAn = $scope.btnAn;
        var a = $ay.swapEle(btnAn, src, tar);
        $scope.btnAn = a;
        bld_and_set_tar_data();
        bld_and_set_tar_hdrAy();
        $scope.$digest();
    }

    function ondragover(ev) {
        ev.preventDefault();
    }

    function ondragstart(ev) {
        var name = ev.target.name;
        console.log(name);
        ev.dataTransfer.setData("text", name);
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
        var nFld = fldAn.length;
        var s = [];
        for (var j = 0; j < nFld; j++) {
            s[j] = a[fldAn[0]];
        }
        var fnd = false;
        for (var j = 0; j < nFld; j++) {
            if (btnNm == fldAn[j]) {
                fnd = true;
                break;
            }
        }

        // -- now j is found
        var goTgl = false;
        for (var i = 0; i < nFld; i++) {
            if (i == j) {
                if (!s[i]) {
                    goTgl = true;
                    break;
                }
            } else {
                if (s[i]) {
                    goTgl = true;
                    break;
                }
            }
        }
        if (!goTgl) return;

        //-- now the button can toggle
        $scope.btn_selected[btnNm] = !$scope.btn_selected[btnNm];
        bld_and_set_tar_data();
        bld_and_set_tar_hdrAy();
    }

    function do_selCus() {
        var cusCd = this.rec.cusCd;
        $scope.shwSelCus = false;
        $tar.scope[$tar.model] = cusCd; //<=====
        if (typeof $cb === 'function') $cb($tar.scope); //<==== call back if cb is given.
    }

    function do_can() {
        $scope.shwSelCus = false;
    }
}])
;