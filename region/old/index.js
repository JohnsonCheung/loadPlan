(function(angular,_) {
	'use strict';
	angular
		.module('regionApp',[])
		.controller('login'     , ['$scope', loginControllerFn])
		.controller('regionLeft', ['$scope', regionLeftControllerFn])
		.controller('regionRec' , ['$scope','$document', regionRecControllerFn ]);

	function loginControllerFn($scope) {
		$scope.isLogin=true;
		$scope.user = "johnson";
		$scope.do_logout = do_logout;
		function do_logout() {
			$scope.isLogin=false;
		}
	}

	function regionRecControllerFn($scope,$document) {
		$scope.isAdd=true;
		$scope.isDsp=true;
		$scope.isUpd=true;

		$scope.do_sav = do_sav;
		$scope.do_upd = do_upd;
		$scope.do_add = do_add;
		$scope.do_dlt = do_dlt;
		$scope.do_dea = do_dea;
		$scope.do_rea = do_rea;
		$scope.do_can = do_can;
		$scope.do_exp = do_exp;
		$scope.do_imp = do_imp;
		$scope.do_log = do_log;

		$scope.sav_new_regCd = sav_new_regCd;
		$scope.sav_new_regCd_keydown = sav_new_regCd_keydown;

		var data = {}
		var deact = {};
		deact.by = "de-act by";
		deact.on = "de-act on";
		deact.rmk = "de-remark";
		data.deact = deact;

		data.regCd = 'region-A';
		data.inpCd = 'input code -aa';
		data.chiNm = 'chinese name: aa'
		data.engNm = 'english name: bb'
		data.nearBy = [
			{regCd:'a1', engNm: 'abb', chiNm: 'acc'},
			{regCd:'b1', engNm: 'bbb', chiNm: 'bcc'},
			{regCd:'c1', engNm: 'cbb', chiNm: 'ccc'}
		]

		var shw = {}
			shw.add = true;
			shw.upd = true;
			shw.dlt = true;
			shw.rea = true;
			shw.dea = true;
			shw.sav = true;
			shw.can = true;
			shw.imp = true;
			shw.exp = true;
			shw.log = true;

		var btn = {};
			btn.add = "Add";
			btn.upd = "Update";
			btn.dlt = "Delete";
			btn.rea = "Re-activate";
			btn.dea = "De-activate";
			btn.sav = "Save";
			btn.can = "Cancel";
			btn.imp = "Import";
			btn.exp = "Export";
			btn.log = "Log";

		var lbl = {};
			lbl.regCd = "Region Code";
			lbl.inpCd = "Input Code";
			lbl.chiNm = "Region Name (Chinese)";
			lbl.engNm = "Region Name (English)";
			lbl.nearBy = "Near By";
		$scope.lbl = lbl;
		$scope.shw = shw;
		$scope.btn = btn;
		$scope.data = data;

		
		function sav_new_regCd(new_regCd) {
			console.log(new_regCd);
			$scope.isUpd = true
			$scope.isDsp = false;
			$scope.isAdd = false;
		}

		function sav_new_regCd_keydown($event) {
			if($event.keyCode!=13)
				return;
			$event.defaultPrevented = true;
			var doc = $document[0];
			var ele = doc.getElementById('new_regCd');
			sav_new_regCd(ele.value)
		}

		function do_sav() {

		}
		function do_upd() {

		}
		function do_add() {
			$scope.data.new_regCd = '';
			$scope.isAdd = true;
			$scope.isUpd = false;
			$scope.isDsp = false;
		}
		function do_dlt() {

		}
		function do_dea() {

		}
		function do_rea() {

		}
		function do_can() {

		}
		function do_imp() {

		}
		function do_exp() {

		}
		function do_log() {

		}
	}
		
	function regionLeftControllerFn($scope) {

		var data = [];
			data.push(['inp1','e1','c1','code1']);
			data.push(['inp2','e2','c2','code2']);
			data.push(['inp3','e3','c3','code3']);
			data.push(['inp4','e4','c4','code4']);
			data.push(['inp5','e5','c5','code5']);
			data.push(['inp6','e6','c6','code6']);
			data.push(['inp1','e' ,'c' ,'code']);
			data.push(['inp' ,'e1','c' ,'code']);
			data.push(['inp' ,'e' ,'c1','code']);
			data.push(['inp' ,'e' ,'c2','code1']);
		var src = {};
			src.data = data;

		var lbl = {};
			lbl.filter = "filter";
			lbl.btn = {};
			lbl.btn.eng = "English";
			lbl.btn.chi = "Chinese";
			lbl.btn.code = "Code";
			lbl.btn.inp = "Inp";

		$scope.do_bld_data    = do_bld_data;
		$scope.do_go_rno      = do_go_rno;
		$scope.do_sel_row     = do_sel_row;
		$scope.do_fmt_ofN_ofT = do_fmt_ofN_ofT;
		$scope.do_tgl_btn     = do_tgl_btn;
		$scope.do_filter_changed = do_filter_changed;
		
		$scope.matrix = {};
		$scope.matrix.data = data;
		$scope.lbl = lbl;
		$scope.src = src;
		$scope.selectedIdx = 3;
		$scope.btn_selected = {chi:true,eng:true,code:true,inp:true}
		$scope.ofN = src.data.length;
		$scope.ofT = src.data.length;

		function do_filter_changed(filter) {
			if(filter===undefined)
				return;
			
			if(filter==='') {
				$scope.matrix.data = $scope.src.data;
				$scope.ofN = $scope.ofT;
				return;
			}

			var filter_substr_ay = (function(filter) {
						'use strict';
						function nospace(ay,i) {
							if(i!=='')
								ay.push(i)
							return ay;
						}
						return filter.split(' ').reduce(nospace,[]);
					})(filter)

			var data = 
				(function(filter_substr_ay) {
					'use strict';
					function isSel(dr) {
						function isSubStrInSomeFld(substr) {
							function isContain(fld) {
								return fld.search(substr) !== -1;
							}
							return _.some(dr, isContain)
						}

						var isSel = _.every(filter_substr_ay, isSubStrInSomeFld);
						return isSel
					}

					var data = $scope.src.data;
					return _.filter(data, isSel);
				})(filter_substr_ay)
			$scope.matrix.data = data;
			$scope.ofN = data.length;
			if($scope.selectedIdx>=$scope.ofN) {
				$scope.selectedIdx = 1;
			}

		}
		function do_fmt_ofN_ofT(ofN) {
			var ofT = $scope.ofT
			return (ofN===ofT)
				? ' of ' + ofN 
				: ' of ' + ofN + ' of ' + ofT;
		}

		function do_go_rno(rno) {
			if(!rno)
				return;
			$scope.selectedIdx = rno - 1;
		}

		function do_bld_data() {
			var src_data   = $scope.src.data;
			var filter     = $scope.filter;
			var rno        = $scope.rno;

			var data = 
				(filter===undefined)
				? src_data
				: src_data
			var inp  = $scope.btn_selected.inp;
			var eng  = $scope.btn_selected.eng;
			var chi  = $scope.btn_selected.chi;
			var code = $scope.btn_selected.code;
			var new_data = _bld_cols(data, inp, eng, chi, code);
			$scope.matrix.data = new_data;
		}

		function _bld_cols(data, inp, eng, chi, code) {
			function reduce(o,tr) {
				var td =[];
				if(inp)
					td.push(tr[0]);
				if(eng)
					td.push(tr[1]);
				if(chi)
					td.push(tr[2]);
				if(code)
					td.push(tr[3]);
				o.push(td);
				return o;
			}
			return data.reduce(reduce,[]);
		}

		function do_sel_row() {
			$scope.selectedIdx = this.$index;
			$scope.rno = this.$index + 1;
		}

		function do_tgl_btn(btnNm) {
			var a = $scope.btn_selected;
			var chi = !!a['chi'];
			var eng = !!a['eng'];
			var inp = !!a['inp'];
			var code = !!a['code'];
			switch(btnNm) {
				case "chi":  if( chi&&!eng&&!inp&&!code) return; break
				case "eng":  if(!chi&& eng&&!inp&&!code) return; break
				case "inp":  if(!chi&&!eng&& inp&&!code) return; break
				case "code": if(!chi&&!eng&&!inp&& code) return; break
			}
			$scope.btn_selected[btnNm] = !$scope.btn_selected[btnNm];
			chi = a['chi']
			eng = a['eng'];
			inp = a['inp'];
			code = a['code'];
			do_bld_data();
		}
	}
})(angular,_);