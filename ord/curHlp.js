/**
 * Created by USER on 2015-07-24.
 */
angular.module('app').factory('curHlp', ['$dta', function ($dta) {
    return {filter_and_sort: filter_and_sort};

    function filter_and_sort(dta, filter, sortFldNmLvs, sortDes) {
        var d = new $dta.Dta(dta);
        var d1 = d.filter(filter);
        var i = 0;
        var sortFldNmAy = sortFldNmLvs.split(' ');
        var x = function(i) {
            return (i===undefined)||(i===null) ? '' : i;
        }
        var x1 = function(dr) {
            var o = [];
            sortFldNmAy.forEach(function(fldNm) {
                o.push(x(dr[fldNm]));
            })
            return o.join("::");
        }
        var dta1 = d1.sort(function (dr1, dr2) {
            var aa = x1(dr1);
            var bb = x1(dr2);
            console.log(i++,aa,bb);
            if (sortDes) {
                if (aa < bb) return -1;
                if (aa > bb) return 1;
                return 0;
            } else {
                if (aa < bb) return 1;
                if (aa > bb) return -1;
                return 0;
            }
        });
        return dta1;
    }
}])