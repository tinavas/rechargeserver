<script>
    $(function () {
        $('#registration_date').Zebra_DatePicker();
    });
    function get_edit_sim_info(simInfo) {
        angular.element($("#edit_sim_info_id")).scope().getSimServiceList(simInfo.sim_no, function () {
            $("#sim_info_show").hide();
            $("#sim_no").val(simInfo.sim_no);
            $("#registration_date").val(simInfo.regitration_date);
            $("#edit_sim_info").show();
        });
    }
</script>

<div class="panel panel-default" ng-controller="transctionController">
    <div id="sim_info_show">
        <div class="panel-heading">Sims</div>
        <div class="panel-body">
            <div class="row col-md-12" style="margin-left: 1px;">            
                <div class="row form-group" style="padding-left:10px;">
                    <div class ="col-md-3 pull-left form-group">
                        <a href="<?php echo base_url() . 'superadmin/transaction/add_sim' ?>">
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
                                    <th style="text-align: center;">Registration date</th>
                                    <th style="text-align: center;">Total Balance</th>
                                    <th style="text-align: center;">Details</th>
                                    <th style="text-align: center;">Edit</th>
                                    <th style="text-align: center;">Status</th>
                                </tr>
                                <tr ng-repeat="simInfo in simList">
                                    <th style="text-align: center;">{{simInfo.sim_no}}</th>
                                    <th style="text-align: center;">{{simInfo.regitration_date}}</th>
                                    <th style="text-align: center;">{{simInfo.total_balance}}</th>
                                    <th style="text-align: center"><a href="<?php echo base_url() . "superadmin/transaction/sim_details/"; ?>{{simInfo.sim_no}}">show</a></th>
                                    <th style="text-align: center"><a id="edit_sim_info_id" onclick="get_edit_sim_info(angular.element(this).scope().simInfo)">edit</a></th>
                                    <th style="text-align: center">{{simInfo.status}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="edit_sim_info" style="display: none">
        <?php $this->load->view("superadmin/sims/edit_sim"); ?>     
    </div>
</div>
