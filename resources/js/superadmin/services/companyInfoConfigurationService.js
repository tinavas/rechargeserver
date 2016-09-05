angular.module('service.CompanyInfoConfiguration', []).
        factory('companyInfoConfigurationService', function($http, $location) {
            var $app_name = "/rechargeserver";
            var companyInfoConfigurationService = {};

            companyInfoConfigurationService.addCompanyInfo = function(companyInfo) {
                return $http({
                    method: 'post',
                    data: {
                       companyInfo: companyInfo
                    }
                });
            };
            return companyInfoConfigurationService;
        });

