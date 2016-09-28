<script>

    function updateRate() {
        var userId = '<?php echo $user_id; ?>';
         angular.element($('#submit_update_rate')).scope().updateRate(userId, function (data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>reseller/update_rate/' +userId;
            });
        });

    }

</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">Rates</span>
    <span class="acton"></span>
</div>
<div class="mypage" ng-controller="resellerController">
    <ng-form>
        <table class="table10" ng-init="setServiceRateList(<?php echo htmlspecialchars(json_encode($rate_list)); ?>)">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Prefix</th>
                    <th>Rate</th>
                    <th>Comm. (%)</th>
                    <th>Charge (%)</th>
                    <th>Code</th>
                    <th>SMS Verification
                        <input type="checkbox" ng-model="allSmsVerifications" ng-click="checkAllSmsVerifications()" />
                    </th>
                    <th>Email Verification
                        <input type="checkbox" ng-model="allEmailVerifications" ng-click="checkAllEmailVerifications()" />
                    </th>
                    <th>
                        <input type="checkbox" ng-model="selectedAll" ng-click="checkallbox()" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="rateInfo in serviceRateList"> 
                    <td>{{rateInfo.title}}</td>
                    <td>01</td>
                    <td class="edit"><input type="text" style="width:100%" ng-model="rateInfo.rate"></td>
                    <td class="edit"><input type="text" ng-model="rateInfo.commission" style="width:100%" name="" value=""></td>
                    <td class="edit"><input type="text" style="width:100%" ng-model="rateInfo.charge" ></td>
                    <td class="edit"><input type="text" style="width:100%" ng-model="rateInfo.code" ></td>
                    <td class="enable last"><input type="checkbox" ng-model="rateInfo.sms_enable" ng-click="toggleSelectionRate(rateInfo)" ></td>
                    <td class="enable last"><input type="checkbox" ng-model="rateInfo.email_enable" ng-click="toggleSelectionRate(rateInfo)" ></td>
                    <td class="enable last"><input type="checkbox" value="{{rateInfo.id}}" ng-model="rateInfo.enable" ng-click="toggleSelectionRate(rateInfo)" ></td>
                </tr>
            </tbody>
        </table>
        <div class="top10">&nbsp;</div>
                <input id="submit_update_rate" name="submit_update_rate" class="button-custom" type="submit" value="Update Rates" onclick="updateRate()"/>
        <p class="help-block form_error"></p>
    </ng-form>
</div>