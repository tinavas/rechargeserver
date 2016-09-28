<div class="ez-asterisk-holder">
    <div id="ez-wrap" class="ez-asterisk-holder ez-main-wrap ez-auth-pages ez-login-wrap">
        <div class="ez-page-title ">
            <h1>Pin</h1>
            <?php echo $message; ?>
        </div>
        <div id="ez-login-wrap">
            <?php echo form_open("superadmin/auth/pin"); ?>
            <div class="ez-form-row" style="min-height: 100px">
                <label for="pin">Pin<span style="color:red">*</span></label>
                <input id="pin" name="pin" class="ez-ltr" type="password" autocomplete="off">
            </div>
            <input type="submit" value="Submit" class="ez-full-width">
            <?php echo form_close(); ?>
        </div>
    </div>
</div>