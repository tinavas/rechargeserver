angular.module('services.Reseller', []).
        factory('resellerService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            var resellerService = {};
            resellerService.createReseller = function (resellerInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/reseller/create_reseller',
                    data: {
                        resellerInfo: resellerInfo
                    }
                });
            }

            return resellerService;
        });

