<div class="loader"></div>
<div class="ezttle"><span class="text">SMS History</span></div>
<div class="mypage">
    <table class="table table-striped table-hover" ng-controller="transctionController">
        <thead>
            <tr>
                <th><a href="">Number</a></th>
                <th><a href="">SMS</a></th>
                <th><a href="">SIZE</a></th>
                <th><a href="">UNIT PRICE</a></th>
                <th><a href="">Date</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot ng-init="setTransactionInfoList(<?php echo htmlspecialchars(json_encode($transaction_list)) ?>)">
            <tr ng-repeat="transctionInfo in transctionInfoList">
                <th>{{transctionInfo.cell_no}}</th>
                <th>{{transctionInfo.sms}}</th>
                <th>{{transctionInfo.length}}</th>
                <th>{{transctionInfo.unit_price}}</th>
                <th>{{transctionInfo.created_on}}</th>
            </tr>
        </tfoot>
    </table>
</div>