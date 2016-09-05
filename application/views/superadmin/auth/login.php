<div>
    <h1><?php echo lang('login_heading'); ?></h1>
    <p><?php echo lang('login_subheading'); ?></p>
    <div class="row">
        <div class="col-md-6">
            <?php echo form_open("superadmin/auth/login"); ?>
            <div class="row col-md-12 form-group">
                <div id="infoMessage"><?php echo $message; ?></div> 
            </div>
            <div class="form-group">
                <label  class="control" for="username">Username</label>
                <?php echo form_input($identity + array('class' => 'form-control')); ?>
            </div>
            <div class="form-group">
                <label   class="control"  for="password">Password</label>
                <?php echo form_input($password + array('class' => 'form-control')); ?>
            </div>
            <div class="form-group">
                <?php echo form_submit($login + array('class' => 'ez-full-width')); ?>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>