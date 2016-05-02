<div class='header ui-widget-header'><?php $clang->eT("Edit Category -" . $_POST['category_id']); ?></div>
<br />
<?php echo CHtml::form(array("admin/profilecategory/sa/mod"), 'post', array('class' => 'form30', 'id' => 'editcategoryform')); ?>
<?php
foreach ($mur as $mrw) {
    ?>

    <ul>
        <li>
            <label for='category_title:'><?php $clang->eT("Category Title:"); ?></label>
            <input type='text' id='category_title' name='category_title' value="<?php echo $mrw['title']; ?>" required="required"/>
            <input type='hidden' id='category_id' name='category_id' value="<?php echo $mrw['id']; ?>" required="required"/>
        </li>
        <li>
            <label for='sort_order'><?php $clang->eT("Sort Order:"); ?></label>
            <input type='text' id='sort_order' name='sort_order' value="<?php echo $mrw['sorder']; ?>" required="required"/>  
        </li>
        <li>
            <label for='IsActive'><?php $clang->eT("Is Active:"); ?></label>
            <?php
            $chk = '';
            if ($mrw['IsActive'] == 1) {
                $chk = 'checked=checked';
            }
            ?>
            <input type="checkbox" <?php echo $chk; ?> name="IsActive" id="IsActive" />
            <span style="background: #ecfbd6;vertical-align: middle;padding: 5px;">If IsActive will not checked all question for these category will be deactivated</span>
        </li>
    </ul>
<?php } ?>
<p>
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='editcategory' />
</p>
</form>
