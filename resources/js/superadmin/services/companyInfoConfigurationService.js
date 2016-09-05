angular.module('service.CompanyInfoConfiguration', []).
        factory('companyInfoConfigurationService', function($http, $location) {
            var $app_name = "/rechargeserver";
            var companyInfoConfigurationService = {};

            companyInfoConfigurationService.addCompanyInfo = function(companyInfo) {
                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/superadmin/company_info_configuration/add_company_info',
                    data: {
                       companyInfo: companyInfo
                    }
                });
            };
            return companyInfoConfigurationService;
        });

