angular.module('controller.Reseller', ['services.Reseller']).
        controller('resellerController', function ($scope, resellerService) {
            $scope.resellerInfo = {};
            $scope.resellerList = [];
            $scope.serviceList = [];
            $scope.serviceRateList = [];
            $scope.setResellerList = function (resellerList) {
                $scope.resellerList = JSON.parse(resellerList);
            }
            $scope.setServiceList = function (serviceList) {
                $scope.serviceList = JSON.parse(serviceList);
            }
            $scope.setServiceRateList = function (serviceRateList) {
                $scope.serviceRateList = JSON.parse(serviceRateList);
                console.log($scope.serviceRateList);
            }
            $scope.createReseller = function (callbackFunction) {
                angular.forEach($scope.serviceList, function (service) {
                    if (typeof $scope.resellerInfo.selected_service_id_list == "undefined") {
                        $scope.resellerInfo.selected_service_id_list = [];
                    }
                    if (service.selected == true) {
                        $scope.resellerInfo.selected_service_id_list.push(service.id);
                    }
                });
                resellerService.createReseller($scope.resellerInfo).
                        success(function (data, status, headers, config) {
                            callbackFunction(data);
                        });
            }

            $scope.updateRate = function () {
//                console.log($scope.serviceRateList);
                var updateServiceList = [];
                angular.forEach($scope.serviceRateList, function (serviceRate) {
                    if (serviceRate.enable == true) {
                        updateServiceList.push(serviceRate);
                    }
                });
                console.log(updateServiceList);


            }
            $scope.checkallbox = function () {
                if ($scope.selectedAll) {
                    $scope.selectedAll = true;
                } else {
                    $scope.selectedAll = false;
                }
                angular.forEach($scope.serviceRateList, function (service) {
                    service.enable = $scope.selectedAll;
                });
            };

            $scope.checkAll = function () {
                if ($scope.selectedAll) {
                    $scope.selectedAll = true;
                } else {
                    $scope.selectedAll = false;
                }
                angular.forEach($scope.serviceList, function (service) {
                    service.selected = $scope.selectedAll;
                });
            };

            $scope.toggleSelection = function toggleSelection(service) {
                var serviceIndex = $scope.serviceList.indexOf(service);
                $scope.serviceList[serviceIndex] = service;
            };




        });


