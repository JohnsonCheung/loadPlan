(function (angular, _) {
    'use strict';
    angular.module('app').controller('regLeft', [
        '$scope',
        '$http',
        '$app',
        '$obj',
        '$ay',
        '$dta',
        '$rootScope',
        a]);

    function a($scope, $http, $app, $obj, $ay, $dta, $rootScope) {
        $scope.$watch("lang", function (lang) {
            $app.getLbl("region", "left", lang, $scope);
        })
        $scope.$watch('rno', function (rno) {
            var i = rno - 1;
            if ($scope.tar === undefined) return;
            if (0 <= i && i < $scope.tar.data.length) {
                set_rootRegCd_byRno();
            }
        })

        $scope.do_sel_row = do_sel_row;
        $scope.do_tgl_btn = do_tgl_btn;
        $scope.$watch('filter', bld_and_set_tar_data);

        $scope.btn0Nm = "cod";
        $scope.btn1Nm = "inp";
        $scope.btn2Nm = "chi";
        $scope.btn3Nm = "eng";
        $scope.btn_selected = $obj.getOptObj("inp cod chi eng");
        $scope.rno = 1;
        $scope.$watch('tar.data.length', function (len) {
            $scope.rno = len === 0 ? "" : 1;
        });

        $http.get("left.php").success(success);

        angular.jj = angular.jj || {};
        angular.jj.ondrop = ondrop;
        angular.jj.ondragstart = ondragstart;
        angular.jj.ondragover = ondragover;
        return;

        function success(data, status, headers, config) {
            $scope.src = {data: data};
            $scope.tar = {};

            var b0 = "cod";
            var b1 = "inp";
            var b2 = "chi";
            var b3 = "eng";
            $scope.btn_selected = {cod: true, inp: true, chi: true, eng: true};
            bld_and_set_tar_data();

            //-- set $rootScope.regCd
            if ($rootScope.regCd === undefined) {
                $rootScope.regCd = data[0].regCd;
            }
            $scope.rno = a($rootScope.regCd);
            $rootScope.regCd = $scope.tar.data[$scope.rno - 1];

            function a(regCd) {
                var tarDta = $scope.tar.data;
                var i = _.tarDta.findIndex(tarDta, function (dr) {
                    return regCd === dr.regCd
                })
                return i == -1 ? "" : i - 1
            }
        }

        function set_rootRegCd_byRno() {
            var rec = $scope.tar.data[$scope.rno - 1];
            if (rec === undefined)
                return;
            $rootScope.regCd = rec.regCd;
        }

        /**
         * The change of $scope.tar.data are trigger @ 3 points: #1 filter, #2 toggle-btn, #3 swap-button
         * They should all call this function to set $scope.tar.data.
         * To build $scope.tar.data, these are the input:
         *      filter
         *      btn_selected        : {inp cod eng chi} where each prp in the obj-btn_selected is boolean
         *      btn1Nm, 2, 3, 4
         * Class Dta should be used to build the tar.data
         */
        function bld_and_set_tar_data() {
            if ($scope.src === undefined)return;
            if($scope.src.data===undefined) return;
            var filter = $scope.filter;
            var ibs = $scope.btn_selected;
            var ib0 = $scope.btn0Nm;
            var ib1 = $scope.btn1Nm;
            var ib2 = $scope.btn2Nm;
            var ib3 = $scope.btn3Nm;
            var i = $scope.src.data;
            //---
            var a = [];
            if (ibs[ib0]) a.push(ib0);
            if (ibs[ib1]) a.push(ib1);
            if (ibs[ib2]) a.push(ib2);
            if (ibs[ib3]) a.push(ib3);
            var b = [];
            a.forEach(function (i) {
                var m;
                switch (i) {
                    case 'cod':
                        m = "regCd";
                        break;
                    case 'inp':
                        m = "inpCd";
                        break;
                    case 'chi':
                        m = "chiNm";
                        break
                    case 'eng':
                        m = "engNm";
                        break;
                    default:
                        throw new Error('unexpect btnNm');
                }
                b.push(m);
            })
            var selColNmLvs = b.join(' ');
            //----
            var d = new $dta.Dta(i);
            var o = d.filter_and_selCol(filter, selColNmLvs, " isDea regCd")
            $scope.tar = {};
            $scope.tar.data = o;
        }

        function do_bld_data() {
            var src_data = $scope.src.data;
            var filter = $scope.filter;
            var btn_selected = $scope.btn_selected;
            var b0 = $scope.btn0Nm;
            var b1 = $scope.btn1Nm;
            var b2 = $scope.btn2Nm;
            var b3 = $scope.btn3Nm;
            $scope.tar.data = $app.selCol(src_data, btn_selected, b0, b1, b2, b3)
        }

        function do_sel_row() {
            $scope.rno = this.$index + 1;
        }

        function do_tgl_btn(btnNm) {
            debugger;
            var a = $scope.btn_selected;
            var s0 = a.chi;	// s for switch
            var s1 = a.eng;
            var s2 = a.cod;
            var s3 = a.inp;
            switch (btnNm) {
                case 'chi':
                    if (s0 && !s1 && !s2 && !s3) return;
                    break
                case 'eng':
                    if (!s0 && s1 && !s2 && !s3) return;
                    break
                case 'cod':
                    if (!s0 && !s1 && s2 && !s3) return;
                    break
                case 'inp':
                    if (!s0 && !s1 && !s2 && s3) return;
                    break
            }
            $scope.btn_selected[btnNm] = !$scope.btn_selected[btnNm];
            do_bld_data();
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
            var new_ay = $ay.swapEle(ay, src, tar);
            b0 = new_ay[0];
            b1 = new_ay[1];
            b2 = new_ay[2];
            b3 = new_ay[3];
            $scope.btn0Nm = b0;
            $scope.btn1Nm = b1;
            $scope.btn2Nm = b2;
            $scope.btn3Nm = b3;

            var srcDta = $scope.src.data;
            var btn_selected = $scope.btn_selected;
            var tarDta = selCol(srcDta, btn_selected, b0, b1, b2, b3);
            $scope.tar.data = tarDta;
            $scope.rno = 1;
        }

        function ondragover(ev) {
            ev.preventDefault();
        }

        function ondragstart(ev) {
            var name = ev.target.name;
            ev.dataTransfer.setData("text", name) // id = btn_chi btn_eng btn_code btn_inp
        }
    }
})(angular, _);