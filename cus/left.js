(function (angular, _) {
    'use strict';
    angular.module('app').controller('cusLeft', [
        '$scope',
        '$http',
        '$ay',
        '$app',
        '$obj',
        '$rootScope',
        a]);
    function a($scope, $http, $ay, $app, $obj, $rootScope) {
        $scope.$watch('lang', function (lang) {
            $app.getLbl("cus", "left", lang, $scope);
        })
        $scope.do_go_rno = do_go_rno;
        $scope.do_sel_row = do_sel_row;
        $scope.do_fmt_ofN_ofT = do_fmt_ofN_ofT;
        $scope.do_tgl_btn = do_tgl_btn;
        $scope.do_filter_changed = do_filter_changed;
        $scope.do_get_cusCd = do_get_cusCd;
        $scope.do_clear_filter = do_clear_filter;

        $scope.btn0Nm = "cod";
        $scope.btn1Nm = "inp";
        $scope.btn2Nm = "chi";
        $scope.btn3Nm = "eng";
        $scope.btn_selected = $obj.getOptObj("inp cod chi eng");
        $scope.selectedIdx = 0;

        var src = {};
        src.data = [];

        $scope.src = src;
        $scope.ofN = src.data.length;
        $scope.ofT = src.data.length;
        $http.get("left.php").success(success);
        $scope.$watch("rno", rno_changed);

        angular.jj = angular.jj || {};
        angular.jj.ondrop = ondrop;
        angular.jj.ondragstart = ondragstart;
        angular.jj.ondragover = ondragover;
        return;

        function rno_changed(new_rno, old_rno) {
            if ((new_rno === undefined) || (new_rno === ""))
                return;
            emit_rno(new_rno);
        }

        function success(data) {
            var srcDta = a(data)
            var src = {};
            src.data = srcDta;

            $scope.src = src;
            $scope.ofN = src.data.length;
            $scope.ofT = src.data.length;
            $scope.tar = {};

            var b0 = "cod";
            var b1 = "inp";
            var b2 = "chi";
            var b3 = "eng";
            var btn_selected = {cod: true, inp: true, chi: true, eng: true};
            debugger;
            var tarDta = $app.selCol(src.data, btn_selected, b0, b1, b2, b3);
            $scope.tar.data = tarDta;

            //-- set $rootScope.regCd
            if ($rootScope.regCd === undefined)
                $rootScope.regCd = data[0].regCd;
            $scope.selectedIdx = b();

            function a(data) {
                'use strict';
                function reduce(o, dr) {
                    var isDea = dr.isDea
                    o.push({dr: dr, isDea: isDea, cusCd: dr.cusCd})
                    return o;
                }
                return data.reduce(reduce, []);
            };

            function b() {
                var tarDta = $scope.tar.data;
                var cusCd = $rootScope.cusCd;
                for (var i = 0; i < tarDta.length; i++) {
                    if (tarDta[i].cusCd === cusCd) return i;
                }
                return 0;
            }
        }

        function do_get_cusCd() {
            //from $scope.selectedIdx
            //from $scope.data is [] of rec.  rec is obj {tr,isDea,cusCd}
            var selectedIdx = $scope.selectedIdx;
            var data = $scope.tar.data;
            if (!data) return null;
            var rec = data[selectedIdx];
            return (rec === undefined)
                ? null
                : rec.cusCd;
        }

        function do_clear_filter() {
            $scope.filter = '';
            do_filter_changed('');
        }

        function do_filter_changed(filter) {
            $scope.tar = $scope.tar || {};
            var data;

            if (filter === '' || filter === undefined) {
                data = bld_data($scope.src.data);
                $scope.tar.data = data;
                $scope.ofN = $scope.ofT;
                return;
            }

            var filter_substr_ay = (function (filter) {
                'use strict';
                function nospace(ay, i) {
                    if (i !== '')
                        ay.push(i)
                    return ay;
                }

                return filter.split(' ').reduce(nospace, []);
            })(filter)

            var data =
                (function (filter_substr_ay) {
                    'use strict';
                    function isSel(rec) {
                        function isSubStrInSomeFld(substr) {
                            function isContain(fld) {
                                if (fld === null) return false;
                                return fld.search(substr) !== -1;
                            }

                            return _.some(rec.dr, isContain)
                        }

                        var isSel = _.every(filter_substr_ay, isSubStrInSomeFld);
                        return isSel
                    }

                    var data = $scope.src.data;
                    return _.filter(data, isSel);
                })(filter_substr_ay)

            $scope.tar.data = bld_data(data);
            $scope.ofN = data.length;
            if ($scope.selectedIdx >= $scope.ofN) {
                $scope.selectedIdx = 0;
            }
            return;

            function bld_data(data) {
                var btn_selected = $scope.btn_selected;
                var btn0Nm = $scope.btn0Nm;
                var btn1Nm = $scope.btn1Nm;
                var btn2Nm = $scope.btn2Nm;
                var btn3Nm = $scope.btn3Nm;
                return $app.selCol(data, btn_selected, btn0Nm, btn1Nm, btn2Nm, btn3Nm);
            }
        }

        function do_fmt_ofN_ofT(ofN) {
            var ofT = $scope.ofT
            return (ofN === ofT)
                ? ' of ' + ofN
                : ' of ' + ofN + ' of ' + ofT;
        }

        function emit_rno(rno) {
            var rec = $scope.tar.data[rno - 1];
            if (rec === undefined)
                return;
            var cusCd = rec.cusCd;
            $scope.cusCd = cusCd;
            //console.log("emit_rno(" + cusCd + ")");
            $scope.$emit("cusCd_changed", cusCd);
        }

        function do_go_rno(rno) {
            if (!rno)
                return;
            $scope.selectedIdx = rno - 1;
            emit_rno(rno);
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
            $scope.selectedIdx = this.$index;
            $scope.rno = this.$index + 1;
        }

        function do_tgl_btn(btnNm) {
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
            var new_ay = swapAyEle(ay, src, tar);
            b0 = new_ay[0];
            b1 = new_ay[1];
            b2 = new_ay[2];
            b3 = new_ay[3];
            $scope.btn0Nm = b0;
            $scope.btn1Nm = b1;
            $scope.btn2Nm = b2;
            $scope.btn3Nm = b3;

            var src_data = $scope.src.data;
            var btn_selected = $scope.btn_selected;
            var new_data = $app.selCol(src_data, btn_selected, b0, b1, b2, b3);
            $scope.tar.data = new_data;
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