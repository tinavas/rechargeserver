angular.module('controller.Subscriber', ['service.Subscriber']).
        controller('subscriberController', function ($scope, subscriberService) {
            $scope.subscriberInfo = {};
//            $scope.resellerList = [];
            $scope.subscriberList = [];
//            $scope.serviceRateList = [];
            $scope.allow_service_action = true;

            $scope.setSubscriberList = function (subscriberList) {
                $scope.subscriberList = JSON.parse(subscriberList);
            }
            $scope.setSubscriberInfo = function (subscriberInfo) {
                $scope.subscriberInfo = JSON.parse(subscriberInfo);
                console.log($scope.subscriberInfo);
            }

            $scope.createSubscriber = function (callbackFunction) {
                if ($scope.allow_service_action == false) {
                    return;
                }
                $scope.allow_service_action = false;
                subscriberService.createSubscriber($scope.subscriberInfo).
                        success(function (data, status, headers, config) {
                            console.log(data);
                            $scope.allow_service_action = true;
                            callbackFunction(data);
                        });
            }
            $scope.updateSubscriber = function (subscriberId, callbackFunction) {
                if ($scope.allow_service_action == false) {
                    return;
                }
                $scope.allow_service_action = false;
                subscriberService.updateSubscriber(subscriberId, $scope.subscriberInfo).
                        success(function (data, status, headers, config) {

                            $scope.allow_service_action = true;
                            callbackFunction(data);
                        });
            }



        });


