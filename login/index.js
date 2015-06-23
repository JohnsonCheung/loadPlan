(function (angular, location, Base64) {
    'use strict';
    angular.module("loadplanApp").controller("loginController", ['$scope', '$http', loginControllerFn]);

    function loginControllerFn($scope, $http) {
        $scope.shwLoginBtn = true;
        $scope.do_login = do_login;
        $scope.erMsg = null;
        $scope.lbl = {};
        $scope.lbl.txt = {};
        $scope.lbl.txt.appNm = '載貨計劃';
        return;
        function do_login() {
            $scope.msg = {usrId: '', pwd: ''};
            var usrId = $scope.usrId;
            var pwd = $scope.pwd;
            var er = false;
            if (typeof pwd !== 'string' || usrId.trim() === '') {
                $scope.msg.pwd = 'required';
                er = true;
            }
            if (typeof usrId !== 'string' || usrId.trim() === '') {
                $scope.msg.usrId = 'required';
                er = true;
            }
            if (er) return;

//            var o =  {a: Base64.toBase64(usrId), b: Base64.toBase64(pwd)};
            var a =encodeURI( Base64.toBase64(usrId));
            var b =encodeURI( Base64.toBase64(pwd));
            $http.get("login.php?a=" + a + '&b=' + b).success(function(data, status) {
                switch(status) {
                    case 202:
                        location.assign('/../menu');
                        break;
                    default:


                }

            })
            /*

             st('login.php', o)
             .success(function (session) {
             debugger;
             $scope.session = session;
             location.pathname = "loadplan/pgm/menu";
             })
             .error(function (erMsg) {
             $scope.erMsg = erMsg;
             });
             */
        }

        function doLogin_Server(loginNm, pwd) {
            //var ciph = des(key, message, 1, 0); // encrypt message against key using DES in ECB mode and without padding.
            //var cipherText = stringToHex(ciph); //convert base the string to HEX. In this way, you would forget about encoding problems when sending the string via POST or GET.

            $http
                .get("login.php?loginNm=" & loginNm & "&pwd=" & pwd)
                .success(successFn)
                .error(errorFn);
        }
    }

    function do_login() {
        $scope.isLoginBtnVis = false;
        //doLogin_Mockup($scope.loginNm, $scope.userNm);
        doLogin_Server($scope.loginNm, $scope.userNm);
    }

//    $scope.loginClicked = loginClicked;
//    $scope.isLoginBtnVis = true;
})
(angular, location, Base64);