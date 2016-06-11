<script>
    function number_validation(phoneNumber) {
        var regexp = /^((^\880|0)[1][1|5|6|7|8|9])[0-9]{8}$/;
        var validPhoneNumber = phoneNumber.match(regexp);
        if (validPhoneNumber) {
            return true;
        }
        return false;
    }
    function edit_sim(simInfo) {
        if (typeof simInfo.sim_no == "undefined" || simInfo.sim_no.length == 0) {
            $("#content").html("Please give sim naumber !");
            $('#common_modal').modal('show');
            return;
        }
        if (number_validation(simInfo.sim_no) == false) {
            $("#content").html("Please give a valid SIM Number");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof simInfo.description == "undefined" || simInfo.description.length == 0) {
            $("#content").html("Please add a description !");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($("#submit_edit_sim_btn")).scope().editSim(simInfo, function (data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>superadmin/sim/edit_sim/'+simInfo.sim_no;
            });
        });

    }
</script>


<div class="panel-heading">Edit Sim</div>
<div class="panel-body" ng-controller="transctionController">
    <div class="form-background top-bottom-padding">
        <div class="row">
            <div class ="col-md-8 margin-top-bottom">
                <?php if (isset($sim_info)) { ?>
                    <div ng-init="setSimInfo(<?php echo htmlspecialchars(json_encode($sim_info)) ?>)"></div>
                <?php } ?>
                <form>
                    <div class="row form-group">
                        <label for="sim_no" class="col-md-6 control-label requiredField">
                            Sim Number:
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control"  ng-model="simInfo.sim_no">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="description" class="col-md-6 control-label requiredField">
                            Description:
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control" placeholder=""  id="" ng-model="simInfo.description">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="current_balance" class="col-md-6 control-label requiredField">
                            Current Balance:
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control" placeholder=""  id="" ng-model="simInfo.current_balance">
                        </div> 
                    </div>                    
                </form>
            </div>
<!--            <div class="col-md-4">
                <div class="row col-md-12">
                    <label for="sim_member" class="control-label requiredField">
                        Services
                    </label>
                </div>
                <div class="row col-md-12">
                    <label for=""  class="control-label requiredField">
                        <input type="checkbox" ng-model="selectedAll" ng-click="checkAll()" />
                        Select All
                    </label>
                </div>
                <div class=" row col-md-12">
                    <div ng-repeat="serviceInfo in serviceList">
                        <input type="checkbox"  ng-model="serviceInfo.selected" value="{{serviceInfo.service_id}}" name="per[]"  ng-click="toggleSelection(serviceInfo)">{{serviceInfo.title}}
                    </div>
                </div>
            </div>-->
        </div>
        <div class="row form-group">
            <div class ="col-md-3 pull-right">
                <input id="submit_edit_sim_btn" name="submit_edit_sim_btn" class="btn btn_custom_button" type="submit" onclick="edit_sim(angular.element(this).scope().simInfo)" value="Update"/>
            </div> 
        </div>
    </div>

