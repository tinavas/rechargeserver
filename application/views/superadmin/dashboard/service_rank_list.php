<div class="textBold borderBottom text-center padding_2px" >
    <div class="row">
        <div class="col-md-4">
            service
        </div>
        <div class="col-md-4">
            Total used Amount
        </div>
        <div class="col-md-4">
            Total Profit
        </div>
    </div>
</div>
<div class="borderBottom padding_2px text-center" >
    <div class="row" ng-repeat="serviceInfo in serviceRankList">
        <div class="col-md-4">
            <span ng-if="serviceInfo.service_id == '<?php echo SERVICE_TYPE_ID_BKASH_CASHIN ?>' ">
                BKash
            </span>
            <span ng-if="serviceInfo.service_id == '<?php echo SERVICE_TYPE_ID_DBBL_CASHIN ?>' ">
                DBBL
            </span>
            <span ng-if="serviceInfo.service_id == '<?php echo SERVICE_TYPE_ID_MCASH_CASHIN ?>' ">
                MCash
            </span>
            <span ng-if="serviceInfo.service_id == '<?php echo SERVICE_TYPE_ID_UCASH_CASHIN ?>' ">
                UChash
            </span>
            <span ng-if="serviceInfo.service_id == '<?php echo SERVICE_TYPE_ID_TOPUP_GP ?>' ">
               Topup-Gp 
            </span>
            <span ng-if="serviceInfo.service_id == '<?php echo SERVICE_TYPE_ID_TOPUP_ROBI ?>' ">
               Topup-Robi 
            </span>
            <span ng-if="serviceInfo.service_id == '<?php echo SERVICE_TYPE_ID_TOPUP_BANGLALINK ?>' ">
               Topup-BanglaLink
            </span>
            <span ng-if="serviceInfo.service_id == '<?php echo SERVICE_TYPE_ID_TOPUP_AIRTEL ?>' ">
               Topup-Airtel
            </span>
            <span ng-if="serviceInfo.service_id == '<?php echo  SERVICE_TYPE_ID_TOPUP_TELETALK ?>' ">
               Topup-Teletalk
            </span>
        </div>
        <div class="col-md-4">
            {{serviceInfo.total_amount}}
        </div>
        <div class="col-md-4">
            {{serviceInfo.total_profit}}
        </div>
    </div>
</div>











