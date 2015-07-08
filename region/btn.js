/**
 * Created by USER on 7/6/2015.
 */
angular.module('app').controller('btn', ['$scope', '$http', '$app', '$obj', function a($scope, $http, $app, $obj) {
    $scope.do_mode = function (mode) {
        $scope.appRegion.mode = mode;
    }
    $scope.$watch("sess.lang", function (lang) {
        $app.getLbl("region", "btn", lang, $scope);
    });

    if (!$scope.auth)
        $http.get("../phpResp/auth.php?pgmNm=region").success(function(data) {
            if (typeof data !== 'string') throw new Exception('auth.php does not return a string');
            $scope.auth = $obj.getOptObj(data);
        });
    $scope.$watch("appRegion.regCd", watch_regCd);
    if (!$scope.appRegion.regCd)
        watch_regCd($scope.appRegion.regCd);
    function watch_regCd(regCd) {
        $http.get("btn_recSts.php?regCd=" + regCd).success(function (data) {
            if (Object.getOwnPropertyNames(data).length === 0) return;
            var isDea = data.isDea;
            var isRef = data.isRef;
            var auth = $scope.auth;
            var shw = $app.getShwBtn(isDea, isRef, auth);
            $scope.shw = shw;
        });
    }
}]);