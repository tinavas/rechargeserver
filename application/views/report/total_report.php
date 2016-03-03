
<div class="ezttle"><span class="text">Total Report</span></div>
<div class="mypage" ng-controller="reportController">
    <div class="top10">&nbsp;</div>
<!--    <form accept-charset="utf-8" method="post" role="form" class="form-inline filter" action="">
        <input type="hidden" style="display:none;" value="" name="elctkn">
        <div class="form-group">
            <label for="reseller">Reseller</label><br>
            <select style="width:120px;" id="reseller" name="reseller" class="form-control input-xs">
                <option value="">--View All--</option>
                <option value="271">My Account</option>
                <optgroup label="Reseller III"></optgroup>
                <optgroup label="Reseller II"></optgroup>
                <optgroup label="Reseller I"></optgroup>
            </select>
        </div>
        <div class="form-group">
            <label for="range">Date Period</label><br>
            <select id="range" name="range" class="form-control input-xs">
                <option selected="" value="5">All The Time</option>
                <option value="1">Today</option>
                <option value="2">Last Month</option>
                <option value="3">Last 3 Month</option>
                <option value="4">This Year</option>
                <option value="6">Selected Date</option>
            </select>
        </div>
        <div class="form-group">
            <label for="date1">Date From</label><br>
            <input type="text" value="" size="18" placeholder="Date From" id="date1" name="from" class="form-control input-xs">
        </div>
        <div class="form-group">
            <label for="date2">Date To</label><br>
            <input type="text" value="" size="18" placeholder="Date To" id="date2" name="to" class="form-control input-xs">
        </div>
        <div class="form-group">
            <label>&nbsp;</label><br>
            <button class="btn btn-primary btn-xs" type="submit"><span class="glyphicon glyphicon-search"></span> Filter</button>
            <a class="btn btn-danger btn-xs" href="javascript:void(0)"><span class="glyphicon glyphicon-print"></span> Print</a>
        </div>
    </form>	-->
    <table class="table table-striped table-hover" ng-init="setProfitList(<?php echo htmlspecialchars(json_encode($profit_list)) ?>)">
        <thead>
            <tr>
                <th><a href="">Amount</a></th>
                <th><a href="">Commission</a></th>
                <th><a href="">Used By</a></th>
                <th><a href="">Date</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr ng-repeat="profitInfo in profitList">
                <th>{{profitInfo.rate}}</th>
                <th>{{profitInfo.amount}}</th>
                <th>{{profitInfo.first_name}} {{profitInfo.last_name}}</th>                
                <th>{{profitInfo.created_on}}</th>
            </tr>
        </tfoot>
    </table>
</div> 
