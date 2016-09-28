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
                    <div type="password"  class="help-block">{{profileInfo.pin}}</div>
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
                <li>{{serviceInfo.title}}</li>
            </ul>
        </div>        
    </div>
    <div class="row col-md-12">
        <a href="<?php echo base_url() . 'reseller/update_user_profile/'; ?>">Update Profile</a>
    </div>
</div>