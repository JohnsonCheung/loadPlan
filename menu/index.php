<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 9/6/2015
 * Time: 6:53
 */
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Load Plan System</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../stdLib/angular.min.js"></script>
    <script>
        (function (angular, location) {
            angular.module("loadplanApp", []).controller("loginController", ['$scope', '$http', menuControllerFn]);
            return;
            function menuControllerFn($scope, $http) {
                $http.get('menu.php').success(function (menuSrc) {
                    $scope.menu = fnd_menu(menuSrc);
                });
            }

            function fnd_menu(menuSrc) {
            }

        })
        (angular, location);
    </script>
</head>
<body ng-App="loadplanApp">
<p>Everytime Kenny is killed, Stan will announce
    <q cite="http://en.wikipedia.org/wiki/Kenny_McCormick#Cultural_impact">
        Oh my God, you/they killed Kenny!
    </q>.
</p>
<table>
    <tr>
        <td width="60%"><h1 align="left" style="display:inline">Loading Plan System</h1>
        <td>
        <td width="10%">{{lbl.txt.welcome}} <a href="../UsrProfile">{{usrNm}}</a>
        <td width="10%"><img src="../images/company.png">
    </tr>
</table>
<hr>
<table align="center">
    <caption>aaa<br>
        <input type=button value="top"/>
        <input type=button value="buttom"/>
        <input type=button value="up"/>
        <input type=button value="down"/>
        <input width="20px" type="text"/>
        <input type=button value="x"/>
    </caption>
    <thead>
    <td>xx
    <td>aaa
    <td>bbb
    <td>bbb
    <td>bbb
    </thead>
    <tr>
        <td><input type=button value="aaa1"/>
        <td><input type=button value="aaa2"/>
        <td><input type=button value="aaa3"/>
        <td><input type=button value="aaa4"/>
        <td><input type=button value="aaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    </tr>
</table>
<hr>
<table align="center">
    <caption>aaa<br>
        <input type=button value="top"/>
        <input type=button value="buttom"/>
        <input type=button value="up"/>
        <input type=button value="down"/>
        <input width="20px" type="text"/>
        <input type=button value="x"/>
    </caption>
    <thead>
    <td>xx
    <td>aaa
    <td>bbb
    <td>bbb
    <td>bbb
    </thead>
    <tr>
        <td><input type=button value="aaa1"/>
        <td><input type=button value="aaa2"/>
        <td><input type=button value="aaa3"/>
        <td><input type=button value="aaa4"/>
        <td><input type=button value="aaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    </tr>
</table>
<hr>
<table align="center">
    <caption>aaa<br>
        <input type=button value="top"/>
        <input type=button value="buttom"/>
        <input type=button value="up"/>
        <input type=button value="down"/>
        <input width="20px" type="text"/>
        <input type=button value="x"/>
    </caption>
    <thead>
    <td>xx
    <td>aaa
    <td>bbb
    <td>bbb
    <td>bbb
    </thead>
    <tr>
        <td><input type=button value="aaa1"/>
        <td><input type=button value="aaa2"/>
        <td><input type=button value="aaa3"/>
        <td><input type=button value="aaa4"/>
        <td><input type=button value="aaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    </tr>
</table>
<hr>
<table align="center">
    <caption>aaa<br>
        <input type=button value="top"/>
        <input type=button value="buttom"/>
        <input type=button value="up"/>
        <input type=button value="down"/>
        <input width="20px" type="text"/>
        <input type=button value="x"/>
    </caption>
    <thead>
    <td>xx
    <td>aaa
    <td>bbb
    <td>bbb
    <td>bbb
    </thead>
    <tr>
        <td><input type=button value="aaa1"/>
        <td><input type=button value="aaa2"/>
        <td><input type=button value="aaa3"/>
        <td><input type=button value="aaa4"/>
        <td><input type=button value="aaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    </tr>
</table>
<hr>
<table align="center">
    <caption>aaa<br>
        <input type=button value="top"/>
        <input type=button value="buttom"/>
        <input type=button value="up"/>
        <input type=button value="down"/>
        <input width="20px" type="text"/>
        <input type=button value="x"/>
    </caption>
    <thead>
    <td>xx
    <td>aaa
    <td>bbb
    <td>bbb
    <td>bbb
    </thead>
    <tr>
        <td><input type=button value="aaa1"/>
        <td><input type=button value="aaa2"/>
        <td><input type=button value="aaa3"/>
        <td><input type=button value="aaa4"/>
        <td><input type=button value="aaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    </tr>
</table>
<hr>
<table align="center">
    <caption>aaa<br>
        <input type=button value="top"/>
        <input type=button value="buttom"/>
        <input type=button value="up"/>
        <input type=button value="down"/>
        <input width="20px" type="text"/>
        <input type=button value="x"/>
    </caption>
    <thead>
    <td>xx
    <td>aaa
    <td>bbb
    <td>bbb
    <td>bbb
    </thead>
    <tr>
        <td><input type=button value="aaa1"/>
        <td><input type=button value="aaa2"/>
        <td><input type=button value="aaa3"/>
        <td><input type=button value="aaa4"/>
        <td><input type=button value="aaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    </tr>
</table>
<hr>
<table align="center"><r
    <caption>aaa<br>
        <a href="#top" value="top">top</a>
        <a href="#bottum"/>bottum</a>
        <input type=button value="up"/>
        <input type=button value="down"/>
        <input width="20px" type="text"/>
        <input type=button value="x"/>
    </caption>
    <thead>
    <td>xx
    <td>aaa
    <td>bbb
    <td>bbb
    <td>bbb
    </thead>
    <tr>
        <td><input type=button value="aaa1"/>
        <td><input type=button value="aaa2"/>
        <td><input type=button value="aaa3"/>
        <td><input type=button value="aaa4"/>
        <td><input type=button value="aaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    </tr>
</table>
<hr>
<table align="center">
    <caption>aaa<br>
        <input type=button value="top"/>
        <input type=button value="buttom"/>
        <input type=button value="up"/>
        <input type=button value="down"/>
        <input width="20px" type="text"/>
        <input type=button value="x"/>
    </caption>
    <thead>
    <td>xx
    <td>aaa
    <td>bbb
    <td>bbb
    <td>bbb
    </thead>
    <tr>
        <td><input type=button value="aaa1"/>
        <td><input type=button value="aaa2"/>
        <td><input type=button value="aaa3"/>
        <td><input type=button value="aaa4"/>
        <td><input type=button value="aaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    <tr>
        <td><input type=button value="baaa1"/>
        <td><input type=button value="baaa2"/>
        <td><input type=button value="baaa3"/>
        <td><input type=button value="baaa4"/>
        <td><input type=button value="baaa5"/>
    </tr>
</table>
</html>