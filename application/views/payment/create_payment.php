<div class="ezttle"><span class="text">Payment</span></div>
<div class="mypage">
    <div class="row" style="margin-top:5px;">
        <div class="col-md-12 fleft">	
                <input name="elctkn" value="30dfe1ad62facbf8e5b1ec2e46f9f084" style="display:none;" type="hidden">
                <table style="width:100%;">
                    <tbody><tr>
                            <td style="width:50%;vertical-align:top;padding-right:20px;">
                                <?php echo form_open("payment/create_payment/".$user_id, array('id' => 'form_create_payment', 'class' => 'form-horizontal')); ?>
                                <div class="row col-md-12" id="box_content_2" class="box-content" style="padding-top: 10px;">
                                    <div class ="row">
                                        <div class="col-md-12"> <?php echo $message; ?> </div>
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
                                        <label for="payment_type_list" class="col-md-6 control-label requiredField">
                                            Type
                                        </label>
                                        <label for="payment_type_list" class="col-md-6 control-label requiredField">
                                            <?php echo form_dropdown('payment_type_list', $payment_type_list, '', 'class=form-control id=payment_type_list'); ?>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="col-md-6 control-label requiredField">
                                            Description
                                        </label>
                                        <label for="description" class="col-md-6 control-label requiredField">
                                            <?php echo form_input($description + array('class' => 'form-control')); ?>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="submit_create_payment" class="col-md-6 control-label requiredField">

                                        </label>
                                        <div class ="col-md-3 pull-right">
                                            <?php echo form_submit($submit_create_payment + array('class' => 'form-control button')); ?>
                                        </div> 
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                    </tbody></table>
            		</div> 
    </div>
</div>