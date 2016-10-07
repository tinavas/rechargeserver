angular.module('controller.User', ['service.User']).
    controller('userController', function ($scope, userService) {
        $scope.userInfo = {};
        $scope.setUserInfo = function (userInfo) {
            $scope.userInfo = JSON.parse(userInfo);
        }
        
        $scope.updateUser = function (callbackFunction) {
            userService.updateUser($scope.userInfo).
                success(function (data, status, headers, config) {
                    callbackFunction(data);
                });
        }
    });


