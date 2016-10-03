<div class="ezttle"><span class="text">Cost &amp; Profit</span></div>
<div class="mypage" ng-controller="reportController">
    <div class="top10">&nbsp;</div>
<!--    <form accept-charset="utf-8" method="post" role="form" class="form-inline filter" action="">
        <input type="hidden" style="display:none;" value="" name="elctkn">
        <div class="form-group">
            <label for="range">Select Month</label><br>
            <select id="month" name="month" class="form-control input-xs">
                <option selected="" value="122015">December 2015</option>
                <option value="112015">November 2015</option>
                <option value="102015">October 2015</option>
            </select>
        </div>
        <div class="form-group">
            <label>&nbsp;</label><br>
            <button class="btn btn-success btn-xs" type="submit"><span class="glyphicon glyphicon-list-alt"></span> Generate</button>
        </div>
    </form>			<div class="top10">&nbsp;</div>-->
    <div style="margin-top:5px;" class="row" ng-init="setUserProfits('<?php echo htmlspecialchars(json_encode($user_profits)) ?>')">
        <div class="col-md-12 ">
            <div class="report">
                <h4>Cost &amp; Profit Report</h4>
                <table class="table10">
                    <thead>
                        <tr>
                            <th width="100">Services</th>
                            <th width="100">Total Amount used</th>
                            <!--<th width="100">My Cost</th>-->
                            <th width="100">Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="userProfit in userProfits">
                            <td width="100">{{userProfit.title}}</td>
                            <td class="amt">{{userProfit.total_used_amount}}</td>
<!--                            <td class="amt">0</td>-->
                            <td class="amt">{{userProfit.total_profit}}</td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th width="100">Total</th>
                            <th width="100" class="amt">{{userTotalProfitInfo.usertotalUsedAmount}}</th>
                            <!--<th width="100" class="amt">0.00</th>-->
                            <th width="100" class="amt">{{userTotalProfitInfo.userTotalProfit}}</th>
                        </tr>
                    </thead>
                </table>
            </div> 
        </div> 
    </div> 
</div> 
