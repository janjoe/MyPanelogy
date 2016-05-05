<?php echo CHtml::form(array('pl/home/sa/support_center'), 'post', array('id' => 'loginform', 'name' => 'loginform')); ?>
<section class="container w95_per" style="margin-left: 0px; min-height: 50px;">
    <div class="box w98_per effect7">
        <h3>New Reply Message</h3>
        <p>
            <label for="password">Message</label>
            <textarea name="message" id="message" cols="50" rows="3" required=""></textarea>
            <input type="hidden" name="parent" value="<?php echo $_GET['id']; ?>"/>
            <input type="hidden" name="email_to" value="<?php echo $_GET['email_to']; ?>"/>
            <input type="hidden" name="subject" value="<?php echo $_GET['subject']; ?>"/>
            <br/><br/>
            <input type="hidden" name="action" value="add"/>
            <input type="submit" value="Save"/>
        </p>
    </div>
</section>
</form>
