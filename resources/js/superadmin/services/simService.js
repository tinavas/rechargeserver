angular.module('service.Sim', []).
        factory('simService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            //var $app_name = "";
            var simService = {};

            simService.addSim = function (simInfo) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/sim/add_sim/',
                    data: {
                        simInfo: simInfo
                    }
                });
            };
            simService.editSim = function (simInfo) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/sim/edit_sim/' + simInfo.simNo,
                    data: {
                        simInfo: simInfo
                    }
                });
            };
            simService.updateBalance = function (simNo) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/sim/get_sim_balance/' + simNo,
                    data: {
                        simNo: simNo
                    }
                });
            };

            simService.getSimServiceList = function (simNumber) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/transaction/get_sim_service_list/',
                    data: {
                        simNumber: simNumber
                    }
                });
            };
            simService.getSimTranscationList = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/transaction/get_sim_transactions/',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            };
            simService.getSMSList = function (searchInfo) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/sim/get_sms_list/',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            };
            simService.getSMSByPagination = function (searchInfo) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/sim/get_sms_list/',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            };
            return simService;
        });

