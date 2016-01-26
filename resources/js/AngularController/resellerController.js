angular.module('controller.Reseller', ['services.Reseller']).
        controller('resellerController', function ($scope, resellerService) {
            $scope.resellerInfo = {};
            $scope.resellerList = [];
            $scope.serviceList = [];
            $scope.setResellerList = function (resellerList) {
                $scope.resellerList = JSON.parse(resellerList);
            }
            $scope.setServiceList = function (serviceList) {
                $scope.serviceList = JSON.parse(serviceList);
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


