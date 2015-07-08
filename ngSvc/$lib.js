/**
 * Created by USER on 25/6/2015.
 */
//************//
//*** $app ***//
//************//
angular.module('app').factory('$app', ['$http', '$document', function $app($http, $doc) {
    var buf = {};
    return {
        getLbl: getLbl,
        selCol: selCol,
        getShwBtn: getShwBtn,
        selCol1: selCol1,
        toAy: toAy,
        go_id: go_id
    };
    function go_id(id) {
        $doc[0].location.href = '#' + id;
    }

    function getLbl(pgmNm, secNm, lang, $scope, cb) {
        if ((lang === undefined) || (lang === null)) {
            lang = 'en';
        }
        var key = pgmNm + ':' + secNm + ':' + lang;
        if (buf[key] !== undefined) {
            $scope.lbl = buf[key];
            if (typeof cb === 'function') cb();
            return;
        }
        var a = '?pgmNm=' + pgmNm;
        var b = '&secNm=' + secNm;
        var c = '&lang=' + lang;
        $http.get("../phpResp/lbl.php" + a + b + c).success(function (data) {
            buf[key] = data;
            $scope.lbl = buf[key];
            if (typeof cb === 'function') cb();
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
        if (a === undefined) debugger;
        var i0 = idx[btn0Cd];
        var i1 = idx[btn1Cd];
        var i2 = idx[btn2Cd];
        var i3 = idx[btn3Cd];
        var o = src_data.reduce(reduce, []);
        return sort_data(o);

        function reduce(o, rec) {
            var dr = {};
            if (a[btn0Cd]) dr[i0] = rec.dr[i0];
            if (a[btn1Cd]) dr[i1] = rec.dr[i1];
            if (a[btn2Cd]) dr[i2] = rec.dr[i2];
            if (a[btn3Cd]) dr[i3] = rec.dr[i3];
            var m = {isDea: rec.dr.isDea, dr: dr, regCd: rec.dr.regCd};
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
}]);

//************//
//*** $obj ***//
//************//
angular.module('app').factory('$obj', function $obj() {
    return {
        setShwDeaReaDltPrp: setShwDeaReaDltPrp,
        cpyPrp: cpyPrp,
        getOptObj: getOptObj,
        isEq: isEq
    };

    function fndScope($rootScope, $id) {
        if ($rootScope.$id = $id) return $rootScope;
    }

    function isEq(o1, o2) {
        return _isEq(o1, o2, {lvl: 0});
        function _isEq(o1, o2, lvl) {
            if (lvl === 10) throw new Error('too deep');
            lvl++
            var t = typeof o1
            if (t !== typeof o2) return false;
            switch (t) {
                case "string":
                case "number" :
                case "boolean":
                case "undefined":
                    return o1 === o2;
                default:
            }
            if (o1 === null && o2 === null) return true;
            if (t !== 'object') throw new Error('o1 has unexpected type [' + t + ']');
            var k1 = Object.getOwnPropertyNames(o1);
            var k2 = Object.getOwnPropertyNames(o1);
            if (_diffKey(k1, k2)) return false;
            lvl.lvl++;
            var sam = k1.every(function (k) {
                return _isEq(o1[k], o2[k], lvl);
            })
            lvl.lvl--;
            return sam;
            function _diffKey(k1, k2) {
                if (k1.length !== k2.length) return false;
                var sam = k1.every(function (i) {
                    return k2.indexOf(i) !== -1;
                })
                return !sam;
            }
        }
    }

    /** set 3 prp to given obj which has prp-isDea, prp-isRef */
    function setShwDeaReaDltPrp(obj_with_isDea_isRef) {
        var a = obj_with_isDea_isRef;
        var isDea = a.isDea;
        var isRef = a.isRef;
        if (isRef === undefined) throw new Error('isRef prp is undefined in given obj');
        if (isDea === undefined) throw new Error('isDea prp is undefined in given obj');
        var o = b(isDea, isRef);
        return cpyPrp(o, 'shwDlt shwRea shwDea', a);

        function b(isDea, isRef) {
            if (isDea) return o(true, false, false);
            if (isRef) return o(false, true, false);
            return o(false, false, true);
            function o(rea, dea, dlt) {
                return {shwRea: rea, shwDea: dea, shwDlt: dlt}
            }
        }
    }

    /** copy those prpNmLvs in srcObj prp to a new tarObj */
    function cpyPrp(srcObj, prpNmLvs, tarObj) {
        var ay = prpNmLvs.split(' ');
        var o = tarObj;
        ay.forEach(function (i) {    // i is prpNm
            o[i] = srcObj[i];
        });
        return o;
    }

    /** return an optObj by optLvs */
    function getOptObj(optLvs) {
        var o = {};
        var ay = optLvs.split(' ');
        ay.forEach(function (i) {
            o[i] = true;
        })
        return o;
    }
});

//************//
//*** $str ***//
//************//
angular.module('app').factory('$str', function $str() {
    return {
        splitLvs: splitLvs
    };

    function splitLvs(lvs) {
        if (lvs === undefined) return [];
        if (lvs === null) return [];
        if (typeof lvs !== 'string') throw new Error('lvs must be string');
        return lvs.trim().split(/\s+/);
    }
});

//************//
//*** $ay ***//
//************//
angular.module('app').factory('$ay', function $ay() {

    return {
        swapEle: swapEle,
        minus: minus
    };

    function minus(ay1, ay2) {
        var o = [];
        for (var j = 0; j < ay1.length; j++) {
            var v = ay1[j];
            var exist = _exist(v);
            if (!exist)
                o.push(v);
        }
        return o;
        function _exist(v) {
            for (var j = 0; j < ay2.length; j++) {
                if (ay2[j] === v) return true;
            }
            return false;
        }
    }

    function swapEle(ay, srcIdx, tarIdx) {
        var j, i, k, u, v;
        var isMovToAft = true;
        for (j = 0; j < ay.length; j++) {
            u = ay[j];
            if (u === srcIdx)
                break;
            else if (u === tarIdx) {
                isMovToAft = false;
                break;
            }
        }
        var o = [];
        if (isMovToAft) {
            for (j = 0; j < ay.length; j++) {
                u = ay[j];
                if (u === srcIdx) {
                    for (i = j + 1; i < ay.length; i++) {
                        v = ay[i];
                        o.push(v)
                        if (v === tarIdx) {
                            o.push(u);
                            for (k = i + 1; k < ay.length; k++) {
                                o.push(ay[k]);
                            }
                            return o;
                        }
                    }
                    throw new Error('ay does not contain tarIdx')
                }
                o.push(u);
            }
            throw new Error('ay does not contain srcIdx');
        }
        for (j = 0; j < ay.length; j++) {
            u = ay[j];
            if (u === tarIdx) {
                o.push(srcIdx)
                for (i = j; i < ay.length; i++) {
                    v = ay[i];
                    if (v !== srcIdx) {
                        o.push(v)
                    }
                }
                return o;
            }
            o.push(u);
        }
        throw new Error('ay does not contain srcIdx');
    }
});

//************//
//*** $dta ***//
//************//
angular.module('app').factory('$dta', ['$str', function ($str) {
    /* class Dta */
    {
        /** a class to handle {dta}
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
            var dta = this.dta;
            dta.forEach(function (iDr) {
                var oDr = {};
                fAy.forEach(function (f) {
                    oDr[f] = iDr[f];
                });
                var m = {};
                hAy.forEach(function (h) {
                    m[h] = iDr[h];
                });
                m.dr = oDr;
                o.push(m);
            })
            return _sort(o);
            function _sort(d) { // d-stru = [{{h1 h2 .. dr{f1 f2 ..}}], it is required to sort by f1
                if (d.length === 0) return [];
                // let k be the key of first field of the first record of {d}
                for (var k in d[0].dr) {
                    break;
                }
                return d.sort(function (a, b) {
                    var aa = a.dr[k]
                    var bb = b.dr[k]
                    if (aa === "" && bb !== "") return 1;
                    if (aa !== "" && bb === "") return -1;
                    if (aa < bb) return -1;
                    if (aa > bb) return 1;
                    return 0;
                })

            }
        }

        Dta.prototype.filter = function (filter) {
            var fAy = $str.splitLvs(filter);
            if (fAy.length === 0) {
                var o = angular.copy(this.dta);
                return o;
            }
            function isSel(dr) { // according to fAy, if {dr} should be selected.
                function isSubStrInSomeFld(substr) {
                    function isContain(fld) {
                        fld = String(fld); // convert {fld} to string, just in case fld is not string
                        return fld.search(substr) !== -1;
                    }

                    return _.some(dr, isContain)
                }

                // is every fAy-element is a subStr in some fld of rec.dr
                return _.every(fAy, isSubStrInSomeFld);
            }

            return _.filter(this.dta, isSel);
        }
        /**
         *
         * @param filter {string} space separated token to select rows from {this.dta}
         */
        Dta.prototype.filter_and_selCol = function filter_and_selCol(filter, selColNmLvs, highlightColNmLvs) {
            var a = this.filter(filter);
            return (new Dta(a)).selCol(selColNmLvs, highlightColNmLvs);
        }
    }
    return {
        Dta: Dta
    };
}]);

angular.module('app').factory('$appRegion', function $appRegion() {
    // data to be shared between different section of pgm-region
    return {
        lang: '',
        regCd: '',
        regCdNxt: ''  // the region to be displaed if regCd is deleted.
    }
});

angular.module('app').factory('$sess', function $sess() {
    // data to be shared between different section of pgm-region
    return {
        lang: '',
        usrId: '',
        usrNm: ''
    }
});

angular.module('app').factory('$win', function $win() {
    return window;
})

angular.module('app').factory('$vdt', ['$http', function $vdt() {
}])