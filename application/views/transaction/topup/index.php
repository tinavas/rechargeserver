<script>

    $(function () {
        error_message = '<?php if (isset($error_message)) { echo $error_message;}?>';
        if (error_message != "") {
            console.log(error_message);
            $("#content").html(error_message);
            $('#common_modal').modal('show');
        }
    });


    function number_validation(phoneNumber) {
        var regexp = /^((^\+880|0)[1][1|6|7|8|9])[0-9]{8}$/;
        var validPhoneNumber = phoneNumber.match(regexp);
        if (validPhoneNumber) {
            return true;
        }
        return false;
    }
    function add_topup_data(topUpInfo) {

        if (typeof topUpInfo.number == "undefined" || topUpInfo.number.length == 0) {
            $("#content").html("Please give a TopUP Number");
            $('#common_modal').modal('show');
            return;
        }
        if (number_validation(topUpInfo.number) == false) {
            $("#content").html("Please give a valid TopUP Number");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof topUpInfo.amount == "undefined" || topUpInfo.amount.length == 0) {
            $("#content").html("Please give an amount ");
            $('#common_modal').modal('show');
            return;
        }
        if (topUpInfo.amount < <?php echo BKASH_MINIMUM_CASH_IN_AMOUNT ?> || topUpInfo.amount > <?php echo BKASH_MAXIMUM_CASH_IN_AMOUNT; ?>) {
            $("#content").html("Please Give a Valid Amount !!");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof topUpInfo.topupType == "undefined" || topUpInfo.topupType.length == 0) {
            $("#content").html("Please Select a Operator Type ");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof topUpInfo.topupOperatorId == "undefined" || topUpInfo.topupOperatorId.length == 0) {
            $("#content").html("Please Select an Operator ");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($('#top_up_id')).scope().addTopUpData(function () {
        });
    }



    function add_multipule_topup(transctionDataList) {
        if (transctionDataList.length <= 0) {
            $("#content").html("Please add a topUp info/ XLSX file!  ");
            $('#common_modal').modal('show');
            return;
        } else {
            for (var i = 0; i < transctionDataList.length; i++) {
                var index = i + +1;
                var transationInfo = transctionDataList[i];
                if (typeof transationInfo.number == "undefined" || transationInfo.number.length == 0) {
                    $("#content").html("Please give a TopUP Number at transction index " + index);
                    $('#common_modal').modal('show');
                    return;
                }
                if (typeof transationInfo.amount == "undefined" || transationInfo.amount.length == 0) {
                    $("#content").html("Please give an amount at transction index " + index);
                    $('#common_modal').modal('show');
                    return;
                }
                if (typeof transationInfo.topupType == "undefined" || transationInfo.topupType.length == 0) {
                    $("#content").html("Please Select a Operator Type at transction index " + index)
                    $('#common_modal').modal('show');
                    return;
                }
                if (typeof transationInfo.topupOperatorId == "undefined" || transationInfo.topupOperatorId.length == 0) {
                    $("#content").html("Please Select an Operator at transction index " + index)
                    $('#common_modal').modal('show');
                    return;
                }
            }
        }
        angular.element($('#multipule_top_up_id')).scope().multipuleTopup(function (data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>transaction/topup';
            });
        });
    }
</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">Topup</span></div>
<div class="mypage"  ng-controller="transctionController">
    <div class="row" style="margin-top:5px;">
        <div class="col-md-12 fleft">	
            <input name="elctkn" value="30dfe1ad62facbf8e5b1ec2e46f9f084" style="display:none;" type="hidden">
            <table style="width:100%;">
                <tbody><tr>
                        <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                            <div class="row col-md-12" id="box_content_2" class="box-content" style="padding-top: 10px;">
                                <form>
                                    <div class ="row">
                                        <div class="col-md-12">  </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-5">
                                            <label for="number" class="col-md-6 control-label requiredField label_custom">
                                                Number
                                            </label>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="number" class="control-label requiredField label_custom">
                                                <input type="text" name="number" id="number" ng-model="topUpInfo.number" class="form-control" placeholder='eg: 0171XXXXXXX'> 
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-5">
                                            <label for="amount" class="col-md-6 control-label requiredField label_custom">
                                                Amount
                                            </label>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="amount" class="control-label requiredField label_custom">
                                                <input type="text" name="amount" id="amount" ng-model="topUpInfo.amount" class="form-control"  placeholder='eg: 100'>  
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-5">
                                            <label for="type" class="col-md-6 control-label requiredField label_custom">
                                                Type
                                            </label>
                                        </div>
                                        <div class="col-md-7">
                                            <select  for="type" id="type"  ng-model="topUpInfo.topupType" class="form-control control-label requiredField" ng-init="setTopUpTypeList(<?php echo htmlspecialchars(json_encode($topup_type_list)); ?>)">
                                                <option class="form-control" value="">Please select</option>
                                                <option class=form-control ng-repeat="topupType in topupTypeList" value="{{topupType.id}}">{{topupType.title}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-5">
                                            <label for="operator" class="col-md-6 control-label requiredField label_custom">
                                                Operator
                                            </label>
                                        </div>
                                        <div class="col-md-7">
                                            <select for="operator" id="operator" ng-model="topUpInfo.topupOperatorId" class=" form-control control-label requiredField" ng-init="setTopupOperatorList(<?php echo htmlspecialchars(json_encode($topup_operator_list)); ?>)">
                                                <option class="form-control" value="">Please select</option>
                                                <option class=form-control ng-repeat="topupOperator in topupOperatorList" value="{{topupOperator.id}}">{{topupOperator.title}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div for="submit_update_api" class="col-md-6 control-label requiredField label_custom"> </div>
                                        <div class ="col-md-6">
                                            <button id="top_up_id" class="button-custom pull-right"  onclick="add_topup_data(angular.element(this).scope().topUpInfo)">Add</button>
                                        </div> 
                                    </div>

                                </form>
                            </div>
                            <div class="row form-group"></div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <a href="<?php echo base_url() . FILES_PATH . SAMPLE_TOPUP_XLSX_FILE_NAME; ?>"><label class="cursor_pointer">Download sample</label></a>
                                </div>
                                <div class="col-md-6">
                                    <a href="<?php echo base_url() . "files/topup_read_me_file_dowload" ?>"><label class="cursor_pointer">Help</label></a>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <?php echo form_open_multipart('files/import_topup_xlsx', array('name' => 'file_upload')); ?>
                                    <div class="form-group">
                                        <label for="fileupload">Upload:</label>
                                        <input id="fileupload" type="file" name="userfile">
                                        <p class="help-block">Select ".XLSX" files only.</p>
                                        <input id="submit_btn" name="submit_btn" value="Upload" type="submit" class="button-custom"/>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table10 form-group" cellspacing="0">
                                        <thead>
                                            <tr>	
                                                <th>Serial</th>
                                                <th>Mobile Number</th>
                                                <th>Amount</th>
                                                <th>Operator Id</th>
                                                <th>type id</th>
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
                                                <td>{{transactionInfo.amount}}</td>
                                                <td>{{transactionInfo.topupOperatorId}}</td>
                                                <td>{{transactionInfo.topupType}}</td>
                                                <td style="text-align: center; cursor: pointer;" ng-click="deleteTransction(transactionInfo)"><div class="glyphicon glyphicon-trash"></div></td>
                                            </tr>
                                        </tbody>

                                    </table>

                                    <div class="row">
                                        <div class="col-md-offset-10 col-md-2">
                                            <button id="multipule_top_up_id" class="button-custom pull-right" onclick="add_multipule_topup(angular.element(this).scope().transactionDataList)">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                        </td>
                        <td style="width:50%;vertical-align:top;padding-right:15px;"  ng-init="setTransctionList(<?php echo htmlspecialchars(json_encode($transaction_list)); ?>)">
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

