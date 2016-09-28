<script>
    function return_payment(paymentInfo) {
        if (typeof paymentInfo.amount == "undefined" || paymentInfo.amount.length == 0) {
            alert("Please give an amount !");
            return;
        }
        angular.element($('#submit_create_payment')).scope().returnBalance(function (data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>payment/reseller_return_balance';
            })
        });
    }
</script>
 <div class="loader"></div>
<div class="ezttle"><span class="text">Payment</span></div>
<div class="mypage" ng-controller="paymentController">
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
                        <div class=" row form-group">
                            <label for="amount" class="col-md-6 control-label requiredField">
                                Amount
                            </label>
                            <label for="amount" class="col-md-6 control-label requiredField">
                                <input type="text" name="amount" ng-model="paymentInfo.amount" class="form-control" placeholder='eg: 100'>              
                            </label>
                        </div>
                        <div class="row form-group">
                            <label for="description" class="col-md-6 control-label requiredField">
                                Description
                            </label>
                            <label for="description" class="col-md-6 control-label requiredField">
                                <input type="text" name="description" ng-model="paymentInfo.description" class="form-control" >              
                            </label>
                        </div>
                        <div class="row form-group">
                            <label for="submit_create_payment" class="col-md-6 control-label requiredField">

                            </label>
                            <div class ="col-md-3 pull-right">
                                <button id="submit_create_payment" class="button-custom"  onclick="return_payment(angular.element(this).scope().paymentInfo)">Send</button>
                            </div> 
                        </div>
                    </div>
                </ng-form>
                </td>
                <td>
                </td>
                </tr>
                </tbody></table>
        </div> 
    </div>
</div>