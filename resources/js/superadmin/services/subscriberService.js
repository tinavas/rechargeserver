angular.module('service.Subscriber', []).
        factory('subscriberService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            var subscriberService = {};
            subscriberService.createSubscriber = function (subscriberInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/subscriber/create_subscriber',
                    data: {
                        subscriberInfo : subscriberInfo
                    }
                });
            }
            subscriberService.updateSubscriber = function (subscriberId, subscriberInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/subscriber/update_subscriber/' + subscriberId,
                    data: {
                        subscriberInfo : subscriberInfo
                    }
                });
            }
           

            return subscriberService;
        });

