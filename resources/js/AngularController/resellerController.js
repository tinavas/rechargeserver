angular.module('controller.Reseller', ['services.Reseller']).
        controller('resellerController', function ($scope, resellerService) {
            $scope.resellerInfo = {};
            $scope.resellerList = [];
            $scope.serviceList = [];
            $scope.serviceRateList = [];
            $scope.allow_reseller_action = true;
            $scope.setResellerInfo = function (resellerInfo) {
                $scope.resellerInfo = JSON.parse(resellerInfo);
            }
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
                        $scope.resellerInfo.selected_service_id_list.push(service.service_id);
                    }
                });
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                resellerService.createReseller($scope.resellerInfo).
                        success(function (data, status, headers, config) {
                            angular.forEach($scope.serviceList, function (service) {
                                service.selected = false;
                            });
                            $scope.allow_reseller_action = true;
                            callbackFunction(data);
                        });
            }

            $scope.updateReseller = function (callbackFunction) {
                angular.forEach($scope.serviceList, function (service) {
                    if (typeof $scope.resellerInfo.selected_service_id_list == "undefined") {
                        $scope.resellerInfo.selected_service_id_list = [];
                    }
                    if (service.selected == true) {
                        $scope.resellerInfo.selected_service_id_list.push(service.service_id);
                    }
                });
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                resellerService.updateReseller($scope.resellerInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_reseller_action = true;
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
                console.log(service);
            };




        });


