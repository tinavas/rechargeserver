<div class="panel panel-default" ng-controller="transctionController">
    <div class="panel-heading">Transactions</div>
    <div class="panel-body">
        <div class="row form-group"></div>
        <div class="row col-md-12" style="margin-left: 1px;">            
            <div class="row" ng-init="setTransctionList('<?php echo htmlspecialchars(json_encode($transction_list)) ?>')">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="table_row_style">
                                <th style="text-align: center;">Api key</th>
                                <th style="text-align: center;">Balance in</th>
                                <th style="text-align: center;">Balance out</th>
                                <th style="text-align: center;">Status id</th>
                                <th style="text-align: center;">Type id</th>
                                <th style="text-align: center;">Cell no</th>
                                <th style="text-align: center;">Description</th>
                                <th style="text-align: center;">Edit</th>
                                <th style="text-align: center;">Delete</th>
                            </tr>
                            <tr ng-repeat=" transctionInfo in transctionList">
                                <th style="text-align: center;">{{transctionInfo.apikey}}</th>
                                <th style="text-align: center;">{{transctionInfo.balanceIn}}</th>
                                <th style="text-align: center;">{{transctionInfo.balanceOut}}</th>
                                <th style="text-align: center;">{{transctionInfo.transactionStatusId}}</th>
                                <th style="text-align: center;">{{transctionInfo.transactionTypeId}}</th>
                                <th style="text-align: center;">{{transctionInfo.cellNumber}}</th>
                                <th style="text-align: center;">{{transctionInfo.description}}</th>
                                <th style="text-align: center"><a href="<?php echo base_url() . "superadmin/transaction/update_transaction/"; ?>{{transctionInfo.transactionId}}">Edit</a></th>
                                <th style="text-align: center; cursor: pointer;"><a onclick="open_modal_delete_transaction(angular.element(this).scope().transctionInfo.transactionId)"value="" class="">
                                        Delete </a></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view("superadmin/transaction/modal_delete_transaction");
