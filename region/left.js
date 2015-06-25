(function (angular, _) {
    'use strict';
    angular.module('app').controller('regLeft', [
        '$scope',
        '$http',
        '$app',
        '$obj',
        '$ay',
        '$rootScope',
        a]);

    function a($scope, $http, $app, $obj, $ay, $rootScope) {
        $scope.$watch("lang", function (lang) {
            $app.getLbl("region", "left", lang, $scope);
        })
        $scope.$watch('rno', function (rno) {
            var i = rno - 1;
            if($scope.tar===undefined) return;
            if (0 <= i && i < $scope.tar.data.length) {
                rootRegCd();
            }
        })

        $scope.do_sel_row = do_sel_row;
        $scope.do_tgl_btn = do_tgl_btn;
        $scope.$watch('filter', watch_filter);

        $scope.btn0Nm = "cod";
        $scope.btn1Nm = "inp";
        $scope.btn2Nm = "chi";
        $scope.btn3Nm = "eng";
        $scope.btn_selected = $obj.getOptObj("inp cod chi eng");
        $scope.rno = 1;

        $http.get("left.php").success(success);

        angular.jj = angular.jj || {};
        angular.jj.ondrop = ondrop;
        angular.jj.ondragstart = ondragstart;
        angular.jj.ondragover = ondragover;
        return;

        function success(data, status, headers, config) {
            var srcDta = a(data);

            var src = {};
            src.data = srcDta;

            $scope.src = src;
            $scope.tar = {};

            var b0 = "cod";
            var b1 = "inp";
            var b2 = "chi";
            var b3 = "eng";
            var btn_selected = {cod: true, inp: true, chi: true, eng: true};
            data = $app.selCol(src.data, btn_selected, b0, b1, b2, b3);
            $scope.tar.data = data;

            //-- set $rootScope.regCd
            if ($rootScope.regCd === undefined) {
                $rootScope.regCd = data[0].regCd;
            }
            $scope.rno = b();
            $rootScope.regCd = c();

            function a(data) {
                'use strict';
                function reduce(o, dr) {
                    var isDea = dr.isDea
                    o.push({dr: dr, isDea: isDea, regCd: dr.regCd})
                    return o;
                }
                return data.reduce(reduce, []);
            }

            function b() {
                var tarDta = $scope.tar.data;
                var regCd = $rootScope.regCd;
                for (var i = 0; i < tarDta.length; i++) {
                    if (tarDta[i].regCd === regCd) return i+1;
                }
                return 1;
            }

            function c() {
                //debugger;
                var i = $scope.rno-1;
                var t = $scope.tar.data;
                return t[i].regCd;
            }
        }

        function watch_filter(filter) {
            $scope.tar = $scope.tar || {};
            var data;

            if (filter === '' || filter === undefined) {
                    if($scope.src===undefined) return;
                    
                data = bld_data($scope.src.data);
                $scope.tar.data = data;
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
            if ($scope.rno > $scope.tar.data.length) {
                $scope.rno = 1;
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

        function rootRegCd() {
            var rec = $scope.tar.data[$scope.rno-1];
            if (rec === undefined)
                return;
            $rootScope.regCd = rec.regCd;
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