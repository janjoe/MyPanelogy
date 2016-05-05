<div class='header ui-widget-header'><?php $clang->eT("Edit Answer -" . $_POST['answer_id']); ?></div>
<br />
<?php echo CHtml::form(array("admin/profilequestion/sa/moda"), 'post', array('class' => 'form30', 'id' => 'editprofileanswerform')); ?>
<?php foreach ($mur as $mrw) {
    ?>
    <ul>
        <li>
            <label for='question_id'><?php $clang->eT("Question:"); ?></label>
            <input type='hidden' id='answer_id' name='answer_id' value="<?php echo $mrw['id']; ?>" required="required"/>
            <input type='hidden' id='question_id' name='question_id' value="<?php echo $mrw['q_id']; ?>" required="required"/>
            <?php echo $mrw['question_title']; ?>        
        </li><li>
            <label for='category_id'><?php $clang->eT("Category:"); ?></label>
            <input type='hidden' id='question_id' name='category_id' value="<?php echo $mrw['cat_id']; ?>" required="required"/>
            <?php echo $mrw['category_title']; ?>
        </li>    
        <li>
            <label for='answer_title'><?php $clang->eT("Answer Title :"); ?></label>
            <input type='text' id='answer_title' name='answer_title' value="<?php echo $mrw['title']; ?>" required="required"/>
        </li>    
        <li>
            <label for='sort_order'><?php $clang->eT("Sort Order:"); ?></label>
            <input type='text' id='sort_order' name='sort_order' value="<?php echo $mrw['sorder']; ?>" required="required"/>         
        </li>
        <li>
            <?php
            $chk = '';
            if ($mrw['IsActive'] == 1) {
                $chk = 'checked=checked';
            }
            ?>
            <label for='IsActive'><?php $clang->eT("Is Active? "); ?></label>
            <input type="checkbox" <?php echo $chk; ?> name='IsActive' id="IsActive" />
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
        <input type='hidden' name='action' value='editanswer' />
    </p>
<?php } ?>
</form>