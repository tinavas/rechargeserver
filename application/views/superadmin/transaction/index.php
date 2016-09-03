<script type="text/javascript">
    $(function () {
        $('#start_date').Zebra_DatePicker();
        $('#end_date').Zebra_DatePicker();
    });
</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">TopUp History</span></div>
<div class="mypage" ng-controller="transctionController">
    <ul class="list-unstyled topUpHistorySearch">
        <li>Start Date</li>
        <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
        <li>End Date</li>
        <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
        <li>Status Type</li>
        <li> <select name="repeatSelect" id="repeatSelect" ng-model="searchInfo.statusId">
                <option  value="">Please select</option>
                <option  value="<?php echo TRANSACTION_STATUS_ID_SUCCESSFUL; ?>">Success</option>
                <option  value="<?php echo TRANSACTION_STATUS_ID_PENDING; ?>">Pending</option>
                <option  value="<?php echo TRANSACTION_STATUS_ID_FAILED; ?>">Failed</option>
                <option  value="<?php echo TRANSACTION_STATUS_ID_CANCELLED; ?>">Canceled</option>
            </select>
        </li>
        <li>Show All</li>
        <li> <input type="checkbox" ng-model="allTransactions"></li>
        <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="search_bkash()" class="button-custom"></li>
    </ul>
    <table class="table table-striped table-hover"> 
        <thead>
            <tr>
                <th><a href="">Sender</a></th>
                <th><a href="">Number</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Status</a></th>
                <th><a href="">Trans.ID</a></th>
                <th><a href="">Date</a></th>
                <th><a href="">Action</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot ng-init="setTransctionList('<?php echo htmlspecialchars(json_encode($transction_list)) ?>')">

            <tr ng-repeat="transctionInfo in transctionInfoList">
                <th>{{transctionInfo.sender_cell_no}}</th>
                <th>{{transctionInfo.cell_no}}</th>
                <th>{{transctionInfo.amount}}</th>
                <th>{{transctionInfo.status}}</th>
                <th>{{transctionInfo.transaction_id}}</th>
                <th>{{transctionInfo.created_on}}</th>
                <th>{{transctionInfo.created_on}}</th>
            </tr>
        </tfoot>
    </table>
</div>
