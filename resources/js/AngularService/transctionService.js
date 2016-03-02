angular.module('services.Transction', []).
        factory('transctionService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            var transctionService = {};

            transctionService.bkash = function (bkashInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/transaction/bkash',
                    data: {
                        bkashInfo: bkashInfo
                    }
                });
            }
            transctionService.dbbl = function (dbblInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/transaction/dbbl',
                    data: {
                        dbblInfo: dbblInfo
                    }
                });
            }
            transctionService.mCash = function (mCashInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/transaction/mcash',
                    data: {
                        mCashInfo: mCashInfo
                    }
                });
            }
            transctionService.uCash = function (uCashInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/transaction/ucash',
                    data: {
                        uCashInfo: uCashInfo
                    }
                });
            }
            transctionService.topUp = function (topUpInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/transaction/topup',
                    data: {
                        topUpInfo: topUpInfo
                    }
                });
            }
            transctionService.getAllHistory = function (startDate, endDate) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/all_transactions',
                    data: {
                        fromDate: startDate,
                        toDate : endDate
                    }
                });
            }
            transctionService.getPaymentHistory = function (searchParam) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/get_payment_history',
                    data: {
                        searchParam: searchParam
                    }
                });
            }
            transctionService.getPaymentHistoryByPagination = function (offset) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/get_payment_history',
                    data: {
                        offset: offset
                    }
                });
            }
            transctionService.getReceiveHistory = function (searchParam) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/get_receive_history',
                    data: {
                        searchParam: searchParam
                      
                    }
                });
            }
            transctionService.getReceiveHistoryByPagination = function (offset) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/get_receive_history',
                    data: {
                        offset: offset
                    }
                });
            }
            transctionService.getTransctionByPagination = function (offset) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/all_transactions',
                    data: {
                        offset: offset
                    }
                });
            }
            return transctionService;
        });

