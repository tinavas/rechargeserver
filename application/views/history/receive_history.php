<script type="text/javascript">
    $(function () {
        $('#start_date').Zebra_DatePicker();
        $('#end_date').Zebra_DatePicker();
    });
    function search_receive_history() {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getReceiveHistory(startDate, endDate);
    }
</script>

<div class="loader"></div>
<div class="ezttle"><span class="text">Receive History</span></div>
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
            <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="search_receive_history()" class="button-custom"></li>
        </ul>
    </ng-form>
    <table class="table table-striped table-hover" ng-init="setReceiveInfoList(<?php echo htmlspecialchars(json_encode($payment_info_list)) ?>, <?php echo htmlspecialchars(json_encode($total_transactions)) ?>, <?php echo htmlspecialchars(json_encode($total_amount)) ?>)">
        <thead>
            <tr>
                <th><a href="">Amount</a></th>
                <th><a href="">Payment Type</a></th>
                <th><a href="">Reference User</a></th>
                <th><a href="">Description</a></th>
                <th><a href="">Date</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        <li style="display: none" dir-paginate="paymentInfo in paymentInfoList|itemsPerPage:pageSize" current-page="currentPage"></li>
        <tr ng-repeat="paymentInfo in paymentInfoList">
            <th>{{paymentInfo.balance_in}}</th>
            <th>
                <span ng-if="paymentInfo.type_id == '<?php echo PAYMENT_TYPE_ID_LOAD_BALANCE ?>'">
                    Load Balance
                </span>
                <span ng-if="paymentInfo.type_id == '<?php echo PAYMENT_TYPE_ID_RECEIVE_CREDIT ?>'">
                    Receive Credit
                </span>
                <span ng-if="paymentInfo.type_id == '<?php echo PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT ?>'">
                    Return Credit
                </span>

            </th>
            <th>{{paymentInfo.first_name}} {{paymentInfo.last_name}}</th>
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
                        <tr><td>Current Page Receive His:</td><td class="amt">{{currentPageAmount}}</td></tr>
                        <tr><td>Total Receive History :</td><td class="amt">{{totalAmount}}</td></tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div> 
    <div class="other-controller">
        <div class="text-center">
            <dir-pagination-controls boundary-links="true" on-page-change="getReceiveHistoryByPagination(newPageNumber)" template-url="<?php echo base_url(); ?>history/pagination_tmpl_load"></dir-pagination-controls>
        </div>
    </div>
</div>
