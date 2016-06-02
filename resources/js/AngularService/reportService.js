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
           
            return reportService;
        });

