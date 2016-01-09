<div class="ezttle"><span class="text"><?php echo $title;?></span></div>
<div class="mypage">
    <div class="top10">&nbsp;</div>
    <div class="row">
        <div class="col-md-12 fleft">	
            <?php echo form_open("reseller/update_reseller/".$reseller_info['user_id'], array('id' => 'form_update_reseller', 'class' => 'inform well', 'style' => 'width:650px;')); ?>
                <input type="hidden" style="display:none;" value="" name="elctkn">
                <table style="width:100%;">
                    <tbody>
                        <tr>
                            <?php echo $message;?>
                        </tr>
                        <tr>
                            <td style="width:50%;vertical-align:top;padding-right:15px;">
                                <p class="help-block">Reseller Information</p>
                                <div class="form-group ">
                                    <label for="username" class="control-label">Username</label>
                                    <input type="text" readonly="readonly" class="form-control input-sm" id="username" name="username" value="<?php echo $reseller_info['username']?>">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="password" class="control-label">Password</label>
                                    <input type="password" autocomplete="off" value="" placeholder="********" class="form-control input-sm" id="password" name="password">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="name" class="control-label">First name</label>
                                    <input type="text" class="form-control input-sm" id="first_name" name="first_name" value="<?php echo $reseller_info['first_name']?>">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="name" class="control-label">Last name</label>
                                    <input type="text" class="form-control input-sm" id="last_name" name="last_name" value="<?php echo $reseller_info['last_name']?>">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="mobile" class="control-label">Mobile Number</label>
                                    <input type="text" class="form-control input-sm" id="mobile" name="mobile" value="<?php echo $reseller_info['mobile']?>">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="text" readonly="readonly" class="form-control input-sm" id="email" name="email" value="<?php echo $reseller_info['email']?>">
                                    <p class="help-block form_error"></p>
                                </div>
                                <div class="form-group ">
                                    <label for="note">Note</label>
                                    <textarea rows="2" name="note" id="note" class="form-control input-sm"><?php echo $reseller_info['note']?></textarea>
                                    <p class="help-block form_error"></p>
                                </div>
                            </td>
                            <td style="width:50%;vertical-align:top;padding-left:15px;">
                                <p style="padding:0px;" class="help-block"><input type="checkbox" onclick="checkallper(this);" value="1" id="allper" name="checkAll"> <label for="allper" style="cursor:pointer;">Reseller Permission</label> </p>
                                <div class="form-group">
                                    <?php foreach($service_list as $service_info){?>
                                    <div class="checkbox"><label><input type="checkbox" <?php echo $service_info['status']==1?'checked="checked"':'';?> value="<?php echo $service_info['service_id']?>" name="per[]"><?php echo $service_info['title']?></label></div>
                                    <?php }?>
                                    <p class="help-block form_error"></p>
                                </div>
                                <p style="padding-top: 0px !important;padding-top: 0px !important;line-height:0px;" class="help-block">&nbsp;</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="help-block line">&nbsp;</p>
                <input id="submit_update_reseller" name="submit_update_reseller" class="btn btn-primary btn-sm" type="submit" value="Update Reseller"/>
            <?php echo form_close(); ?>
        </div> 
    </div> 
</div>