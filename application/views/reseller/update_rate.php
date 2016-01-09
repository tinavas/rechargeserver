<div class="ezttle"><span class="text">Rates</span>
    <span class="acton"></span>
</div>
<div class="mypage">
    <?php echo form_open("reseller/update_rate/".$user_id, array('id' => 'form_create_reseller', 'class' => 'form-inline')); ?>
        <table class="rates">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Prefix</th>
                    <th>Rate</th>
                    <th>Comm. (%)</th>
                    <th>Charge (%)</th>
                    <th><input type="checkbox" checked="" onclick="checkallbox(this);" name="checkAll"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rate_list as $rate_info){?>
                <tr> 
                    <td><?php echo $rate_info['title']?></td>
                    <td>01</td>
                    <input type="hidden" name="update[<?php echo $rate_info['service_id']?>][key]" value="<?php echo $rate_info['user_service_id']?>">
                    <td class="edit"><input type="text" style="width:100%" name="update[<?php echo $rate_info['service_id']?>][rate]" value="<?php echo $rate_info['rate']?>"></td>
                    <td class="edit"><input type="text" style="width:100%" name="update[<?php echo $rate_info['service_id']?>][commission]" value="<?php echo $rate_info['commission']?>"></td>
                    <td class="edit"><input type="text" style="width:100%" name="update[<?php echo $rate_info['service_id']?>][charge]" value="<?php echo $rate_info['charge']?>"></td>
                    <td class="enable last"><input type="checkbox" checked="" value="1" name="update[<?php echo $rate_info['service_id']?>][enable]"></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
        <div class="top10">&nbsp;</div>
        <input id="submit_update_rate" name="submit_update_rate" class="btn btn-primary btn-sm" type="submit" value="Update Rates"/>
        <p class="help-block form_error"></p>
    <?php echo form_close(); ?>	
</div>