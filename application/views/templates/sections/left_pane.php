
<script type="text/javascript">

    $(function () {
        angular.element($('#set_user_service_id')).scope().getUserServiceList();
    });



</script>

<div class="left_menu"  ng-controller="leftController">
    <div class="sidebar" id="set_user_service_id" >
        <ul id="navmenu">
            <li class="home"><a href="<?php echo base_url(); ?>" id="homepage" class="top">Dashboard</a></li>
            <li>
                <a class="chld" href="javascript:void(0)">New Request</a>
                <ul id="baby">
                    <li ng-if="topup_service_allow_flag != false"><a href="<?php echo base_url() . 'transaction/topup' ?>">Topup</a></li>
                    <li><a href="#">Bulk Flexiload</a></li>
                    <div ng-repeat="service in serviceList">
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/bkash' ?>">bKash</a></li>
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/dbbl' ?>">DBBL</a></li>
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/mcash' ?>">M-Cash</a></li>
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'transaction/ucash' ?>">U-Cash</a></li>
                    </div>
                    <li><a href="#">Global Topup</a></li>
                </ul>
            </li>
            <li><a href="#">Pending Request </a></li>	
            <li>
                <a class="chld" href="#">Prepaid Card</a>
                <ul id="baby">
                    <li><a href="#">Buy Card</a></li>
                    <li><a href="#">History</a></li>
                </ul>
            </li>
            <li>
                <a class="chld" href="#">Bill Pay</a>
                <ul id="baby">
                    <li><a href="#">New BillPay</a></li>
                    <li><a href="#">BillPay History</a></li>
                </ul>
            </li>
            <li>
                <a class="chld" href="javascript:void(0)">Message</a>
                <ul id="baby">
                    <li><a href="#">Send SMS</a></li>
                    <li><a href="#">Bulk SMS</a></li>
                    <li><a href="#">AddressBook</a></li>
                    <li><a href="#">SMS History</a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0)" class="chld">History</a>
                <ul id="baby">
                    <li><a href="<?php echo base_url() . 'history/all' ?>">All History</a></li>
                    <li  ng-if="topup_service_allow_flag != false "> <a href ="<?php echo base_url() . 'history/topup' ?>">Topup</a></li>						
                    <div ng-repeat="service in serviceList">
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?>"><a href="<?php echo base_url() . 'history/bkash' ?>">bKash</a></li>						
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>"><a href="<?php echo base_url() . 'history/dbbl' ?>">DBBL</a></li>						
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'history/mcash' ?>">M-Cash</a></li>						
                        <li ng-if="service.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN; ?>"><a href="<?php echo base_url() . 'history/ucash' ?>">U-Cash</a></li>	
                    </div>
                    <li><a href="#">Global Topup</a></li>					
                </ul>
            </li>

            <li><a href="<?php echo base_url() . 'reseller/get_reseller_list' ?>">Resellers</a></li>		
            <li><a href="#">Payment History</a></li>
            <li><a href="#">Receive History</a></li>	
            <li><a href="javascript:void(0)" class="chld">Report </a>
                <ul id="baby">
                    <li><a href="#">Cost &amp; Profit</a></li>
                    <li><a href="#">Balance Report</a></li>
                    <li><a href="#">Total Report</a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0)" class="chld">My Account </a>
                <ul id="baby">
                    <li><a href="<?php echo base_url(); ?>reseller/get_reseller_service_rate">My Rates</a></li>
                    <li><a href="#">API Key</a></li>
                    <li><a href="<?php echo base_url(); ?>payment/reseller_return_balance">Return Balance</a></li>
                    <li><a href="#">My Profile</a></li>
                    <li><a href="#">Access Logs</a></li>
                    <li><a href="#">Change Pin</a></li>                
                    <li><a href="#">Change Password</a></li>
                </ul>
            </li>
            <li><a href="#">Complain </a></li>
            <li><a href="<?php echo base_url() . 'auth/logout' ?>">
                    <img src="<?php echo base_url(); ?>resources/images/logout.png"> 
                    <b>Logout</b>
                </a>
            </li>

        </ul>
        <div class="clrGap">&nbsp;</div>
    </div>
</div>