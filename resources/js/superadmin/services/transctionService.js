angular.module('service.Transction', []).
        factory('transctionService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            //var $app_name = "";
            var transctionService = {};
            transctionService.updateTransction = function (transctionInfo) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/transaction/update_transaction/' + transctionInfo.transactionId ,
                    data: {
                        transctionInfo : transctionInfo
                    }
                });
            };
            transctionService.addSim = function (simInfo) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/sim/add_sim/' ,
                    data: {
                        simInfo : simInfo
                    }
                });
            };
            transctionService.editSim = function (simInfo) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/sim/edit_sim/'+simInfo.sim_no ,
                    data: {
                        simInfo : simInfo
                    }
                });
            };
            transctionService.loadBalance = function (balanceInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/transaction/add_service_balance/' ,
                    data: {
                        balanceInfo : balanceInfo
                    }
                });
            };
            transctionService.getSimServiceList = function (simNumber) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/transaction/get_sim_service_list/' ,
                    data: {
                        simNumber : simNumber
                    }
                });
            };
            transctionService.getSimTranscationList = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/transaction/get_sim_transactions/' ,
                    data: {
                        searchInfo : searchInfo
                    }
                });
            };
            return transctionService;
        });

