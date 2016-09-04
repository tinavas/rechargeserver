angular.module('service.Service', []).
        factory('serviceService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            var serviceService = {};
            serviceService.createService = function (serviceInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/service/create_service',
                    data: {
                        serviceInfo : serviceInfo
                    }
                });
            }
            serviceService.updateService = function (serviceInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/service/update_service/' + serviceInfo.id,
                    data: {
                        serviceInfo : serviceInfo
                    }
                });
            }
           

            return serviceService;
        });

