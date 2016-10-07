angular.module('service.User', []).
    factory('userService', function ($http, $location) {
        var $app_name = "/rechargeserver/";
        var userService = {};
        userService.updateUser = function (userInfo) {
            return $http({
                method: 'post',
                url: $location.path() + $app_name + 'superadmin/user/update_user',
                data: {
                    userInfo : userInfo
                }
            });
        };
        return userService;
    });

