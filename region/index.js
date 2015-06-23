(function (angular) {
    angular.module("app").controller("index", ['$scope', '$http', '$app', '$rootScope', a]);
    function a($scope, $http, $app, $rootScope) {
        $rootScope.lang="en";
        $rootScope.mode = "dsp";
        $scope.usrShtNm = "johnson";
        $scope.do_toLang = do_toLang;
        $scope.$watch("lang", function(lang) {
            $app.getLbl("region","index", lang, $scope);
        })
        function do_toLang() {
            $scope.lang = $scope.lang === 'zh' ? 'en' : 'zh';
        }
    }
})
(angular)