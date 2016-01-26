angular.module('controller.Transction', ['services.Transction']).
        controller('transctionController', function ($scope, transctionService) {
            $scope.bkashInfo = {};
            $scope.dbblInfo = {};
            $scope.mCashInfo = {};
            $scope.uCashInfo = {};
            $scope.transctionList = [];
            $scope.topupTypeList = [];
            $scope.topupOperatorList = [];

            $scope.bkash = function (callbackFunction) {
                transctionService.bkash($scope.bkashInfo).
                        success(function (data, status, headers, config) {
                            callbackFunction(data);
                        });
            };
            $scope.dbbl = function (callbackFunction) {
                transctionService.dbbl($scope.dbblInfo).
                        success(function (data, status, headers, config) {
                            callbackFunction(data);
                        });
            };
            $scope.mCash = function (callbackFunction) {
                transctionService.mCash($scope.mCashInfo).
                        success(function (data, status, headers, config) {
                            callbackFunction(data);
                        });
            };
            $scope.uCash = function (callbackFunction) {
                transctionService.uCash($scope.uCashInfo).
                        success(function (data, status, headers, config) {
                            callbackFunction(data);
                        });
            };

            $scope.setTransctionList = function (transctionList) {
                $scope.transctionList = JSON.parse(transctionList);
            }
            $scope.setTopUpTypeList = function (topupTypeList) {
                $scope.topupTypeList = JSON.parse(topupTypeList);
                console.log($scope.topupTypeList);
            }
            $scope.setTopupOperatorList = function (topupOperatorList) {
                $scope.topupOperatorList = JSON.parse(topupOperatorList);
            }

        });


