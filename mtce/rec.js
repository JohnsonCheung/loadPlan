(function(angular,_) {
	'use strict';
	angular
		.module('app')
		.controller('regMtc' , ['$scope','$document', regMtcControllerFn ])
	function regMtcControllerFn($scope,$document) {
		$scope.mode="add";

	}
})(angular,_);