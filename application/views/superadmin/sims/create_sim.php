<script>
    function create_sim(simInfo) {
        if (typeof simInfo.simNo == "undefined" || simInfo.simNo.length == 0) {
            $("#content").html("Please give sim naumber !");
            $('#common_modal').modal('show');
            return;
        }
        if (number_validation(simInfo.simNo) == false) {
            $("#content").html("Please give a valid SIM Number");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($("#submit_create_sim")).scope().addSim(simInfo, function(data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function() {
                window.location = '<?php echo base_url() ?>superadmin/sim';
            });
        });

    }

</script>
<div class="panel panel-default">
    <div class="panel-heading">Add Sim</div>
    <div class="panel-body" ng-controller="simController">
        <div class="form-background top-bottom-padding">
            <div class="row">
                <div class ="col-md-4 margin-top-bottom">
                    <form>
                        <div class="row form-group">
                            <label for="sim_number" class="col-md-6 control-label requiredField">
                                Sim Number:
                            </label>
                            <div class ="col-md-6">
                                <input type="text" placeholder="88017XXXXXXXX" value="" class="form-control input-xs customInputMargin" placeholder=""  id="" ng-model="simInfo.simNo">
                            </div> 
                        </div>
                        <div class="row form-group">
                            <label for="description" class="col-md-6 control-label requiredField">
                                Description:
                            </label>
                            <div class ="col-md-6">
                                <input type="text" value="" class="form-control input-xs customInputMargin" placeholder=""  id="" ng-model="simInfo.description">
                            </div> 
                        </div>
                        <div class="row form-group"  ng-init="setSimStatusList('<?php echo htmlspecialchars(json_encode($sim_status_list)); ?>')">
                            <label for="status" class="col-md-6 control-label requiredField">
                                Status:
                            </label>
                            <div class ="col-md-6">
                                <select  for="type" id="type"  ng-model="simInfo.status"  class="form-control input-xs customInputMargin">
                                     <option value="" class="hidden_class"></option>
                                    <option ng-selected="statusInfo.selected"  ng-repeat="statusInfo in simStatusList" value="{{statusInfo.id}}">{{statusInfo.title}}</option>
                                </select>
                            </div> 
                        </div>
                    </form>
                </div>
                <div class="col-md-8" ng-init="setSimCategoryList('<?php echo htmlspecialchars(json_encode($sim_category_list)); ?>')">
                    <div class="row col-md-12">
                        <label for="sim_member" class="control-label requiredField">
                            Services
                        </label>
                    </div>
                    
                    <table class="table table-responsive">
                        <thead>
                            <tr class="table_heading_title">
                                <th>
                                    <label for=""  class="control-label requiredField">
                                        <input type="checkbox" ng-model="selectedAll" ng-click="checkAll()" />
                                        Select All
                                    </label>
                                </th>
                                <th>
                                    <label for=""  class="control-label requiredField">
                                        Category type
                                    </label> 
                                </th>
                                <th>
                                    <label for=""  class="control-label requiredField">
                                        Current balance
                                    </label>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <div ng-init="setServiceList(<?php echo htmlspecialchars(json_encode($service_list));  ?>)" >
                            <tr ng-repeat="serviceInfo in serviceList" class="table_content">
                                <td class="service">
                                    <input type="checkbox"  ng-model="serviceInfo.selected" value="{{serviceInfo.service_id}}" name="per[]"  ng-click="toggleSelection(serviceInfo)">{{serviceInfo.title}}
                                </td>
                                <td>
                                    <select  for="type" id="type"  ng-model="serviceInfo.categoryId" class="form-control input-xs">
                                        <option value="" class="hidden_class"></option>
                                        <option ng-selected="category.selected"  ng-repeat="category in simCategoryList" value="{{category.id}}">{{category.title}}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" value="" placeholder="1000"  id="" ng-model="serviceInfo.currentBalance"  class="form-control input-xs">
                                </td>
                            </tr>
                        </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class ="col-md-offset-9 col-md-3">
                <input id="submit_create_sim" name="submit_create_sim" class="btn btn_custom_button pull-right" type="submit" onclick="create_sim(angular.element(this).scope().simInfo)" value="Add"/>
            </div> 
        </div>
    </div>
</div>
