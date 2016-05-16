<script>
    function load_balance(blanaceInfo) {
        blanaceInfo.serviceTitle = $("#service_title").val();
        blanaceInfo.serviceId = $("#service_id").val();
        blanaceInfo.simNo = $("#sim_number").val();
        if (typeof blanaceInfo.amount == "undefined" || blanaceInfo.amount.length == 0) {
            $("#content").html("Please give an amount !");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($("#load_balance_btn")).scope().loadBalance(blanaceInfo, function (data) {

            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>superadmin/sim/show_sims';
            });
        });

    }

</script>

<div class="panel-heading">Load Balance</div>
<div class="panel-body">
    <div class="form-background top-bottom-padding">
        <div class="row">
            <div class ="col-md-8 margin-top-bottom">
                <form>
                    <div class="row form-group">
                        <label for="title" class="col-md-6 control-label requiredField">
                            Service:
                        </label>
                        <div class ="col-md-6">
                            <input type="text"  class="form-control" id="service_title" >
                            <input type="hidden" value="" class="form-control" id="service_id" >
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="sim_member" class="col-md-6 control-label requiredField">
                            Sim Number:
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control"  id="sim_number" >
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="registration_date" class="col-md-6 control-label requiredField">
                            Balance :
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control" placeholder="Ex-1000"  id="" ng-model="loadBalanceInfo.amount" >
                        </div> 
                    </div>
                    <div class="row form-group">
                        </label>
                        <div class ="col-md-3 pull-right">
                            <input id="load_balance_btn" name="" class="btn btn_custom_button" type="submit" onclick="load_balance(angular.element(this).scope().loadBalanceInfo)" value="Send"/>
                        </div> 
                    </div>
                </form>
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
