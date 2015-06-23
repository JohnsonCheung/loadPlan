(function (angular) {
    'use strict';
    angular.module('app').controller('upd', ['$scope', '$http', '$obj', a]);
    function a($scope, $http, $obj) {
        $http.get('../phpResp/lbl.php?pgmNm=cus&&secNm=upd&lang=' + $scope.lang).success(function (lbl) {
            $scope.lbl = lbl;
        });
        $scope.do_can = do_can;
        $scope.do_sav = do_sav;
        $scope.do_vdt = do_vdt;
        $scope.do_dltAdr = do_dltAdr;
        $scope.do_deaAdr = do_deaAdr;
        $scope.do_reaAdr = do_reaAdr;
        $scope.do_addAdr = do_addAdr;
        $scope.do_reset = do_reset;
        $scope.view1 = true;
        $scope.view1Color = "lightblue";
        $scope.do_view1 = do_view1;
        $scope.do_view2 = do_view2;
        $http.get('../phpResp/tblDc.php?t=region&f=regCd').success(function (data) {
            $scope.regCdDc = data;
        });

        $scope.$watch('cusCd', function (cusCd) {
            $http.get('updGetDta.php?cusCd=' + cusCd).success(function (data) {
                var cusDro = data.cus;
                var adrDta = data.adr;
                $scope.dataOrg = angular.copy(data);
                $scope.data = data;
                return;
            });
        })
        return;
        function do_view1() {
            $scope.view1 = true;
            $scope.view1Color = "lightblue";
            $scope.view2 = false;
            $scope.view2Color = "buttonface";
        }

        function do_view2() {
            $scope.view2 = true;
            $scope.view2Color = "lightblue";
            $scope.view1 = false;
            $scope.view1Color = "buttonface";
        }

        function do_vdt() {

        }

        function do_reset() {
            $scope.data = angular.copy($scope.dataOrg);
        }

        function do_dltAdr() {
            var idx = this.$index;
            $scope.data.adr.splice(idx, 1);
        }

        function do_addAdr() {
            $scope.data.adr.push({shwDlt:true});
        }


        function do_deaAdr() {
            var idx = this.$index;
            $scope.data.adr[idx].isDea = '1';
        }


        function do_reaAdr() {
            var idx = this.$index;
            $scope.data.adr[idx].isDea = '01';
        }

        function do_sav() {
            var cus = $scope.data.cus;
            var adr = $scope.data.adr;
            var data = {cus: cus, adr: adr};
            $http.post("upd.php", data).success(function (data) {
                var cusCd = $scope.data.cus.cusCd;
                $scope.$emit("dsp", cusCd);
            })
        }

        function do_can() {
            $scope.$emit("dsp", cusCd);
        }
    }
})
(angular);