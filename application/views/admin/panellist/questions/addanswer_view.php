<div class='header ui-widget-header'><?php $clang->eT("Add Answer"); ?></div>
<br />
<?php echo CHtml::form(array("admin/profilequestion/sa/adda"), 'post', array('class' => 'form30', 'id' => 'newprofilequestionform')); ?>
<?php
for ($i = 0; $i < count($answer_arr); $i++) {
    $usr = $answer_arr[$i];
    ?>            
    <ul>
        <li>
            <label for='question_id'><?php $clang->eT("Question:"); ?></label>
            <input type='hidden' id='question_id' name='question_id' value="<?php echo $usr['q_id']; ?>" required="required"/>
    <?php echo $usr['question_title']; ?>        
        </li>
        <li>
            <label for='category_id'><?php $clang->eT("Category:"); ?></label>
            <input type='hidden' id='question_id' name='category_id' value="<?php echo $usr['cat_id']; ?>" required="required"/>
    <?php echo $usr['category_title']; ?>
        </li>    
        <li>
            <label for='answer_title'><?php $clang->eT("Answer Title :"); ?></label>
            <input type='text' id='answer_title' name='answer_title' required="required"/>
        </li>    
        <li>
            <label for='sort_order'><?php $clang->eT("Sort Order:"); ?></label>
            <input type='text' id='sort_order' name='sort_order' required="required"/>         
        </li>
        <li>
            <label for='IsActive'><?php $clang->eT("Is Active? "); ?></label>
            <input type="checkbox" name='IsActive' id="IsActive" />
        </li>
        <li>
            <label for='IsActive'><?php $clang->eT("Note : "); ?></label>
            <span>
                Use following parameter for Country and Language answer<br/>
                Country : [[COUNTRY]]
                Language : [[LANGUAGE]]
            </span>
        </li>
    </ul>
    <p>
        <input type='submit' value='<?php $clang->eT("Save"); ?>' />
        <input type='hidden' name='action' value='addanswer' />
    </p>
<?php } ?>
</form>