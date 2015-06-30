/**
 * touch point:
 *      inp: $scope.appRegion.regCd
 *           $scope.sess.lang
 *      oup: $scope.appRegion.mode = "dsp"  [when cancel or update]
 *           $scope.appRegion.mode = "dsp"  [when cancel or update]
 *      oup-ref:
 *           $scope.appRegion.noChg         [it is set @ vdt]
 *           $scope.appRegion.updMsg        [it is set @ vdt(byJS) or sav(byPHP)].  It has structure = {isOk erMsg okMsg}
 */
angular.module('app').controller('upd', ['$scope', '$http', '$app', '$obj', function a($scope, $http, $app, $obj) {
    $scope.$watch('sess.lang', watch_lang);
    $scope.$watch('appRegion.regCd', watch_regCd);
    $scope.do_shwSelReg = do_shwSelReg;
    $scope.do_can = do_can;
    $scope.do_sav = do_sav;
    $scope.do_dlt_nearBy = do_dlt_nearBy;
    $scope.do_add_nearBy = do_add_nearBy;
    $scope.do_nearBy_changed = do_nearBy_changed;
    $scope.do_reset = do_reset;
    $http.get('../phpResp/tblDc.php?t=majReg&f=majRegCd').success(function (data) {
        $scope.majRegCdDc = data;
    });
    $http.get('../phpResp/tblDc.php?t=region&f=regCd').success(function (data) {
        for (var j = 0; j < data.length; j++) {
            if (data[j] === $scope.regCd) {
                data.splice(j, 1);
                break;
            }
        }
        $scope.regCdDc = data;
    })

    return;
    function watch_lang(lang) {
        $app.getLbl("region", "upd", lang, $scope);
    };

    function watch_regCd(regCd) {
        $http.get('dsp.php?regCd=' + regCd).success(function (data) {
            $scope.dataOrg = angular.copy(data);
            $scope.data = data;
        });
    }

    function do_shwSelReg(ev, index) {
        //$broadcast('shwSelReg', $event, $scope.data.nearByDt[$index],'nearBy'
        $scope.$broadcast('shwSelReg', ev, $scope.data.nearByDt[index], 'nearBy', do_nearBy_changed);
    }

    function do_nearBy_changed(tarObj, tarPrpNm) {
        var regCd = tarObj.nearBy;
        $http.get("../phpResp/tblDr.php?t=region&f=regCd&v=" + regCd).success(function (data) {
            tarObj.chiNm = data.chiNm;
            tarObj.engNm = data.engNm;
            tarObj.inpCd = data.inpCd;
            tarObj.majRegCd = data.majRegCd;
        })
    }

    function do_reset() {
        $scope.data = angular.copy($scope.dataOrg);
    }

    function do_dlt_nearBy() {
        var idx = this.$index;
        var dt = $scope.data.nearByDt;
        dt.splice(idx, 1);
    }

    function do_add_nearBy() {
        var m = {
            chiNm: '',
            engNm: '',
            inpCd: '',
            isDea: '0',
            majRegCd: '',
            nearBy: ''
        };
        $scope.data.nearByDt.push(m);
    }

    function do_sav() {
        var dt = $scope.data.nearByDt;
        _rmvBlankNearBy(dt);
        _rmvDupNearBy(dt);
        //$scope.updMsg = _vdt();
        //if (!$scope.updMsg.isOk) return;

        if (_noChg())   return $scope.appRegion.mode = "dsp";
        var nearByAy = [];
        $scope.data.nearByDt.forEach(function (dr) {
            nearByAy.push(dr.nearBy);
        });

        var data = {regDro: $scope.data.regDro, nearByAy: nearByAy, lang: $scope.sess.lang};
        $http.post("upd.php", data).success(function (updMsg) { // a PHP update always return: retMsg = {isEr erMsg okMsg}
                debugger;
                $scope.appRegion.updMsg = updMsg;
                if (updMsg.isOk) $scope.appRegion.mode = "dsp";
            }
        );

        function _rmvBlankNearBy(nearByDt) {
            var dt = nearByDt;
            for (var j = dt.length - 1; j >= 0; j--) {
                if (dt[j].nearBy === '')
                    dt.splice(j, 1);
            }
        }

        function _rmvDupNearBy(nearByDt) {
            var dt = nearByDt;
            for (var j = dt.length - 1; j > 0; j--) {
                var nearBy = dt[j].nearBy;
                var isDup = _isDup(nearBy, j - 1)
                if (isDup)
                    dt.splice(j, 1);
            }
            function _isDup(nearBy, i) {
                for (var j = 0; j <= i; j++) {
                    var inearBy = dt[j].nearBy;
                    if (inearBy === nearBy) return true;
                }
                return false;
            }
        }

        function _noChg() {
            var o1, o2;
            var o1 = $scope.data;
            var o2 = $scope.dataOrg;
            console.log("noChg is set.....");
            $scope.appRegion.noChg = $obj.isEq(o1, o2);
            return $scope.appRegion.noChg;
        }

        function _vdt() {
            // is it a standard to build the validation message
            // $scope.data.xxxMsg        , where xxx is the action name.  Example (add upd dlt)
            // xxxMsg has struct {isOk erMsg okMsg}  isOk is boolean, optional
            //                                       erMsg is [secNm][fldNm]->msg, optional
            //                                             if secNm is a dt (eg region: data.nearByDt), then,
            //                                                  erMsg is [secNm][idx][fldNm]
            //                                       okMsg is dic_ofFldNm2Msg, optional
            return;
            var a = _vdt_regDro($scope.data.regDro);
            var erMsg = {}
            if (a) erMsg.regDro = a;
            return Object.keys(erMsg).length ? {erMsg: erMsg} : {isOk: true};
        }

        function _vdt_regDro(regDro) {
            var x = regDro;
            var o = {};
            if (a) o.majRegCd = a;
            if (b) o.inpCd = b;
            if (a || b) return o;
        }
    }

    function do_can() {
        $scope.appRegion.mode = "dsp"
    }
}])
;

