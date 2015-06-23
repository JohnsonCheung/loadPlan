(function (angular) {
    'use strict';
    angular.module('app').controller('add', ['$scope', '$http', '$document', '$app', '$rootScope', a]);
    function a($scope, $http, $document, $app, $rootScope) {
        $rootScope.$watch('lang', function (lang) {
            $app.getLbl('cus', 'add', lang, $scope);
        })
        $scope.do_can = do_can;
        $scope.do_add = do_add;
        $scope.addAy = [];
        return;
        function do_add() {
            $http.get('add.php?cusCd=' + $scope.newCusCd)
                .success(function (data, status) {
                    $scope.msg = null;
                    switch (status) {
                        case 201:
                            $scope.addAy.push($scope.newCusCd);
                            debugger;
                            $scope.newCusCd = "";
                            break;
                        case 202:
                            $scope.msg = '[' + $scope.newCusCd + '] ' + $scope.lbl.msg.alreadyAdded;
                            $scope.newCusCd = "";
                            break;
                        case 203:
                            $scope.msg = data;
                            break;
                        default:
                            $scope.msg = "status=[" + status + "]"
                    }
                })
        }

        function do_can() {
            $rootScope.mode = "dsp";
        }
    }
})
(angular);