(function(angular) {
	'use strict';
	angular
		.module('app')
		.controller('logout'     , ['$scope', logoutControllerFn])
	function logoutControllerFn($scope) {
		$scope.isLogin=true;
		$scope.user = "johnson";
		$scope.do_logout = do_logout;
		function do_logout() {
			$scope.isLogin=false;
		}
	}
})(angular);