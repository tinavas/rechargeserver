<div class="loader"></div>
<div class="ezttle"><span class="text">Profile Information</span>
    <span class="acton"></span>
</div>
<div class="mypage" ng-controller="resellerController">
    <div class="row" ng-init="setProfileInfo('<?php echo htmlspecialchars(json_encode($profile_info))?>')">
        <div class="col-md-6">
            <div class="row form-group">
                <div class="col-md-12">
                     <label>Basic Information</label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label>Username</label>
                </div>
                <div class="col-md-6">
                    <div class="help-block">{{profileInfo.username}}</div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label>Pin</label>
                </div>
                <div class="col-md-6">
                    <div type="password" class="help-block">{{profileInfo.pin}}</div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label>Name</label>
                </div>
                <div class="col-md-6">
                    <div class="help-block">{{profileInfo.first_name}}&nbsp;{{profileInfo.last_name}}</div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label>Email</label>
                </div>
                <div class="col-md-6">
                    <div class="help-block">{{profileInfo.email}}</div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label>Mobile Number</label>
                </div>
                <div class="col-md-6">
                    <div class="help-block">{{profileInfo.mobile}}</div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label>Max Allowable Users No</label>
                </div>
                <div class="col-md-6">
                    <div class="help-block">{{profileInfo.max_user_no}}</div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label>Note</label>
                </div>
                <div class="col-md-6">
                    <div class="help-block">{{profileInfo.note}}</div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label>Message</label>
                </div>
                <div class="col-md-6">
                    <div class="help-block">{{profileInfo.message}}</div>
                </div>
            </div>
        </div>
        <div class="col-md-offset-1 col-md-5" ng-init="setServiceList('<?php echo htmlspecialchars(json_encode($service_list))?>')">
            <div class="row form-group">
                <div class="col-md-12">
                    <label>Service List</label>
                </div>
            </div>
            <ul class="profileCustomResellerList" ng-repeat="serviceInfo in serviceList">
                <li ng-if="serviceInfo.service_id == <?php echo SERVICE_TYPE_ID_BKASH_CASHIN?>">
                    <a href="<?php echo base_url() . 'history/bkash_transactions/'; ?>{{profileInfo.user_id}}">{{serviceInfo.title}}</a>
                </li>
                <li ng-if="serviceInfo.service_id == <?php echo SERVICE_TYPE_ID_DBBL_CASHIN?>">
                    <a href="<?php echo base_url() . 'history/dbbl_transactions/'; ?>{{profileInfo.user_id}}">{{serviceInfo.title}}</a>
                </li>
                <li ng-if="serviceInfo.service_id == <?php echo SERVICE_TYPE_ID_MCASH_CASHIN?>">
                    <a href="<?php echo base_url() . 'history/mcash_transactions/'; ?>{{profileInfo.user_id}}">{{serviceInfo.title}}</a>
                </li>
                <li ng-if="serviceInfo.service_id == <?php echo SERVICE_TYPE_ID_UCASH_CASHIN?>">
                    <a href="<?php echo base_url() . 'history/ucash_transactions/'; ?>{{profileInfo.user_id}}">{{serviceInfo.title}}</a>
                </li>
                <li ng-if="serviceInfo.service_id == <?php echo SERVICE_TYPE_ID_TOPUP_GP?>">
                    <a href="<?php echo base_url() . 'history/topup_transactions/'; ?>{{profileInfo.user_id}}">{{serviceInfo.title}}</a>
                </li>
                <li ng-if="serviceInfo.service_id == <?php echo SERVICE_TYPE_ID_TOPUP_ROBI?>">
                    <a href="<?php echo base_url() . 'history/topup_transactions/'; ?>{{profileInfo.user_id}}">{{serviceInfo.title}}</a>
                </li>
                <li ng-if="serviceInfo.service_id == <?php echo SERVICE_TYPE_ID_TOPUP_BANGLALINK?>">
                    <a href="<?php echo base_url() . 'history/topup_transactions/'; ?>{{profileInfo.user_id}}">{{serviceInfo.title}}</a>
                </li>
                <li ng-if="serviceInfo.service_id == <?php echo SERVICE_TYPE_ID_TOPUP_AIRTEL?>">
                    <a href="<?php echo base_url() . 'history/topup_transactions/'; ?>{{profileInfo.user_id}}">{{serviceInfo.title}}</a>
                </li>
                <li ng-if="serviceInfo.service_id == <?php echo SERVICE_TYPE_ID_TOPUP_TELETALK?>">
                    <a href="<?php echo base_url() . 'history/topup_transactions/'; ?>{{profileInfo.user_id}}">{{serviceInfo.title}}</a>
                </li>
                <li ng-if="serviceInfo.service_id == <?php echo SERVICE_TYPE_ID_SEND_SMS?>">
                    <a href="<?php echo base_url() . 'history/sms_transactions/'; ?>{{profileInfo.user_id}}">{{serviceInfo.title}}</a>
                </li>
            </ul>
        </div>        
    </div>
</div>