<script>
    function sendSMS(transctionSMSDataList) {
        if (transctionSMSDataList.length <= 0) {
            $("#content").html("Please upload a XLSX file ");
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
                            <div class="row form-group"></div>

                            <div class=" row form-group">

                                <div class="col-md-6">
                                    <div class="row ">
                                        <div class="col-md-3">
                                            <a target="_blank" href="<?php echo base_url() . SMS_FILE_DOWNLOAD_DIRECTORY . SMS_XLSX_FILE_NAME ?>"><label class="cursor_pointer">Sample File</label></a>
                                        </div>
                                        <div class="col-md-3">
                                            <a target="_blank" href="<?php echo base_url() . SMS_FILE_DOWNLOAD_DIRECTORY . SMS_README_FILE_NAME ?>"><label class="cursor_pointer">Help</label></a>
                                        </div>
                                        <div class="col-md-6" >
                                            <?php echo form_open_multipart('transaction/sms', array('name' => 'file_upload')); ?>
                                            <div class="form-group">
                                                <label for="fileupload">Upload:</label>
                                                <input id="fileupload" type="file" name="userfile">
                                                <p class="help-block">Select ".XLSX" files only.</p>
                                                <input id="submit_btn" name="submit_btn" value="Upload" type="submit" class="button-custom"/>
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>
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
<!--                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="50%">Name</th>
                                                <th ng-show="uploader.isHTML5">Size</th>
                                                <th ng-show="uploader.isHTML5">Progress</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="item in uploader.queue">
                                                <td><strong>{{ item.file.name}}</strong></td>
                                                <td ng-show="uploader.isHTML5" nowrap>{{ item.file.size / 1024 / 1024|number:2 }} MB</td>
                                                <td ng-show="uploader.isHTML5">
                                                    <div class="progress" style="margin-bottom: 0;">
                                                        <div class="progress-bar" role="progressbar" ng-style="{
                                                                    'width': item.progress + '%' }"></div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
                                                    <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
                                                    <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
                                                </td>
                                                <td nowrap>
                                                    <button type="button" class="btn btn-success btn-xs" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                                                        <span class="glyphicon glyphicon-upload"></span> Upload
                                                    </button>
                                                    <button type="button" class="btn btn-warning btn-xs" ng-click="item.cancel()" ng-disabled="!item.isUploading">
                                                        <span class="glyphicon glyphicon-ban-circle"></span> Cancel
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>-->
                                    <table class="table10 form-group" cellspacing="0" >
                                        <thead>
                                            <tr>	
                                                <th>Serial</th>
                                                <th>Mobile Number</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php if (isset($transactions_data)) { ?>
                                            <tbody ng-init="setTransctionDataList(<?php echo htmlspecialchars(json_encode($transactions_data)); ?>)">
                                                <tr ng-repeat="(key, transactionInfo) in transactionDataList">
                                                    <td>
                                                        {{key + +1}}
                                                    </td>
                                                    <td>{{transactionInfo.number}}</td>
                                                    <td style="text-align: center; cursor: pointer;" ng-click="deleteTransction(transactionInfo)"><div class="glyphicon glyphicon-trash"></div></td>
                                                </tr>
                                            </tbody>
                                        <?php } ?>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-offset-10 col-md-2">
                                            <button id="send_sms_id" class="button-custom pull-right" onclick="sendSMS(angular.element(this).scope().transactionDataList)">Submit</button>
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