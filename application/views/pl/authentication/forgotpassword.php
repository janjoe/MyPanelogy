<?php echo CHtml::form(array('pl/authentication/sa/forgotpassword'), 'post', array('id' => 'forgotpassword', 'name' => 'forgotpassword')); ?>
<section class="container w100_per cen">
    <div class="box w45_per hauto effect7">
        <h3>Recover your password</h3>
        <h5>To receive a new password by email you have to enter your email address.</h5>
        <p>
            <input type="hidden" value="Authdb" name="authMethod" id="authMethod"/>
            <label for="user">Email Address</label>
            <input name="email" id="email" type="email" size="40" maxlength="40" value="" required="" autofocus=""/>
            <input type="hidden" name="action" value="forgotpass" />
            <br/>
            <input type="submit" name="submit" value="Submit" role="button" aria-disabled="false">
            <a href="<?php echo $this->createUrl("/pl"); ?>"><?php $clang->eT('Main Login Screen'); ?></a>

        </p>
    </div>
</section>
<?php echo CHtml::endForm(); ?>
