/**
 * Created by USER on 7/6/2015.
 */
angular.module('app').controller('btn', ['$scope', '$http', '$app', '$obj', function a($scope, $http, $app, $obj) {
    'use strict';
    $scope.do_mode = function (mode) {
        $scope.appRegion.mode = mode;
    }
    $scope.$watch("sess.lang", function (lang) {
        $app.getLbl("region", "btn", lang, $scope);
    });

    if (!$scope.auth)
        $http.get("../phpResp/auth.php?pgmNm=region").success(function success_auth(data) {
            if (typeof data !== 'string') throw new Exception('auth.php does not return a string');
            $scope.auth = $obj.getOptObj(data);
        });

    $scope.$watch("appRegion.regCd", watch_regCd)
    if (!$scope.appRegion.regCd)
        watch_regCd($scope.appRegion.regCd);
    function watch_regCd(regCd) {
        $http.get("btn_recSts.php?regCd=" + regCd).success(function (data) {
            if (Object.getOwnPropertyNames(data).length === 0) return;
            var isDea = data.isDea;
            var isRef = data.isRef;
            var auth = $scope.auth;
            var shw = fnd_scope_shw(isDea, isRef, auth);
            $scope.shw = shw;
        });
        return;

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