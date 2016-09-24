angular.module('controller.Reseller', ['services.Reseller']).
        controller('resellerController', function ($scope, resellerService) {
            $scope.resellerInfo = {};
            $scope.profileInfo = {};
            $scope.resellerList = [];
            $scope.serviceList = [];
            $scope.accountStatusList = [];
            $scope.serviceRateList = [];
            $scope.allow_reseller_action = true;
            $scope.topup_service_allow_flag = false;
            $scope.createResellerInitialize = function (pin) {
                $scope.resellerInfo.pin = pin;
                $scope.resellerInfo.init_balance = 0;
            }
            $scope.setResellerInfo = function (resellerInfo) {
                $scope.resellerInfo = JSON.parse(resellerInfo);
            }
            $scope.setProfileInfo = function (profileInfo) {
                $scope.profileInfo = JSON.parse(profileInfo);
            }
            $scope.setResellerList = function (resellerList) {
                $scope.resellerList = JSON.parse(resellerList);
            }
            $scope.setServiceList = function (serviceList) {
                $scope.serviceList = JSON.parse(serviceList);
            }
            $scope.setAccountStatusList = function (accountStatusList) {
                $scope.accountStatusList = JSON.parse(accountStatusList);
            }
            $scope.setServiceRateList = function (serviceRateList) {
                $scope.serviceRateList = JSON.parse(serviceRateList);
            }
            $scope.getUserServiceList = function () {
                resellerService.getUserServiceList().
                        success(function (data, status, headers, config) {
                            if (typeof data.service_list != "undefined") {
                                $scope.serviceList = data.service_list;
                            }
                            if (typeof data.topup_service_allow_flag != "undefined") {
                                $scope.topup_service_allow_flag = data.topup_service_allow_flag;
                            }
                        });

            }
            $scope.createReseller = function (callbackFunction) {

                angular.forEach($scope.serviceList, function (service) {
                    if (typeof $scope.resellerInfo.selected_service_id_list == "undefined") {
                        $scope.resellerInfo.selected_service_id_list = [];
                    }
                    if (service.selected == true) {
                        $scope.resellerInfo.selected_service_id_list.push(service.service_id);
                    }
                });
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                resellerService.createReseller($scope.resellerInfo).
                        success(function (data, status, headers, config) {
                            angular.forEach($scope.serviceList, function (service) {
                                service.selected = false;
                            });
                            $scope.allow_reseller_action = true;
                            callbackFunction(data);
                        });
            }

            $scope.updateReseller = function (callbackFunction) {
                angular.forEach($scope.serviceList, function (service) {
                    if (typeof $scope.resellerInfo.selected_service_id_list == "undefined") {
                        $scope.resellerInfo.selected_service_id_list = [];
                    }
                    if (service.selected == true) {
                        $scope.resellerInfo.selected_service_id_list.push(service.service_id);
                    }
                });
                if ($scope.allow_transction == false) {
                    return;
                }
                $scope.allow_transction = false;
                resellerService.updateReseller($scope.resellerInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_reseller_action = true;
                            callbackFunction(data);
                        });
            }
            
            $scope.updateUserProfile = function (callbackFunction) {
                resellerService.updateUserProfile($scope.resellerInfo).
                        success(function (data, status, headers, config) {
                            callbackFunction(data);
                        });
            }

            $scope.updateRate = function (userId, callbackFunction) {
                var updateServiceList = [];
                angular.forEach($scope.serviceRateList, function (serviceRate) {
                    if (serviceRate.enable == true) {
                        updateServiceList.push(serviceRate);
                    }
                });
                if (updateServiceList.length < 1) {
                    return;
                }
                resellerService.updateServiceRate(userId, updateServiceList).success(function (data, status, headers, config) {
                    callbackFunction(data);
                });
            }
            $scope.checkAllSmsVerifications = function () {
                if ($scope.allSmsVerifications) {
                    $scope.allSmsVerifications = true;
                } else {
                    $scope.allSmsVerifications = false;
                }
                angular.forEach($scope.serviceRateList, function (service) {
                    service.sms_enable = $scope.allSmsVerifications;
                }, $scope.serviceRateList);
            };
            $scope.checkAllEmailVerifications = function () {
                if ($scope.allEmailVerifications) {
                    $scope.allEmailVerifications = true;
                } else {
                    $scope.allEmailVerifications = false;
                }
                angular.forEach($scope.serviceRateList, function (service) {
                    service.email_enable = $scope.allEmailVerifications;
                }, $scope.serviceRateList);
            };
            $scope.checkallbox = function () {
                if ($scope.selectedAll) {
                    $scope.selectedAll = true;
                } else {
                    $scope.selectedAll = false;
                }
                angular.forEach($scope.serviceRateList, function (service) {
                    service.enable = $scope.selectedAll;
                }, $scope.serviceRateList);
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
            $scope.toggleSelectionRate = function toggleSelection(serviceRate) {
                var serviceIndex = $scope.serviceRateList.indexOf(serviceRate);
                $scope.serviceRateList[serviceIndex] = serviceRate;
            };




        });


