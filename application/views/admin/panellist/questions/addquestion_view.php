<div class='header ui-widget-header'><?php $clang->eT("Add Profile Question"); ?></div>
<br />
<?php echo CHtml::form(array("admin/profilequestion/sa/add"), 'post', array('class' => 'form30', 'id' => 'newprofilequestionform')); ?>

<ul>
    <li>
        <label for='category'><?php $clang->eT("Category:"); ?></label>
         <?php
                    $category = category::model()->findAll(array('condition' => "IsActive = 1"));
                    $catlist = CHtml::listData($category, 'id', 'title');
                    echo CHtml::dropDownList('category',"" , $catlist, array('prompt' => 'Select Category...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;','required'=>true ));
                    ?>
    </li>
    <li>
        <label for='question_short_title'><?php $clang->eT("Question Short Title:"); ?></label>
        <input type='text' id='question_short_title' name='question_short_title' required="required"/>
    </li>
    <li>
        <label for='question_title'><?php $clang->eT("Question Title :"); ?></label>
        <input type='text' id='question_title' name='question_title' required="required"/>
    </li>
    <li>
        <label for='question_field_type'><?php $clang->eT("Question Field Type:"); ?></label>
         <?php
            $qtype = "SELECT name,display_name  FROM {{profile_question_type}} WHERE for_other = 0";
            $r_type = Yii::app()->db->createCommand($qtype)->query();
            $typelist = CHtml::listData($r_type, 'name', 'display_name');
            echo CHtml::dropDownList('question_field_type',"", $typelist);
         ?>        
    </li>
    <li>
        <label for='question_is_other'><?php $clang->eT("Is Having Option For Other? "); ?></label>
        <input type="checkbox" name='question_is_other' id="question_is_other" />
    </li>
    <li>
        <label for='question_field_other_type'><?php $clang->eT("Field Type For Other:"); ?></label>
        <?php
            $qtype = "SELECT name,display_name  FROM {{profile_question_type}} WHERE for_other = 1";
            $r_type = Yii::app()->db->createCommand($qtype)->query();
            $typelist = CHtml::listData($r_type, 'name', 'display_name');
            echo CHtml::dropDownList('question_field_other_type',"", $typelist);
         ?>                        
    </li>
    <li>
        <label for='question_outdate_threshold'><?php $clang->eT("Outdate Threshold:"); ?></label>
        <input type='text' id='question_outdate_threshold' name='question_outdate_threshold' required="required" value="0"/> (months) Enter 0 for Never
    </li>
    <li>
        <label for='question_priority'><?php $clang->eT("Priority:"); ?></label>
        <input type='text' id='question_priority' name='question_priority' required="required" value="0"/> Enter 0 for highest
    </li>
    <li>
        <label for='sort_order'><?php $clang->eT("Sort Order:"); ?></label>
        <input type='text' id='sort_order' name='sort_order' required="required" value="0"/>         
    </li>
    <li>
        <label for='question_is_profile'><?php $clang->eT("Is Profile Question? "); ?></label>
        <input type="checkbox" name='question_is_profile' id="question_is_profile" />
    </li>
     <li>
        <label for='question_is_project'><?php $clang->eT("Is Project Question? "); ?></label>
        <input type="checkbox" name='question_is_project' id="question_is_project" />
    </li>
     <li>
        <label for='IsActive'><?php $clang->eT("Is Active? "); ?></label>
        <input type="checkbox" name='IsActive' id="IsActive" />
    </li>
</ul>
<p>
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addquestion' />
</p>
</form>
