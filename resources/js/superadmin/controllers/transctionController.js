angular.module('controller.Transction', ['service.Transction']).
        controller('transctionController', function ($scope, transctionService) {
            $scope.transctionInfo = {};
//            $scope.resellerList = [];
            $scope.transctionList = [];
//            $scope.serviceRateList = [];
            $scope.allow_service_action = true;

            $scope.setTransctionList = function (transctionList) {
                $scope.transctionList = JSON.parse(transctionList);
            }
            $scope.setTransctionInfo = function (transctionInfo) {
                $scope.transctionInfo = JSON.parse(transctionInfo);
            }

            $scope.updateTransction = function (callbackFunction) {
                if ($scope.allow_service_action == false) {
                    return;
                }
                $scope.allow_service_action = false;
                transctionService.updateTransction($scope.transctionInfo).
                        success(function (data, status, headers, config) {

                            $scope.allow_service_action = true;
                            callbackFunction(data);
                        });
            }



        });


