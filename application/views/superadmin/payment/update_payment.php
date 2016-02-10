<div class="panel panel-default">
    <div class="panel-heading">Update Payment</div>
    <div class="panel-body">
        <div class="form-background top-bottom-padding">
            <div class="row">
                <div class ="col-md-8 margin-top-bottom">
                   <?php echo form_open("payment/update_payment", array('id' => 'form_update_payment', 'class' => 'form-horizontal')); ?>
                    <div class ="row">
                        <div class="col-md-12"> </div>
                    </div>
                    <div class="form-group">
                        <label for="suscribers" class="col-md-6 control-label requiredField">
                            Subscribers
                        </label>
                        <div class ="col-md-6">
                            <select id="subscriber_list" class="form-control" name="subscriber_list">
                                <option selected="selected" value="0">Select</option>
                                <option value="1">Registrtaion Date</option>
                                <option value="2">Expired Date</option>
                                <option value="3">Max members</option>
                            </select>
                        </div> 
                    </div>
                    <div class="form-group">
                        <label for="amount" class="col-md-6 control-label requiredField">
                            Amount : 
                        </label>
                        <div class ="col-md-6">
                            <input id="amount" class="form-control" type="text" value="" name="amount">
                        </div> 
                    </div>
                    <div class="form-group">
                        <label for="submit_update_payment" class="col-md-6 control-label requiredField">
                        </label>
                        <div class ="col-md-3 pull-right">
                           <input id="submit_update_subscriber" class="form-control button btn_custom_button" type="submit" value="update" name="submit_update_subscriber">
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>