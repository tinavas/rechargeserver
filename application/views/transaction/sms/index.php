<script>

    $(function () {
        error_message = '<?php if (isset($error_message)) { echo $error_message; } ?>';
        if (error_message != "") {
            $("#content").html(error_message);
            $('#common_modal').modal('show');
        }
    });
    function sendSMS(transctionSMSDataList, smsInfo) {
        if (transctionSMSDataList.length <= 0) {
            $("#content").html("Please give a sms info! ");
            $('#common_modal').modal('show');
            return;
        } else if (typeof smsInfo.sms == "undefined" || smsInfo.sms.length == 0) {
            $("#content").html("Please write sms text! ");
            $('#common_modal').modal('show');
            return;
        } else {
            for (var i = 0; i < transctionSMSDataList.length; i++) {
                var index = i + +1;
                var transationInfo = transctionSMSDataList[i];
                if (typeof transationInfo.number == "undefined" || transationInfo.number.length == 0) {
                    $("#content").html("Please give a Cell Number at index " + index);
                    $('#common_modal').modal('show');
                    return;
                }
            }
            angular.element($('#send_sms_id')).scope().sendSMS(function (data) {
                $("#content").html(data.message);
                $('#common_modal').modal('show');
                $('#modal_ok_click_id').on("click", function () {
                    window.location = '<?php echo base_url() ?>transaction/sms';
                });
            });
        }
    }

  
    function add_sms_data(smsInfo) {
        if (typeof smsInfo.number == "undefined" || smsInfo.number.length == 0) {
            $("#content").html("Please give a Number");
            $('#common_modal').modal('show');
            return;
        }
        if (number_validation(smsInfo.number) == false) {
            $("#content").html("Please give a valid Number");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($('#sms_add_id')).scope().addSMSData(function () {
        });

    }
</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">SMS</span></div>
<div class="mypage"   ng-controller="transctionController">
    <div class="row" style="margin-top:5px;">
        <div class="col-md-12 fleft">	
            <input name="elctkn" value="30dfe1ad62facbf8e5b1ec2e46f9f084" style="display:none;" type="hidden">
            <table style="width:100%;" >
                <tbody>
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                            <div class="row col-md-12" id="box_content_2" class="box-content" style="padding-top: 10px;">
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row ">
                                        <div class="col-md-3">
                                            <a target="_blank" href="<?php echo base_url() . SMS_FILE_DOWNLOAD_DIRECTORY . SMS_XLSX_FILE_NAME ?>"><label class="cursor_pointer">Sample File</label></a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="<?php echo base_url() . "files/sms_readme_file_dowload" ?>"><label class="cursor_pointer">Help</label></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>

                            <div class=" row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        <?php echo form_open_multipart('transaction/sms', array('name' => 'file_upload')); ?>
                                        <div class="form-group">
                                            <label  class="col-md-2" for="fileupload">Upload:</label>
                                            <input class="col-md-4" id="fileupload" type="file" name="userfile">
                                            <div class="col-md-2"></div>
                                            <input id="submit_btn"  name="submit_btn" value="Upload" type="submit" class="col-md-2 button-custom"/>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <p class="help-block">Select ".XLSX" files only.</p>   
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <form>
                                            <label for="number" class="col-md-2 control-label requiredField">
                                                Number:
                                            </label>
                                            <label for="number" class="col-md-6 control-label requiredField">
                                                <input  ng-model="smsInfo.number" class="form-control" placeholder='eg: 0171XXXXXXX'>           
                                            </label>
                                            <button id="sms_add_id" class=" col-md-2 button-custom" onclick="add_sms_data(angular.element(this).scope().smsInfo)">Add</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row ">
                                        <label for="number" class="col-md-2 control-label requiredField">
                                            SMS
                                        </label>
                                        <label for="number" class="col-md-10 control-label requiredField">
                                            <span id="span_remaining_chars" class="pull-right">160 remaining</span>
                                            <span id="span_message_counter" class="pull-left"> sms 1</span>
                                            <textarea id="textarea_message" rows="5" name="number" ng-model="smsInfo.sms" class="form-control"></textarea>              
                                        </label>
                                    </div>
                                </div>


                            </div>
                            <div class="row form-group">

                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table10 form-group" cellspacing="0" >
                                        <thead>
                                            <tr>	
                                                <th>Serial</th>
                                                <th>Mobile Number</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php if (isset($transactions_data)) { ?>
                                            <div ng-init="setTransctionDataList(<?php echo htmlspecialchars(json_encode($transactions_data)); ?>)"></div>
                                        <?php } ?>
                                        <tbody>
                                            <tr ng-repeat="(key, transactionInfo) in transactionDataList">
                                                <td>
                                                    {{key + +1}}
                                                </td>
                                                <td>{{transactionInfo.number}}</td>
                                                <td style="text-align: center; cursor: pointer;" ng-click="deleteTransction(transactionInfo)"><div class="glyphicon glyphicon-trash"></div></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-offset-10 col-md-2">
                                            <button id="send_sms_id" class="button-custom pull-right" onclick="sendSMS(angular.element(this).scope().transactionDataList, angular.element(this).scope().smsInfo)">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="other-controller">
                <div class="text-center">
                    <dir-pagination-controls boundary-links="true" on-page-change="getPaymentHistoryByPagination(newPageNumber)" template-url="<?php echo base_url(); ?>history/pagination_tmpl_load"></dir-pagination-controls>
                </div>
            </div>

        </div> 
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#textarea_message').keyup(function () {
            var chars = this.value.length;
            var messages = Math.ceil(chars / 160);
            var remaining = messages * 160 - (chars % (messages * 160) || messages * 160);
            $('#span_message_counter').text('sms ' + messages);
            $('#span_remaining_chars').text(remaining + ' remaining');
        });
    });
</script>