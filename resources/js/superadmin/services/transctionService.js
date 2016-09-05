angular.module('service.Transction', []).
        factory('transctionService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            //var $app_name = "";
            var transctionService = {};
            transctionService.updateTransction = function (transactionInfo) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/transaction/update_transaction/' + transactionInfo.transaction_id,
                    data: {
                        transactionInfo: transactionInfo
                    }
                });
            };
            transctionService.loadBalance = function (balanceInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/transaction/add_service_balance/',
                    data: {
                        balanceInfo: balanceInfo
                    }
                });
            };
            transctionService.getTransactionList = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/transaction/get_transaction_list/',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            };
            transctionService.getTransactionByPagination = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/transaction/get_transaction_list/',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            };
            return transctionService;
        });

