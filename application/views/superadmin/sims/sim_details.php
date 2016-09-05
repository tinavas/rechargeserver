<script>
    function load_balance_show(loadBalanceInfo) {
        
        $("#service_title").val(loadBalanceInfo.service_title);
        $("#service_id").val(loadBalanceInfo.service_id);
        $("#sim_number").val('<?php echo $sim_no ?>');
        $("#sim_details_id").hide();
        $("#load_balance").show();

    }
</script>
<div class="panel panel-default" ng-controller="simController">
    <div id="sim_details_id">  
        <div class="panel-heading">Sim Details -> <?php //echo $sim_info ?></div>
        <div class="panel-body">
            <div class="row" style="margin-left: 1px;">  
                <div class="col-md-12">
                    <div class="row">
                        <?php if (isset($service_list)) { ?>
                            <div ng-init="setSimServiceList(<?php echo htmlspecialchars(json_encode($service_list)) ?>)"></div>
                        <?php } ?> 
                        <div class="table-responsive">

                            <table class="table table-bordered">
                                <thead>
                                    <tr class="table_row_style">
                                        <th style="text-align: center;">Service</th>
                                        <th style="text-align: center;">Current Balance</th>
                                        <th style="text-align: center;">add Balance</th>
                                        <th style="text-align: center;">Transactions</th>
                                    </tr>
                                    <tr ng-repeat="serviceInfo in simServiceList">
                                    <th style="text-align: center;">{{serviceInfo.service_title}}</th>
                                    <th style="text-align: center;">{{serviceInfo.current_balance}}</th>
                                    <th style="text-align: center"><a id="load_balance_id" onclick="load_balance_show(angular.element(this).scope().serviceInfo)">load Balance</a></th>
                                    <th style="text-align: center"><a href="<?php echo base_url() . "superadmin/transaction/get_sim_transactions/"; ?><?php echo $sim_no;?>">show</a></th>
                                </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="load_balance" style="display: none">
        <?php $this->load->view("superadmin/sims/sim_load_balance"); ?> 
    </div>
   
</div>





