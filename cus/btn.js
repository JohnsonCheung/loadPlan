/**
 * Created by USER on 7/6/2015.
 */
(function (angular) {
    'use strict';
    angular.module('app').controller('btn', ['$scope', '$http', '$app', '$obj', '$rootScope', a]);
    function a($scope, $http, $app, $obj, $rootScope) {
        $scope.do_mode = function (mode) {
            console.log(mode)
            $rootScope.mode = mode;
        }
        $scope.$watch('lang', function (lang) {
            $app.getLbl('cus', 'btn', lang, $scope);
        })
        if (!$scope.auth)
            $http.get("../phpResp/auth.php?pgmNm=cus").success(function (data) {
                if (typeof data !== 'string') throw new Exception('auth.php does not return a string');
                //debugger;
                $scope.auth = $obj.getOptObj(data);
            });
        $scope.$watch('cusCd', function (cusCd) {
            //adebugger;
            $http.get("btn_recSts.php?cusCd=" + cusCd).success(function (data) {
                var isDea = data.isDea;
                var isRef = data.isRef;
                var auth = $scope.auth;
                var shw = $app.getShwBtn(isDea, isRef, auth);
                $scope.shw = shw;
            });
        });
        return;

    }
})
(angular)