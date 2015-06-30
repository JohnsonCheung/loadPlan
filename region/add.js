angular.module('app').controller('add', ['$scope', '$http', '$app', function ($scope, $http, $app) {
    $scope.$watch('sess.lang', function (lang) {
        $app.getLbl("region", "add", lang, $scope);
    });
    $scope.appRegion.regCd = "";
    $scope.do_can = do_can;
    $scope.do_add = do_add;
    function do_add() {
        $http.post('add.php', {regCd: $scope.appRegion.regCd, lang: $scope.sess.lang})
            .success(function (addMsg) {
                $scope.appRegion.addMsg = addMsg;
                if (addMsg.isOk) {
                    $scope.appRegion.mode = "upd";
                }
            })
    }

    function do_can() {
        $scope.appRegion.mode = "dsp";
    }
}]);
