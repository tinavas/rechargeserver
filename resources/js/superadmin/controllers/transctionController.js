angular.module('controller.Transction', ['service.Transction']).
        controller('transctionController', function ($scope, transctionService) {
            $scope.transctionInfo = {};
            $scope.simInfo = {};
            $scope.searchInfo = {};
            $scope.loadBalanceInfo = {};
            $scope.serviceList = [];
            $scope.simList = [];
            $scope.simServiceList = [];
            $scope.transctionList = [];
//            $scope.serviceRateList = [];
            $scope.allow_action = true;


            $scope.setTransctionList = function (transctionList) {
                $scope.transctionList = JSON.parse(transctionList);
            }
            $scope.setTransctionInfo = function (transctionInfo) {
                $scope.transctionInfo = JSON.parse(transctionInfo);
            }

            $scope.updateTransction = function (callbackFunction) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                transctionService.updateTransction($scope.transctionInfo).
                        success(function (data, status, headers, config) {

                            $scope.allow_action = true;
                            callbackFunction(data);
                        });
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

                if (typeof $scope.simInfo.selectedIdList == "undefined") {
                    $scope.simInfo.selectedIdList = [];
                }
                angular.forEach($scope.serviceList, function (service) {
                    if (service.selected == true) {
                        $scope.simInfo.selectedIdList.push(service.service_id);
                    }
                });
                transctionService.addSim($scope.simInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_action = true;
                            callbackFunction(data);
                        });
            };
            $scope.editSim = function (simNumber, registrationDate, callbackFunction) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                $scope.simInfo.simNumber = simNumber;
                $scope.simInfo.registrationDate = registrationDate;

                if (typeof $scope.simInfo.selectedIdList == "undefined") {
                    $scope.simInfo.selectedIdList = [];
                }
                angular.forEach($scope.serviceList, function (service) {
                    if (service.selected == true) {
                        $scope.simInfo.selectedIdList.push(service.service_id);
                    }
                });
                transctionService.editSim($scope.simInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_action = true;
                            callbackFunction(data);
                        });
            };
            $scope.loadBalance = function (balanceInfo, callbackFunction) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                transctionService.loadBalance(balanceInfo).
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
                transctionService.getSimServiceList(simInfo).
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
                transctionService.getSimTranscationList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionList = data.transction_list;
                            $scope.allow_service_action = true;
                        });
            }


        });


