/**
 * Created by USER on 2015-07-08.
 */
angular.module('app').controller('dsp', ['$scope', '$http', '$app', function ($scope, $http, $app) {
    $scope.go_id = $app.go_id;
    $scope.$watch("sess.lang", function (lang) {
        $app.getLbl("ord", "dsp", lang, $scope);
    });
    $scope.$watch("appOrd.ord", function (ord) {
        $http.post('dspH.php', $scope.appOrd.ord).success(function (data) {
            $scope.data = data;
        });
    })
    $scope.do_prt = do_prt;
    $scope.do_upd = do_upd;
    $scope.do_can = do_can;
    $scope.do_back = do_back;
    $scope.$watch('appOrd.ord', function (ord) {
        return;
        $http.post('dspH.php', ord).success(function (data) {
            $scope.data = data;
        })
    })
    function do_upd() {
    }

    function do_back() {
        appOrd.mode = "list";
    }

    function do_can() {
        $http.post("dspCan.php", $scope.appOrd.ord);
        $scope.appOrd.mode = "list";
    }

    function do_prt() {
        $http.post("prtOrd.php", $scope.appOrd.ord).success(function (data) {

        })
    }
}]);
