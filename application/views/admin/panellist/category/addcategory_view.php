<div class='header ui-widget-header'><?php $clang->eT("Add Profile Category"); ?></div>
<br />
<?php echo CHtml::form(array("admin/profilecategory/sa/add"), 'post', array('class' => 'form30', 'id' => 'newprofilecategoryform')); ?>

<ul>
    <li>
        <label for='category_title:'><?php $clang->eT("Category Title:"); ?></label>
        <input type='text' id='category_title' name='category_title' required="required"/>
    </li>
    <li>
        <label for='sort_order'><?php $clang->eT("Sort Order:"); ?></label>
        <input type='text' id='sort_order' name='sort_order' required="required"/> 
    </li>
</ul>
<p>
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addcategory' />
</p>
</form>
