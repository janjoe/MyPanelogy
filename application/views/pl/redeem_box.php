<?php //echo CHtml::form(array('pl/home/sa/redeem_box'), 'post', array('id' => 'redeemform', 'name' => 'redeemform'));     ?>

<!--</form>-->


<?php echo CHtml::form(array('pl/home/sa/redeem_box'), 'post', array('id' => 'redeemform', 'name' => 'redeemform')); ?>
<section class="container w95_per" style="margin-left: 0px; min-height: 50px;">
    <div class="box w98_per effect7">
        <h3>Points Detail</h3>
        <p>
            <label for="">
                <?php
                $newbal = $balance - $red;
                ?>
                Your current available point balance is <b><?php echo number_format($balance, 0); ?></b> points
                <br />
                Are you sure you wish like to redeem this reward?
                <br />
                After redemption your new balance will be <b><?php echo number_format($newbal, 0) ?></b> points
            </label>
            <br/><br/>
            <input type="hidden" name="action" value="add"/>
            <input type="hidden" name="reward_id" value="<?php echo $reward_id ?>"/>
            <input type="submit" value="Continue"/>&nbsp;&nbsp;&nbsp;
            <a href="javascript:;" onclick="$.fancybox.close();"><input type="button" value="Cancel"/></a>
        </p>
    </div>
</section>
</form>