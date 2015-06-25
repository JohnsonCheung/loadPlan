/**
 * Created by USER on 20/6/2015.
 */
(function (angular) {
    angular.module('app').factory('$ay', a);
    function a() {
        var $ay = {
            swapEle: swapEle,
            minus: minus
        };
        return $ay;
    }

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
})(angular);