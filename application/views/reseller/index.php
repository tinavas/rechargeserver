<div class="loader"></div>
<div class="ezttle"><span class="text"><?php echo $title; ?></span></div>
<div class="mypage" ng-controller="resellerController">
    <?php if ($group !== TYPE4 && $allow_user_create !== FALSE) { ?>
        <div class="btn-group">
            <a href="<?php echo base_url() . 'reseller/create_reseller' ?>" class="btn btn-primary btn-sm" href="reSellersAdd.html"><span class="glyphicon glyphicon-plus-sign"></span> Add Reseller</a>
        </div>
    <?php } ?>
    <div class="top10">&nbsp;</div>
    <input type="hidden" style="display:none;" value="" name="elctkn">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><a href="">Username</a></th>
                <th><a href="">Name</a></th>
                <th><a href="">Email</a></th>
                <th><a href="">Mobile</a></th>
                <th><a href="">Balance</a></th>
                <th><a href="">Created Date</a></th>
                <th><a href="">Last Login</a></th>
                <th><a href="">Details</a></th>
                <?php if ($allow_user_edit !== FALSE) { ?> 
                    <th width="170">Action</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody ng-init="setResellerList(<?php echo htmlspecialchars(json_encode($reseller_list)); ?>)">
            <tr ng-repeat="resellerInfo in resellerList">
                <?php if ($allow_user_edit !== FALSE) { ?> 
                    <td><a href="<?php echo base_url() . 'reseller/update_reseller/'; ?>{{resellerInfo.user_id}}"> {{resellerInfo.username}}</a></td>
                <?php } else if ($allow_user_edit == FALSE) { ?>
                    <td><a href="<?php echo base_url() . 'reseller/show_reseller/'; ?>{{resellerInfo.user_id}}"> {{resellerInfo.username}}</a></td>
                <?php } ?>
                <td>{{resellerInfo.first_name}} &nbsp; {{resellerInfo.last_name}}</td>
                <td>{{resellerInfo.email}}</td>
                <td>{{resellerInfo.mobile}}</td>
                <td>{{resellerInfo.current_balance}}</td>
                <td>{{resellerInfo.created_on}}</td>
                <td>{{resellerInfo.last_login}}</td>
                <td>
                    <a href="<?php echo base_url() . 'reseller/get_reseller_list/'; ?>{{resellerInfo.user_id}}">View</a>
                </td>
                <?php if ($allow_user_edit !== FALSE) { ?> 
                    <td class="action">
                        <a href="<?php echo base_url() . 'payment/create_payment/'; ?>{{resellerInfo.user_id}}">Payment</a>
                        |<a href="<?php echo base_url() . 'reseller/update_rate/'; ?>{{resellerInfo.user_id}}">Rates</a>
                    </td>
                <?php } ?>
            </tr>
        </tbody>
    </table>	
</div>