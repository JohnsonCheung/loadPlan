/**
 * Created by USER on 7/6/2015.
 */
angular.module('app').controller('btn', ['$scope', '$http', '$app', '$obj', function a($scope, $http, $app, $obj) {
    $scope.do_mode = function (mode) {
        $scope.appCus.mode = mode;
    }
    $scope.$watch('sess.lang', function (lang) {
        $app.getLbl('cus', 'btn', lang, $scope);
    })
    if (!$scope.auth)
        $http.get("../phpResp/auth.php?pgmNm=cus").success(function (data) {
            if (typeof data !== 'string') throw new Exception('auth.php does not return a string');
            $scope.auth = $obj.getOptObj(data);
        });
    $scope.$watch('appCus.cusCd', watch_cusCd);
    if (!$scope.appCus.cusCd)
        watch_cusCd($scope.appCus.cusCd);
    function watch_cusCd(cusCd) {
        $http.get("btn_recSts.php?cusCd=" + cusCd).success(function (data) {
            var isDea = data.isDea;
            var isRef = data.isRef;
            var auth = $scope.auth;
            var shw = $app.getShwBtn(isDea, isRef, auth);
            $scope.shw = shw;
        });

        function fnd_scope_shw(isDea, isRef, auth) {
            var shw = angular.copy(auth);
            if (isDea === undefined) {
                shw.dlt = false;
                shw.dea = false;
                shw.upd = false;
                return;
            }
            if (isDea) {
                if (auth.rea) {
                    shw.rea = true;
                }
                shw.dlt = false;
                shw.dea = false;
                shw.upd = false;
            } else {
                shw.rea = false;
                if (isRef) {
                    shw.dlt = false;
                } else {
                    shw.dea = false;
                }
            }
            return shw;
        }
    }
}]);
 

