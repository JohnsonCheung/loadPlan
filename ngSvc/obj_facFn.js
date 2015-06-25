/**
 * Created by USER on 20/6/2015.
 */
(function $obj(angular) {
    angular.module('app').factory('$obj', a);
    function a() {
        //debugger;
        console.log('this should be running first.....');
        var $obj = {
            setShwDeaReaDltPrp: setShwDeaReaDltPrp,
            cpyPrp: cpyPrp,
            getOptObj: getOptObj,
            isEq: isEq
        };
        return $obj;
    };
    function fndScope($rootScope, $id) {
        if($rootScope.$id=$id) return $rootScope;
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
})
(angular);