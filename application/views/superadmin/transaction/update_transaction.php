
<script>
    function update_transction(transactionInfo) {
        if (typeof transactionInfo.trx_id_operator == "undefined" || transactionInfo.trx_id_operator.length == 0) {
            $("#content").html("Please give Transaction operator id !");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($("#submit_update_transction")).scope().updateTransction(function (data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>superadmin/transaction/get_transaction_list';
            });
        });

    }

</script>

<div class="panel panel-default" ng-controller="transctionController">
    <div class="panel-heading">Update Transactions</div>
    <div class="panel-body" ng-init="setTransactionInfo('<?php echo htmlspecialchars(json_encode($transaction_info)) ?>')">
        <div class="form-background top-bottom-padding">
            <div class="row">
                <div class ="col-md-8 margin-top-bottom">

                    <div class="row form-group">
                        <label for="transaction_operator_id" class="col-md-6 control-label requiredField">
                            Tranx.op.Id : 
                        </label>
                        <div class ="col-md-6">
                            <input type="text" value="" class="form-control" ng-model="transactionInfo.trx_id_operator">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="transaction_id" class="col-md-6 control-label requiredField">
                            Tranx.Id : 
                        </label>
                        <div class ="col-md-6">
                            <input readonly="" type="text" value="" class="form-control" ng-model="transactionInfo.transaction_id">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="number" class="col-md-6 control-label requiredField">
                            Number : 
                        </label>
                        <div class ="col-md-6">
                            <input readonly="" type="text" value="" class="form-control" ng-model="transactionInfo.cell_no">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="amount" class="col-md-6 control-label requiredField">
                            Amount : 
                        </label>
                        <div class ="col-md-6">
                            <input readonly="" type="text" value="" class="form-control" ng-model="transactionInfo.amount">
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="status_id" class="col-md-6 control-label requiredField">
                            Status_id : 
                        </label>
                        <div class ="col-md-6">
                            <select  for="status_type" id="type"  ng-model="transactionInfo.status_id" class="form-control control-label requiredField" ng-init="setTansactionStatusList('<?php echo htmlspecialchars(json_encode($transaction_status_list)); ?>')">
                                <option ng-selected="statusInfo.selected" class=form-control ng-repeat="statusInfo in transactionStatusList" value="{{statusInfo.id}}">{{statusInfo.title}}</option>
                            </select>
                        </div> 
                    </div>
                    <div class="row form-group">
                        <label for="submit_update_transaction" class="col-md-6 control-label requiredField">
                        </label>
                        <div class ="col-md-3 pull-right">
                            <input id="submit_update_transction" name="submit_update_transction" class="btn btn_custom_button" type="submit" onclick="update_transction(angular.element(this).scope().transactionInfo)" value="Update"/>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
