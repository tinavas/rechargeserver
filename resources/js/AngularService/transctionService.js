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
            return transctionService;
        });

