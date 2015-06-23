(function (angular) {
    angular.module("cusApp").controller("index", ['$scope', '$http', mainControllerFn]);
    function mainControllerFn($scope,$http) {
        $http.get('../phpResp/lbl.php?pgmNm=cusion&secNm=index').success(function (lbl) {
            $scope.lbl = lbl;
        })
        $scope.cusCd="";
        $scope.usrShtNm = "johnson";
        $scope.$on("cusCd_changed", cusCd_changed);
        $scope.mode = "dsp";

        $scope.$on('add', function () {
            $scope.mode = "add"
        });
        $scope.$on('upd', function () {
            $scope.mode = "upd"
        });
        $scope.$on('dsp', function () {
            $scope.mode = "dsp"
        });
        $scope.$on('rea', function () {
            $scope.mode = "rea"
        });
        $scope.$on('dlt', function () {
            $scope.mode = "dlt"
        });
        $scope.$on('dea', function () {
            $scope.mode = "dea"
        });
        $scope.$on('log', function () {
            $scope.mode = "log"
        });
        $scope.$on('exp', function () {
            $scope.mode = "exp"
        });
        $scope.$on('imp', function () {
            $scope.mode = "imp"
        });
        var last_cusCd;

        function cusCd_changed(ev, cusCd) {
            //console.log(cusCd);
            $scope.cusCd = cusCd;
            if (last_cusCd === cusCd)
                return
            last_cusCd = cusCd;
            //$scope.$broadcast("cusCd_changed", cusCd);
        }
    }
})(angular)