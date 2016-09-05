var smsFileUploadController = angular.module('controller.SmsFileUpload', ['services.Transction', 'ngSanitize', 'ngCsv', 'angularFileUpload']).
    controller('smsFileUploadController', function ($scope, transctionService, $filter, FileUploader) {
        $scope.smsInfo = {};
        $scope.allow_transction = true;
        $scope.sendSMS = function (callbackFunction) {
            if ($scope.allow_transction == false) {
                return;
            }
            $scope.allow_transction = false;
            transctionService.sendSMS($scope.transactionSMSDataList, $scope.smsInfo).
                    success(function (data, status, headers, config) {
                        $scope.allow_transction = true;
                        callbackFunction(data);
                    });

        };
        
        
        
        //import csv file 
        var smsUploader = $scope.uploader = new FileUploader({
            url: 'http://localhost/rechargeserver/files/smsFileUpload'
        });

        // FILTERS

        smsUploader.filters.push({
            name: 'customFilter',
            fn: function (item /*{File|FileLikeObject}*/, options) {
                return this.queue.length < 10;
            }
        });

        // CALLBACKS

        smsUploader.onWhenAddingFileFailed = function (item /*{File|FileLikeObject}*/, filter, options) {
        };
        smsUploader.onAfterAddingFile = function (fileItem) {
        };
        smsUploader.onAfterAddingAll = function (addedFileItems) {
        };
        smsUploader.onBeforeUploadItem = function (item) {
        };
        smsUploader.onProgressItem = function (fileItem, progress) {
        };
        smsUploader.onProgressAll = function (progress) {
        };
        smsUploader.onSuccessItem = function (fileItem, response, status, headers) {
        };
        smsUploader.onErrorItem = function (fileItem, response, status, headers) {
            //$scope.transactionDataList = [];
        };
        smsUploader.onCancelItem = function (fileItem, response, status, headers) {
        };
        smsUploader.onCompleteItem = function (fileItem, response, status, headers) {
        };
        smsUploader.onCompleteAll = function () {
        };
        
        
        $scope.transactionSMSDataList = [];
        $scope.handler = function (e, files) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var string = reader.result;
                var fileData = $filter('csvToArray')(string);

                if (fileData[0].errorExist != true) {
                    $scope.transactionSMSDataList = fileData;
                }
                //do what you want with obj !
            }
            reader.readAsText(files[0]);
        };
        $scope.deleteSMSTransction = function (transactionInfo) {
            var index = $scope.transactionSMSDataList.indexOf(transactionInfo);
            $scope.transactionSMSDataList.splice(index, 1);
        }
    });
    
    smsFileUploadController.filter('csvToArray', function () {
    return function (input) {
        var rows = input.split('\r\n');
        var transactionDataList = [];
        //remove file header 
        rows.splice(0, 1);
        angular.forEach(rows, function (val, index) {
            var trmpIndex = index + +2;
            if (val != "") {
                var row = val.split(',');
                var tempObj = {};
                angular.forEach(row, function (key, element) {
                    if (element == 0) {
                        var tempNumber = 0 + key;
                        var regexp = /^((^\880|0)[1][1|6|7|8|9])[0-9]{8}$/;
                        var validPhoneNumber = tempNumber.match(regexp);
                        if (validPhoneNumber) {
                            tempObj.number = tempNumber;
                        } else {
                            transactionDataList.unshift({errorExist: true});
                            alert("Please Eenter a vaild Phone number at your csv file row number " + trmpIndex);
                        }

                    }
                });
                if (tempObj.number != "") {
                    transactionDataList.push(tempObj);
                }
            }
        });

        return transactionDataList;
    };



});