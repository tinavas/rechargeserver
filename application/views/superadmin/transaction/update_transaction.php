
<script>
    function update_transction(transctionInfo) {

        var createdOn = $("#created_on").val();
        var modifiedOn = $("#modified_on").val();
        transctionInfo.createdOn = createdOn;
        transctionInfo.modifiedOn = modifiedOn;

        if (typeof transctionInfo.apikey == "undefined" || transctionInfo.apikey.length == 0) {
            $("#content").html("Please give API key!");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof transctionInfo.balanceIn == "undefined" || transctionInfo.balanceIn.length == 0) {
            $("#content").html("Balance In is empty !");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof transctionInfo.balanceOut == "undefined" || transctionInfo.balanceOut.length == 0) {
            $("#content").html("Balance Out is empty !");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof transctionInfo.transactionStatusId == "undefined" || transctionInfo.transactionStatusId.length == 0) {
            $("#content").html("Transction status Id is Required !");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof transctionInfo.transactionTypeId == "undefined" || transctionInfo.transactionTypeId.length == 0) {
            $("#content").html("Transction Type Id is Required !");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof transctionInfo.cellNumber == "undefined" || transctionInfo.cellNumber.length == 0) {
            $("#content").html("Cell Number is Required !");
            $('#common_modal').modal('show');
            return;
        }
        
     
        angular.element($("#submit_update_transction")).scope().updateTransction(function (data) {

            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>superadmin/transaction/show_transactions';
            });
        });

    }

</script>

<div class="panel panel-default" ng-controller="transctionController">
    <div class="panel-heading">Update Transactions</div>
    <div class="panel-body" ng-init="setTransctionInfo('<?php echo htmlspecialchars(json_encode($transction_info))?>')">
        <div class="form-background top-bottom-padding">
            <div class="row">
                <div class ="col-md-8 margin-top-bottom">
                  
                    <div class="row form-group">
                        <label for="api_key" class="col-md-6 control-label requiredField">
                            Api_key : 
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control" ng-model="transctionInfo.apikey">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="balance_in" class="col-md-6 control-label requiredField">
                            Balance_in : 
                        </label>
                        <div class ="col-md-6">
                             <input type="text" value="" class="form-control" ng-model="transctionInfo.balanceIn">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="balance_out" class="col-md-6 control-label requiredField">
                            Balance_out : 
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control" ng-model="transctionInfo.balanceOut">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="status_id" class="col-md-6 control-label requiredField">
                            Status_id : 
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control" ng-model="transctionInfo.transactionStatusId">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="type_id" class="col-md-6 control-label requiredField">
                            Type_id : 
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control" ng-model="transctionInfo.transactionTypeId">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="cell_no" class="col-md-6 control-label requiredField">
                            Cell_no : 
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control" ng-model="transctionInfo.cellNumber">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="description" class="col-md-6 control-label requiredField">
                            Description : 
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control" ng-model="transctionInfo.description">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="created_on" class="col-md-6 control-label requiredField">
                            Created_on : 
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" id="created_on" class="form-control" ng-model="transctionInfo.createdOn">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="modified_on" class="col-md-6 control-label requiredField">
                            Modified_on : 
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" id="modified_on" class="form-control" ng-model="transctionInfo.modifiedOn">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="submit_update_transaction" class="col-md-6 control-label requiredField">
                        </label>
                        <div class ="col-md-3 pull-right">
                             <input id="submit_update_transction" name="submit_update_transction" class="btn btn_custom_button" type="submit" onclick="update_transction(angular.element(this).scope().transctionInfo)" value="Update"/>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#created_on').Zebra_DatePicker();
        $('#modified_on').Zebra_DatePicker();
    });
</script>