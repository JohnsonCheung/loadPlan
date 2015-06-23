(function (angular) {
    'use strict';
    angular.module('app').controller('dsp', ['$scope', '$http', '$app', '$rootScope', dspControllerFn]);

    function dspControllerFn($scope, $http, $app, $rootScope) {
        $scope.$watch("lang", function (lang) {
            $app.getLbl("region", "dsp", lang, $scope);
        })
        $scope.do_can = do_can;
        $scope.do_dlt = do_dlt;
        $scope.do_rea = do_rea;
        $scope.do_dea = do_dea;
        $scope.$watch("regCd", function (regCd) {
            $http.get("dsp.php?regCd=" + regCd).success(function (data) {
                $scope.data = data;
            });
        });
        return;
        function do_can() {
            $rootScope.mode = "dsp";
        }

        function do_dlt() {
            $http.get("dspDlt.php?regCd=" + $scope.regCd);
            $rootScope.mode = "dsp";
        }

        function do_rea() {
            $http.get("dspRea.php?regCd=" + $scope.regCd);
            $rootScope.mode = "dsp";
        }

        function do_dea() {
            $http.get("dspDea.php?regCd=" + $scope.regCd);
            $rootScope.mode = "dsp";
        }
    }
})
(angular);
