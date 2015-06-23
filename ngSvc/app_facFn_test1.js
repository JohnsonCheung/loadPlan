/**
 * Created by USER on 20/6/2015.
 */
angular.module('app').controller('test', ['$scope', '$app', a]);
function a($scope, $app) {
    var pgmNm = 'region';
    var secNm = 'dsp';
    var lang = 'zh';
    $app.getLbl(pgmNm, secNm, lang, $scope);
}
