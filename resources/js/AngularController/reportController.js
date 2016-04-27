angular.module('controller.Report', ['services.Report']).
        controller('reportController', function ($scope, reportService) {
            $scope.profitList = [];
            $scope.userProfits = [];
            $scope.userTotalProfitInfo = {};
            $scope.setUserProfits = function (userProfits) {
                $scope.userProfits = JSON.parse(userProfits);
                $scope.userTotalProfitInfo.usertotalUsedAmount  = 0;
                $scope.userTotalProfitInfo.userTotalProfit  = 0;
                 angular.forEach($scope.userProfits, function (profit) {
                   $scope.userTotalProfitInfo.usertotalUsedAmount =  $scope.userTotalProfitInfo.usertotalUsedAmount + +profit.total_used_amount;
                   $scope.userTotalProfitInfo.userTotalProfit =  $scope.userTotalProfitInfo.userTotalProfit + +profit.total_profit;
                   
                });
            }

            $scope.setProfitList = function (profitList) {
                $scope.profitList = JSON.parse(profitList);
            }
        });


