<script>
    function update_balance(simNumber) {

        angular.element($("#update_balance_id")).scope().updateBalance(simNumber, function (data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>superadmin/sim';
            });
        });

    }
</script>
<div class="panel panel-default" ng-controller="simController">
    <div id="sim_info_show">
        <div class="panel-heading">SIM List</div>
        <div class="panel-body">
            <div class="row col-md-12" style="margin-left: 1px;">            
                <div class="row form-group" style="padding-left:10px;">
                    <div class ="col-md-3 pull-left form-group">
                        <a href="<?php echo base_url() . 'superadmin/sim/add_sim' ?>">
                            <button id="menu_create_id" value="" class="form-control pull-right btn_custom_button">Add Sim</button>  
                        </a>
                    </div>
                </div>
                <div class="row">
                    <?php if (isset($sim_list)) { ?>
                        <div ng-init="setSimList(<?php echo htmlspecialchars(json_encode($sim_list)) ?>)"></div>
                    <?php } ?> 
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table_row_style">
                                    <th style="text-align: center;">Sim Number</th>
                                    <th style="text-align: center;">Description</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: center;">Balance</th>
                                    <th style="text-align: center;">Show</th>
                                    <th style="text-align: center;">Edit</th>
                                </tr>
                                <tr ng-repeat="simInfo in simList">
                                    <th style="text-align: center;">{{simInfo.sim_no}}</th>
                                    <th style="text-align: center;">{{simInfo.description}}</th>
                                    <th style="text-align: center;" ng-if="simInfo.status == '<?php echo SIM_STATUS_ENABLE ?>'">Active</th>
                                    <th style="text-align: center;" ng-if="simInfo.status == '<?php echo SIM_STATUS_DISABLE ?>'">Inactive</th>
                                    <th style="text-align: center"><a id="update_balance_id" onclick="update_balance(angular.element(this).scope().simInfo.sim_no)">Update</a></th>
                                    <th style="text-align: center"><a href="<?php echo base_url() . "superadmin/sim/show_sim/"; ?>{{simInfo.sim_no}}">Show</a></th>
                                    <th style="text-align: center"><a href="<?php echo base_url() . "superadmin/sim/edit_sim/"; ?>{{simInfo.sim_no}}">Edit</a></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    <div id="edit_sim_info" style="display: none">
    <?php //$this->load->view("superadmin/sims/edit_sim"); ?>     
        </div>-->
</div>
