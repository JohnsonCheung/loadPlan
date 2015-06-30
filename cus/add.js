angular.module('app').controller('add', ['$scope', '$http', '$app',
    function a($scope, $http, $app) {
        $scope.$watch('sess.lang', function (lang) {
            $app.getLbl('cus', 'add', lang, $scope);
        });
        $scope.appCus.cusCd = "";
        $scope.do_can = do_can;
        $scope.do_add = do_add;
        function do_add() {
            $http.post('add.php?cusCd', {cusCd: $scope.appCus.cusCd, lang: $scope.sess.lang})
                .success(function (addMsg) {
                    $scope.appCus.addMsg = addMsg;
                    if (addMsg.isOk) {
                        $scope.appCus.mode = "upd";
                    }
                })
        }

        function do_can() {
            $scope.appCus.mode = "dsp";
        }
    }
])
;

