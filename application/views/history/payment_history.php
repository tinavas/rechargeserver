<script>
    function search_payment_history() {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getPaymentHistory(startDate, endDate);
    }
</script>


<div class="loader"></div>
<div class="ezttle"><span class="text">Payment History</span>
    <span class="acton"></span>
</div>
<div class="mypage" ng-controller="transctionController"> 
    <ng-form>
        <ul class="list-unstyled paymentHistorySearch" ng-init="setPaymentTypeIds('<?php echo htmlspecialchars(json_encode($payment_type_ids)) ?>')">
            <li>Start Date</li>
            <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>End Date</li>
            <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>Type</li>
            <li> <select name="repeatSelect" id="repeatSelect" ng-model="paymentType.key">
                    <option  value="">Please select</option>
                    <option ng-repeat="(key, paymentType) in paymentTypeIds" value="{{key}}">{{paymentType}}</option>
                </select>
            </li>
            <li><input id="search_submit_btn" type="submit" size="18" value="Submit" onclick="search_payment_history()" class="btn btn-xs btn-default"></li>
        </ul>
    </ng-form>
    <table class="table table-striped table-hover" ng-init="setPaymentInfoList(<?php echo htmlspecialchars(json_encode($payment_info_list)) ?>)">
        <thead>
            <tr>
                <th><a href="">ID</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Payment Type</a></th>
                <th><a href="">Reference User</a></th>
                <th><a href="">Description</a></th>
                <th><a href=""> Date</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr ng-repeat="paymentInfo in paymentInfoList">
                <th>{{paymentInfo.id}}</th>
                <th>{{paymentInfo.balance_out}}</th>
                <th>
                    <span ng-if="paymentInfo.type_id == '<?php echo PAYMENT_TYPE_ID_SEND_CREDIT ?>'">
                        Credit Transfer
                    </span>
                    <span ng-if="paymentInfo.type_id == '<?php echo PAYMENT_TYPE_ID_RETURN_CREDIT ?>'">
                        Credit return to Parent
                    </span>

                </th>
                <th>{{paymentInfo.username}}</th>
                <th>{{paymentInfo.description}}</th>
                <th>{{paymentInfo.created_on}}</th>
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
<!--                        <tr><td>Total Return :</td><td class="amt">0.00</td></tr>
                        <tr><td>Total Canceled :</td><td class="amt">0.00</td></tr>
                        <tr><td>Total :</td><td class="amt">0.00</td></tr>-->
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
        <li><a ng-click="getPaymentHistoryByPagination('<?php echo INITIAL_OFFSET; ?>')">1</a></li>
        <li><a ng-click="getPaymentHistoryByPagination('<?php echo INITIAL_LIMIT; ?>')">2</a></li>
        <li><a ng-click="getPaymentHistoryByPagination('<?php echo INITIAL_LIMIT + INITIAL_LIMIT; ?>')">3</a></li>
        <li><a ng-click="getPaymentHistoryByPagination('<?php echo INITIAL_LIMIT + INITIAL_LIMIT + INITIAL_LIMIT; ?>')">4</a></li>
        <li><a ng-click="getPaymentHistoryByPagination('<?php echo INITIAL_LIMIT + INITIAL_LIMIT + INITIAL_LIMIT + INITIAL_LIMIT; ?>')">5</a></li>
        <li >
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

