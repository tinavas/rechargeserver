<div class="panel panel-default" ng-controller="serviceController">
    <div class="panel-heading">Services</div>
    <div class="panel-body">
        <div class="row col-md-12">            
<!--            <div class="row form-group" style="padding-left:10px;">
                <div class ="col-md-3 pull-left form-group">
                    <a href="<?php echo base_url() . 'superadmin/service/create_service' ?>">
                        <button id="menu_create_id" value="" class="form-control pull-right btn_custom_button">Create Service</button>  
                    </a>
                </div>
            </div>-->
            <div class="row" ng-init="setServiceList('<?php echo htmlspecialchars(json_encode($service_list)) ?>')">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Process type</th>
                                <th style="text-align: center">Edit</th>
                            </tr>
                            <tr ng-repeat="serviceInfo in serviceList">
                                <td>{{serviceInfo.title}}</td>
                                <td>{{serviceInfo.process_type}}</td>
                                <td>
                                    <a href="<?php echo base_url() . "superadmin/service/update_service/" ; ?>{{serviceInfo.id}}">
                                        Edit
                                    </a>
                                </td>                                
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
