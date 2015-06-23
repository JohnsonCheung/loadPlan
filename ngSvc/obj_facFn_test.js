/**
 * Created by USER on 20/6/2015.
 */
var module = angular.mock.module;
var inject = angular.mock.inject;
describe('', function () {
    var $obj;
    beforeEach(module('app'));
    beforeEach(inject(function (_$obj_) {
        $obj = _$obj_;
    }));
    describe('service $obj', function () {
        it("just a dummy to see how jasime works", function () {
            expect(true).toEqual(true);
        });
        describe('has isEq function', function () {
            it("should return true for isEq(1,1)", function () {
                expect($obj.isEq(1, 1)).toBeTruthy()
            });
            it("should return false for isEq(1,'1')", function () {
                expect($obj.isEq(1, '1')).toBeFalsy()
            });
            it("should return true for isEq('1','1')", function () {
                expect($obj.isEq('1', '1')).toBeTruthy()
            });
            it("should return true for isEq({a:1},{a:1})", function () {
                expect($obj.isEq({a: 1}, {a: 1})).toBeTruthy();
            });
            it("should return true for isEq({a:1,b:2},{b:2,a:1})", function () {
                expect($obj.isEq({a: 1, b: 2}, {b: 2, a: 1})).toBeTruthy()
            });
            it("should return true for isEq({a: 1, b: {x: 1, y: 2}}, {b: {y: 2, x: 1}, a: 1)", function () {
                expect($obj.isEq({a: 1, b: {x: 1, y: 2}}, {b: {y: 2, x: 1}, a: 1})).toBeTruthy()
            });
            it("should return false for isEq({a: 1, b: {x: 1, y: 3}}, {b: {y: 2, x: 1}, a: 1)", function () {
                expect($obj.isEq({a: 1, b: {x: 1, y: 3}}, {b: {y: 2, x: 1}, a: 1})).toBeFalsy()
            })
        });
        describe('has getOptObj function', function () {
            it("should work properly", function () {
                var act = $obj.getOptObj("a c b");
                var keys = Object.getOwnPropertyNames(act);
                expect(act).toEqual({a: true, b: true, c: true});
                expect(keys).toEqual(['a', 'c', 'b']);
            });
        });
        describe('has setShwDeaReaDltPrp function', function () {
            it('should work properly', function () {
                var setShwDeaReaDltPrp = $obj.setShwDeaReaDltPrp;
                var a = {a: 1, b: 1, isDea: true, isRef: true};
                setShwDeaReaDltPrp(a);
                var exp = {a: 1, b: 1, isDea: true, isRef: true, shwDea: false, shwRea: true, shwDlt: false};
                expect(a).toEqual(exp);
            })
        })
    });
});