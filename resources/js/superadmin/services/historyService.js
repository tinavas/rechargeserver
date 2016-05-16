angular.module('service.History', []).
        factory('historyService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            var historyService = {};
            historyService.getServiceRankListByVolumn = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/history/get_service_volume_rank_list',
                    data: {
                        searchInfo : searchInfo
                    }
                });
            }
            historyService.getServiceProfitList = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/history/get_service_profit_rank_list',
                    data: {
                        searchInfo : searchInfo
                    }
                });
            }
            historyService.getTopCustomer = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/history/get_top_customer_list',
                    data: {
                        searchInfo : searchInfo
                    }
                });
            }
  
           

            return historyService;
        });

