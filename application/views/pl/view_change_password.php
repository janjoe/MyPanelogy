<style>
    .error{
        border: 1px solid red !important;
        background: rgb(255, 207, 207) !important;
    }
    span.error{
        color: black;
    }
</style>
<script>
    $(document).ready(function(){
        //global vars
        var form = $("#changepwd");
        var current_pwd = $("#current_password");
        var db_pwd = $("#db_password");
        var current_pwdInfo = $("#current_pwdInfo");
        var pwd = $("#password");
        var pwdInfo = $("#pwdInfo");
        var cpwd = $("#cpassword");
        var cpwdInfo = $("#cpwdInfo");
        pwd.blur(validatepassword);
        pwd.keyup(validatepassword);

        current_pwd.blur(validatecurpassword);
        current_pwd.keyup(validatecurpassword);

        cpwd.blur(validatecpassword);
        cpwd.keyup(validatecpassword);
        form.submit(function(){
            if(validatepassword() & validatecpassword() & validatecurpassword())
                return true
            else
                return false;
        });
        function validatepassword(){
            if(pwd.val() == ''){
                pwd.addClass("error");
                pwdInfo.text("Enter your new password");
                pwdInfo.addClass("error");
                return false;
            } else {
                pwd.removeClass("error");
                pwdInfo.text("");
                pwdInfo.removeClass("error");
                return true;
            }
        }
	
        function validatecpassword(){
            if(cpwd.val() == ''){                    
                cpwd.addClass("error");
                cpwdInfo.text("Please confirm new password");
                cpwdInfo.addClass("error");
                return false;
            }else if(cpwd.val() != pwd.val()){
                cpwd.addClass("error");
                cpwdInfo.text("Please confirm your password");
                cpwdInfo.addClass("error");
                return false;
            } else {
                cpwd.removeClass("error");
                cpwdInfo.text("");
                cpwdInfo.removeClass("error");
                return true;
            }
        }
	
        function validatecurpassword(){
            if(current_pwd.val() == ''){
                current_pwd.addClass("error");
                current_pwdInfo.text("Enter your current password");
                current_pwdInfo.addClass("error");
                return false;
            }else if(current_pwd.val() != db_pwd.val()){
                current_pwd.addClass("error");
                current_pwdInfo.text("Your current password is not valid");
                current_pwdInfo.addClass("error");
                return false;
            }  else {
                current_pwd.removeClass("error");
                current_pwdInfo.text("");
                current_pwdInfo.removeClass("error");
                return true;
            }
        }	
    });
</script>
<?php echo CHtml::form(array('pl/registration/sa/changepassword'), 'post', array('id' => 'changepwd', 'name' => 'changepwd')); ?>
<section class="container w100_per cen">
    <div class="box w45_per hauto effect7">
        <h3>Change Password</h3>
        <p>
            <?php
            if ($success) {
                echo 'Your password has been successfully updated.<br /><br />
                      You may now Login at any time with your new password.<br />';
            } elseif ($sendmailtrue) {
                echo 'A request to confirm your password has just been sent to your email address.<br/>
                    Simply click on Confirm Password within that email to complete your password change<br/>
                    If you do not receive this email within 15 minutes, please check your junk/spam folder';
            } else {
                //if ($success && !$sendmailtrue) echo 'An email has not been sent from the server. Please contact your webmaster';
                $pl_details = $plans_list = array();
                $sql = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '" . $_SESSION['plid'] . "'";
                $pl_details = Yii::app()->db->createCommand($sql)->query()->readAll();
                $pwd = base64_decode(urldecode($pl_details[0]['password']));
                ?>
                <label for="user">Current Password</label>
                <input name="current_password" id="current_password" type="password" size="40" maxlength="40" value=""/>
                <span id="current_pwdInfo"></span>
                <input name="panellist_id" id="panellist_id" type="hidden" value="<?php echo $pl_details[0]['panel_list_id']; ?>" />
                <input name="db_password" id="db_password" type="hidden" value="<?php echo $pwd; ?>" />

                <label for="user">New Password</label>
                <input name="password" id="password" type="password" size="40" maxlength="40" value=""/>
                <span id="pwdInfo"></span>

                <label for="password">Confirm Password</label>
                <input name="cpassword" id="cpassword" type="password" size="40" maxlength="40" value=""/>
                <span id="cpwdInfo"></span>
                <input type="hidden" name="action" value="updatepassword">
                <br>
                <br>
                <input type="submit" name="login_submit" value="Update Password" role="button" aria-disabled="false"/> 
                <?php
            }
            ?>

        </p>
    </div>
</section>
<?php echo CHtml::endForm(); ?>