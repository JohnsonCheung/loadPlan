(function (angular) {
    'use strict';
    var app = angular.module('app');
    var ctrl = app.controller('add', ['$scope', '$http', '$document', '$app', '$rootScope', addControllerFn]);
    function addControllerFn($scope, $http, $document, $app, $rootScope) {
        $scope.$watch('lang', function (lang) {
            $app.getLbl("region","add", lang, $scope);
        })
        $scope.do_can = do_can;
        $scope.do_add = do_add;
        function do_add() {
            $http.post('add.php', $scope.regCd)
                .success(function (data, status) {
                    $scope.msg = "";
                    switch (status) {
                        case 201:
                            $rootScope.regCd = $scope.regCd;
                            $rootScope.mode = 'upd';
                            $scope.regCd="";
                            break;
                        case 202:
                            $scope.msg = "[" + $scope.regCd + "] " + $scope.lbl.msg.alreadyAdded;
                            break;
                        case 203:
                            $scope.msg = data;
                            break;
                        default:
                            $scope.msg = "status=[" + status + "]  data=[" + data + "]";
                    }
                })
        }

        function do_can() {
            $rootScope.mode='dsp';
        }
    }
})
(angular);