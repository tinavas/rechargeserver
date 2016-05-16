


<div class="loader"></div>
<div class="ezttle"><span class="text">Subscriber History</span>
    <span class="acton"></span>
</div>
<div ng-controller="" class="mypage">
    <div class="row">
        <div class="col-md-4">
            <level>Search:</level>
        </div>
        <div class="col-lg-6">

            <input id="" type="text"  placeholder=""  name="from" class="form-control input-xs customInputMargin">
        </div>
        <div class="col-md-2"> 
            <input id="search_submit_btn" ng-model="search" type="submit" size="18" value="Search" onclick="search_payment_history()" class="button-custom">
        </div>
<!--        <ng-form>

            <ul class="list-unstyled paymentHistorySearch" >
                <li>Search:</li>
                <li><input id="" type="text"  placeholder=""  name="from" class="form-control input-xs customInputMargin"></li>
                                <li>Start Date</li>
                                <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
                                <li>End Date</li>
                                <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
                                <li>Type</li>
                                <li>Show All</li>
                                <li> <input type="checkbox" ng-model="allTransactions"></li>
                <li><input id="search_submit_btn" ng-model="search" type="submit" size="18" value="Search" onclick="search_payment_history()" class="button-custom"></li>
            </ul>
        </ng-form>-->
    </div>
    <div class="row">
        <table id="" class="table table-striped table-hover" >
            <thead>
                <tr>
                    <th><a href="">User Name</a></th>
                    <th><a href="">Email</a></th>
                    <th><a href="">Number</a></th>
                    <th><a href="">Current Balance</a></th>
                    <th><a href="">Total Amount Used</a></th>
                    <th><a href="">Total Profit</a></th>
                    <th><a href=""> Services</a></th>
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


