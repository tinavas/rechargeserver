<script type="text/javascript">
    $(function () {
        $('#start_date').Zebra_DatePicker();
        $('#start_date').val('<?php echo $current_date ?>');
        $('#end_date').Zebra_DatePicker();
        $('#end_date').val('<?php echo $current_date ?>');
        $('#repeatSelect').val('<?php echo TRANSACTION_STATUS_ID_PENDING ?>');
    });
   
    function search_transaction(searchInfo) {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getTransactionList(startDate, endDate);
    }
</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">Transaction History</span></div>
<div class="mypage" ng-controller="transctionController" ng-init="setTransactionStatusList('<?php echo htmlspecialchars(json_encode($transction_status_list)) ?>')">
    <ul class="list-unstyled paymentHistorySearch">
        <li>Start Date</li>
        <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
        <li>End Date</li>
        <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
        <li>Cell No</li>
        <li> <input type="text" class="form-control input-xs customInputMargin" placeholder="017XXXXXXXX" ng-model="searchInfo.cellNo"></li>
        <li>Status Type</li>
        <li> 
            <select  ng-model='searchInfo.statusId' required ng-options='transactionStatus.id as transactionStatus.title for transactionStatus in transactionStatusList' class="form-control input-xs"></select>
        </li>
        <li ng-init="setTransactionProcessTypeList('<?php echo htmlspecialchars(json_encode($transaction_process_type_list)) ?>')">
            Process Type</li>
        <li> 
            <select  ng-model='searchInfo.processId' required ng-options='transactionProcessType.id as transactionProcessType.title for transactionProcessType in transactionProcessTypeList' class="form-control input-xs"></select>
        </li>
        <li>Show All</li>
        <li> <input type="checkbox" ng-model="allTransactions"></li>
        <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="search_transaction(angular.element(this).scope().searchInfo)" class="button-custom"></li>
    </ul>
    <table class="table table-striped table-hover"> 
        <thead>
            <tr>
                <th><a href="">Id</a></th>
                <th><a href="">Transaction Id</a></th>
                <th><a href="">User</a></th>
                <th><a href="">Title</a></th>
                <th><a href="">Sender</a></th>
                <th><a href="">Number</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Status</a></th>
                <th><a href="">Process</a></th>
                <th><a href="">Date</a></th>
                <th><a href="">Action</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot ng-init="setTransctionList('<?php echo htmlspecialchars(json_encode($transaction_list)) ?>', '<?php echo htmlspecialchars(json_encode($total_transactions)) ?>')">
            <tr ng-repeat="transctionInfo in transctionInfoList">
                <th>{{transctionInfo.transaction_id}}</th>
                <th>{{transctionInfo.trx_id_operator}}</th>
                <th>{{transctionInfo.username}}</th>
                <th>{{transctionInfo.service_title}}</th>
                <th>{{transctionInfo.sender_cell_no}}</th>
                <th>{{transctionInfo.cell_no}}</th>
                <th>{{transctionInfo.amount}}</th>
                <th>{{transctionInfo.status}}</th>
                <th ng-if="transctionInfo.process_type_id == '<?php echo TRANSACTION_PROCESS_TYPE_ID_AUTO; ?>'">Auto</th>
                <th ng-if="transctionInfo.process_type_id == '<?php echo TRANSACTION_PROCESS_TYPE_ID_MANUAL; ?>'">Manual</th>
                <th>{{transctionInfo.created_on}}</th>
                <th><a href="<?php echo base_url() . "superadmin/transaction/update_transaction/"; ?>{{transctionInfo.transaction_id}}">Edit</a></th>
            </tr>
        </tfoot>
    </table>
    <li style="display: none" dir-paginate="transactionInfo in transctionInfoList|itemsPerPage:pageSize" current-page="currentPage"></li>
    <div class="other-controller">
        <div class="text-center">
            <dir-pagination-controls boundary-links="true" on-page-change="getTransactionByPagination(newPageNumber)" template-url="<?php echo base_url(); ?>superadmin/transaction/pagination_tmpl_load"></dir-pagination-controls>
        </div>
    </div>
</div>

<?php
//$this->load->view("superadmin/transaction/modal_delete_transaction");
