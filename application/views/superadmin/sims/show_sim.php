<div class="panel-heading">Sim Info</div>
<div class="panel-body" ng-controller="simController">
    <div class="form-background top-bottom-padding">
        <div class="row">
            <div class ="col-md-4 margin-top-bottom">
                <form>
                    <?php if (isset($sim_info)) { ?>
                        <div ng-init="setSimInfo(<?php echo htmlspecialchars(json_encode($sim_info)) ?>)"></div>
                    <?php } ?>
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
                            <select  class="form-control input-xs customInputMargin" ng-model='simInfo.status' required ng-options='statusInfo.id as statusInfo.title for statusInfo in simStatusList'></select>
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
                                    category type
                                </label>
                            </th>
                            <th>
                                <label for=""  class="control-label requiredField">
                                    current balance
                                </label>
                            </th>
                            <th>
                                <label for=""  class="control-label requiredField">
                                    Last Update
                                </label>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <div  ng-init="setServiceList(<?php echo htmlspecialchars(json_encode($service_list)); ?>)" >
                        <tr ng-repeat="serviceInfo in serviceList" class="table_content">
                            <td class="service">
                                <input ng-model="serviceInfo.selected" type="checkbox" value="{{serviceInfo.service_id}}" name="per[]"  ng-click="toggleSelection(serviceInfo)">{{serviceInfo.title}}
                            </td>
                            <td>
                                <select  ng-model='serviceInfo.categoryId' required ng-options='category.id as category.title for category in simCategoryList' class="form-control input-xs"></select>
                            </td>
                            <td>
                                <input type="text" value="" placeholder="1000"  id="" ng-model="serviceInfo.currentBalance" class="form-control input-xs"> 
                            </td>
                            <td>
                                <input type="text" value="" id="" ng-model="serviceInfo.modifiedOn" class="form-control input-xs"> 
                            </td>
                        </tr>
                    </div>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

