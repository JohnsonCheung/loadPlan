angular.module('app').controller('dsp', ['$scope', '$http', '$app', function dspControllerFn($scope, $http, $app) {
    $scope.$watch('sess.lang', function (lang) {
        $app.getLbl("cus", "dsp", lang, $scope);
    });
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
    $scope.$watch("appCus.cusCd", function (cusCd) {
        $http.get("updGetDta.php?cusCd=" + cusCd).success(function (data) {
            $scope.cus = data.cusDro;
            $scope.adr = data.adrDt;
        });
    });

    function do_can() {
        $scope.appCus.mode = "dsp"
    }

    function do_dlt() {
        $http.post("dspDlt.php", $scope.appCus.cusCd);
        $scope.appCus.cusCd = null; // set to null so that [left] will set rno to 1.
        $scope.appCus.mode = "dsp"
    }

    function do_rea() {
        $http.post("dspRea.php", $scope.appCus.cusCd);
        $scope.appCus.mode = "dsp"
    }

    function do_dea() {
        $http.post("dspDea.php", $scope.appCus.cusCd);
        $scope.appCus.mode = "dsp"
    }
}]);

