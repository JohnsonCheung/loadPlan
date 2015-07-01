angular.module('app').controller('dsp', ['$scope', '$http', '$app', function ($scope, $http, $app) {
    'use strict';
    $scope.$watch("sess.lang", function (lang) {
        $app.getLbl("region", "dsp", lang, $scope);
    });
    $scope.do_can = do_can;
    $scope.do_dlt = do_dlt;
    $scope.do_rea = do_rea;
    $scope.do_dea = do_dea;
    $scope.$watch("appRegion.regCd", function (regCd) {
        $http.get("dsp.php?regCd=" + regCd).success(function (data) {
            $scope.data = data;
        });
    });
    return;
    function do_can() {
        $scope.appRegion.mode = "dsp";
    }

    function do_dlt() {
        $http.post("dspDlt.php", $scope.appRegion.regCd);
        $scope.appRegion.mode = "dsp";
    }

    function do_rea() {
        $http.post("dspRea.php", $scope.appRegion.regCd);
        $scope.appRegion.mode = "dsp";
    }

    function do_dea() {
        $http.post("dspDea.php", $scope.appRegion.regCd);
        $scope.appRegion.mode = "dsp";
    }
}
]);

