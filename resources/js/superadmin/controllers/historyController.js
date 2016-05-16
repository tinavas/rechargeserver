angular.module('controller.History', ['service.History']).
        controller('historyController', function ($scope, historyService) {
            $scope.summaryInfo = {};
            $scope.searchInfo = {};
            $scope.serviceRankList = [];
            $scope.topCustomerList = [];
            $scope.profitList = [];
            $scope.allow_service_action = true;
            var chart1 = {};

            $scope.setSummaryInfo = function (summaryInfo) {
                $scope.summaryInfo = JSON.parse(summaryInfo);
            }
            $scope.setServiceRankList = function (serviceRankList) {
                $scope.serviceRankList = JSON.parse(serviceRankList);
            }
            $scope.setTopCustomerList = function (topCustomerList) {
                $scope.topCustomerList = JSON.parse(topCustomerList);
            }
            $scope.setProfitRankList = function (profitList) {
                $scope.profitList = JSON.parse(profitList);
                angular.forEach($scope.profitList, function (profitInfo, value) {
                    chart1.data.push([profitInfo.service, profitInfo.service_percentage]);
                });
            }



            $scope.getServiceRankListByVolumn = function (startDate, endDate) {
                if ($scope.allow_service_action == false) {
                    return;
                }
                $scope.allow_service_action = false;
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.startDate = startDate;
                    $scope.searchInfo.endDate = endDate;
                }
                historyService.getServiceRankListByVolumn($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.serviceRankList = data.service_volumn_rank_list;
                            $scope.allow_service_action = true;
                        });
            }
            $scope.getServiceProfitList = function (startDate, endDate) {
                if ($scope.allow_service_action == false) {
                    return;
                }
                $scope.allow_service_action = false;
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.startDate = startDate;
                    $scope.searchInfo.endDate = endDate;
                }
                console.log($scope.searchInfo);
                historyService.getServiceProfitList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.profitList = data.profit_rank_list;
                            chart1.data = [
                                ['service', 'Service_percentage']
                            ];
                            angular.forEach($scope.profitList, function (profitInfo, value) {
                                chart1.data.push([profitInfo.service, profitInfo.service_percentage]);
                            });
                        });
            }
            $scope.getTopCustomer = function (startDate, endDate) {
                if ($scope.allow_service_action == false) {
                    return;
                }
                $scope.allow_service_action = false;
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.startDate = startDate;
                    $scope.searchInfo.endDate = endDate;
                }
                historyService.getTopCustomer($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.topCustomerList = data.top_customer_list;
                            $scope.allow_service_action = true;
                        });
            }



            chart1.type = "PieChart";

            chart1.data = [
                ['service', 'Service_percentage']
            ];
            chart1.options = {
                displayExactValues: true,
                width: 400,
                height: 200,
                is3D: true,
                chartArea: {left: 10, top: 10, bottom: 0, height: "100%"}
            };

            chart1.formatters = {
                number: [{
                        columnNum: 1,
                        pattern: ""
                    }]
            };
            console.log(chart1.data);

            $scope.chart = chart1;




        });


