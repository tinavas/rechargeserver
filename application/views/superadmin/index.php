<script type="text/javascript">
    function get_service_rank_list_by_volumn() {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getServiceRankListByVolumn(startDate, endDate);
    }
    function get_service_profit_list() {
        var startDate = $("#start_date_profit").val();
        var endDate = $("#end_date_profit").val();
        angular.element($("#profit_search_submit_btn")).scope().getServiceProfitList(startDate, endDate);
    }
    function get_top_customer() {
        var startDate = $("#start_date_customer").val();
        var endDate = $("#end_date_customer").val();
        angular.element($("#customer_search_submit_btn")).scope().getTopCustomer(startDate, endDate);
    }
    $(function () {
        $('#start_date').Zebra_DatePicker();
        $('#end_date').Zebra_DatePicker();
        $('#start_date_customer').Zebra_DatePicker();
        $('#end_date_customer').Zebra_DatePicker();
        $('#start_date_profit').Zebra_DatePicker();
        $('#end_date_profit').Zebra_DatePicker();
    });
</script>


<div class="dashBoardBackground" ng-controller="historyController">
    <div class="row form-group">
        <div class="col-md-12">
            <div class="tableHeaderTitle borderFull padding_5px">
                Today's Status
            </div>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-12">
            <div class="maxWindow form-group">
                <?php if (isset($summary_info)) { ?>
                    <span ng-init="setSummaryInfo('<?php echo htmlspecialchars(json_encode($summary_info)) ?>')">
                    </span>
                <?php } ?>
                <div class="textBold borderWithoutTop padding_5px whiteBackground">
                    <div class="row col-md-12">

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="borderRight">Total Amount Used</div>
                        </div>
                        <div class="col-md-4">
                            <div class="borderRight marginLeft">Total Profit</div>
                        </div>
                        <div class="col-md-4">
                            <div class="borderRight marginLeft">Total Resellers</div>
                        </div>
                    </div>
                </div>
                <div class="textBold borderWithoutTop padding_5px" >
                    <div class="row">
                        <div class="col-md-4">
                            <div class="borderRight">{{summaryInfo.total_amount}}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="borderRight marginLeft">{{summaryInfo.total_profit}}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="marginLeft">{{summaryInfo.total_subscriber}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4">
            <div class="paglet">
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="textBold">Service Rank List</div>
                    </div>
                </div>
                <div class="row">
                    <?php if (isset($service_volumn_rank_list)) { ?>  
                        <span ng-init="setServiceRankList(<?php echo htmlspecialchars(json_encode($service_volumn_rank_list)) ?>)"></span>
                    <?php } ?>
                    <div class="col-md-12">
                        <div class="row form-group">
                            <div class="col-md-4">
                                <input id="start_date" type="text"  placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin customInput">
                            </div>
                            <div class="col-md-4">
                                <input id="end_date" type="text"  placeholder="End Date"  name="from" class="form-control input-xs customInputMargin customInput"> 
                            </div>
                            <div class="col-md-4">
                                <input id="search_submit_btn" ng-model="search" type="submit"  value="Search" onclick="get_service_rank_list_by_volumn()" class="custom_button customInput"> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="cutomerAcdYToday">
                                        <?php
                                        $this->load->view("superadmin/dashboard/service_rank_list");
                                        ?>
                                    </div>
                                </div> 
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="paglet">
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="textBold">Top 10 Customers </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row form-group">
                            <div class="col-md-3">
                                <input id="start_date_customer" type="text"  placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin customInput">
                            </div>
                            <div class="col-md-3">
                                <input id="end_date_customer" type="text"  placeholder="End Date"  name="from" class="form-control input-xs customInputMargin customInput"> 
                            </div>
                            <div class="col-md-4">
                                <select name="repeatSelect" id="repeatSelect" ng-model="searchInfo.serviceId">
                                    <option  value="">Please select</option>
                                    <option  value="<?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?>">Bkash</option>
                                    <option  value="<?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>">DBBL</option>
                                    <option  value="<?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>">M-Cash</option>
                                    <option  value="<?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>">U-Cash</option>
                                    <option  value="<?php echo SERVICE_TYPE_ID_TOPUP_GP; ?>">Topup-GP</option>
                                    <option  value="<?php echo SERVICE_TYPE_ID_TOPUP_ROBI; ?>">Topup-Robi</option>
                                    <option  value="<?php echo SERVICE_TYPE_ID_TOPUP_BANGLALINK; ?>">Topup-BanglaLink</option>
                                    <option  value="<?php echo SERVICE_TYPE_ID_TOPUP_AIRTEL; ?>">Topup-Airtel</option>
                                    <option  value="<?php echo SERVICE_TYPE_ID_TOPUP_TELETALK; ?>">Topup-Teletalk</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input id="customer_search_submit_btn" ng-model="search" type="submit"  value="Search" onclick="get_top_customer()" class="custom_button customInput"> 
                            </div>
                        </div>
                        <div class="row">
                            <?php if (isset($top_customer_list)) { ?>  
                                <span ng-init="setTopCustomerList(<?php echo htmlspecialchars(json_encode($top_customer_list)) ?>)"></span>
                            <?php } ?>
                            <div class="col-md-12">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="cutomerAcdYToday">
                                        <?php
                                        $this->load->view("superadmin/dashboard/top_customers");
                                        ?>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="paglet">
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="textBold">Profit wise services</div>
                    </div>
                </div>
                <div class="row  form-group">
                    <?php if (isset($profit_rank_list)) { ?>  
                        <span ng-init="setProfitRankList(<?php echo htmlspecialchars(json_encode($profit_rank_list)) ?>)"></span>
                    <?php } ?>
                    <div class="col-md-12">
                        <div class="row form-group">
                            <div class="col-md-4">
                                <input id="start_date_profit" type="text"  placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin customInput">
                            </div>
                            <div class="col-md-4">
                                <input id="end_date_profit" type="text"  placeholder="End Date"  name="from" class="form-control input-xs customInputMargin customInput"> 
                            </div>
                            <div class="col-md-4">
                                <input id="profit_search_submit_btn" ng-model="search" type="submit"  value="Search" onclick="get_service_profit_list()" class="custom_button customInput"> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="cutomerAcdYToday">
                                        <?php
//                                        $this->load->view("superadmin/dashboard/profit_wise_services");
                                        ?>
                                        <div google-chart chart="chart"
                                             style="border:1px inset black;padding:0;width:400px">
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
