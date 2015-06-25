/**
 * Created by USER on 20/6/2015.
 */
var module = angular.mock.module;
var inject = angular.mock.inject;
describe('', function () {
    var $ay;
    beforeEach(module('app'));
    beforeEach(inject(function (_$ay_) {
        $ay = _$ay_;
    }))
    describe('service $ay', function () {
        it("just a dummy to see how jasime works", function () {
            expect(true).toEqual(true);
        });
        describe("has minus", function () {
            it('minus([0, 1, 2] , [0, 3] = [1,2]', function () {
                var act = $ay.minus([0, 1, 2], [0, 3]);
                expect(act).toEqual([1, 2]);
            })
        })
        describe("has swapEle", function () {
            it('swapEle([0, 1, 2, 3, 4, 5, 6], 3, 2) => [0, 1, 3, 2, 4, 5, 6]', function () {
                var swapEle = $ay.swapEle;
                expect(swapEle([0, 1, 2, 3, 4, 5, 6], 3, 2)).toEqual([0, 1, 3, 2, 4, 5, 6]);
            });
            it('should work properly', function () {
                var ay = [0, 1, 2, 3, 4, 5, 6];
                var swapEle = $ay.swapEle;

                expect(swapEle(ay, 2, 3)).toEqual([0, 1, 3, 2, 4, 5, 6]);
                expect(swapEle(ay, 2, 6)).toEqual([0, 1, 3, 4, 5, 6, 2]);
                expect(swapEle(ay, 2, 1)).toEqual([0, 2, 1, 3, 4, 5, 6]);
            });
        });
    });
})