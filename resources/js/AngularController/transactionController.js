angular.module('controller.Transction', ['services.Transction']).
        controller('transctionController', function ($scope, transctionService) {
            $scope.bkashInfo = {};
            $scope.dbblInfo = {};
            $scope.mCashInfo = {};
            $scope.uCashInfo = {};
            $scope.topUpInfo = {};
            $scope.paymentTypeIds = [];
            $scope.transctionList = [];
            $scope.transctionInfoList = [];
            $scope.paymentInfoList = [];
            $scope.topupTypeList = [];
            $scope.topupOperatorList = [];
            $scope.allow_transction = true;

            $scope.bkash = function (callbackFunction) {
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                transctionService.bkash($scope.bkashInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_transction = true;
                            callbackFunction(data);
                        });
            };
            $scope.dbbl = function (callbackFunction) {
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                transctionService.dbbl($scope.dbblInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_transction = true;
                            callbackFunction(data);
                        });
            };
            $scope.mCash = function (callbackFunction) {
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                transctionService.mCash($scope.mCashInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_transction = true;
                            callbackFunction(data);
                        });
            };
            $scope.uCash = function (callbackFunction) {
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                transctionService.uCash($scope.uCashInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_transction = true;
                            callbackFunction(data);
                        });
            };
            $scope.topUp = function (callbackFunction) {
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                transctionService.topUp($scope.topUpInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_transction = true;
                            callbackFunction(data);
                        });
            };

            $scope.setPaymentInfoList = function (paymentInfoList) {
                $scope.paymentInfoList = JSON.parse(paymentInfoList);
            }
            $scope.getAllHistory = function (startDate, endDate) {
                transctionService.getAllHistory(startDate, endDate).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list
                        });
            }
            $scope.getPaymentHistory = function (startDate, endDate) {
                var searchParam = {};
                if (startDate != "" && endDate != "") {
                    searchParam.startDate = startDate;
                    searchParam.endDate = endDate;
                }
                if (typeof $scope.paymentType != "undefined" && $scope.paymentType.key != "") {
                    searchParam.paymentTypeId = $scope.paymentType.key;
                }
                transctionService.getPaymentHistory(searchParam).
                        success(function (data, status, headers, config) {
                            $scope.paymentInfoList = data.payment_info_list;
                        });


            }
            $scope.getPaymentHistoryByPagination = function (offset) {
                transctionService.getPaymentHistoryByPagination(offset).
                        success(function (data, status, headers, config) {
                            $scope.paymentInfoList = data.payment_info_list;
                        });

            }
            $scope.getReceiveHistory = function (startDate, endDate) {
                var searchParam = {};
                if (startDate != "" && endDate != "") {
                    searchParam.startDate = startDate;
                    searchParam.endDate = endDate;
                }
                if (typeof $scope.paymentType != "undefined") {
                    searchParam.paymentTypeId = $scope.paymentType.key;
                }

                transctionService.getReceiveHistory(searchParam).
                        success(function (data, status, headers, config) {
                            $scope.paymentInfoList = data.payment_info_list;
                            console.log($scope.paymentInfoList);
                        });


            }
            $scope.getReceiveHistoryByPagination = function (offset) {
                transctionService.getReceiveHistoryByPagination(offset).
                        success(function (data, status, headers, config) {
                            $scope.paymentInfoList = data.payment_info_list;
                        });

            }
            $scope.getTransctionByPagination = function (offset) {
                transctionService.getTransctionByPagination(offset).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list
                        });

            }


            $scope.setPaymentTypeIds = function (paymentTypeIds) {
                $scope.paymentTypeIds = JSON.parse(paymentTypeIds);
                console.log($scope.paymentTypeIds);
            }
            $scope.setTransctionList = function (transctionList) {
                $scope.transctionList = JSON.parse(transctionList);
            }
            $scope.setTopUpTypeList = function (topupTypeList) {
                $scope.topupTypeList = JSON.parse(topupTypeList);
            }
            $scope.setTopupOperatorList = function (topupOperatorList) {
                $scope.topupOperatorList = JSON.parse(topupOperatorList);
            }

            $scope.setTransactionInfoList = function (transctionList) {
                $scope.transctionInfoList = JSON.parse(transctionList);
            }


        });


