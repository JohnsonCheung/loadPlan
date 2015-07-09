/**
 * touch point:
 *      oup: $scope.appRegion.cusCd
 *      inp: $scope.sess.lang
 */
angular.module('app').controller('future', ['$scope', '$http', '$app', '$obj', '$ay', '$dta', function ($scope, $http, $app, $obj, $ay, $dta) {
    var sortFldNm = "ordNoFmt";
    var sortDes = false; // sorting is in descending
    $scope.do_dspOrd = do_dspOrd;
    $scope.$watch("sess.lang", function (lang) {
        $app.getLbl("ord", "future", lang, $scope);
    })
    $scope.$watch('rno', function (rno) {
        if ($scope.tar === undefined)return;
        if (!angular.isArray($scope.tar.data))return;
        var i = rno - 1;
        if (0 <= i && i < $scope.tar.data.length) {
            var rec = $scope.tar.data[rno - 1];
            if (rec === undefined)
                return;
            $scope.appOrd.ord = rec.ord;
        }
    })
    $scope.$watch('filter', bld_and_set_tar_data);
    $scope.do_sel_row = do_sel_row;
    $scope.do_sort = do_sort;
    $http.get("future.php").success(success);

    function do_dspOrd(ord) {
        $scope.appOrd.mode="dsp";
        $scope.appOrd.ord = ord;
    }

    function do_sort(fldNm) {
        if (fldNm === sortFldNm) {
            sortDes = !sortDes;
        } else {
            sortFldNm = fldNm;
            sortDes = true;
        }
        console.log(sortFldNm, sortDes);
        bld_and_set_tar_data();
    }

    function success(data) {
        $scope.src = {data: data};
        $scope.tar = $scope.tar || {};
        bld_and_set_tar_data();
        $scope.rno = $scope.appOrd.ord
            ? _rno()
            : 1;
    }

    function _rno() {
        if (!$scope.tar)return;
        var cusCd = $scope.appOrd.ord;
        var tarDta = $scope.tar.data;
        var i = _.findIndex(tarDta, function (dr) {
            return ord === dr.ord
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
        dta = bld_and_set_tar_data__filter(dta, filter);
        dta = bld_and_set_tar_data__sort(dta, sortFldNm, sortDes);
        $scope.tar = $scope.tar || {};
        $scope.tar.data = dta;
    }

    function bld_and_set_tar_data__filter(dta, filter) {
        var d = new $dta.Dta(dta);
        return d.filter(filter);
    }

    function bld_and_set_tar_data__sort(dta, sortFldNm, sortDes) {
        var dta1 = dta.sort(function (dr1, dr2) {
            var aa = dr1[sortFldNm];
            var bb = dr2[sortFldNm];
            if (false) {
                if (aa === null || aa === undefined || aa === '') {
                    if (bb === null || bb === undefined || bb === '') return 0;
                    return -1;
                } else if (bb === null || bb === undefined || bb === '') {
                    return -1;
                }
            }
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

    function do_sel_row() {
        $scope.rno = this.$index + 1;
    }
}]);