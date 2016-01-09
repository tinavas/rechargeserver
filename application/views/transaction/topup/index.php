<div class="ezttle"><span class="text">Topup</span></div>
<div class="mypage">
    <div class="row" style="margin-top:5px;">
        <div class="col-md-12 fleft">	
                <input name="elctkn" value="30dfe1ad62facbf8e5b1ec2e46f9f084" style="display:none;" type="hidden">
                <table style="width:100%;">
                    <tbody><tr>
                            <td style="width:50%;vertical-align:top;padding-right:20px;">
                                <?php echo form_open("transaction/topup", array('id' => 'form_create_topup', 'class' => 'form-horizontal')); ?>
                                <div class="row col-md-12" id="box_content_2" class="box-content" style="padding-top: 10px;">
                                    <div class ="row">
                                        <div class="col-md-12"> <?php echo $message; ?> </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="number" class="col-md-6 control-label requiredField">
                                            Number
                                        </label>
                                        <label for="number" class="col-md-6 control-label requiredField">
                                            <?php echo form_input($number + array('class' => 'form-control')); ?>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount" class="col-md-6 control-label requiredField">
                                            Amount
                                        </label>
                                        <label for="amount" class="col-md-6 control-label requiredField">
                                            <?php echo form_input($amount + array('class' => 'form-control')); ?>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="type" class="col-md-6 control-label requiredField">
                                            Type
                                        </label>
                                        <label for="type" class="col-md-6 control-label requiredField">
                                            <?php echo form_dropdown('topup_type_list', $topup_type_list, '', 'class=form-control id=topup_type_list'); ?>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="operator" class="col-md-6 control-label requiredField">
                                            Operator
                                        </label>
                                        <label for="operator" class="col-md-6 control-label requiredField">
                                            <?php echo form_dropdown('topup_operator_list', $topup_operator_list, '', 'class=form-control id=topup_operator_list'); ?>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="submit_update_api" class="col-md-6 control-label requiredField">

                                        </label>
                                        <div class ="col-md-3 pull-right">
                                            <?php echo form_submit($submit_create_transaction + array('class' => 'form-control button')); ?>
                                        </div> 
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </td>
                            <td>
                            </td><td style="width:50%;vertical-align:top;padding-right:15px;">
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
                                            <?php foreach($transaction_list as $transaction_info){?>
                                            <tr>	
                                                <td><?php echo $transaction_info['cell_no']?></td>
                                                <td><?php echo $transaction_info['amount']?></td>
                                                <td><?php echo $transaction_info['status']?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div></td>
                        </tr>
                    </tbody></table>
            		</div> 
    </div>
</div>