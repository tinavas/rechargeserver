<script>
    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    function update_reseller(resellerInfo) {
        if (typeof resellerInfo.username == "undefined" || resellerInfo.username.length == 0) {
            $("#content").html("Please give a User Name !");
            $('#common_modal').modal('show');
            return;
        }
//        if (typeof resellerInfo.first_name == "undefined" || resellerInfo.first_name.length == 0) {
//            $("#content").html("Please give First Name !");
//            $('#common_modal').modal('show');
//            return;
//        }
//        if (typeof resellerInfo.last_name == "undefined" || resellerInfo.last_name.length == 0) {
//            $("#content").html("Please give Last Name !");
//            $('#common_modal').modal('show');
//            return;
//        }
//        if (typeof resellerInfo.mobile == "undefined" || resellerInfo.mobile.length == 0) {
//            $("#content").html("Please give Mobile Number !");
//            $('#common_modal').modal('show');
//            return;
//        }
//        if (typeof resellerInfo.email == "undefined" || resellerInfo.email.length == 0) {
//            $("#content").html("Please give Email address !");
//            $('#common_modal').modal('show');
//            return;
//        }
//        var varificationResult = validateEmail(resellerInfo.email);
//        if (varificationResult == false) {
//            $("#content").html("Please Enter a valid Email Address!");
//            $('#common_modal').modal('show');
//            return false;
//        }
        angular.element($('#submit_update_user_profile')).scope().updateUserProfile(function (data) {

            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>reseller/show_user_profile';
            });
        });
    }

</script>

<div class="loader"></div>
<div class="mypage"  ng-controller="resellerController">
    <div class="top10">&nbsp;</div>
    <div class="row" ng-init="setResellerInfo(<?php echo htmlspecialchars(json_encode($reseller_info)); ?>)">
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
                                    <input type="text" value="" placeholder="Username" class="form-control input-sm" id="username" name="username" ng-model="resellerInfo.username">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="password" class="control-label">Password</label>
                                    <input type="password" autocomplete="off" value="" placeholder="********" class="form-control input-sm" id="password" name="password" ng-model="resellerInfo.new_password">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="name" class="control-label">First name</label>
                                    <input type="text" value="" placeholder="First name" class="form-control input-sm" id="first_name" name="first_name" ng-model="resellerInfo.first_name">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="name" class="control-label">Last name</label>
                                    <input type="text" value="" placeholder="Last name" class="form-control input-sm" id="last_name" name="last_name" ng-model="resellerInfo.last_name">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="mobile" class="control-label">Mobile Number</label>
                                    <input type="text" value="" placeholder="Mobile Number" class="form-control input-sm" id="mobile" name="mobile" ng-model="resellerInfo.mobile">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="text" value="" placeholder="Email" class="form-control input-sm" id="email" name="email" ng-model="resellerInfo.email">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="note">Note</label>
                                    <textarea rows="2" name="note" id="note" class="form-control input-sm" ng-model="resellerInfo.note"></textarea>
                                    <p class="help-block form_error"></p>
                                </div>
                            </td>                            
                        </tr>
                    </tbody>
                </table>
                <p class="help-block line">&nbsp;</p>               
                <input id="submit_update_user_profile" name="submit_update_user_profile" class="btn btn-primary btn-sm" type="submit" onclick="update_reseller(angular.element(this).scope().resellerInfo)" value="Update Profile"/>
            </ng-from>
        </div> 
    </div> 
</div>