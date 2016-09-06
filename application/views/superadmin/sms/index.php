<script type="text/javascript">
    function number_validation(phoneNumber) {
        var regexp = /^((^\880|0)[1][1|5|6|7|8|9])[0-9]{8}$/;
        var validPhoneNumber = phoneNumber.match(regexp);
        if (validPhoneNumber) {
            return true;
        }
        return false;
    }
    $(function () {
        $('#start_date').Zebra_DatePicker();
        $('#start_date').val('<?php echo $current_date ?>');
        $('#end_date').Zebra_DatePicker();
        $('#end_date').val('<?php echo $current_date ?>');
    });
    function search_sms(searchInfo) {
        if (typeof searchInfo.simNo != "undefined" && searchInfo.simNo.length != 0) {
            if (number_validation(searchInfo.simNo) == false) {
                $("#content").html("Please give a valid SIM Number");
                $('#common_modal').modal('show');
                return;
            }
        }
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getSMSList(startDate, endDate);
    }
</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">Transaction History</span></div>
<div class="mypage" ng-controller="simController">
    <ul class="list-unstyled paymentHistorySearch">
        <li>Sim No</li>
        <li> <input type="text" class="form-control input-xs customInputMargin" placeholder="88017XXXXXXXX" ng-model="searchInfo.simNo"></li>
        <li>Start Date</li>
        <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
        <li>End Date</li>
        <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
        <li>Show All</li>
        <li> <input type="checkbox" ng-model="searchInfo.selectAll"></li>

        <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="search_sms(angular.element(this).scope().searchInfo)" class="button-custom"></li>
    </ul>
    <table class="table table-striped table-hover"> 
        <thead>
            <tr>
                <th><a href="">Id</a></th>
                <th><a href="">Sim Number</a></th>
                <th><a href="">Sender</a></th>
                <th><a href="">SMS</a></th>
                <th><a href="">Date</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot ng-init="setSMSList('<?php echo htmlspecialchars(json_encode($sms_list)) ?>', '<?php echo htmlspecialchars(json_encode($total_counter)) ?>')">
            <tr ng-repeat="smsInfo in smsList">
                <th>{{smsInfo.id}}</th>
                <th>{{smsInfo.simNo}}</th>
                <th>{{smsInfo.sender}}</th>
                <th>{{smsInfo.sms}}</th>
                <th>{{smsInfo.createdOn}}</th>
            </tr>
        </tfoot>
    </table>
    <li style="display: none" dir-paginate="smsInfo in smsList|itemsPerPage:pageSize" current-page="currentPage"></li>
    <div class="other-controller">
        <div class="text-center">
            <dir-pagination-controls boundary-links="true" on-page-change="getSIMByPagination(newPageNumber)" template-url="<?php echo base_url(); ?>superadmin/transaction/pagination_tmpl_load"></dir-pagination-controls>
        </div>
    </div>
</div>
