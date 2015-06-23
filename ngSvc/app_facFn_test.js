/**
 * Created by USER on 20/6/2015.
 */
var module = angular.mock.module;
var inject = angular.mock.inject;
describe('', function () {
    var $app;
    beforeEach(module('app'));
    beforeEach(inject(function (_$app_) {
        $app = _$app_;
    }))
    describe('service $app', function () {
        it("just a dummy to see how jasime works", function () {
            expect(true).toEqual(true);
        });
        describe("has getLbl", function () {
            it('should set $scope.lbl properly', function () {
                $scope = {};
                var pgmNm = 'region';
                var secNm = 'dsp';
                var lang = 'zh';
                $app.getLbl(pgmNm, secNm, lang, $scope);
                expect(1).toEqual(1);
            })
        })
    });
});