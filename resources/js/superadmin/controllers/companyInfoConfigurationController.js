angular.module('controller.CompanyInfoConfiguration', ['service.CompanyInfoConfiguration'])

        .controller('CompanyInfoConfigurationController', function($scope, companyInfoConfigurationService) {
            $scope.companyInfo = {};

            $scope.addCompanyInfo = function(callbackFunction) {
                companyInfoConfigurationService.addCompanyInfo($scope.companyInfo).
                        success(function(data, status, headers, config) {
                            callbackFunction(data);
                        });
            };
        });
        