/**
 * Created by USER on 20/6/2015.
 */
var module = angular.mock.module;
var inject = angular.mock.inject;
describe('sevice $app', function () {
    var $app;
    beforeEach(module('app'));
    beforeEach(inject(function (_$app_) {
        $app = _$app_;
    }))

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
            // by ajax async, $scope will be changed but how to test it???
            expect(1).toEqual(1);
        })
    })
    describe("has toAy function", function () {
        it('should work properly', function () {
            var toAy = $app.toAy;
            var act;
            act = toAy('a');
            expect(act).toEqual(['a']);
            act = toAy(' a ');
            expect(act).toEqual(['a']);
            act = toAy(['a']);
            expect(act).toEqual(['a']);
        })
    })

    describe("has Dta class", function () {
        describe("has  constructor", function () {
            it('should work properly', function () {
                var dta = [{a: 1}, {b: 1}, {c: 1}];
                var a = new $app.Dta(dta)
                expect(a.dta).toEqual(dta);
                expect(a.dta === dta).toBeTruthy();
            })
            describe("has function selCol", function () {
                it('should work properly', function () {
                    var dta = [{a: 1}, {b: 1}, {c: 1}];
                    var d = new $app.Dta(dta);
                    var act;
                    act = d.selCol("a b", "c");
                    exp = [
                        {c: undefined, dr: {a:1, b:undefined, c:undefined}},
                        {c: undefined, dr: {a:undefined, b:1, c:undefined}},
                        {c: 1, dr: {a:undefined, b:undefined, c:1}},
                    ]
                    expect(act).toEqual(exp);

                })
            })
        })
    })
    describe("has function getLbl", function () {
        it('should set $scope.lbl properly', function () {
            $scope = {};
            var pgmNm = 'region';
            var secNm = 'dsp';
            var lang = 'zh';
            $app.getLbl(pgmNm, secNm, lang, $scope);
            // by ajax async, $scope will be changed but how to test it???
            expect(1).toEqual(1);
        })
    })

})