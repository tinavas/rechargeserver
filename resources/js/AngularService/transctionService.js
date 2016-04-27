angular.module('services.Transction', []).
        factory('transctionService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            var transctionService = {};

            transctionService.sendSMS = function (transactionDataList, smsInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/transaction/sms',
                    data: {
                        transactionDataList: transactionDataList,
                        smsInfo: smsInfo
                    }
                });
            }

            transctionService.multipuleTopup = function (transactionDataList) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/transaction/multipule_topups',
                    data: {
                        transactionDataList: transactionDataList
                    }
                });
            }
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
            };
            transctionService.getAllHistory = function (searchParam) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/all_transactions',
                    data: {
                        searchParam: searchParam
                    }
                });
            };
            transctionService.getPaymentHistory = function (searchParam) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/get_payment_history',
                    data: {
                        searchParam: searchParam
                    }
                });
            };
            transctionService.getPaymentHistoryByPagination = function (offset) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/get_payment_history',
                    data: {
                        offset: offset
                    }
                });
            };
            transctionService.getReceiveHistory = function (searchParam) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/get_receive_history',
                    data: {
                        searchParam: searchParam

                    }
                });
            };
            transctionService.getTopupTransactionList = function (searchParam) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/topup_transactions',
                    data: {
                        searchParam: searchParam

                    }
                });
            };
            transctionService.getBkashTransactionList = function (searchParam) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/bkash_transactions',
                    data: {
                        searchParam: searchParam

                    }
                });
            };
            
            transctionService.getDBBLTransactionList = function (searchParam) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/dbbl_transactions',
                    data: {
                        searchParam: searchParam
                    }
                });
            };
            transctionService.getMcashTransactionList = function (searchParam) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/mcash_transactions',
                    data: {
                        searchParam: searchParam
                    }
                });
            };
            transctionService.getUcashTransactionList = function (searchParam) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/history/ucash_transactions',
                    data: {
                        searchParam: searchParam
                    }
                });
            };
            return transctionService;
        });

