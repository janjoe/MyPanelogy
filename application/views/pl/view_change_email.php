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
        $("#email").blur(function() {
            var fldValue = $("#email");
            var value = $(fldValue).val();
            var remail = $("#remail").val();
            if ( value.length > 0 ) {
                chk_value(value);
            }
        });
        
        $("#remail").blur(function() {
            var email = $("#email").val();
            var remail = $("#remail").val();
            if(email != remail){
                $('#reInfo').html('Email does not match')
            }
        });
        function chk_value(fldValue){
            $.ajax({
                type: 'POST',
                data: {fldval:fldValue},
                url: '<?php echo CController::createUrl('pl/registration/sa/selectemail') ?>',
                success: function(data){
                    //alert(data)
                    if(data.trim() != 'Correct'){
                        $('#emailInfo').html(data)
                        return true;
                    }
                }
            })
        }
    });
    
    function valdatesame(){
        var email = $("#email").val();
        var remail = $("#remail").val();
        if(email != remail){
            alert('Email does not match');
            return false;
        }else{
            return true;
        }

    }
</script>
<?php echo CHtml::form(array('pl/registration/sa/changeemail'), 'post', array('id' => 'changeemail', 'name' => 'changeemail', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return valdatesame()')); ?>
<section class="container w100_per cen">
    <div class="box w45_per hauto effect7">
        <h3>Change Email</h3>
        <p>
            <?php
            if ($success) {
                echo 'Your email address has been successfully updated.<br /><br />
                      You may now Login at any time with your new Email address.<br />';
            } elseif ($sendmailtrue) {
                echo 'A confirmation has just been sent to the new email address you have provided.<br/>
                    Simply click on Activate Email Address within that email to complete your email address change.<br/>
                    If you do not receive this email within 15 minutes, please check your junk/spam folder';
            } else {

                $pl_details = $plans_list = array();
                $sql = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '" . $_SESSION['plid'] . "'";
                $pl_details = Yii::app()->db->createCommand($sql)->query()->readAll();
                $pwd = base64_decode(urldecode($pl_details[0]['password']));
                ?>
                <label for="user">New Email Address</label>
                <input name="email" id="email" type="email" size="40" maxlength="40" value=""/>
                <span id="emailInfo"></span>
                <input name="panellist_id" id="panellist_id" type="hidden" value="<?php echo $pl_details[0]['panel_list_id']; ?>" />
                <input name="old_email" id="old_email" type="hidden" value="<?php echo $pl_details[0]['email']; ?>" />

                <label for="user">Re-Enter Email Address</label>
                <input name="remail" id="remail" type="email" size="40" maxlength="40" value=""/>
                <span id="reInfo"></span>

                <input type="hidden" name="action" value="updateemail">
                <br>
                <br>
                <input type="submit" name="login_submit" value="Update Email" role="button" aria-disabled="false"/> 
                <?php
            }
            ?>
        </p>
    </div>
</section>
<?php echo CHtml::endForm(); ?>