<script>
    function number_validation(phoneNumber) {
        var regexp = /^((^\+880|0)[1][1|5|6|7|8|9])[0-9]{8}$/;
        var validPhoneNumber = phoneNumber.match(regexp);
        if (validPhoneNumber) {
            return true;
        }
        return false;
    }
    function create_sim(simInfo) {

        var registrationDate = $("#registration_date").val();
        simInfo.registrationDate = registrationDate;
        if (typeof simInfo.simNumber == "undefined" || simInfo.simNumber.length == 0) {
            $("#content").html("Please give sim naumber !");
            $('#common_modal').modal('show');
            return;
        }
        if (number_validation(simInfo.simNumber) == false) {
            $("#content").html("Please give a valid Sim Number");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof simInfo.registrationDate == "undefined" || simInfo.registrationDate.length == 0) {
            $("#content").html("Please Select Registration Date !");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($("#submit_create_sim")).scope().addSim(simInfo, function (data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
//            $('#modal_ok_click_id').on("click", function () {
//                window.location = '<?php echo base_url() ?>superadmin/sim/show_sims';
//            });
        });

    }

</script>
<div class="panel panel-default">
    <div class="panel-heading">Add Sim</div>
    <div class="panel-body" ng-controller="transctionController">
        <div class="form-background top-bottom-padding">
            <div class="row">
                <div class ="col-md-8 margin-top-bottom">
                    <form>
                        <div class="row form-group">
                            <label for="sim_member" class="col-md-6 control-label requiredField">
                                Sim Number:
                            </label>
                            <div class ="col-md-6">
                                <input type="text" value="" class="form-control" placeholder=""  id="" ng-model="simInfo.simNumber">
                            </div> 
                        </div>
                        <div class="row form-group">
                            <label for="registration_date" class="col-md-6 control-label requiredField">
                                Registration_date :
                            </label>
                            <div class ="col-md-6">
                                <input type="text" value="" class="form-control" placeholder="registration date"  id="registration_date" >
                            </div> 
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="row col-md-12">
                        <label for="sim_member" class="control-label requiredField">
                            Services
                        </label>
                    </div>
                    <div class="row col-md-12">
                        <label for="sim_member"  class="control-label requiredField">
                            <input type="checkbox" ng-model="selectedAll" ng-click="checkAll()" />
                            Select All
                        </label>
                    </div>
                    <div class=" row col-md-12"  ng-init="setServiceList(<?php echo htmlspecialchars(json_encode($service_list)); ?>)">
                        <div ng-repeat="serviceInfo in serviceList">
                            <input type="checkbox"  ng-model="serviceInfo.selected" value="{{serviceInfo.service_id}}" name="per[]"  ng-click="toggleSelection(serviceInfo)">{{serviceInfo.title}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class ="col-md-3 pull-right">
                    <input id="submit_create_sim" name="submit_create_sim" class="btn btn_custom_button" type="submit" onclick="create_sim(angular.element(this).scope().simInfo)" value="Add"/>
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
