angular.module("app").controller("index", ['$scope', '$app', function a($scope, $app) {
    $scope.appOrd = {}
    $scope.appOrd.mode= "list";
    $scope.appOrd.ord= 0;
    $scope.sess = {lang: "en", usrShtNm: "johnson"};
    $scope.do_toLang = function () {
        $scope.sess.lang = $scope.sess.lang === 'zh' ? 'en' : 'zh';
    };
    $scope.$watch("sess.lang", function (lang) {
        $app.getLbl("ord", "index", lang, $scope);
    });
}])
