<script type="text/javascript">
    function ValidationPassword(){
        var pwd = $("#password").val();
        var cpwd = $("#cpassword").val();
    
        if(pwd != cpwd){
            alert('Password Not match, Try Again');
            return false
        }else{
            return true;
        }
    }
</script>

<?php echo CHtml::form(array('pl/registration/sa/process'), 'post', array('id' => 'resetpassword', 'name' => 'resetpassword', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return ValidationPassword()')); ?>
<section class="container w100_per cen">
    <div class="box w45_per hauto effect7">
        <h3>Recover Your Password</h3>
        <p>
            <input type="hidden" value="Authdb" name="authMethod" id="authMethod">
            <label for="user">New Password</label>
            <input name="password" id="password" type="password" size="40" maxlength="40" value="" required="" autofocus=""/>
            <input name="panellist_id" id="panellist_id" type="hidden" value="<?php echo $Panel_list_id; ?>" />

            <label for="password">Confirm Password</label>
            <input name="cpassword" id="cpassword" type="password" size="40" maxlength="40" value="" required=""/>
            <input type="hidden" name="action" value="resetpassword">
            <br>
            <br>
            <input type="submit" name="login_submit" value="Update Password" role="button" aria-disabled="false">
        </p>
    </div>
</section>
<?php echo CHtml::endForm(); ?>