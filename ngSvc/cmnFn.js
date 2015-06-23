/**
 * Created by USER on 7/6/2015.
 */
(function (angular) {
    'use strict';
    angular.module('app').factory('cmnFn', cmnFn_factory);
    return;
    function cmnFn_factory() {
        return {
            getOptObj: getOptObj
        }
    }

    function getOptObj(str) {
        return str.split(' ').reduce(reduce, {});
        function reduce(o, i) {
            o[i] = true;
            return o;
        }
    }

    var buf = {};


})(angular);