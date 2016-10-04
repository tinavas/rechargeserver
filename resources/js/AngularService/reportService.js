angular.module('services.Report', []).
        factory('reportService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            //var $app_name = "";
            var reportService = {};

            reportService.bkash = function (bkashInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/transaction/bkash',
                    data: {
                        bkashInfo: bkashInfo
                    }
                });
            }
            reportService.getDetailRepotHistory = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/report/get_detailed_report',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            }
            reportService.getRepotHistory = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/report/get_total_report',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            }
            reportService.getBkashTransactionList = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/report/get_total_report',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            }
            reportService.getProfitLossHistory = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/report/get_user_profit_loss',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            }
           
            return reportService;
        });

