<script>
    function update_user(userInfo) {
        if (typeof userInfo.username == "undefined" || userInfo.username.length == 0) {
            $("#content").html("Please give a User Name !");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof userInfo.pin == "undefined" || userInfo.pin.length == 0) {
            $("#content").html("Please give a pin !");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($('#submit_update_user')).scope().updateUser(function (data) {

            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>superadmin/user/update_user';
            });
        });
    }
</script>

<div class="loader"></div>
<div class="mypage"  ng-controller="userController">
    <div class="top10">&nbsp;</div>
    <div class="row" ng-init="setUserInfo(<?php echo htmlspecialchars(json_encode($user_info)); ?>)">
        <div class="col-md-12 fleft">	
            <ng-from>    
                <input type="hidden" style="display:none;" value="" name="elctkn">
                <table style="width:50%;">
                    <tbody>

                        <tr>
                            <td style="width:50%;vertical-align:top;padding-right:15px;">
                                <p class="help-block">Profile Information</p>
                                <div class="form-group ">
                                    <label for="username" class="control-label">Username*</label>
                                    <input type="text" value="" placeholder="Username" class="form-control input-sm" id="username" name="username" ng-model="userInfo.username">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="password" class="control-label">Password</label>
                                    <input type="password" autocomplete="off" value="" placeholder="********" class="form-control input-sm" id="password" name="password" ng-model="userInfo.new_password">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="pin" class="control-label">Pin</label>
                                    <input type="text" autocomplete="off" value="" placeholder="" class="form-control input-sm" id="pin" name="pin" ng-model="userInfo.pin">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="name" class="control-label">First name</label>
                                    <input type="text" value="" placeholder="First name" class="form-control input-sm" id="first_name" name="first_name" ng-model="userInfo.first_name">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="name" class="control-label">Last name</label>
                                    <input type="text" value="" placeholder="Last name" class="form-control input-sm" id="last_name" name="last_name" ng-model="userInfo.last_name">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="mobile" class="control-label">Mobile Number</label>
                                    <input type="text" value="" placeholder="Mobile Number" class="form-control input-sm" id="mobile" name="mobile" ng-model="userInfo.mobile">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="text" value="" placeholder="Email" class="form-control input-sm" id="email" name="email" ng-model="userInfo.email">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="note">Note</label>
                                    <textarea rows="2" name="note" id="note" class="form-control input-sm" ng-model="userInfo.note"></textarea>
                                    <p class="help-block form_error"></p>
                                </div>
                            </td>                            
                        </tr>
                    </tbody>
                </table>
                <p class="help-block line">&nbsp;</p> 
                <div class="row form-group">
                    <div class="col-md-6">
                        <input id="submit_update_user" name="submit_update_user" class="button-custom pull-right" type="submit" onclick="update_user(angular.element(this).scope().userInfo)" value="Update Profile"/>
                    </div>
                </div>
            </ng-from>
        </div> 
    </div> 
</div>