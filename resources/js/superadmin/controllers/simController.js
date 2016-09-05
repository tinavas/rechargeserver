angular.module('controller.Sim', ['service.Sim']).
        controller('simController', function ($scope, simService) {

            $scope.simInfo = {};
            $scope.serviceList = [];
            $scope.simCategoryList = [];
            $scope.simList = [];
            $scope.simServiceList = [];
            $scope.allow_action = true;

            $scope.setSimInfo = function (simInfo) {
                $scope.simInfo = JSON.parse(simInfo);
            };
            $scope.setSimServiceList = function (simServiceList) {
                $scope.simServiceList = JSON.parse(simServiceList);
            };
            $scope.setSimList = function (simList) {
                $scope.simList = JSON.parse(simList);
            };
            $scope.setServiceList = function (serviceList) {
                $scope.serviceList = JSON.parse(serviceList);
            };
            $scope.setSimCategoryList = function (simCategoryList) {
                $scope.simCategoryList = JSON.parse(simCategoryList);
            };
            $scope.checkAll = function () {
                if ($scope.selectedAll) {
                    $scope.selectedAll = true;
                } else {
                    $scope.selectedAll = false;
                }
                angular.forEach($scope.serviceList, function (service) {
                    if (service.status != 0 || service.status != "0") {
                        service.selected = $scope.selectedAll;
                    }
                }, $scope.serviceList);
            };
            $scope.toggleSelection = function toggleSelection(service) {
                var serviceIndex = $scope.serviceList.indexOf(service);
                $scope.serviceList[serviceIndex] = service;
            };

            $scope.addSim = function (simInfo, callbackFunction) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                $scope.simInfo = simInfo;
//                if (typeof $scope.simInfo.selectedIdList == "undefined") {
//                    $scope.simInfo.selectedIdList = [];
//                }
//                angular.forEach($scope.serviceList, function (service) {
//                    if (service.selected == true) {
//                        $scope.simInfo.selectedIdList.push(service.service_id);
//                    }
//                });
                simService.addSim($scope.simInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_action = true;
                            callbackFunction(data);
                        });
            };
            $scope.editSim = function (simInfo, callbackFunction) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                $scope.simInfo = simInfo;
                simService.editSim($scope.simInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_action = true;
                            callbackFunction(data);
                        });
            };

            $scope.getSimServiceList = function (simInfo, callbackFunction) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                simService.getSimServiceList(simInfo).
                        success(function (data, status, headers, config) {
                            $scope.serviceList = data.service_list;
                            $scope.allow_action = true;
                            callbackFunction();
                        });
            };
            $scope.getSimTranscationList = function (startDate, endDate) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.startDate = startDate;
                    $scope.searchInfo.endDate = endDate;
                }
                simService.getSimTranscationList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionList = data.transction_list;
                            $scope.allow_service_action = true;
                        });
            }

        });


