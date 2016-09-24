<script type="text/javascript">
    function search_report_history() {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getRepotHistory(startDate, endDate);
    }
    $(function () {
        $('#start_date').Zebra_DatePicker();
        $('#start_date').val('<?php echo $current_date ?>');
        $('#end_date').Zebra_DatePicker();
        $('#end_date').val('<?php echo $current_date ?>');
        $('#payment_type').val('0');
    });
</script>
<div class="ezttle"><span class="text">Total Report</span></div>
<div class="mypage" ng-controller="reportController" ng-init="setTransactionStatusList('<?php echo htmlspecialchars(json_encode($transction_status_list)) ?>')">
    <div class="top10">&nbsp;</div>
    <ng-form>
        <ul class="list-unstyled paymentHistorySearch" ng-init="setServiceIdList('<?php echo htmlspecialchars(json_encode($service_list)) ?>')">
            <li>Start Date</li>
            <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>End Date</li>
            <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>Status Type</li>
            <li>
                 <select  ng-model='searchInfo.statusId' required ng-options='transactionStatus.id as transactionStatus.title for transactionStatus in transactionStatusList' class="form-control input-xs"></select>
            </li>
            <li>Service </li>
            <li>
                 <select  ng-model='searchInfo.serviceId' required ng-options='service.id as service.title for service in serviceList' class="form-control input-xs"></select>
            </li>
            <li>Show All</li>
            <li> <input type="checkbox" ng-model="allTransactions"></li>
            <li><input id="search_submit_btn" ng-model="search" type="submit" size="18" value="Search" onclick="search_report_history()" class="button-custom"></li>
        </ul>
    </ng-form>
    <table class="table table-striped table-hover" ng-init="setProfitList(<?php echo htmlspecialchars(json_encode($profit_list)) ?>, <?php echo htmlspecialchars(json_encode($total_transactions)) ?>)">
        <thead>
            <tr>
                <th><a href="">Id</a></th>
                <th><a href="">Service</a></th>
                <th><a href="">Number</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Commission</a></th>
                <th><a href="">Used By</a></th>
                <th><a href="">Date</a></th>
                <th><a href="">Status</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr ng-repeat="profitInfo in profitList">
                <th>{{profitInfo.transaction_id}}</th>
                <th>{{profitInfo.service_title}}</th>
                <th>{{profitInfo.cell_no}}</th>
                <th>{{profitInfo.rate}}</th>
                <th>{{profitInfo.amount}}</th>
                <th>{{profitInfo.username}}</th>                
                <th>{{profitInfo.created_on}}</th>
                <th ng-if="profitInfo.status_id == '<?php echo TRANSACTION_STATUS_ID_PENDING; ?>'">Pending</th>
                <th ng-if="profitInfo.status_id == '<?php echo TRANSACTION_STATUS_ID_PROCESSED; ?>'">Processed</th>
                <th ng-if="profitInfo.status_id == '<?php echo TRANSACTION_STATUS_ID_SUCCESSFUL; ?>'">Success</th>
            </tr>
        </tfoot>
    </table>
     <li style="display: none" dir-paginate="profitInfo in profitList|itemsPerPage:pageSize" current-page="currentPage"></li>
    <div class="other-controller">
        <div class="text-center">
            <dir-pagination-controls boundary-links="true" on-page-change="getProfitByPagination(newPageNumber)" template-url="<?php echo base_url(); ?>history/pagination_tmpl_load"></dir-pagination-controls>
        </div>
    </div>
</div> 
