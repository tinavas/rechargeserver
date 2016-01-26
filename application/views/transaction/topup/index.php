
<script>

    function top_up(topUpInfo) {
        console.log(topUpInfo.number);
        if (typeof topUpInfo.number == "undefined" || topUpInfo.number.length == 0) {
            alert("Please give a TopUP Number");
            return;
        }
        if (typeof topUpInfo.amount == "undefined" || topUpInfo.amount.length == 0) {
            alert("Please give an amount ");
            return;
        }
        angular.element($('#top_up_id')).scope().topUp(function (data) {
            alert(data.message);
            window.location = '<?php echo base_url() ?>transaction/top_up' ;

        });
    }
</script>
<div class="ezttle"><span class="text">Topup</span></div>
<div class="mypage" ng-app="app.Transction" ng-controller="transctionController">
    <div class="row" style="margin-top:5px;">
        <div class="col-md-12 fleft">	
            <input name="elctkn" value="30dfe1ad62facbf8e5b1ec2e46f9f084" style="display:none;" type="hidden">
            <table style="width:100%;">
                <tbody><tr>
                        <td style="width:50%;vertical-align:top;padding-right:20px;">
                <ng-form>
                    <div class="row col-md-12" id="box_content_2" class="box-content" style="padding-top: 10px;">
                        <div class ="row">
                            <div class="col-md-12">  </div>
                        </div>
                        <div class="row form-group">
                            <label for="number" class="col-md-6 control-label requiredField">
                                Number
                            </label>
                            <label for="number" class="col-md-6 control-label requiredField">
                                <input type="text" name="number" ng-model="topUpInfo.number" class="form-control" placeholder='eg: 0171XXXXXXX'> 
                            </label>
                        </div>
                        <div class="row form-group">
                            <label for="amount" class="col-md-6 control-label requiredField">
                                Amount
                            </label>
                            <label for="amount" class="col-md-6 control-label requiredField">
                                <input type="text" name="amount" ng-model="topUpInfo.amount" class="form-control"  placeholder='eg: 100'>  
                            </label>
                        </div>
                        <div class="row form-group">
                            <label for="type" class="col-md-6 control-label requiredField">
                                Type
                            </label>
                            <select for="type" class="col-md-6 control-label requiredField" ng-init="setTopUpTypeList(<?php echo htmlspecialchars(json_encode($topup_type_list)); ?>)">
                                <option class=form-control ng-repeat="topupType in topupTypeList" value="{{topupType}}">{{topupType}}</option>
                            </select>
                        </div>
                        <div class="row form-group">
                            <label for="operator" class="col-md-6 control-label requiredField">
                                Operator
                            </label>
                            <select for="operator" class="col-md-6 control-label requiredField" ng-init="setTopupOperatorList(<?php echo htmlspecialchars(json_encode($topup_operator_list)); ?>)">
                                <option class=form-control ng-repeat="topupOperator in topupOperatorList" value="{{topupOperator}}">{{topupOperator}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="submit_update_api" class="col-md-6 control-label requiredField">

                            </label>
                            <div class ="col-md-3 pull-right">
                                <button id="top_up_id" class="form-control button"  onclick="topup(angular.element(this).scope().topUpInfo)">Send</button>
                                <?php // echo form_submit($submit_create_transaction + array('class' => 'form-control button')); ?>
                            </div> 
                        </div>
                    </div>
                </ng-form>
                </td>
                <td>
                </td><td style="width:50%;vertical-align:top;padding-right:15px;"  ng-init="setTransctionList(<?php echo htmlspecialchars(json_encode($transaction_list)); ?>)">
                    <p class="help-block">Last 10 Requests</p>
                    <div style="margin:0px;padding:0px;background:#fff;">
                        <table class="table10" cellspacing="0">
                            <thead>
                                <tr>	
                                    <th>Number</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="transactionInfo in transctionList">	
                                    <td>{{transactionInfo.cell_no}}</td>
                                    <td>{{transactionInfo.amount}}</td>
                                    <td>{{transactionInfo.status}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div></td>
                </tr>
                </tbody></table>
        </div> 
    </div>
</div>