angular.module('controller.Transction', ['service.Transction']).
        controller('transctionController', function ($scope, transctionService) {
            $scope.currentPage = 1;
            $scope.pageSize = 10;
            $scope.transactionInfo = {};
            $scope.searchInfo = {};
            $scope.loadBalanceInfo = {};
            $scope.serviceList = [];
            $scope.transctionInfoList = [];
            $scope.transactionStatusList = [];
            $scope.simList = [];
//            $scope.serviceRateList = [];
            $scope.allow_action = true;
            $scope.allTransactions = false;
            $scope.setTransactionStatusList = function (transactionStatusList) {
                $scope.transactionStatusList = JSON.parse(transactionStatusList);
                $scope.searchInfo.statusId = 0;
            };
            $scope.setSimList = function (simList) {
                $scope.simList = JSON.parse(simList);
            };
            $scope.setTransactionProcessTypeList = function (transactionProcessTypeList) {
                $scope.transactionProcessTypeList = JSON.parse(transactionProcessTypeList);
                $scope.searchInfo.processId = 0;
            };
            $scope.setTransctionList = function (transctionList, collectionCounter) {
                $scope.transctionInfoList = JSON.parse(transctionList);
                setCollectionLength(collectionCounter);
            }
            $scope.getTransactionByPagination = function (num) {
                $scope.searchInfo.offset = getOffset(num);
                transctionService.getTransactionByPagination($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                        });
            };
            $scope.setTransactionInfo = function (transactionInfo) {
                $scope.transactionInfo = JSON.parse(transactionInfo);
            }
            $scope.setTansactionStatusList = function (transactionStatusList) {
                $scope.transactionStatusList = JSON.parse(transactionStatusList);
            }

            $scope.updateTransction = function (callbackFunction) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                transctionService.updateTransction($scope.transactionInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_action = true;
                            callbackFunction(data);
                        });
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
            $scope.getTransactionList = function (startDate, endDate) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                $scope.searchInfo.limit = $scope.allTransactions;
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                transctionService.getTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                            $scope.allow_action = true;
                        });
            };

            //pagination
            function getOffset(number) {
                var initIndex;
                initIndex = $scope.pageSize * (number - 1);
                return initIndex;
            }


        });


