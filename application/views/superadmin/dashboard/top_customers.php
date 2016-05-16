<div class="chart">
    <span ng-repeat="customerInfo in topCustomerList">
        <div style="width: {{customerInfo.amount_percentage}}%"><span>{{customerInfo.user_name}}</span></div>
    </span>
</div>


