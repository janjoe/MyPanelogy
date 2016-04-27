<section class="container w90_per">
    <div class="box w98_per effect7">
        <h3>Activate Account</h3>
        <p style="padding: 3%;">
            <?php
            if ($Sucess) {
                echo 'Congratulations <br/> You have successfully activated your account
                    
                    <a href="' . Yii::app()->createAbsoluteUrl('pl/home') . '" >Click here to complete your profile </a>.';
            } elseif ($Error) {
                echo 'Please Enter Activation Code.';
            } else {
                echo 'Your account has already been activated and confirmed.';
            }
            ?>
        </p>
    </div>
</section>