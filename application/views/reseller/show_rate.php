
<div class="loader"></div>
<div class="ezttle"><span class="text">Rates</span>
    <span class="acton"></span>
</div>
<div class="mypage" ng-controller="resellerController">
    <table class="table_show"  ng-init="setServiceRateList(<?php echo htmlspecialchars(json_encode($rate_list)); ?>)">
        <thead>
            <tr>
                <th>Service</th>
                <th>Prefix</th>
                <th>Rate</th>
                <th>Comm. (%)</th>
                <th>Charge (%)</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="rateInfo in serviceRateList"> 
                <td>{{rateInfo.title}} </td>
                <td>01 </td>
                <td>{{rateInfo.rate}} </td>
                <td>{{rateInfo.commission}} </td>
                <td>{{rateInfo.charge}} </td>
            </tr>
        </tbody>
    </table>
</div>