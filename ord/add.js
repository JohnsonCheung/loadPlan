angular.module('app').controller('add', ['$scope', '$http', '$app', '$filter', function ($scope, $http, $app, $filter) {
    $scope.$watch('sess.lang', function (lang) {
        $app.getLbl('ord', 'add', lang, $scope);
    });
    var a = angular.module('app').controller('add');
    var curDate = (new Date).getDate();
    $scope.do_can = do_can;
    $scope.do_add = do_add;
    $scope.dteAy = dteAy();
    $scope.dteTo = _day(30);
    $scope.delvDte = $scope.dteAy[0];
    $scope.do_selCus = function (ev) {
        $scope.$broadcast('shwSelCus', ev, $scope.appOrd, 'cusCd');
    };
    $scope.$watch('delvDte', _ordDelvDte);
    $scope.$watch('othDelvDte', _ordDelvDte);
    function _ordDelvDte() {
        var delvDte = $scope.delvDte;
        var othDelvDte = $scope.othDelvDte;
        $scope.appOrd.ordDelvDte = (delvDte === 'other')
            ? $filter('date')(othDelvDte, 'yyyy-MM-dd')
            : delvDte;
    }

    function _day(n) {
        var a = new Date;
        a.setDate(curDate + n);
        return $filter('date')(a, 'yyyy-MM-dd');
    }

    function dteAy() {
        var o = [];
        for (var j = 0; j <= 7; j++) {
            o[j] = _day(j);
        }
        return o;
    }

    function do_add() {
        var cusCd = $scope.appOrd.cusCd.toUpperCase();
        var data = {cusCd: cusCd, lang: $scope.sess.lang, ordDelvDte: $scope.appOrd.ordDelvDte};
        $http.post('add.php', data).success(function (svrMsg) {
            $scope.appOrd.svrMsg = svrMsg;
            if (svrMsg.isOk) {
                $scope.appOrd.mode = "upd";
                $scope.appOrd.ord = svrMsg.ord;
                return;
            }
        });
    }

    function do_can() {
        $scope.appOrd.mode = "dsp";
    }
}]);