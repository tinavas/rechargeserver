angular.module('services.Payment', []).
        factory('paymentService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            var paymentService = {};
            paymentService.createPayment = function (userId, paymentInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/payment/create_payment/'+ userId,
                    data: {
                        paymentInfo: paymentInfo
                    }
                });
            }
            paymentService.loadBalance = function (paymentInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/admin/load_balance',
                    data: {
                        paymentInfo: paymentInfo
                    }
                });
            }
            paymentService.returnBalance = function (paymentInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/reseller/return_balance',
                    data: {
                        paymentInfo: paymentInfo
                    }
                });
            }

            return paymentService;
        });

