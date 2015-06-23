(function (angular) {
    'use strict';
    angular.module('cusApp').controller('dsp', ['$scope', '$http', dspControllerFn]);
    function load_lbl($http, $scope, lang) {
        $http.get("../phpResp/lbl.php?pgmNm=cus&secNm=dsp&lang=" + lang).success(function (lbl) {
            $scope.lbl = lbl;
        });

    }

    function dspControllerFn($scope, $http) {
        load_lbl($http, $scope, $scope.lang);
        $scope.do_can = do_can;
        $scope.do_dlt = do_dlt;
        $scope.do_rea = do_rea;
        $scope.do_dea = do_dea;
        $scope.view1 = true;
        $scope.view1Color = "lightblue";
        $scope.do_view1 = function () {
            $scope.view1 = true;
            $scope.view1Color = "lightblue";
            $scope.view2 = false;
            $scope.view2Color = "buttonface";
        }
        $scope.do_view2 = function () {
            $scope.view2 = true;
            $scope.view2Color = "lightblue";
            $scope.view1 = false;
            $scope.view1Color = "buttonface";
        }
        $scope.$on("lang_changed", function ($ev, lang) {
            load_lbl($http, $scope, lang)
        })
        $scope.$watch("cusCd", function (newCusCd, oldCusCd) {
            console.log({newCusCd: newCusCd, oldCusCd: oldCusCd});
            $http.get("dsp.php?cusCd=" + newCusCd).success(function (data) {
                $scope.cus = data.cus;
                $scope.adr = data.adr;
            });
        });
        return;
        function do_can() {
            $rootScope.mode = "dsp"
        }

        function do_dlt() {
            $http.get("dspDlt.php?cusCd=" + $scope.cusCd);
            $rootScope.mode = "dlt"
        }

        function do_rea() {
            $http.get("dspRea.php?cusCd=" + $scope.cusCd);
            $rootScope.mode = "dsp"
        }

        function do_dea() {
            $http.get("dspDea.php?cusCd=" + $scope.cusCd);
            $rootScope.mode = "dsp"
        }
    }
})
(angular);