<?php echo CHtml::form(array('pl/home/sa/cancel_account'), 'post', array('id' => 'loginform', 'name' => 'loginform')); ?>
<section class="container w90_per">
    <div class="box w98_per effect7">
        <h3>Cancel Account</h3>
        <p style="display: inline-block">
        <table width="100%">
            <tr>
                <td>
                    <?php
                    echo '<b>Are you sure you wish to cancel  your account?</b>';
                    echo '<br /><br />';
                    echo 'By doing so you will no longer receive any survey opportunities AND you will forfeit any reward points you may have accumulated.';
                    echo '<br /><br />';
                    ?>
                    <input type="hidden" name="pid" value="<?php echo $_SESSION['plid']; ?>"/>
                    <input type="hidden" name="action" value="Yes"/>
                    <input type="submit" value="Yes"/>
                    <a href="<?php echo CController::createUrl('pl/home') ?>" class="nav-cta login">
                        <input type="button" value="No"/>
                    </a>
                </td>
            </tr>
        </table>
        </p>
    </div>
</section>
<?php echo CHtml::endForm(); ?>