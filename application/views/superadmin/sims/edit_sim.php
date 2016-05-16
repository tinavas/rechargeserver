<script>

    function edit_sim() {

        var simNumber = $("#sim_no").val();
        var registrationDate = $("#registration_date").val();
        angular.element($("#submit_edit_sim_btn")).scope().editSim(simNumber, registrationDate, function (data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
//            $('#modal_ok_click_id').on("click", function () {
//                window.location = '<?php echo base_url() ?>superadmin/sim/show_sims';
//            });
        });

    }
</script>


<div class="panel-heading">Edit Sim</div>
<div class="panel-body">
    <div class="form-background top-bottom-padding">
        <div class="row">
            <div class ="col-md-8 margin-top-bottom">
                <form>
                    <div class="row form-group">
                        <label for="sim_member" class="col-md-6 control-label requiredField">
                            Sim Number:
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control"  id="sim_no" >
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="registration_date" class="col-md-6 control-label requiredField">
                            Registration_date :
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control" placeholder="registration date"  id="registration_date"  >
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
            </div>
        </div>
        <div class="row form-group">
            <div class ="col-md-3 pull-right">
                <input id="submit_edit_sim_btn" name="submit_edit_sim_btn" class="btn btn_custom_button" type="submit" onclick="edit_sim()" value="Update"/>
            </div> 
        </div>
    </div>

