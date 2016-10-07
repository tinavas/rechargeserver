
<div class="left_menu" ng-controller="leftController">
    <div class="sidebar" id="set_user_service_id" ng-init="setServiceList('<?php echo htmlspecialchars(json_encode($my_service_list)) ?>')">
        <ul id="navmenu">
            <li class="home"><a href="<?php echo base_url(); ?>" id="homepage" class="top">Dashboard</a></li>
            <li>
                <a class="chld" href="javascript:void(0)">New Request</a>
                <ul id="baby">
                    <li ng-if="'<?php echo $topup_service_allow_flag; ?>' != false"><a href="<?php echo base_url() . 'transaction/topup' ?>">Topup</a></li>
                    <!--<li><a href="#">Bulk Flexiload</a></li>-->
                    <div ng-repeat="service in serviceList">
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/bkash' ?>" >bKash</a></li>
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/dbbl' ?>">DBBL</a></li>
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/mcash' ?>">M-Cash</a></li>
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/ucash' ?>">U-Cash</a></li>
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_SEND_SMS; ?>"><a href="<?php echo base_url() . 'transaction/sms' ?>">Send SMS</a></li>
                    </div>
                    <!--<li><a href="#">Global Topup</a></li>-->
                </ul>
            </li>
            <li><a href="<?php echo base_url(); ?>history/pending">Pending Request</a></li>
            <li><a href="javascript:void(0)" class="chld">History</a>
                <ul id="baby">
                    <li><a href="<?php echo base_url() . 'history/all_transactions' ?>">All History</a></li>
                    <li  ng-if="'<?php echo $topup_service_allow_flag; ?>' != false"> <a href ="<?php echo base_url() . 'history/topup_transactions' ?>">Topup</a></li>						
                    <div ng-repeat="service in serviceList">
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?>"><a href="<?php echo base_url() . 'history/bkash_transactions' ?>">bKash</a></li>						
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>"><a href="<?php echo base_url() . 'history/dbbl_transactions' ?>">DBBL</a></li>						
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'history/mcash_transactions' ?>">M-Cash</a></li>						
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'history/ucash_transactions' ?>">U-Cash</a></li>	
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_SEND_SMS; ?>"><a href="<?php echo base_url() . 'history/sms_transactions' ?>">Send SMS</a></li>	
                    </div>                    				
                </ul>
            </li>

            <li><a href="<?php echo base_url() . 'reseller/get_reseller_list' ?>">Resellers</a></li>		
            <li><a href="<?php echo base_url(); ?>history/get_payment_history">Payment History</a></li>
            <li><a href="<?php echo base_url(); ?>history/get_receive_history">Receive History</a></li>	
            <li><a href="javascript:void(0)" class="chld">Report </a>
                <ul id="baby">
                    <li><a href="<?php echo base_url() . 'report/get_cost_and_profit' ?>">Cost &amp; Profit</a></li>
                    <!--<li><a href="<?php echo base_url() . 'report/get_balance_report' ?>">Balance Report</a></li>-->
                    <li><a href="<?php echo base_url() . 'report/get_total_report' ?>">Total Report</a></li>
                    <li><a href="<?php echo base_url() . 'report/get_detailed_report' ?>">Detailed Report</a></li>
                    <li><a href="<?php echo base_url() . 'report/get_user_profit_loss' ?>">Profit/Loss Analysis</a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0)" class="chld">My Account </a>
                <ul id="baby">
                    <li><a href="<?php echo base_url(); ?>reseller/update_rate">My Rates</a></li>
                    <!--<li><a href="#">API Key</a></li>-->
                    <li><a href="<?php echo base_url(); ?>admin/load_balance">Add Balance</a></li>
                    <li><a href="<?php echo base_url(); ?>reseller/show_user_profile">My Profile</a></li>
                    <!--                    <li><a href="#">Access Logs</a></li>
                                        <li><a href="#">Change Pin</a></li>                
                                        <li><a href="#">Change Password</a></li>-->
                </ul>
            </li>
            <!--<li><a href="#">Complain </a></li>-->
            <li><a href="<?php echo base_url() . 'auth/logout' ?>">
                    <img src="<?php echo base_url(); ?>resources/images/logout.png"> 
                    <b>Logout</b>
                </a>
            </li>
        </ul>
        <div class="clrGap">&nbsp;</div>
    </div>
</div>