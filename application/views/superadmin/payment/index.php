<div class="panel panel-default">
    <div class="panel-heading">Payments</div>
    <div class="panel-body">
        <div class="row col-md-12" style="margin-left: 1px;">            
            <div class="row form-group" style="padding-left:10px;">
                <div class ="col-md-2 pull-left form-group">
                    <a href="<?php echo base_url() . 'payment/create_payment' ?>">
                        <button id="" value="" class="form-control pull-right btn_custom_button">Add Payment</button>  
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="table_row_style">
                                <th style="text-align: center;">Subscribers</th>
                                <th style="text-align: center;">Amount</th>
                                <th style="text-align: center;">Edit</th>
                                <th style="text-align: center;">Delete</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;"> Registration date</th>
                                <th style="text-align: center;">5000</th>
                                <th style="text-align: center"><a href="<?php echo base_url() . "payment/update_payment"; ?>">Edit</a></th>
                                <th style="text-align: center; cursor: pointer;"><a onclick="open_modal_delete_payment()"value="" class="">
                                        Delete </a></th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Expired date</th>
                                <th style="text-align: center;">3000</th>
                                <th style="text-align: center"><a href="<?php echo base_url() . "payment/update_payment"; ?>">Edit</a></th>
                                <th style="text-align: center; cursor: pointer;"><a onclick="open_modal_delete_payment()"value="" class="">
                                        Delete </a></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("superadmin/payment/modal_delete_payment");
