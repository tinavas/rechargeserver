angular.module('controller.Left', ['services.Reseller']).
        controller('leftController', function ($scope, resellerService) {
            $scope.resellerList = [];
            $scope.serviceList = [];
            $scope.setServiceList = function (serviceList) {
                $scope.serviceList = JSON.parse(serviceList);
            }
            $scope.getUserServiceList = function () {
                resellerService.getUserServiceList().
                        success(function (data, status, headers, config) {
                            if (typeof data.service_list != "undefined") {
                                $scope.serviceList = data.service_list;
                            }
                            if (typeof data.topup_service_allow_flag != "undefined") {
                                $scope.topup_service_allow_flag = data.topup_service_allow_flag;
                            }
                        });

            }


        });


