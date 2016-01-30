angular.module('controller.Payment', ['services.Payment']).
        controller('paymentController', function ($scope, paymentService) {
            $scope.paymentInfo = {};
            $scope.paymentTypeList = [];

            $scope.createPayment = function (userId, callbackFunction) {
                paymentService.createPayment(userId, $scope.paymentInfo).
                        success(function (data, status, headers, config) {
                            callbackFunction(data);
                        });
            }

            $scope.setPaymentTypeList = function (paymentTypeList) {
                $scope.paymentTypeList = JSON.parse(paymentTypeList);
                console.log($scope.paymentTypeList);
            }


        });


