<?php echo CHtml::form(array('pl/authentication/sa/login'), 'post', array('id' => 'loginform', 'name' => 'loginform')); ?>
<section class="container w100_per cen">
    <div class="box w45_per hauto effect7">
        <h3>Enter Login Details</h3>
        <p>
            <input type="hidden" value="Authdb" name="authMethod" id="authMethod">
            <label for="user">Email Address</label>
            <input name="email" id="email" type="email" size="40" maxlength="40" value="" required="" autofocus="">

            <label for="password">Password</label>
            <input name="password" id="password" type="password" size="40" maxlength="40" value="" required="">
            <input type="hidden" name="action" value="login">
            <br>
            <br>
            <input type="submit" name="login_submit" value="Login" role="button" aria-disabled="false">
            <!--            <a href="forgotpassword">Forgot your password?</a><br>-->
            <a href="<?php echo CController::createUrl('pl/authentication/sa/forgotpassword'); ?>">Forgot your password?</a><br>
        </p>
    </div>
</section>
<?php echo CHtml::endForm(); ?>