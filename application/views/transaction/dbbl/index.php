<script>

    function dbbl(dbblInfo) {
        console.log(dbblInfo.number);
        if (typeof dbblInfo.number == "undefined" || dbblInfo.number.length == 0) {
            $("#content").html("Please give a DBBL Number");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof dbblInfo.amount == "undefined" || dbblInfo.amount.length == 0) {
            $("#content").html("Please give an amount ");
            $('#common_modal').modal('show');
            return;
        }
        if (dbblInfo.amount < <?php echo DBBL_MINIMUM_CASH_IN_AMOUNT ?>) {
            $("#content").html("Please give a minimum amount TK. " + '<?php echo DBBL_MINIMUM_CASH_IN_AMOUNT ?>');
            $('#common_modal').modal('show');
            return;
        }
        if (dbblInfo.amount > <?php echo DBBL_MAXIMUM_CASH_IN_AMOUNT; ?>) {
            $("#content").html("Please give a maximum amount TK. " + '<?php echo DBBL_MAXIMUM_CASH_IN_AMOUNT; ?>');
            $('#common_modal').modal('show');
            return;
        }
        angular.element($('#dbbl_cash_in_id')).scope().dbbl(function(data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function() {
                window.location = '<?php echo base_url() ?>transaction/dbbl';
            });

        });
    }
    $(function() {
        setInterval(callFunction, <?php echo TRANSACTION_LIST_CALLING_INTERVER; ?>);
    });
    function callFunction() {
        var serviceIdList = [<?php echo SERVICE_TYPE_ID_DBBL_CASHIN; ?>];
        angular.element($('#transaction_list_id')).scope().getAjaxTransactionList(serviceIdList);
    }
</script>

<div class="loader"></div>
<div class="ezttle"><span class="text">DBBL</span></div>
<div class=" mypage"  ng-controller="transctionController">
    <div class="row" style="margin-top:5px;">
        <div class="col-md-12 fleft">	
            <input name="elctkn" value="30dfe1ad62facbf8e5b1ec2e46f9f084" style="display:none;" type="hidden">
            <table style="width:100%;">
                <tbody><tr>
                        <td style="width:50%;vertical-align:top;padding-right:20px;">
                <ng-form>
                    <?php // echo form_open("transaction/dbbl", array('id' => 'form_create_dbbl', 'class' => 'form-horizontal')); ?>
                    <div class="row col-md-12" id="box_content_2" class="box-content" style="padding-top: 10px;">
                        <div class ="row">
                            <div class="col-md-12"> <?php // echo $message;          ?> </div>
                        </div>
                        <div class="form-group">
                            <label for="number" class="col-md-6 control-label requiredField">
                                Number
                            </label>
                            <label for="number" class="col-md-6 control-label requiredField">
                                <input type="text" name="number" ng-model="dbblInfo.number" class="form-control" placeholder='eg: 0171XXXXXXX'>  
                                <?php // echo form_input($number + array('class' => 'form-control')); ?>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="amount" class="col-md-6 control-label requiredField">
                                Amount
                            </label>
                            <label for="amount" class="col-md-6 control-label requiredField">
                                <input type="text" name="amount" ng-model="dbblInfo.amount" class="form-control"  placeholder='eg: 100'>  
                                <?php // echo form_input($amount + array('class' => 'form-control')); ?>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="submit_update_api" class="col-md-6 control-label requiredField">

                            </label>
                            <div class ="col-md-3 pull-right">
                                <button id="dbbl_cash_in_id" class="form-control button"  onclick="dbbl(angular.element(this).scope().dbblInfo)">Send</button>
                                <?php // echo form_submit($submit_create_transaction + array('class' => 'form-control button')); ?>
                            </div> 
                        </div>
                    </div>
                </ng-form>
                <?php // echo form_close(); ?>
                </td>
                <td>
                </td><td   id="transaction_list_id" style="width:50%;vertical-align:top;padding-right:15px;"  ng-init="setTransctionList(<?php echo htmlspecialchars(json_encode($transaction_list)); ?>)">
                    <p class="help-block">Last 10 Requests</p>
                    <div style="margin:0px;padding:0px;background:#fff;">
                        <table class="table10" cellspacing="0">
                            <thead>
                                <tr>	
                                    <th>Number</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php // foreach ($transaction_list as $transaction_info) { ?>
                                <tr ng-repeat="transactionInfo in transctionList">	
                                    <td>{{transactionInfo.cell_no}}</td>
                                    <td>{{transactionInfo.amount}}</td>
                                    <td>{{transactionInfo.status}}</td>
                                    <td>{{transactionInfo.created_on}}</td>
                                </tr>	
                            </tbody>
                        </table>
                    </div></td>
                </tr>
                </tbody></table>
        </div> 
    </div>
</div>

