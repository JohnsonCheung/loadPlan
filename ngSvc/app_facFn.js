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
            selCol1: selCol1,
            Dta: Dta,
            toAy: toAy
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

    function toAy(str_or_strAy) {
        var i = str_or_strAy;
        var o;
        switch (typeof i) {
            case 'undefined':
            case 'string':
                o = [i.trim()];
                break;
            case 'object':
                if (i instanceof Array) o = i;
                break;
            default:
                throw new Error("{str_or_strAy must be string or array");
        }
        return o;
    }

    /** a class to handle {dta}
     *
     * @param dta {array}  [{f1 f2 ..}], where f is field.
     * @constructor
     */
    function Dta(dta) {
        this.dta = dta;
    }

    /** return {@dspDta} from {this.dta} by selecting columns-{selColNmLvs} and hightlighting-{highlightColNmLvs}
     * Note: {this.dta} is [{f1 f2, ..}]
     * Note: {@dspDta} is [{h1 h2 dr{f1 f2 ..}}]
     * @param selColNmLvs
     * @param highlightColNmLvs
     */
    Dta.prototype.selCol = function (selColNmLvs, highlightColNmLvs) {
        var o = [];
        var fAy = selColNmLvs.trim().split(/\s+/);
        var hAy = highlightColNmLvs.trim().split(/\s+/);
        this.dta.forEach(function (dr) {
            fAy.forEach(function (f) {
                var dr = {};
                dr[f] = dr[f];
            });
            var m = {};
            fAy.forEach(function (h) {
                m[h] = dr[h];
            });
            m.dr = dr;
            o.push(m);
        })
        return o;
    }
    /**
     * Return a new tar_data which is same stru as src_data,  but it is sorted according to col-{btn0Cd} and the col in {dr} is selected
     * according to {btn_selected}
     * @param src_data {array} [{isDea pkVal dr{chiNm engNm inpCd isDea regCd}}]
     * @param btn_selected {object} {cod: true, inp: true, eng: true: chi: true}
     * @param btn0Cd {string} one of these 4: cod inp eng chi
     * @param btn1Cd {string} one of these 4: cod inp eng chi
     * @param btn2Cd {string} one of these 4: cod inp eng chi
     * @param btn3Cd {string} one of these 4: cod inp eng chi
     * @returns {array} same stru as src_data, but sorted and selected.
     * @going-to-phrase-out
     */
    function selCol(src_data, btn_selected, btn0Cd, btn1Cd, btn2Cd, btn3Cd) {
        // return data good to show on left by following:
        //        <tr>
        //            <th ng-repeat="th in tar.th">
        //        <tr ng-repeat="rec in tar.data" ng-class="($index==selectedIdx) ? 'selected' : ''" ng-click="do_sel_row()">
        //            <td class="{{(rec.isDea==='1') ? 'dim' : ''}}" ng-repeat="td in rec.regDr">{{td}}
        // the dta = [ {isDea, regDr} ]
        // regDr = regCd, inpCd, chiNm, engNm
        // src_data: is from PHP: @see http://localhost/loadPlan/pgm/region/list.php
        //              = [ { regCd inpCd chiNm engNm isDea } ]
        var idx = {cod: 'regCd', inp: 'inpCd', chi: 'chiNm', eng: 'engNm'};
        var a = btn_selected;
        var i0 = idx[btn0Cd];
        var i1 = idx[btn1Cd];
        var i2 = idx[btn2Cd];
        var i3 = idx[btn3Cd];
        var o = src_data.reduce(reduce, []);
        debugger;
        return sort_data(o);

        function reduce(o, rec) {
            var dr = {};
            if (a[btn0Cd]) dr[i0] = rec.dr[i0];
            if (a[btn1Cd]) dr[i1] = rec.dr[i1];
            if (a[btn2Cd]) dr[i2] = rec.dr[i2];
            if (a[btn3Cd]) dr[i3] = rec.dr[i3];
            var m = {isDea: rec.dr.isDea, regDr: dr, regCd: rec.dr.regCd};
            o.push(m);
            return o;
        }
    }

    /**
     * Return a new tar_data which is same stru as src_data,  but it is sorted according to col-{btn0Cd} and the col in {dr} is selected
     * according to {btn_selected}
     * @param src_data {array} [{isDea regCd dr{chiNm engNm inpCd isDea regCd}}]
     * @param btn_selected {object} {cod: true, inp: true, eng: true: chi: true}
     * @param btn0Cd {string} one of these 4: cod inp eng chi
     * @param btn1Cd {string} one of these 4: cod inp eng chi
     * @param btn2Cd {string} one of these 4: cod inp eng chi
     * @param btn3Cd {string} one of these 4: cod inp eng chi
     * @returns {array} same stru as src_data, but sorted and selected.
     * @going-to-phrase-out
     */
    function selCol(src_data, btn_selected, btn0Cd, btn1Cd, btn2Cd, btn3Cd) {
        // return data good to show on left by following:
        //        <tr>
        //            <th ng-repeat="th in tar.th">
        //        <tr ng-repeat="rec in tar.data" ng-class="($index==selectedIdx) ? 'selected' : ''" ng-click="do_sel_row()">
        //            <td class="{{(rec.isDea==='1') ? 'dim' : ''}}" ng-repeat="td in rec.regDr">{{td}}
        // the dta = [ {isDea, regDr} ]
        // regDr = regCd, inpCd, chiNm, engNm
        // src_data: is from PHP: @see http://localhost/loadPlan/pgm/region/list.php
        //              = [ { regCd inpCd chiNm engNm isDea } ]
        var idx = {cod: 'regCd', inp: 'inpCd', chi: 'chiNm', eng: 'engNm'};
        var a = btn_selected;
        var i0 = idx[btn0Cd];
        var i1 = idx[btn1Cd];
        var i2 = idx[btn2Cd];
        var i3 = idx[btn3Cd];
        var o = src_data.reduce(reduce, []);
        debugger;
        return sort_data(o);

        function reduce(o, rec) {
            var dr = {};
            if (a[btn0Cd]) dr[i0] = rec.dr[i0];
            if (a[btn1Cd]) dr[i1] = rec.dr[i1];
            if (a[btn2Cd]) dr[i2] = rec.dr[i2];
            if (a[btn3Cd]) dr[i3] = rec.dr[i3];
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
})
(angular);