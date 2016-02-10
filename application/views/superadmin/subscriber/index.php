<div class="panel panel-default" ng-controller="subscriberController">
    <div class="panel-heading">Subscribers</div>
    <div class="panel-body">
        <div class="row col-md-12" style="margin-left: 1px;">            
            <div class="row form-group" style="padding-left:10px;">
                <div class ="col-md-3 pull-left form-group">
                    <a href="<?php echo base_url() . 'superadmin/subscriber/create_subscriber' ?>">
                        <button id="menu_create_id" value="" class="form-control pull-right btn_custom_button">Add Subscriber</button>  
                    </a>
                </div>
            </div>
            <div class="row" ng-init="setSubscriberList('<?php echo htmlspecialchars(json_encode($subscriber_list))?>')">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="table_row_style">
                                <th style="text-align: center;">Registration date</th>
                                <th style="text-align: center;">Expired date</th>
                                <th style="text-align: center;">Max members</th>
                                <th style="text-align: center;">Ip address</th>
                                <th style="text-align: center;">Edit</th>
                            </tr>
                            <tr ng-repeat="subscriberInfo in subscriberList">
                                    <th style="text-align: center;">{{subscriberInfo.registrationDate}}</th>
                                    <th style="text-align: center;">{{subscriberInfo.expiredDate}}</th>
                                    <th style="text-align: center;">{{subscriberInfo.maxMembers}}</th>
                                    <th style="text-align: center;">{{subscriberInfo.ipAddress}}</th>
                                    <th style="text-align: center"><a href="<?php echo base_url() . "superadmin/subscriber/update_subscriber/" ;?>{{subscriberInfo.userId}}">Edit</a></th>
                                </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
