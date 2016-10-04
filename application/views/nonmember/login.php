<div class="ez-asterisk-holder">
    <div id="ez-wrap" class="ez-asterisk-holder ez-main-wrap ez-auth-pages ez-login-wrap">
        <div class="ez-page-title ">
            <h1>Login</h1>
            <?php if ($message_flag == 1) { ?>
            <div class="message_flag_color_red">
                    <?php echo strip_tags($message); ?>
                </div>
            <?php } else { ?>
                <div class="message_flag_color_green">
                    <?php echo strip_tags($message); ?>
                </div>
            <?php } ?>
<!--                        <p id="ez-page-subtitle">
                <a href="register">New to ewallet2u - Sign up here</a>
            </p>-->
        </div>
        <div id="ez-login-wrap" style="">
<!--            <form id="loginForm" accept-charset="utf-8" method="post" style="padding-bottom: 20px;" name="loginForm" action="http://www.ewallet2u.com/login">-->
                <?php echo form_open("auth/login");?>
                <input type="hidden" style="display:none;" value="646906f7f6523800871b1476be229be9" name="elctkn">
                <div class="ez-form-row" style="min-height: 100px">
                    <label for="username">Username<span style="color:red">*</span></label>
                    <input id="identity" class="ez-ltr" type="text" autocomplete="off" name="identity">
                </div>
                <div class="ez-form-row" style="min-height: 100px">
                    <label for="password">Password<span style="color:red">*</span></label>
                    <input id="password" class="ez-ltr" type="password" autocomplete="off" name="password">
                </div>
                <input type="hidden" name="valid" value="null">
                <!--<button id="ez-login-btn" class="ez-full-width">Login</button>-->
                <input type="submit" value="Login" class="ez-full-width">
                <?php echo form_close();?>
                <!--                            <a class="ez-forgot-pass" href="forgot">Forgot password?</a>-->
                <br>
                <br>
<!--            </form>-->
        </div>
    </div>
</div>