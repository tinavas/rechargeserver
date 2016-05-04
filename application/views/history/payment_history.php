<script type="text/javascript">
    function search_payment_history() {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getPaymentHistory(startDate, endDate);
    }
    $(function () {
        $('#start_date').Zebra_DatePicker();
        $('#end_date').Zebra_DatePicker();
    });
</script>


<div class="loader"></div>
<div class="ezttle"><span class="text">Payment History</span>
    <span class="acton"></span>
</div>

<div ng-controller="transctionController" class="mypage">
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
            <li>Show All</li>
            <li> <input type="checkbox" ng-model="allTransactions"></li>
            <li><input id="search_submit_btn" ng-model="search" type="submit" size="18" value="Search" onclick="search_payment_history()" class="button-custom"></li>
        </ul>
    </ng-form>
    <table id="" class="table table-striped table-hover" ng-init="setPaymentInfoList(<?php echo htmlspecialchars(json_encode($payment_info_list)) ?>, <?php echo htmlspecialchars(json_encode($total_transactions)) ?>, <?php echo htmlspecialchars(json_encode($total_amount)) ?>)">
        <thead>
            <tr>
                <th><a href="">Amount</a></th>
                <th><a href="">Payment Type</a></th>
                <th><a href="">Reference User</a></th>
                <th><a href="">Description</a></th>
                <th><a href=""> Date</a></th>
            </tr>
        </thead>
        <tbody>
        <li style="display: none" dir-paginate="paymentInfo in paymentInfoList|itemsPerPage:pageSize" current-page="currentPage"></li>
        <tr ng-repeat="paymentInfo in paymentInfoList">
            <th>{{paymentInfo.balance_out}}</th>
            <th>
                <span ng-if="paymentInfo.type_id == '<?php echo PAYMENT_TYPE_ID_SEND_CREDIT ?>'">
                    Credit Transfer
                </span>
                <span ng-if="paymentInfo.type_id == '<?php echo PAYMENT_TYPE_ID_RETURN_CREDIT ?>'">
                    Credit return to Parent
                </span>

            </th>
            <th>{{paymentInfo.first_name}} {{paymentInfo.last_name}}</th>
            <th>{{paymentInfo.description}}</th>
            <th>{{paymentInfo.created_on}}</th>
        </tr>
        </tbody>
    </table>
    <div class="form-group">
        <div class="col-md-12 fleft">
            <div class="summery">
                <p>Summary</p>
                <table>
                    <tbody>
                        <tr><td>Current Page Payment :</td><td class="amt">{{currentPageAmount}}</td></tr>
                        <tr><td>Total Payment :</td><td class="amt">{{totalAmount}}</td></tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>

    <div class="other-controller">
        <div class="text-center">
            <dir-pagination-controls boundary-links="true" on-page-change="getPaymentHistoryByPagination(newPageNumber)" template-url="<?php echo base_url(); ?>history/pagination_tmpl_load"></dir-pagination-controls>
        </div>
    </div>
</div>
<div class="row"></div>
<div class="row"></div>
<div class="row"></div>


