/**
 * Created by USER on 21/6/2015.
 */
(function (angular) {
    angular.module('app').factory('$app', ['$http', a]);
    var $http;
    var buf = {};

    function a(http) {
        $http = http;
        var $app = {
            getLbl: getLbl,
            selCol: selCol,
            getShwBtn: getShwBtn,
            selCol1: selCol1
        };
        return $app;
    }

    function getLbl(pgmNm, secNm, lang, $scope) {
        if ((lang === 'undefined') || (lang === null)) {
            lang = 'en';
        }
        var key = pgmNm + ':' + secNm + ':' + lang;
        if (buf[key] !== undefined) {
            $scope.lbl = buf[key];
            return;
        }
        var a = '?pgmNm=' + pgmNm;
        var b = '&secNm=' + secNm;
        var c = '&lang=' + lang;
        $http.get("../phpResp/lbl.php" + a + b + c).success(function (data, status) {
            buf[key] = data;
            $scope.lbl = buf[key];
        });
    }

    function sort_data(data) {
        if (data.length === 0) return [];
        for (var k in data[0].regDr) {
            break;
        }
        return data.sort(function (a, b) {
            var aa = a.regDr[k]
            var bb = b.regDr[k]
            if (aa === "" && bb !== "") return 1;
            if (aa !== "" && bb === "") return -1;
            if (aa < bb) return -1;
            if (aa > bb) return 1;
            return 0;
        })
    }

    function selCol1(dta, colNmLvs) {
        var o = [];
        if (dta === undefined) return [];
        if (dta.length === 0) return [];
        var colAy = colNmLvs.split(' ');
        dta.forEach(function (dr) {
            var m = {};
            colAy.forEach(function (colNm) {
                m[colNm] = dr[colNm];
            })
            o.push(m);
        });
        return o;
    }

    function selCol(src_data, btn_selected, btn0Nm, btn1Nm, btn2Nm, btn3Nm) {
        // return data good to show on left by following:
        //        <tr ng-repeat="rec in tar.data" ng-class="($index==selectedIdx) ? 'selected' : ''" ng-click="do_sel_row()">
        //            <td class="{{(rec.isDea==='1') ? 'dim' : ''}}" ng-repeat="td in rec.regDr">{{td}}
        // the dta = [ {isDea, regDr} ]
        // regDr = regCd, inpCd, chiNm, engNm
        // src_data: is from PHP: @see http://localhost/loadPlan/pgm/region/list.php
        //              = [ { regCd inpCd chiNm engNm isDea } ]
        var idx = {cod: 'regCd', inp: 'inpCd', chi: 'chiNm', eng: 'engNm'};
        var a = btn_selected;
        var i0 = idx[btn0Nm];
        var i1 = idx[btn1Nm];
        var i2 = idx[btn2Nm];
        var i3 = idx[btn3Nm];
        var o = src_data.reduce(reduce, []);
        return sort_data(o);

        function reduce(o, rec) {
            var dr = {};
            if (a[btn0Nm]) dr[i0] = rec.dr[i0];
            if (a[btn1Nm]) dr[i1] = rec.dr[i1];
            if (a[btn2Nm]) dr[i2] = rec.dr[i2];
            if (a[btn3Nm]) dr[i3] = rec.dr[i3];
            var m = {isDea: rec.dr.isDea, regDr: dr, regCd: rec.dr.regCd};
            o.push(m);
            return o;
        }
    }

    function getShwBtn(isDea, isRef, auth) {
        var shw = angular.copy(auth);
        if (isDea === undefined) {
            shw.dlt = false;
            shw.dea = false;
            shw.upd = false;
            return;
        }
        if (isDea) {
            if (auth.rea) {
                shw.rea = true;
            }
            shw.dlt = false;
            shw.dea = false;
            shw.upd = false;
        } else {
            shw.rea = false;
            if (isRef) {
                shw.dlt = false;
            } else {
                shw.dea = false;
            }
        }
        return shw;
    }
})(angular);