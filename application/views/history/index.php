<script>
    function search_receive_history() {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getAllHistory(startDate, endDate);
    }
</script>

<div class="loader"></div>
<div class="ezttle"><span class="text"> Transaction History</span></div>
<div class="mypage" ng-controller="transctionController">
    <ul class="list-unstyled paymentHistorySearch">
        <li>Start Date</li>
        <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
        <li>End Date</li>
        <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
        <li>Type</li>
        <li><select id="type" name="type" class="form-control input-xs">
                <option value="">--Any--</option>
                <option value="transfer">Payment</option>
                <option value="return">Return</option>
                <option value="canceled">Canceled</option>
            </select>
        </li>
        <li><input id="search_submit_btn" type="submit" size="18" value="Submit" onclick="search_receive_history()" class="btn btn-xs btn-default"></li>
    </ul>
    <table class="table table-striped table-hover" ng-init="setTransactionInfoList(<?php echo htmlspecialchars(json_encode($transaction_list)) ?>)">
        <thead>
            <tr>
                <th><a href="">Service</a></th>
                <th><a href="">Sender</a></th>
                <th><a href="">Number</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Status</a></th>
                <th><a href="">Trans.ID</a></th>
                <th><a href="">Date</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr ng-repeat="transctionInfo in transctionInfoList">
                <th>{{transctionInfo.service_title}}</th>
                <th>{{transctionInfo.sender_cell_no}}</th>
                <th>{{transctionInfo.cell_no}}</th>
                <th>{{transctionInfo.amount}}</th>
                <th>{{transctionInfo.status}}</th>
                <th>{{transctionInfo.transaction_id}}</th>
                <th>{{transctionInfo.created_on}}</th>
            </tr>
        </tfoot>
    </table>
    <div class="form-group">
        <div class="col-md-12 fleft">
            <div class="summery">
                <p>Summary</p>
                <table>
                    <tbody>
                        <tr><td>Total Payment :</td><td class="amt">0.00</td></tr>
                        <tr><td>Total Return :</td><td class="amt">0.00</td></tr>
                        <tr><td>Total Canceled :</td><td class="amt">0.00</td></tr>
                        <tr><td>Total :</td><td class="amt">0.00</td></tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div> 
    <ul class="pagination pull-right">
        <li>
            <a href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <li><a ng-click="getTransctionByPagination('<?php echo INITIAL_OFFSET ;?>')">1</a></li>
        <li><a ng-click="getTransctionByPagination('<?php echo INITIAL_LIMIT;?>')">2</a></li>
        <li><a ng-click="getTransctionByPagination('<?php echo INITIAL_LIMIT + INITIAL_LIMIT ;?>')">3</a></li>
        <li><a ng-click="getTransctionByPagination('<?php echo INITIAL_LIMIT + INITIAL_LIMIT + INITIAL_LIMIT;?>')">4</a></li>
        <li><a ng-click="getTransctionByPagination('<?php echo INITIAL_LIMIT + INITIAL_LIMIT + INITIAL_LIMIT +INITIAL_LIMIT;?>')">5</a></li>
        <li>
            <a href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</div>
<script type="text/javascript">
    $(function () {
        $('#start_date').Zebra_DatePicker();
        $('#end_date').Zebra_DatePicker();
    });
</script>