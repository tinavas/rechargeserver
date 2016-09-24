angular.module('controller.Sim', ['service.Sim']).
        controller('simController', function ($scope, simService) {

            $scope.simInfo = {};
            $scope.serviceList = [];
            $scope.simCategoryList = [];
            $scope.simList = [];
            $scope.simServiceList = [];
            $scope.simStatusList = [];
            $scope.smsList = [];
            $scope.allow_action = true;
            $scope.currentPage = 1;
            $scope.pageSize = 10;
            $scope.searchInfo = {};

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
            $scope.setSimStatusList = function (simStatusList) {
                $scope.simStatusList = JSON.parse(simStatusList);
            };
            $scope.setSMSList = function (smsList, collectionCounter) {
                $scope.smsList = JSON.parse(smsList);
                setCollectionLength(collectionCounter);
            };
            $scope.getSMSList = function (startDate, endDate) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                if ($scope.searchInfo.selectAll != false) {
                    $scope.searchInfo.limit = $scope.searchInfo.selectAll;
                }
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                simService.getSMSList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.smsList = data.sms_list;
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_counter;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_counter);
                            $scope.allow_action = true;
                        });

            }
            $scope.getSIMByPagination = function (num) {
                $scope.searchInfo.offset = getOffset(num);
                simService.getSMSByPagination($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.smsList = data.sms_list;
                        });
            };
            $scope.updateBalance = function (simNumber, callbackFunction) {
                simService.updateBalance(simNumber).
                        success(function (data, status, headers, config) {
                            callbackFunction(data);
                        });
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
                if (typeof $scope.simInfo.serviceInfoList == "undefined") {
                    $scope.simInfo.serviceInfoList = [];
                }
                angular.forEach($scope.serviceList, function (service) {
                    if (service.selected == true) {
                        var serviceInfo = {};
                        serviceInfo.serviceId = service.service_id;
                        if (typeof service.categoryId == "undefined" || service.categoryId == "") {
                            service.categoryId = $scope.simCategoryList[0]['id'] + "";
                        }
                        serviceInfo.categoryId = service.categoryId;
                        serviceInfo.currentBalance = service.currentBalance;
                        $scope.simInfo.serviceInfoList.push(serviceInfo);
                    }
                });
                if (typeof $scope.simInfo.status == "undefined" || $scope.simInfo.status.categoryId == "") {
                    $scope.simInfo.status = $scope.simStatusList[0]['id'] + "";
                }
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
                if (typeof $scope.simInfo.serviceInfoList == "undefined") {
                    $scope.simInfo.serviceInfoList = [];
                }
                angular.forEach($scope.serviceList, function (service) {
                    if (service.selected == true) {
                        var serviceInfo = {};
                        serviceInfo.serviceId = service.service_id;
                        serviceInfo.categoryId = service.categoryId;
                        serviceInfo.currentBalance = service.currentBalance;
                        $scope.simInfo.serviceInfoList.push(serviceInfo);
                    }
                });
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
            //pagination
            function getOffset(number) {
                var initIndex;
                initIndex = $scope.pageSize * (number - 1);
                return initIndex;
            }

        });


