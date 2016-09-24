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




<!--<div class="ezttle"><span class="text">Detailed Report</span></div>
<div class="mypage" ng-controller="">
    <div class="top10">&nbsp;</div>
    <ng-form>
        <ul class="list-unstyled custom_unorder_list" ng-init="">
            <li>Select Reseller/Client</li>
            <li> <select name="" id="" ng-model="" class="form-control custom_form_control">
                    <option  value="">Please select</option>
                    <option ng-repeat="" value="">Reseller 1</option>
                    <option value="">Reseller 1</option>
                    <option value="">Reseller 2</option>
                    <option value="">Reseller 3</option>
                </select>
            </li>
            <li>Select Date</li>
            <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>-</li>
            <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="" class="button-custom"></li>
        </ul>
    </ng-form>
    <table class="table table-responsive green_color_table" ng-init="">
        <thead>
            <tr>
                <th><a href="">Service</a></th>
                <th><a href="">TTL Request</a></th>
                <th><a href="">Successful</a></th>
                <th><a href="">Failed</a></th>
                <th><a href="">In Processed</a></th>
                <th><a href="">% of Success</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Cost</a></th>
                <th><a href="">Profit</a></th>
            </tr>
        </thead>
        <tbody>
            <?php
            //for ($i = 0; $i <= 9; $i++) {
                ?>
                <tr>
                    <td><a href="">Flexiload</a></td>
                    <td><a href="">203</a></td>
                    <td><a href="">156</a></td>
                    <td><a href="">44</a></td>
                    <td><a href="">3</a></td>
                    <td><a href="">-</a></td>
                    <td><a href="">6,931.00</a></td>
                    <td><a href="">6,895.84</a></td>
                    <td><a href="">35.16</a></td>
                </tr>
                <?php
            //}
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td><a href="">Total</a></td>
                <td><a href="">2030</a></td>
                <td><a href="">1560</a></td>
                <td><a href="">440</a></td>
                <td><a href="">30</a></td>
                <td><a href="">-</a></td>
                <td><a href="">69,310.00</a></td>
                <td><a href="">68,958.40</a></td>
                <td><a href="">351.6</a></td>
            </tr>
        </tfoot>
    </table>
</div> 


<div class="ezttle"><span class="text">Profit/Loss Analysis</span></div>
<div class="mypage" ng-controller="">
    <div class="top10">&nbsp;</div>
    <ng-form>
        <ul class="list-unstyled custom_unorder_list" ng-init="">
            <li>Select Reseller/Client</li>
            <li> <select name="" id="" ng-model="" class="form-control custom_form_control">
                    <option  value="">Please select</option>
                    <option ng-repeat="" value="">Reseller 1</option>
                    <option value="">Reseller 1</option>
                    <option value="">Reseller 2</option>
                    <option value="">Reseller 3</option>
                </select>
            </li>
            <li>Select Date</li>
            <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>-</li>
            <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="" class="button-custom"></li>
        </ul>
    </ng-form>
    <table class="table table-responsive green_color_table" ng-init="">
        <thead>
            <tr>
                <th><a href="">Service</a></th>
                <th><a href="">TTL Request</a></th>
                <th><a href="">Successful</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Cost</a></th>
                <th><a href="">Profit</a></th>
            </tr>
        </thead>
        <tbody>
            <?php
//            for ($i = 0; $i <= 9; $i++) {
                ?>
                <tr>
                    <td><a href="">Flexiload</a></td>
                    <td><a href="">203</a></td>
                    <td><a href="">156</a></td>
                    <td><a href="">6,931.00</a></td>
                    <td><a href="">6,895.84</a></td>
                    <td><a href="">35.16</a></td>
                </tr>
                <?php
//            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td><a href="">Total</a></td>
                <td><a href="">2030</a></td>
                <td><a href="">1560</a></td>
                <td><a href="">69,310.00</a></td>
                <td><a href="">68,958.40</a></td>
                <td><a href="">351.6</a></td>
            </tr>
        </tfoot>
    </table>
</div> 


<div class="ezttle"><span class="text">Recharge History</span></div>
<div class="mypage" ng-controller="">
    <div class="top10">&nbsp;</div>
    <ng-form>
        <ul class="list-unstyled custom_unorder_list" ng-init="">
            <li>Select Reseller/Client</li>
            <li> <select name="" id="" ng-model="" class="form-control custom_form_control">
                    <option  value="">Please select</option>
                    <option ng-repeat="" value="">Reseller 1</option>
                    <option value="">Reseller 1</option>
                    <option value="">Reseller 2</option>
                    <option value="">Reseller 3</option>
                </select>
            </li>
            <li>Select Date</li>
            <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>-</li>
            <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="" class="button-custom"></li>
        </ul>
    </ng-form>
    <table class="table table-responsive green_color_table" ng-init="">
        <thead>
            <tr>
                <th><a href="">Service Name</a></th>
                <th><a href="">Operator</a></th>
                <th><a href="">Sender</a></th>
                <th><a href="">Receiver</a></th>
                <th><a href="">Transaction ID</a></th>
                <th><a href="">Transaction Amount</a></th>
                <th><a href="">Balance</a></th>
            </tr>
        </thead>
        <tbody>
            <?php
//            for ($i = 0; $i <= 9; $i++) {
                ?>
                <tr>
                    <td><a href="">Flexiload</a></td>
                    <td><a href="">Grameenphone</a></td>
                    <td><a href="">173243487</a></td>
                    <td><a href="">17345347</a></td>
                    <td><a href="">erterw4t345sd</a></td>
                    <td><a href="">121</a></td>
                    <td><a href="">56755</a></td>
                </tr>
                <?php
//            }
            ?>
        </tbody>
    </table>
</div> 


<div class="ezttle"><span class="text">Payment</span></div>
<div class="mypage" ng-controller="">
    <div class="top10">&nbsp;</div>
    <ng-form>
        <ul class="list-unstyled custom_unorder_list" ng-init="">
            <li>Select Reseller/Client</li>
            <li> <select name="" id="" ng-model="" class="form-control custom_form_control">
                    <option  value="">Please select</option>
                    <option ng-repeat="" value="">Reseller 1</option>
                    <option value="">Reseller 1</option>
                    <option value="">Reseller 2</option>
                    <option value="">Reseller 3</option>
                </select>
            </li>
            <li>Select Date</li>
            <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>-</li>
            <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="" class="button-custom"></li>
        </ul>
    </ng-form>
    <table class="table table-responsive green_color_header_table" ng-init="">
        <thead>
            <tr>
                <th><a href="">ID</a></th>
                <th><a href="">Time</a></th>
                <th><a href="">Added By</a></th>
                <th><a href="">Reseller/Admin Name</a></th>
                <th><a href="">Added to (Reseller/Admin)</a></th>
                <th><a href="">Reseller/Admin Name</a></th>
                <th><a href="">Note</a></th>
                <th><a href="">Type</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Balance</a></th>
            </tr>
        </thead>
        <tbody>
            <?php
//            for ($i = 0; $i <= 9; $i++) {
                ?>
                <tr>
                    <td><a href=""><?php echo ($i + 1) ?></a></td>
                    <td><a href="">21-09-2016  05:49:00 PM</a></td>
                    <td><a href="">Ressler 1</a></td>
                    <td><a href="">Kamrul</a></td>
                    <td><a href="">Admin4 (IV)</a></td>
                    <td><a href="">Maruf</a></td>
                    <td><a href="">Previous due not paid</a></td>
                    <td><a href="">Payment</a></td>
                    <td><a href="">100,000.00</a></td>
                    <td><a href="">100,213.00</a></td>
                </tr>
                <?php
//            }
            ?>
        </tbody>
    </table>
    <div class="row form-group">
        <div class="col-md-offset-3 col-md-9">
            <div class="page_number">
                <p>Page 1 of 1 (Showing 1 to 10 of 10 records)</p>
            </div>
        </div> 
    </div>
    <div class="row form-group">
        <div class="col-md-12">
            <div class="summery">
                <p>Summary</p>
                <table>
                    <tbody>
                        <tr><td>Total Payment :</td><td class="amt">100,000.00</td></tr>
                        <tr><td>Total Credit :</td><td class="amt">200</td></tr>
                        <tr><td>Total Return :</td><td class="amt">433</td></tr>
                        <tr><td>Total Canceled :</td><td class="amt">455</td></tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
</div> 


<div class="ezttle"><span class="text">Search Clients</span></div>
<div class="mypage" ng-controller="">
    <div class="top10">&nbsp;</div>
    <ng-form>
        <ul class="list-unstyled custom_unorder_list" ng-init="">
            <li>Search User</li>
            <li><input id="search_user" type="text" size="18" placeholder="Search"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>Status</li>
            <li> <select name="" id="" ng-model="" class="form-control custom_form_control">
                    <option  value="">Please select</option>
                    <option ng-repeat="" value="">Reseller 1</option>
                    <option value="">Active</option>
                    <option value="">Inactive</option>
                    <option value="">Blocked</option>
                </select>
            </li>
            <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="" class="button-custom"></li>
        </ul>
    </ng-form>
    <table class="table table-responsive pink_color_header_table" ng-init="">
        <thead>
            <tr>
                <th><a href="">User Name</a></th>
                <th><a href="">Present Balance</a></th>
                <th><a href="">Add Balance</a></th>
                <th><a href="">Status</a></th>
                <th><a href="">Created By</a></th>
                <th><a href="">View Payments History</a></th>
            </tr>
        </thead>
        <tbody>
            <?php
//            for ($i = 0; $i <= 9; $i++) {
                ?>
                <tr>
                    <td><a href="">Uzzal</a></td>
                    <td>0</td>
                    <td><a href="">Return Balance</a></td>
                    <td>Active</td>
                    <td>Masud</td>
                    <td><a href="">View Payment</a></td>
                </tr>
                <?php
//            }
            ?>
        </tbody>
    </table>
</div>


<div class="ezttle"><span class="text">Search Transaction</span></div>
<div class="mypage" ng-controller="">
    <div class="top10">&nbsp;</div>
    <ng-form>
        <ul class="list-unstyled custom_unorder_list" ng-init="">
            <li>Search By Mobile Number</li>
            <li><input id="mobile_number" type="text" size="18" placeholder="Mobile Number"  name="from" class="form-control input-xs customInputMargin"></li>
              <li>Search By Transaction No</li>
            <li><input id="transaction_no" type="text" size="18" placeholder="Transaction No"  name="from" class="form-control input-xs customInputMargin"></li>
            <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="" class="button-custom"></li>
        </ul>
    </ng-form>
    <table class="table table-responsive green_color_table" ng-init="">
        <thead>
            <tr>
                <th><a href="">Service Name</a></th>
                <th><a href="">Operator</a></th>
                <th><a href="">Sender</a></th>
                <th><a href="">Receiver</a></th>
                <th><a href="">Transaction ID</a></th>
                <th><a href="">Transaction Amount</a></th>
            </tr>
        </thead>
        <tbody>
            <?php
//            for ($i = 0; $i <= 9; $i++) {
                ?>
                <tr>
                    <td><a href="">Flexiload</a></td>
                    <td><a href="">Grameenphone</a></td>
                    <td><a href="">173243487</a></td>
                    <td><a href="">1732454984</a></td>
                    <td><a href="">erterw4t345sd</a></td>
                    <td><a href="">121</a></td>
                </tr>
                <?php
//            }
            ?>
        </tbody>
    </table>
</div> 


<div class="ezttle"><span class="text">Operator Wise Balance Report Summary</span></div>
<div class="mypage" ng-controller="">
    <div class="top10">&nbsp;</div>
    <ng-form>
        <ul class="list-unstyled custom_unorder_list" ng-init="">
            <li>Select Date</li>
            <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>-</li>
            <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="" class="button-custom"></li>
        </ul>
        <ul class="list-unstyled" ng-init="">
            <li><a>Today</a></li>
            <li><a>Yesterday</a></li>
            <li><a>Last 7 Days</a></li>
            <li><a>Last 30 Days</a></li>
            <li><a>All</a></li>
        </ul>
    </ng-form>
    <table class="table table-responsive pink_color_table" ng-init="">
        <thead>
            <tr>
                <th><a href="">Date</a></th>
                <th><a href="">Operator</a></th>
                <th><a href="">Amount</a></th>
            </tr>
        </thead>
        <tbody>
            <?php
//            for ($i = 0; $i <= 9; $i++) {
                ?>
                <tr>
                <td>Today</td>
                <td>Grameen Phone</td>
                <td>50000</td>
                </tr>
                <?php
//            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td><a href="">Total Amount</a></td>
                <td><a href=""></a></td>
                <td><a href="">500000</a></td>
            </tr>
        </tfoot>
    </table>
</div> 


<div class="ezttle"><span class="text">Reseller Wise Sale Report</span></div>
<div class="mypage" ng-controller="">
    <div class="top10">&nbsp;</div>
    <ng-form>
        <ul class="list-unstyled custom_unorder_list" ng-init="">
            <li>Select Date</li>
            <li><input id="start_date" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li>-</li>
            <li><input id="end_date" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
            <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="" class="button-custom"></li>
        </ul>
    </ng-form>
    <table class="table table-responsive pink_color_table" ng-init="">
        <thead>
            <tr>
                <th><a href="">Username</a></th>
                <th><a href="">Total Sales</a></th>
                <th><a href="">Remaining Balance</a></th>
            </tr>
        </thead>
        <tbody>
            <?php
//            for ($i = 0; $i <= 9; $i++) {
                ?>
                <tr>
                     <td>happy1212 (Reseller III)</td>
                <td>-</td>
                <td>0</td>
                </tr>
                <?php
//            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td><a href="">Total:</a></td>
                <td><a href="">0</a></td>
                <td><a href="">0</a></td>
            </tr>
        </tfoot>
    </table>
</div> -->
