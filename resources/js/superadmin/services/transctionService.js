angular.module('service.Transction', []).
        factory('transctionService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            var transctionService = {};
            transctionService.updateTransction = function (transctionInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/transaction/update_transaction/' + transctionInfo.transactionId ,
                    data: {
                        transctionInfo : transctionInfo
                    }
                });
            }
            return transctionService;
        });

