var transactionController = angular.module('controller.Transction', ['services.Transction', 'ngSanitize', 'ngCsv', 'angularFileUpload']).
        controller('transctionController', function ($scope, transctionService, $filter, FileUploader) {
            $scope.currentPage = 1;
            $scope.pageSize = 3;
            $scope.bkashInfo = {};
            $scope.dbblInfo = {};
            $scope.mCashInfo = {};
            $scope.uCashInfo = {};
            $scope.topUpInfo = {};
            $scope.paymentTypeIds = [];
            $scope.transctionList = [];
            $scope.transctionInfoList = [];
            $scope.transactionDataList = [];
            $scope.paymentInfoList = [];
            $scope.paymentSearchList = [];
            $scope.topupTypeList = [];
            $scope.topupOperatorList = [];
            $scope.topupDataList = [];
            $scope.allow_transction = true;
            $scope.totalAmount = 0;
            $scope.currentPageAmount = 0;
            $scope.allTransactions = false;
            $scope.searchInfo = {};
            $scope.smsInfo = {};
            $scope.bkash = function (callbackFunction) {
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                transctionService.bkash($scope.bkashInfo).
                        success(function (data, status, headers, config) {
                            callbackFunction(data);
                            $scope.allow_transction = true;
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

            $scope.addTopUpData = function () {
                $scope.transactionDataList.push($scope.topUpInfo);
                $scope.topUpInfo = {};
            };
            $scope.addSMSData = function () {
                $scope.transactionDataList.push({"number": $scope.smsInfo.number});
                $scope.smsInfo.number = "";
            };

            $scope.appendTopUpInfo = function (topUpInfo, requestFunction) {
                $scope.topupDataList.push(topUpInfo);
                requestFunction($scope.topupDataList);
            }


            $scope.setTransctionDataList = function (transactionList) {
                $scope.transactionDataList = JSON.parse(transactionList);
            };

            $scope.sendSMS = function (callbackFunction) {
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                transctionService.sendSMS($scope.transactionDataList, $scope.smsInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_transction = true;
                            callbackFunction(data);
                        });
            };
            $scope.setTopUpData = function (topEmptyList) {
                $scope.topupDataList = [];

            };

            function getCurrentPagePayment() {
                var currentPageAmount = 0;
                for (var i = 0; i < $scope.paymentInfoList.length; i++) {
                    currentPageAmount = currentPageAmount + +$scope.paymentInfoList[i].balance_out;
                }
                $scope.currentPageAmount = currentPageAmount;
            }
            $scope.setPaymentInfoList = function (paymentInfoList, collectionCounter, totalAmount) {
                $scope.totalAmount = totalAmount;
                $scope.paymentInfoList = JSON.parse(paymentInfoList);
                getCurrentPagePayment();
                setCollectionLength(collectionCounter);
            };
            function getCurrentPageReceivePayment() {
                var currentPageAmount = 0;
                for (var i = 0; i < $scope.paymentInfoList.length; i++) {
                    currentPageAmount = currentPageAmount + +$scope.paymentInfoList[i].balance_in;
                }
                $scope.currentPageAmount = currentPageAmount;
            }
            $scope.setReceiveInfoList = function (paymentInfoList, collectionCounter, totalAmount) {
                $scope.totalAmount = totalAmount;
                $scope.paymentInfoList = JSON.parse(paymentInfoList);
                getCurrentPageReceivePayment();
                setCollectionLength(collectionCounter);
            };
            $scope.getPaymentHistory = function (startDate, endDate) {
                if ($scope.allTransactions != false) {
                    $scope.searchInfo.limit = $scope.allTransactions;
                }
                ;
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                if (typeof $scope.paymentType != "undefined" && $scope.paymentType.key != "") {
                    $scope.searchInfo.paymentTypeId = $scope.paymentType.key;
                }
                transctionService.getPaymentHistory($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.paymentInfoList = data.payment_info_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPagePayment();
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                        });
            };

            $scope.getReceiveHistory = function (startDate, endDate) {
                if ($scope.allTransactions != false) {
                    $scope.searchInfo.limit = $scope.allTransactions;
                }
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                if (typeof $scope.paymentType != "undefined" && $scope.paymentType.key != "") {
                    $scope.searchInfo.paymentTypeId = $scope.paymentType.key;
                }
                transctionService.getReceiveHistory($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.paymentInfoList = data.payment_info_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageReceivePayment();
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                        });
            };

            $scope.getTopupTransactionList = function (startDate, endDate, userId) {
                if ($scope.allTransactions != false) {
                    $scope.searchInfo.limit = $scope.allTransactions;
                }
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                if (userId != "" && userId != 0) {
                    $scope.searchInfo.userId = userId;
                }
                if (typeof $scope.paymentType != "undefined" && $scope.paymentType.key != "") {
                    $scope.searchInfo.paymentTypeId = $scope.paymentType.key;
                }
                transctionService.getTopupTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                        });
            };
            $scope.getBkashTransactionList = function (startDate, endDate, userId) {
                if ($scope.allTransactions != false) {
                    $scope.searchInfo.limit = $scope.allTransactions;
                }
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                if (userId != "" && userId != 0) {
                    $scope.searchInfo.userId = userId;
                }
                if (typeof $scope.paymentType != "undefined" && $scope.paymentType.key != "") {
                    $scope.searchInfo.paymentTypeId = $scope.paymentType.key;
                }
                transctionService.getBkashTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                        });
            };


            $scope.getDBBLTransactionList = function (startDate, endDate, userId) {
                if ($scope.allTransactions != false) {
                    $scope.searchInfo.limit = $scope.allTransactions;
                }
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                if (userId != "" && userId != 0) {
                    $scope.searchInfo.userId = userId;
                }
                if (typeof $scope.paymentType != "undefined" && $scope.paymentType.key != "") {
                    $scope.searchInfo.paymentTypeId = $scope.paymentType.key;
                }
                transctionService.getDBBLTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                        });
            };
            $scope.getMcashTransactionList = function (startDate, endDate, userId) {
                if ($scope.allTransactions != false) {
                    $scope.searchInfo.limit = $scope.allTransactions;
                }
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                if (userId != "" && userId != 0) {
                    $scope.searchInfo.userId = userId;
                }
                if (typeof $scope.paymentType != "undefined" && $scope.paymentType.key != "") {
                    $scope.searchInfo.paymentTypeId = $scope.paymentType.key;
                }
                transctionService.getMcashTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                        });
            };

            $scope.getUcashTransactionList = function (startDate, endDate, userId) {
                if ($scope.allTransactions != false) {
                    $scope.searchInfo.limit = $scope.allTransactions;
                }
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                if (userId != "" && userId != 0) {
                    $scope.searchInfo.userId = userId;
                }
                if (typeof $scope.paymentType != "undefined" && $scope.paymentType.key != "") {
                    $scope.searchInfo.paymentTypeId = $scope.paymentType.key;
                }
                transctionService.getUcashTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                        });
            };
            $scope.getSMSTransactionList = function (startDate, endDate, userId) {
                if ($scope.allTransactions != false) {
                    $scope.searchInfo.limit = $scope.allTransactions;
                }
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                if (userId != "" && userId != 0) {
                    $scope.searchInfo.userId = userId;
                }
                if (typeof $scope.paymentType != "undefined" && $scope.paymentType.key != "") {
                    $scope.searchInfo.paymentTypeId = $scope.paymentType.key;
                }
                transctionService.getSMSTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageSMSAmount();
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                        });
            };




            $scope.getPaymentHistoryByPagination = function (num) {
                $scope.searchInfo.offset = getOffset(num);
                transctionService.getPaymentHistory($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            if (typeof data.payment_info_list != "undefined") {
                                $scope.paymentInfoList = data.payment_info_list;
                                $scope.totalAmount = data.total_amount;
                                getCurrentPagePayment();
                            }
                        });
            };
            $scope.getReceiveHistoryByPagination = function (num) {
                $scope.searchInfo.offset = getOffset(num);
                transctionService.getReceiveHistory($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            if (typeof data.payment_info_list != "undefined") {
                                $scope.paymentInfoList = data.payment_info_list;
                                $scope.totalAmount = data.total_amount;
                                getCurrentPageReceivePayment();
                            }
                        });
            };

            $scope.getTopupByPagination = function (num, userId) {
                $scope.searchInfo.offset = getOffset(num);
                $scope.searchInfo.userId = userId;
                transctionService.getTopupTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                        });
            };
            $scope.getBkashByPagination = function (num, userId) {
                $scope.searchInfo.offset = getOffset(num);
                $scope.searchInfo.userId = userId;
                transctionService.getBkashTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                        });
            };
            $scope.getDBBLByPagination = function (num, userId) {
                $scope.searchInfo.offset = getOffset(num);
                $scope.searchInfo.userId = userId;
                transctionService.getDBBLTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                        });
            };
            $scope.getMcashByPagination = function (num, userId) {
                $scope.searchInfo.offset = getOffset(num);
                $scope.searchInfo.userId = userId;
                transctionService.getMcashTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                        });
            };
            $scope.getUcashByPagination = function (num, userId) {
                $scope.searchInfo.offset = getOffset(num);
                $scope.searchInfo.userId = userId;
                transctionService.getUcashTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                        });
            };

            $scope.getSMSByPagination = function (num, userId) {
                $scope.searchInfo.offset = getOffset(num);
                $scope.searchInfo.userId = userId;
                transctionService.getSMSTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageSMSAmount();
                        });
            };

            $scope.pageChangeHandler = function (num) {
                $scope.searchInfo.offset = getOffset(num);
                transctionService.getPaymentHistory($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            if (typeof data.payment_info_list != "undefined") {
                                $scope.paymentInfoList = data.payment_info_list;
                                $scope.totalAmount = data.total_amount;
                                getCurrentPagePayment();
                            }
                        });
            };



// return pagination collection initital index;
            function getOffset(number) {
                var initIndex;
                initIndex = $scope.pageSize * (number - 1);
                return initIndex;
            }
            function getCurrentPageTransctionAmount() {
                var currentPageAmount = 0;
                for (var i = 0; i < $scope.transctionInfoList.length; i++) {
                    currentPageAmount = currentPageAmount + +$scope.transctionInfoList[i].amount;
                }
                $scope.currentPageAmount = currentPageAmount;
            }
            $scope.setTransactionInfoList = function (transctionList, collectionCounter, totalAmount) {
                $scope.transctionInfoList = JSON.parse(transctionList);
                $scope.totalAmount = totalAmount;
                getCurrentPageTransctionAmount();
                setCollectionLength(collectionCounter);
            };

            function getCurrentPageSMSAmount() {
                var currentPageAmount = 0;
                for (var i = 0; i < $scope.transctionInfoList.length; i++) {
                    currentPageAmount = currentPageAmount + +$scope.transctionInfoList[i].unit_price;
                }
                $scope.currentPageAmount = currentPageAmount;
            }
            $scope.setSMSTransactionInfoList = function (transctionList, collectionCounter, totalAmount) {
                $scope.transctionInfoList = JSON.parse(transctionList);
                $scope.totalAmount = totalAmount;
                getCurrentPageSMSAmount();
                setCollectionLength(collectionCounter);
            };

            $scope.getAllHistory = function (startDate, endDate) {
                if ($scope.allTransactions != false) {
                    $scope.searchInfo.limit = $scope.allTransactions;
                }
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
//                if (typeof $scope.paymentType != "undefined" && $scope.paymentType.key != "") {
//                    $scope.searchInfo.paymentTypeId = $scope.paymentType.key;
//                }
                transctionService.getAllHistory($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                        });
            };
            $scope.getTransctionByPagination = function (num) {
                $scope.searchInfo.offset = getOffset(num);
                transctionService.getAllHistory($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            if (typeof data.transaction_list != "undefined") {
                                $scope.transctionInfoList = data.transaction_list;
                                $scope.totalAmount = data.total_amount;
                                getCurrentPageTransctionAmount();
                            }
                        });

            };

            $scope.setPaymentTypeIds = function (paymentTypeIds) {
                $scope.paymentTypeIds = JSON.parse(paymentTypeIds);
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



            $scope.multipuleTopup = function (callbackFunction) {
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                transctionService.multipuleTopup($scope.transactionDataList).
                        success(function (data, status, headers, config) {
                            $scope.allow_transction = true;
                            callbackFunction(data);
                        });

            }

            $scope.deleteTransction = function (transactionInfo) {
                var index = $scope.transactionDataList.indexOf(transactionInfo);
                $scope.transactionDataList.splice(index, 1);
            };


            $scope.PhoneNumberValidityCheck = function (phoneNumber) {
//                var phonenumber = "01623598606";
                var regexp = /^((^\+880|0)[1][1|6|7|8|9])[0-9]{8}$/;
                var validPhoneNumber = phoneNumber.match(regexp);
                if (validPhoneNumber) {
                    return true;
                }
                return false;
            };



// pagination next data
            $scope.pageChangeHandler = function (num) {
                var initIndex = getOffset(num);

                $scope.searchInfo.offset = initIndex;
                transctionService.getPaymentHistory($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            var tempCollectionLength = 0;
                            var collectionIndex = 0;
                            if (typeof data.payment_info_list != "undefined") {
                                $scope.paymentInfoList = data.payment_info_list;
                                $scope.totalAmount = data.total_amount;
                                getCurrentPagePayment();
                            }
                        });
            };

// return pagination collection initital index;
            function getOffset(number) {
                var initIndex;
                initIndex = $scope.pageSize * (number - 1);
                return initIndex;
            }

            $scope.rows = ['Row 1', 'Row 2'];

            $scope.counter = 3;

            $scope.addRow = function () {

                $scope.rows.push('Row ' + $scope.counter);
                $scope.counter++;
            }


        });


