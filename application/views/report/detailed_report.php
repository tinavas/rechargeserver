<script type="text/javascript">
    function search_report_history() {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getDetailRepotHistory(startDate, endDate);
    }
    $(function () {
        $('#start_date').Zebra_DatePicker();
        $('#start_date').val('<?php echo $current_date ?>');
        $('#end_date').Zebra_DatePicker();
        $('#end_date').val('<?php echo $current_date ?>');
        $('#payment_type').val('0');
    });
</script>
<div class="ezttle"><span class="text">Detailed Report</span></div>
<div class="mypage" ng-controller="reportController" ng-init="setTransactionStatusList('<?php echo htmlspecialchars(json_encode($transction_status_list)) ?>')">
    <div class="top10">&nbsp;</div>
    <ng-form>
        <ul class="list-unstyled paymentHistorySearch" ng-init="setServiceIdList('<?php echo htmlspecialchars(json_encode($service_list)) ?>')">
            <li>Reseller:</li>
            <li> <select name="" id="" ng-model="searchInfo.userId" class="form-control custom_form_control">
                    <option  value="">Please select</option>
                    <option  value="">Reseller 1</option>
                    <option value="">Reseller 1</option>
                    <option value="">Reseller 2</option>
                    <option value="">Reseller 3</option>
                </select>
            </li>
            <li>Start Date:</li>
            <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>End Date:</li>
            <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>

            <li>Service: </li>
            <li>
                <select  ng-model='searchInfo.serviceId' required ng-options='service.id as service.title for service in serviceList' class="form-control input-xs"></select>
            </li>
            <li>Status Type:</li>
            <li>
                <select  ng-model='searchInfo.statusId' required ng-options='transactionStatus.id as transactionStatus.title for transactionStatus in transactionStatusList' class="form-control input-xs"></select>
            </li>
            <li>Show All:</li>
            <li> <input type="checkbox" ng-model="allTransactions"></li>
            <li><input id="search_submit_btn" ng-model="search" type="submit" size="18" value="Search" onclick="search_report_history()" class="button-custom"></li>
        </ul>
    </ng-form>
    <table class="table table-responsive green_color_table" ng-init="setReportList(<?php echo htmlspecialchars(json_encode($report_list)) ?>, <?php echo htmlspecialchars(json_encode($report_summary)) ?>)">
        <thead>
            <tr>
                <th><a href="">Service</a></th>
                <th><a href="">TTL Request</a></th>
                <th><a href="">Pending</a></th>
                <th><a href="">Successful</a></th>
                <th><a href="">In Processed</a></th>
                <th><a href="">Failed</a></th>
                <th><a href="">% of Success</a></th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="reportInfo in reportList">
                <th>{{reportInfo.title}}</th>
                <th>{{reportInfo.total_request}}</th>
                <th>{{reportInfo.pending}}</th>
                <th>{{reportInfo.success}}</th>
                <th>{{reportInfo.processed}}</th>
                <th>{{reportInfo.failed}}</th>
                <th>{{reportInfo.per_of_success}}</th>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td><a href="">Total</a></td>
                <td>{{reportSummery.total_request}}</td>
                <td>{{reportSummery.total_request}}</td>
                <td>{{reportSummery.total_pending}}</td>
                <td>{{reportSummery.total_success}}</td>
                <td>{{reportSummery.total_processed}}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    
</div>


























