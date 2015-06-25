/**
 * Created by USER on 20/6/2015.
 */
var module = angular.mock.module;
var inject = angular.mock.inject;
describe('libray - $lib.js', function () {
    var $app;
    var $obj;
    var $str;
    var $ay;
    var $dta;
    beforeEach(module('app'));
    beforeEach(inject(function (_$app_, _$obj_, _$str_, _$ay_, _$dta_) {
        $app = _$app_;
        $obj = _$obj_;
        $str = _$str_;
        $ay = _$ay_;
        $dta = _$dta_;
    }))
    it("just a dummy to see how jasimine works", function () {
        expect(true).toEqual(true);
    });
    describe("SERVICE $app", function () {
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
        describe("has function toAy function", function () {
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
    describe('SERVICE $obj', function () {
        describe("has function aa", function () {
            it("should work properly", function () {
                expect(1).toEqual(1);
            })

        })
    })
    describe('SERVICE $ay', function () {
        var $app;
        beforeEach(module('app'));
        beforeEach(inject(function (_$app_) {
            $app = _$app_;
        }))
    })
    describe('SERVICE $str', function () {
        var act;
        describe('has function splitLvs', function () {
            it('splitLvs(null) should return []', function () {
                act = $str.splitLvs(null);
                expect(act).toEqual([]);
            })
            it('splitLvs(undefined) should return []', function () {
                act = $str.splitLvs(undefined);
                expect(act).toEqual([]);
            })
            it('splitLvs(" a b ") should return ["a","b"]', function () {
                act = $str.splitLvs(' a b ');
                expect(act).toEqual(['a', 'b']);
            })
        })
    })
    describe('SERVICE $dta', function () {
        describe("CLASS Dta", function () {
            var dta, t, act;
            function init() {
                dta = [{a: 1}, {b: 1}, {c: 1}];
                t = new $dta.Dta(dta);      // tarDta <<-- the Dta instance to be tested
            }
            beforeEach(init);
            describe("has  constructor", function () {
                beforeEach(init);
                it('should work properly', function () {
                    expect(t.dta).toEqual(dta);
                    expect(t.dta === dta).toBeTruthy();
                })
            });
            describe("has function selCol", function () {
                it('should work properly', function () {
                    act = t.selCol("a b", "c");
                    var a0 = act[0];
                    var a1 = act[1];
                    var a2 = act[2];
                    var e0 = {c: undefined, dr: {a: 1, b: undefined}};
                    var e1 = {c: undefined, dr: {a: undefined, b: 1}};
                    var e2 = {c: 1, dr: {a: undefined, b: undefined}};
                    expect(act.length).toEqual(3);
                    expect(a0).toEqual(e0);
                    expect(a1).toEqual(e1);
                    expect(a2).toEqual(e2);
                })
            });
            describe("has function filter", function () {
                it("should work properly", function () {
                    var r0, r1, r2, r3, r4;
                    var a0, a1, a2, a3, a4, a;
                    var e0, e1, e2, e3, e4, e;
                    r0 = {a: 1, b: 2, c: 13, d: 14};
                    r1 = {a: 11, b: 12, c: 13, d: 14};
                    r2 = {a: 21, b: 22, c: 23, d: 24};
                    r3 = {a: 31, b: 32, c: 33, d: 34};
                    r4 = {a: 41, b: 42, c: 43, d: 44};
                    dta = [r0, r1, r2, r3, r4];
                    t = new $dta.Dta(dta);
                    a = t.filter('2 13', 'a b', 'd');
                    e0 = {a: 1, b: 2, c: 13, d: 14};
                    e1 = {a: 11, b: 12, c: 13, d: 14};
                    e = [e0, e1];
                    a0 = a[0];
                    a1 = a[1];
                    expect(a0).toEqual(e0);
                    expect(a1).toEqual(e1);
                    expect(a).toEqual(e);
                })
            })
        })
    })
})

