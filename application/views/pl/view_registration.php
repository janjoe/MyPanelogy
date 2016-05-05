<script type="text/javascript">
    function submitform(){
        $("#resendActivationcode").submit();
    }
</script>
<section class="container w90_per">
    <div class="box w98_per effect7">
        <h3>Registration</h3>
        <p style="padding: 3%;">
            <?php
            if ($Pending) {
                echo CHtml::form(array('pl/registration/sa/process'), 'post', array('id' => 'resendActivationcode', 'name' => 'resendActivationcode'));
                echo 'Your account has not yet been activated.<br/>
                    Please see your confirmation email in order to activate your account<br/>';
                ?>
                <input type="hidden" name="action" value="resend">
                <input type="hidden" name="panellist_id" value="<?php echo $panellist_id ?>">
                <a style="text-decoration: none;" href="#" onclick="submitform()" title="Click Here">Click Here</a>
                <?php
                echo 'To have your confirmation email re-sent.';
                echo '</form>';
            } elseif ($success) {
                echo 'Your account has already been successfully activated. <br/>If you did not submit this request <br/>
                    please contact us at <a href="mailto:' . Yii::app()->getConfig("siteadminemail") . '">' . Yii::app()->getConfig("siteadminemail") . '</a>';
            } else {
                ?>
                Thank you for your time and welcome to the Survey Panel!<br/> 
                An email with the subject line 'Welcome to SurveyOffices' will be sent to you within the next hour regarding completion of your registration.<br/> 
                Please click on the activation link within that email to complete the registration process and verify your membership account.<br/>
                Once your account is activated, you will be able to participate in our paid market research studies and earn valuable rewards.<br/>
                Please take this time to add <?php echo Yii::app()->getConfig("siteadminemail") ?> 
                to your trusted or safe sender list to ensure that our emails are delivered to your Inbox.
                <?php
            }
            ?>
        </p>
    </div>
</section>