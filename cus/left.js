/**
 * touch point:
 *      oup: $scope.appRegion.cusCd
 *      inp: $scope.sess.lang
 */
angular.module('app').controller('left', ['$scope', '$http', '$app', '$obj', '$ay', '$dta', function ($scope, $http, $app, $obj, $ay, $dta) {
    var fldLvs = "cusCd inpCd chiShtNm engShtNm";
    var fldAn = fldLvs.split(' ');
    $scope.fldLvs = fldLvs;
    $scope.fldAn = fldAn;
    $scope.$watch("sess.lang", function (lang) {
        $app.getLbl("cus", "left", lang, $scope, bld_and_set_tar_hdrAy);
    })
    $scope.$watch('rno', function (rno) {
        if ($scope.tar === undefined)return;
        if (!angular.isArray($scope.tar.data))return;
        var i = rno - 1;
        if (0 <= i && i < $scope.tar.data.length) {
            var rec = $scope.tar.data[rno - 1];
            if (rec === undefined)
                return;
            $scope.appCus.cusCd = rec.cusCd;
        }
    })
    $scope.$watch('filter', bld_and_set_tar_data);
    $scope.do_sel_row = do_sel_row;
    $scope.do_tgl_btn = do_tgl_btn;
    $scope.btn0Nm = fldAn[0];
    $scope.btn1Nm = fldAn[1];
    $scope.btn2Nm = fldAn[2];
    $scope.btn3Nm = fldAn[3];
    $scope.btn_selected = $obj.getOptObj(fldLvs);
    $app.getLbl("cus", "left", $scope.sess.lang, $scope, bld_and_set_tar_hdrAy);
    $http.get("left.php").success(success);

    angular.jj = angular.jj || {};
    angular.jj.ondrop = ondrop;
    angular.jj.ondragstart = ondragstart;
    angular.jj.ondragover = ondragover;
    return;

    function success(data, status, headers, config) {
        $scope.src = {data: data};
        $scope.tar = $scope.tar || {};
        $scope.btn_selected = $obj.getOptObj(fldLvs);
        bld_and_set_tar_data();

        $scope.rno = $scope.appCus.cusCd
            ? _rno()
            : 1;
    }

    function _rno() {
        if (!$scope.tar)return;
        var cusCd = $scope.appCus.cusCd;
        var tarDta = $scope.tar.data;
        var i = _.findIndex(tarDta, function (dr) {
            return cusCd === dr.cusCd
        })
        return i == -1 ? "" : i + 1;
    }

    /**
     * The change of $scope.tar.data are trigger @ 3 points: #1 filter, #2 toggle-btn, #3 swap-button
     * They should all call this function to set $scope.tar.data.
     * To build $scope.tar.data, these are the input:
     *      filter
     *      btn_selected        : {inp cus eng chi} where each prp in the obj-btn_selected is boolean
     *      btn1Nm, 2, 3, 4
     * Class Dta should be used to build the tar.data
     */
    function bld_and_set_tar_data() {
        if ($scope.src === undefined)return;
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
        var o = d.filter_and_selCol(filter, selColNmLvs, " isDea cusCd")
        $scope.tar = $scope.tar || {};
        $scope.tar.data = o;
        function _selColNmLvs(bs, b0, b1, b2, b3) {
            // input:
            //      bs-stru = {chi eng inp cus} where all prp are boolean
            //      b0      = btn0Nm        which is string of value one of these 4: (chi eng inp cus)
            //      b1 2 3  = btn1Nm 2 3    ^-- similar
            // output:
            //      selColNmLvs = Lvs-string: "inp cus eng chi" with each element as optional.
            var a = [];
            if (bs[b0]) a.push(b0);
            if (bs[b1]) a.push(b1);
            if (bs[b2]) a.push(b2);
            if (bs[b3]) a.push(b3);
            var selColNmLvs = a.join(' ');
            return selColNmLvs;
        }
    }

    function bld_and_set_tar_hdrAy() {
        var bs = $scope.btn_selected;
        if (!bs)return;
        var b0 = $scope.btn0Nm;
        var b1 = $scope.btn1Nm;
        var b2 = $scope.btn2Nm;
        var b3 = $scope.btn3Nm;
        var lblDic = $scope.lbl.btn;

        var a = [];
        if (bs[b0]) a.push(lblDic[b0]);
        if (bs[b1]) a.push(lblDic[b1]);
        if (bs[b2]) a.push(lblDic[b2]);
        if (bs[b3]) a.push(lblDic[b3]);
        $scope.tar = $scope.tar || {};
        $scope.tar.hdrAy = a;
    }

    function do_sel_row() {
        $scope.rno = this.$index + 1;
    }

    function do_tgl_btn(btnNm) {
        var a = $scope.btn_selected;
        var s0 = a[fldAn[0]];	// s for switch
        var s1 = a[fldAn[1]];
        var s2 = a[fldAn[2]];
        var s3 = a[fldAn[3]];
        switch (btnNm) {
            case fldAn[0]:
                if (s0 && !s1 && !s2 && !s3) return;
                break;
            case fldAn[1]:
                if (!s0 && s1 && !s2 && !s3) return;
                break;
            case fldAn[2]:
                if (!s0 && !s1 && s2 && !s3) return;
                break;
            case fldAn[3]:
                if (!s0 && !s1 && !s2 && s3) return;
                break;
        }
        $scope.btn_selected[btnNm] = !$scope.btn_selected[btnNm];
        bld_and_set_tar_data();
        bld_and_set_tar_hdrAy();
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
        ev.dataTransfer.setData("text", name) // name equals to field name, which = button name.
    }
}]);
