<script>

    function m_cash(mCashInfo) {
        console.log(mCashInfo.number);
        if (typeof mCashInfo.number == "undefined" || mCashInfo.number.length == 0) {
            $("#content").html("Please give a M-CASH Number");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof mCashInfo.amount == "undefined" || mCashInfo.amount.length == 0) {
            $("#content").html("Please give an amount ");
            $('#common_modal').modal('show');
            return;
        }
        if (mCashInfo.amount < <?php echo MCASH_MINIMUM_CASH_IN_AMOUNT ?>) {
            $("#content").html("Please give a minimum amount TK. " + '<?php echo MCASH_MINIMUM_CASH_IN_AMOUNT ?>');
            $('#common_modal').modal('show');
            return;
        }
        if (mCashInfo.amount > <?php echo MCASH_MAXIMUM_CASH_IN_AMOUNT; ?>) {
            $("#content").html("Please give a maximum amount TK. " + '<?php echo MCASH_MAXIMUM_CASH_IN_AMOUNT; ?>');
            $('#common_modal').modal('show');
            return;
        }
        angular.element($('#m_cash_in_id')).scope().mCash(function(data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function() {
                window.location = '<?php echo base_url() ?>transaction/mcash';
            });
        });
    }
</script>

<div class="loader"></div>
<div class="ezttle"><span class="text">M-Cash</span></div>
<div class="mypage" ng-controller="transctionController">
    <div class="row" style="margin-top:5px;">
        <div class="col-md-12 fleft">	
            <input name="elctkn" value="30dfe1ad62facbf8e5b1ec2e46f9f084" style="display:none;" type="hidden">
            <table style="width:100%;">
                <tbody><tr>
                        <td style="width:50%;vertical-align:top;padding-right:20px;">
                <ng-form>
                    <div class="row col-md-12" id="box_content_2" class="box-content" style="padding-top: 10px;">
                        <div class ="row">
                            <div class="col-md-12"></div>
                        </div>
                        <div class="form-group">
                            <label for="number" class="col-md-6 control-label requiredField">
                                Number
                            </label>
                            <label for="number" class="col-md-6 control-label requiredField">
                                <input type="text" name="number" ng-model="mCashInfo.number" class="form-control" placeholder='eg: 0171XXXXXXX'> 
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="amount" class="col-md-6 control-label requiredField">
                                Amount
                            </label>
                            <label for="amount" class="col-md-6 control-label requiredField">
                                <input type="text" name="amount" ng-model="mCashInfo.amount" class="form-control"  placeholder='eg: 100'>  
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="submit_update_api" class="col-md-6 control-label requiredField">

                            </label>
                            <div class ="col-md-3 pull-right">
                                <button id="m_cash_in_id" class="form-control button"  onclick="m_cash(angular.element(this).scope().mCashInfo)">Send</button>
                            </div> 
                        </div>
                    </div>
                </ng-form>
                </td>
                <td>
                </td><td style="width:50%;vertical-align:top;padding-right:15px;" ng-init="setTransctionList(<?php echo htmlspecialchars(json_encode($transaction_list)); ?>)">
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