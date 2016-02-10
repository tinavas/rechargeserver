angular.module('controller.Service', ['service.Service']).
        controller('serviceController', function ($scope, serviceService) {
            $scope.serviceInfo = {};
//            $scope.resellerList = [];
            $scope.serviceList = [];
//            $scope.serviceRateList = [];
            $scope.allow_service_action = true;

            $scope.setServiceList = function (serviceList) {
                $scope.serviceList = JSON.parse(serviceList);
            }
            $scope.setServiceInfo = function (serviceInfo) {
                $scope.serviceInfo = JSON.parse(serviceInfo);
            }

            $scope.createService = function (callbackFunction) {
                if ($scope.allow_service_action == false) {
                    return;
                }
                $scope.allow_service_action = false;
                serviceService.createService($scope.serviceInfo).
                        success(function (data, status, headers, config) {

                            $scope.allow_service_action = true;
                            callbackFunction(data);
                        });
            }
            $scope.updateService = function (callbackFunction) {
                if ($scope.allow_service_action == false) {
                    return;
                }
                $scope.allow_service_action = false;
                serviceService.updateService($scope.serviceInfo).
                        success(function (data, status, headers, config) {

                            $scope.allow_service_action = true;
                            callbackFunction(data);
                        });
            }



        });


