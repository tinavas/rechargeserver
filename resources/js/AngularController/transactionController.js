var transactionController = angular.module('controller.Transction', ['services.Transction', 'ngSanitize', 'ngCsv', 'angularFileUpload']).
        controller('transctionController', function ($scope, transctionService, $filter, FileUploader) {
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
            $scope.topupDataList = [];
            $scope.allow_transction = true;

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

            $scope.appendTopUpInfo = function (topUpInfo, requestFunction) {
                $scope.topupDataList.push(topUpInfo);
                requestFunction($scope.topupDataList);
                console.log($scope.topupDataList);
            }


            $scope.setTopUpData = function (topEmptyList) {
                $scope.topupDataList = [];
                console.log($scope.topupDataList);
            }
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
            
            
            
            //import csv file 
            var uploader = $scope.uploader = new FileUploader({
                url: 'http://localhost/rechargeserver/files/upload'
            });

            // FILTERS

            uploader.filters.push({
                name: 'customFilter',
                fn: function (item /*{File|FileLikeObject}*/, options) {
                    return this.queue.length < 10;
                }
            });

            // CALLBACKS

            uploader.onWhenAddingFileFailed = function (item /*{File|FileLikeObject}*/, filter, options) {
            };
            uploader.onAfterAddingFile = function (fileItem) {
            };
            uploader.onAfterAddingAll = function (addedFileItems) {
            };
            uploader.onBeforeUploadItem = function (item) {
            };
            uploader.onProgressItem = function (fileItem, progress) {
            };
            uploader.onProgressAll = function (progress) {
            };
            uploader.onSuccessItem = function (fileItem, response, status, headers) {
            };
            uploader.onErrorItem = function (fileItem, response, status, headers) {
                $scope.transactionDataList = [];
            };
            uploader.onCancelItem = function (fileItem, response, status, headers) {
            };
            uploader.onCompleteItem = function (fileItem, response, status, headers) {
            };
            uploader.onCompleteAll = function () {
            };


            $scope.transactionDataList = [];
            /*$scope.handler = function (e, files) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var string = reader.result;
                    var fileData = $filter('csvToArray')(string);
                    
                    if (fileData[0].errorExist != true) {
                        $scope.transactionDataList = fileData;
                    }
                    //do what you want with obj !
                }
                reader.readAsText(files[0]);
            }*/
            /*$scope.deleteTransction = function (transactionInfo) {
                var index = $scope.transactionDataList.indexOf(transactionInfo);
                $scope.transactionDataList.splice(index, 1);
            }*/


            $scope.PhoneNumberValidityCheck = function (phoneNumber) {
//                var phonenumber = "01623598606";
                var regexp = /^((^\+880|0)[1][1|6|7|8|9])[0-9]{8}$/;
                var validPhoneNumber = phoneNumber.match(regexp);
                if (validPhoneNumber) {
                    return true;
                }
                return false;
            };

//            $scope.filename = "file";
////            $scope.getArray = [{a: 1, b: 2}, {a: 3, b: 4}];
//            $scope.addRandomRow = function () {
//                $scope.transactionDataList.push({a: Math.floor((Math.random() * 10) + 1), b: Math.floor((Math.random() * 10) + 1)});
//            };
//            $scope.getHeader = function () {
//                return ["number", "amount", "operator id", "operator type id"]
//            };
//            $scope.clickFn = function () {
//                console.log("click click click");
//            };

            
        });

/*transactionController.filter('csvToArray', function () {
    return function (input) {
        var rows = input.split('\r\n');
        var transactionDataList = [];
        //remove file header 
        rows.splice(0, 1);
        //remove example data if given while providing csv file 
//        rows.splice(0, 1);
        //convert file rows to array 
        angular.forEach(rows, function (val, index) {
            var trmpIndex = index + +3;
            if (val != "") {
                var row = val.split(',');
                var tempObj = {};
                var phoneOperatorSelection = "0";
                angular.forEach(row, function (key, element) {
                    if (element == 0) {
                        var tempNumber = 0 + key;
                        var regexp = /^((^\+880|0)[1][1|6|7|8|9])[0-9]{8}$/;
                        var validPhoneNumber = tempNumber.match(regexp);
//                        console.log(validPhoneNumber);
                        if (validPhoneNumber) {
                            var tempOperatorType = validPhoneNumber[1];
                            var splits = tempOperatorType.split('01');
                            phoneOperatorSelection = splits[1];
                            tempObj.number = tempNumber;
                        } else {
                            transactionDataList.unshift({errorExist: true});
                            alert("Please Eenter a vaild Phone number at your csv file row number " + trmpIndex);
                        }

                    }
//                    console.log(phoneOperatorSelection);
                    if (element == 1) {
                        tempObj.amount = key;
                    }
                    if (element == 2) {
                        tempObj.topupOperatorId = key;
                    }
                    if (element == 3) {
                        tempObj.topupType = key;
                    }
                });
                if (tempObj.number != "" && tempObj.amount) {
                    transactionDataList.push(tempObj);
                }
            }
        });

        return transactionDataList;
    };



});*/

