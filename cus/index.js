angular.module("app").controller("index", ['$scope', '$app', function a($scope, $app) {
    $scope.appCus = {mode: "dsp"}
    $scope.sess = {lang: "en", usrShtNm: "johnson"};
    $scope.do_toLang = function () {
        $scope.sess.lang = $scope.sess.lang === 'zh' ? 'en' : 'zh';
    };
    $scope.$watch("sess.lang", function (lang) {
        $app.getLbl("cus", "index", lang, $scope);
    });
}])
