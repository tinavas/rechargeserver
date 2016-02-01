<div class="left_menu" >
    <div class="sidebar" >
        <ul id="navmenu">
            <li class="home"><a href="<?php echo base_url();?>" id="homepage" class="top">Dashboard</a></li>
            <li>
                <a class="chld" href="javascript:void(0)">New Request</a>
                <ul id="baby">
                    <li><a href="#">Bulk Flexiload</a></li>
                    <li><a href="<?php echo base_url().'transaction/topup'?>">Topup</a></li>
                    <li><a href="<?php echo base_url().'transaction/bkash'?>">bKash</a></li>
                    <li><a href="<?php echo base_url().'transaction/dbbl'?>">DBBL</a></li>
                    <li><a href="<?php echo base_url().'transaction/mcash'?>">M-Cash</a></li>
                    <li><a href="<?php echo base_url().'transaction/ucash'?>">U-Cash</a></li>
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
                    <li><a href="<?php echo base_url().'history/all'?>">All History</a></li>
                    <li><a href="<?php echo base_url().'history/topup'?>">Topup</a></li>						
                    <li><a href="<?php echo base_url().'history/bkash'?>">bKash</a></li>						
                    <li><a href="<?php echo base_url().'history/dbbl'?>">DBBL</a></li>						
                    <li><a href="<?php echo base_url().'history/mcash'?>">M-Cash</a></li>						
                    <li><a href="<?php echo base_url().'history/ucash'?>">U-Cash</a></li>						
                    <li><a href="#">Global Topup</a></li>					
                </ul>
            </li>

            <li><a href="<?php echo base_url().'reseller'?>">Resellers</a></li>		
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
                    <li><a href="<?php echo base_url();?>reseller/return_balance">Return Balance</a></li>
                    <li><a href="#">My Rates</a></li>
                    <li><a href="#">My Profile</a></li>
                    <li><a href="#">Access Logs</a></li>
                    <li><a href="#">Change Pin</a></li>                
                    <li><a href="#">Change Password</a></li>
                </ul>
            </li>
            <li><a href="#">Complain </a></li>
            <li><a href="<?php echo base_url().'auth/logout'?>">
                    <img src="<?php echo base_url();?>resources/images/logout.png"> 
                    <b>Logout</b>
                </a>
            </li>

        </ul>
        <div class="clrGap">&nbsp;</div>
    </div>
</div>