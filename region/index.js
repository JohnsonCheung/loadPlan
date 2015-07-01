angular.module("app").controller("index", ['$scope', '$app', function a($scope, $app) {
    $scope.appRegion = {mode: "dsp"}
    $scope.sess = {lang: "en", usrShtNm: "johnson"};
    $scope.do_toLang = function () {
        $scope.sess.lang = $scope.sess.lang === 'zh' ? 'en' : 'zh';
    };
    $scope.$watch("sess.lang", function (lang) {
        $app.getLbl("region", "index", lang, $scope);
    });
}])
