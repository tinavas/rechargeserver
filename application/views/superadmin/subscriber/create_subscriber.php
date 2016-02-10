<script>
    function create_subscriber(subscriberInfo) {

        var registrationDate = $("#registration_date").val();
        var expiredDate = $("#expired_date").val();
        subscriberInfo.registrationDate = registrationDate;
        subscriberInfo.expiredDate = expiredDate;

        if (typeof subscriberInfo.registrationDate == "undefined" || subscriberInfo.registrationDate.length == 0) {
            $("#content").html("Please Select Registration Date !");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof subscriberInfo.expiredDate == "undefined" || subscriberInfo.expiredDate.length == 0) {
            $("#content").html("Please Select Expired Date !");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof subscriberInfo.maxMembers == "undefined" || subscriberInfo.maxMembers.length == 0) {
            $("#content").html("Please give maximum user naumber !");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof subscriberInfo.refUserName == "undefined" || subscriberInfo.refUserName.length == 0) {
            $("#content").html("Please give reference user name !");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($("#submit_create_subscriber")).scope().createSubscriber(function (data) {

            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>superadmin/subscriber/show_subscribers';
            });
        });

    }

</script>
<div class="panel panel-default">
    <div class="panel-heading">Create Subscriber</div>
    <div class="panel-body" ng-controller="subscriberController">
        <div class="form-background top-bottom-padding">
            <div class="row">
                <div class ="col-md-8 margin-top-bottom">
                    <form>
                        <div class="row form-group">
                            <label for="registration_date" class="col-md-6 control-label requiredField">
                                Registration_date :
                            </label>
                            <div class ="col-md-6">
                                <input type="text" value="" class="form-control" placeholder="registration date"  id="registration_date" >
                            </div> 
                        </div>
                        <div class="row form-group">
                            <label for="expired_date" class="col-md-6 control-label requiredField">
                                Expired_date :
                            </label>
                            <div class ="col-md-6">
                                <input type="text" value="" class="form-control" placeholder="expired date"  id="expired_date">
                            </div> 
                        </div>
                        <div class="row form-group">
                            <label for="max_member" class="col-md-6 control-label requiredField">
                                Max_member :
                            </label>
                            <div class ="col-md-6">
                                <input type="text" value="" class="form-control" placeholder=""  id="expired_date" ng-model="subscriberInfo.maxMembers">
                            </div> 
                        </div>
                        <div class="row form-group">
                            <label for="ip_address" class="col-md-6 control-label requiredField">
                                Ip_Address :
                            </label>
                            <div class ="col-md-6">
                                <input type="text" value="" class="form-control" placeholder=""  id="" ng-model="subscriberInfo.ipAddress">
                            </div> 
                        </div>
                        <div class="row form-group">
                            <label for="ref_user_name" class="col-md-6 control-label requiredField">
                                Reference User Name :
                            </label>
                            <div class ="col-md-6">
                                <input type="text" value="" class="form-control" placeholder=""  id="ref_user_name" ng-model="subscriberInfo.refUserName">
                            </div> 
                        </div>
                        <div class="row form-group">
                            </label>
                            <div class ="col-md-3 pull-right">
                                <input id="submit_create_subscriber" name="submit_create_subscriber" class="btn btn_custom_button" type="submit" onclick="create_subscriber(angular.element(this).scope().subscriberInfo)" value="Create"/>
                            </div> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#registration_date').Zebra_DatePicker();
        $('#expired_date').Zebra_DatePicker();
    });
</script>
