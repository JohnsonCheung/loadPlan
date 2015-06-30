angular.module('app').controller('upd', ['$scope', '$http', '$obj', '$app', function a($scope, $http, $obj, $app) {
    $scope.$watch('sess.lang', watch_lang);
    $scope.$watch('appCus.cusCd', watch_cusCd);
    $scope.do_can = do_can;
    $scope.do_sav = do_sav;
    $scope.do_vdt = do_vdt;
    $scope.do_dltAdr = do_dltAdr;
    $scope.do_deaAdr = do_deaAdr;
    $scope.do_reaAdr = do_reaAdr;
    $scope.do_addAdr = do_addAdr;
    $scope.do_shwSelReg = do_shwSelReg;
    $scope.do_reset = do_reset;
    $scope.view1 = true;
    $scope.view1Color = "lightblue";
    $scope.do_view1 = do_view1;
    $scope.do_view2 = do_view2;
    $http.get('../phpResp/tblDc.php?t=region&f=regCd').success(function (data) {
        $scope.regCdDc = data;
    });
    function watch_cusCd(cusCd) {
        $http.get('updGetDta.php?cusCd=' + cusCd).success(function (data) {
            $scope.dataOrg = angular.copy(data);
            $scope.data = data;
            return;
        });
    }

    function watch_lang(lang) {
        $app.getLbl("cus", "upd", lang, $scope);
    }

    function do_view1() {
        $scope.view1 = true;
        $scope.view1Color = "lightblue";
        $scope.view2 = false;
        $scope.view2Color = "buttonface";
    }

    function do_view2() {
        $scope.view2 = true;
        $scope.view2Color = "lightblue";
        $scope.view1 = false;
        $scope.view1Color = "buttonface";
    }

    function do_vdt() {
        debugger;
        $scope.updMsg = _vdt();
    }

    function do_reset() {
        $scope.data = angular.copy($scope.dataOrg);
    }

    function do_dltAdr() {
        var idx = this.$index;
        $scope.data.adrDt.splice(idx, 1);
    }

    function do_addAdr() {
        $scope.data.adrDt.push({shwDlt: true, newRec: true});
    }

    function do_deaAdr() {
        var idx = this.$index;
        $scope.data.adrDt[idx].isDea = '1';
    }

    function do_reaAdr() {
        var idx = this.$index;
        $scope.data.adrDt[idx].isDea = '0';
    }

    function do_shwSelReg(ev, index) {
        //$broadcast('shwSelReg', $event, $scope.data.nearByDt[$index],'nearBy'
        $scope.$broadcast('shwSelReg', ev, $scope.data.adrDt[index], 'regCd');
    }

    function do_sav() {
        var cus = $scope.data.cusDro;
        var adr = $scope.data.adrDt;
        do_vdt();
        if (!$scope.updMsg.isOk)
            return;
        var data = {cusDro: cus, adrDt: adr, lang: $scope.sess.lang};
        $http.post("upd.php", data).success(function (updMsg) {
            if (updMsg.isOk) {
                $scope.appCus.mode = "dsp";
                return
            }
            $scope.updMsg = updMsg;
        })
    }

    function do_vdt() {
        var o = _vdt_dupAdrCd($scope.data.adrDt);
        var isEr = o[0];
        var adrDt = o[1];
        $scope.updMsg = {};
        if (isEr) {
            $scope.updMsg.erMsg = {adrDt: adrDt};
            return;
        }
        $scope.updMsg.isOk = true;

    }

    function _vdt_dupAdrCd(adrDt) {
        var o = [];
        var idx = 0;
        var adrCdAy = [];
        adrDt.forEach(function (adrDr) {
            adrCdAy.push(adrDr.adrCd);
        });
        var isEr = false;
        adrDt.forEach(function (adrDro) {
            var adrCd = adrDro.adrCd;
            var msg = _vdt_dupAdrCd_dupMsg(adrCd, adrCdAy, idx++)
            if (msg) {
                isEr = true;
            }
            o.push({adrCd: msg});
            ;
        })
        return [isEr, o];
    }

    function _vdt_dupAdrCd_dupMsg(adrCd, adrCdAy, idx) {
        if (adrCd === '' || adrCd === undefined)
            return null;
        for (var j = 0; j < adrCdAy.length; j++) {
            if (idx !== j) {
                if (adrCdAy[j] === adrCd)
                    return $scope.lbl.msg.dup.replace("$rno", j + 1);
                ;
            }
        }
        return null;
    }

    function do_can() {
        $scope.appCus.mode = "dsp";
    }
}]);
