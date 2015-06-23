(function (angular) {
    'use strict';
    angular.module('app').controller('upd', ['$scope', '$http', '$app', '$obj', '$rootScope', a]);
    function a($scope, $http, $app, $obj, $rootScope) {
        $scope.$watch('lang', function (lang) {
            $app.getLbl("region", "upd", lang, $scope);
        });
        $scope.do_can = do_can;
        $scope.do_sav = do_sav;
        $scope.do_dlt_nearBy = do_dlt_nearBy;
        $scope.do_add_nearBy = do_add_nearBy;
        $scope.do_nearBy_changed = do_nearBy_changed;
        $scope.do_reset = do_reset;
        $http.get('../phpResp/tblDc.php?t=majReg&f=majRegCd').success(function (data) {
            $scope.majRegCdDc = data;
        });
        $http.get('../phpResp/tblDc.php?t=region&f=regCd').success(function (data) {
            for (var j = 0; j < data.length; j++) {
                if (data[j] === $scope.regCd) {
                    data.splice(j, 1);
                    break;
                }
            }
            $scope.regCdDc = data;
        })

        $scope.$watch('regCd', function (regCd) {
            console.log(regCd);
            $http.get('dsp.php?regCd=' + regCd).success(function (data) {
                console.log(data);
                $scope.dataOrg = angular.copy(data);
                $scope.data = data;
            });
        })
        return;

        function do_nearBy_changed() {
            var regCd = this.i.nearBy;
            var regDr = this.i;
            $http.get("../phpResp/tblDr.php?t=region&f=regCd&v=" + regCd).success(function (data) {
                regDr.chiNm = data.chiNm;
                regDr.engNm = data.engNm;
                regDr.inpCd = data.inpCd;
                regDr.majRegCd = data.majRegCd;
            })
        }

        function do_reset() {
            $scope.data = angular.copy($scope.dataOrg);
        }

        function do_dlt_nearBy() {
            var idx = this.$index;
            var nearByAy = $scope.data.nearBy;
            nearByAy.splice(idx, 1);
        }

        function do_add_nearBy() {
            var m = {
                chiNm: '',
                engNm: '',
                inpCd: '',
                isDea: '0',
                majRegCd: '',
                nearBy: ''
            };
            $scope.data.nearBy.push(m);
        }

        function do_sav() {
            var dataVdt = _vdt();
            $scope.dataVdt = dataVdt;
            if (dataVdt.isEr)
                return;

            if (_noChg()) {
                $rootScope.mode = "dsp";
                return;
            }

            var nearByAy = [];
            for (var i in nearBy) {
                nearByAy.push(nearBy[i].nearBy);
            }
            var data = {regDro: $scope.data.regDro, nearByAy: nearByAy};
            $http.post("upd.php", data).success(function (data) {
                var regCd = $scope.data.region.regCd;
                $rootScope.mode = "dsp";
            })

            function _noChg() {
                var o1, o2;
                var o1 = $scope.data;
                var o2 = $scope.dataOrg;
                console.log("noChg is set.....");
                $rootScope.noChg = $obj.isEq(o1, o2);
                return $scope.noChg;
            }

            function _vdt() {
                var vdt = {isEr: true};
                var a = $scope.data.regDro.majRegCd;
                if (a === '' || a===null) {
                    vdt.regDro = {};
                    vdt.regDro.majRegCd = $scope.lbl.fldMsg.majRegCd.cannotBlank;
                }
                return vdt;
            }
        }

        function do_can() {
            $rootScope.mode = "dsp";
        }
    }
})
(angular);