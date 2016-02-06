angular.module('controller.Transction', ['services.Transction']).
        controller('transctionController', function ($scope, transctionService) {
            $scope.bkashInfo = {};
            $scope.dbblInfo = {};
            $scope.mCashInfo = {};
            $scope.uCashInfo = {};
            $scope.topUpInfo = {};
            $scope.transctionList = [];
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


