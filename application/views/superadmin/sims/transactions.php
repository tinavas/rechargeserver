<script>
    $(function () {
        $('#start_date').Zebra_DatePicker();
        $('#end_date').Zebra_DatePicker();
    });
    function get_transaction_list() {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getSimTranscationList(startDate, endDate);
    }
</script>

<div class="panel panel-default" ng-controller="simController">
    <div class="panel-heading">Transactions</div>

    <div class="panel-body">
        <div class="row form-group">
            <div class="col-md-2">
                <input id="start_date" type="text"  placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin customInput">
            </div>
            <div class="col-md-2">
                <input id="end_date" type="text"  placeholder="End Date"  name="from" class="form-control input-xs customInputMargin customInput"> 
            </div>
            <div class="col-md-2">
                <input id="search_submit_btn" ng-model="search" type="submit"  value="Search" onclick="get_transaction_list()" class="custom_button customInput"> 
            </div>
        </div>
        <div class="row form-group"></div>
        <div class="row col-md-12" style="margin-left: 1px;">            
            <div class="row" ng-init="setTransctionList(<?php echo htmlspecialchars(json_encode($transction_list)) ?>)">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="table_row_style">
                                <th style="text-align: center;">Transaction Id</th>
                                <th style="text-align: center;">Number</th>
                                <th style="text-align: center;">Amount</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: center;">Date</th>
                            </tr>
                            <tr ng-repeat=" transctionInfo in transctionList">
                                <th style="text-align: center;">{{transctionInfo.transctionId}}</th>
                                <th style="text-align: center;">{{transctionInfo.number}}</th>
                                <th style="text-align: center;">{{transctionInfo.amount}}</th>
                                <th style="text-align: center;">{{transctionInfo.status}}</th>
                                <th style="text-align: center;">{{transctionInfo.date}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

