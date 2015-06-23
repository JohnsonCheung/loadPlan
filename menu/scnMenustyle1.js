(function scnMenuStyle1Init(angular) {
    angular.module('loadPlanApp').controller('menuController', ['$scope','$http',menuControllerFn]);

 function menuControllerFn($scope, $http) {
        var tr1, tr2, tr3, tr4, tr5, tr6;

        tr1 = {rowLbl: '一般數據', scnAy: "region customer address truck person".split(" ")};
        tr2 = {rowLbl: '每日數據', scnAy: "order gpsDta truckAvail personAttend truckReturn".split(" ")};
        tr3 = {rowLbl: '系統運算', scnAy: "loadPlan loadSheet loadSts".split(" ")};
        tr4 = {rowLbl: '預計', scnAy: "planTrucks planPerson planSts".split(" ")};
        tr5 = {rowLbl: '統計', scnAy: "pivotReport delvReport".split(" ")};
        tr6 = {rowLbl: '使用設定', scnAy: "chgPwd user userAuth resetPwd enableUsr".split(" ")};
        $scope.scnCaption = {
            region: '地區',
            customer: '客戶',
            address: '地址',
            truck: '車輛',
            person:'人員',
            order:'柯打',
            gpsDta:'車輛GPS數據',
            truckAvail:'可用更輛',
            personAttend:'出勤人員',
            truckReturn:'回車',
            loadPlan: '當日載貨計劃',
            loadSheet:'載貨紙生成',
            loadSts:'當日出貨狀態',
            planPerson: '預計人員',
            planTrucks: '預計車輛',
            planSts: '預計出貨狀態',
            delvReport: '車輛到達統計',
            pivotReport: '柯打+出貨樞紐分析',
            chgPwd:'改密碼',
            user: '系統使用者',
            userAuth:'使用者權限',
            resetPwd:'密碼解鎖',
            enableUsr:'停止使用者'
        }
        $scope.rows = [tr1, tr2, tr3, tr4, tr5, tr6]
        $scope.goScn = function goScn() {
            var par = $scope.$parent.$parent;
            par.scnNm = this.s;
            console.log("menu-->" + this.s);
        }
    }
})(window.angular)