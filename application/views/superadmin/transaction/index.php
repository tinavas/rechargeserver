
<div class="ezttle"><span class="text">TopUp History</span></div>
<div class="mypage" ng-controller="transctionController">
    <table class="table table-striped table-hover"> 
        <thead>
            <tr>
                <th><a href="">Sender</a></th>
                <th><a href="">Number</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Status</a></th>
                <th><a href="">Trans.ID</a></th>
                <th><a href="">Date</a></th>
                <th><a href="">Action</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot ng-init="setTransctionList('<?php echo htmlspecialchars(json_encode($transction_list)) ?>')">

            <tr ng-repeat="transctionInfo in transctionInfoList">
                <th>{{transctionInfo.sender_cell_no}}</th>
                <th>{{transctionInfo.cell_no}}</th>
                <th>{{transctionInfo.amount}}</th>
                <th>{{transctionInfo.status}}</th>
                <th>{{transctionInfo.transaction_id}}</th>
                <th>{{transctionInfo.created_on}}</th>
                <th>{{transctionInfo.created_on}}</th>
            </tr>
        </tfoot>
    </table>
</div>
