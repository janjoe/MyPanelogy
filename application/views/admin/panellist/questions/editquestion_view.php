<div class='header ui-widget-header'><?php $clang->eT("Edit Question -".$_GET['question_id']); ?></div>
<br />
<?php echo CHtml::form(array("admin/profilequestion/sa/mod/action/editquestion"), 'post', array('class' => 'form30', 'id' => 'editquestionform')); ?>
<?php 
foreach ($mur as $mrw) {
    ?>

<ul>
    <li>
        <label for='category'><?php $clang->eT("Category:"); ?></label>
         <?php
                    $category = category::model()->findAll(array('condition' => "IsActive = 1"));
                    $catlist = CHtml::listData($category, 'id', 'title');
                    echo CHtml::dropDownList('category',$mrw['category_id'] , $catlist, array('prompt' => 'Select Category...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;','required'=>true ));
                    ?>
    </li>
    <li>        
        <label for='question_short_title:'><?php $clang->eT("Question Short Title:"); ?></label>
        <input type='text' id='question_short_title' name='question_short_title' value="<?php echo $mrw['short_title']; ?>" required="required"/>
        <input type='hidden' id='question_id' name='question_id' value="<?php echo $mrw['id']; ?>" required="required"/>
    </li>
    <li>
        <label for='question_title:'><?php $clang->eT("Question Title :"); ?></label>
        <input type='text' id='question_title' name='question_title' value="<?php echo $mrw['title']; ?>"  required="required"/>
    </li>
    <li>
        <label for='question_field_type'><?php $clang->eT("Question Field Type:"); ?></label>
         <?php
            $qtype = "SELECT name,display_name  FROM {{profile_question_type}} WHERE for_other = 0";
            $r_type = Yii::app()->db->createCommand($qtype)->query();
            $typelist = CHtml::listData($r_type, 'name', 'display_name');
            echo CHtml::dropDownList('question_field_type',$mrw['field_type'], $typelist);
         ?>        
    </li>
    <li>
        <?php
        $chk = '';
        if ($mrw['is_other'] == 1) {
            $chk = 'checked=checked';
        }
        ?>
        <label for='question_is_other'><?php $clang->eT("Is Having Option For Other? "); ?></label>
        <input type="checkbox" <?php echo $chk; ?> name='question_is_other' id="question_is_other" />
    </li>
    <li>
        <label for='question_field_other_type'><?php $clang->eT("Field Type For Other:"); ?></label>
        <?php
            $qtype = "SELECT name,display_name  FROM {{profile_question_type}} WHERE for_other = 1";
            $r_type = Yii::app()->db->createCommand($qtype)->query();
            $typelist = CHtml::listData($r_type, 'name', 'display_name');
            echo CHtml::dropDownList('question_field_other_type',$mrw['is_other_field_type'], $typelist);
         ?>                        
    </li>
    <li>
        <label for='question_outdate_threshold'><?php $clang->eT("Outdate Threshold:"); ?></label>
        <input type='text' id='question_outdate_threshold'  value="<?php echo $mrw['outdate_threshold']; ?>" name='question_outdate_threshold' required="required"/> (months) Enter 0 for Never
    </li>
    <li>
        <label for='question_priority:'><?php $clang->eT("Priority:"); ?></label>
        <input type='text' id='question_priority' value="<?php echo $mrw['priority']; ?>" name='question_priority' required="required"/> Enter 0 for highest
    </li>
    <li>
        <label for='sort_order'><?php $clang->eT("Sort Order:"); ?></label>
        <input type='text' id='sort_order' value="<?php echo $mrw['sorder']; ?>" name='sort_order' required="required"/>         
    </li>
    <li>
        <?php
        $chk = '';
        if ($mrw['is_profile'] == 1) {
            $chk = 'checked=checked';
        }
        ?>
        <label for='question_is_profile'><?php $clang->eT("Is Profile Question? "); ?></label>
        <input type="checkbox" <?php echo $chk;?> name='question_is_profile' id="question_is_profile" />
    </li>
     <li>
       <?php
        $chk = '';
        if ($mrw['is_project'] == 1) {
            $chk = 'checked=checked';
        }
        ?>
        <label for='question_is_project'><?php $clang->eT("Is Project Question? "); ?></label>
        <input type="checkbox" <?php echo $chk; ?>name='question_is_project' id="question_is_project" />
    </li>
     <li>
        <?php
        $chk = '';
        if ($mrw['IsActive'] == 1) {
            $chk = 'checked=checked';
        }
        ?>
        <label for='IsActive'><?php $clang->eT("Is Active? "); ?></label>
        <input type="checkbox" <?php echo $chk;?> name='IsActive' id="IsActive" />
    </li>
</ul>
<?php } ?>
<p>
    <input type='submit' value='<?php $clang->eT("Submit"); ?>' />
    <input type='hidden' name='action' value='editquestion' />
</p>
</form>
<div class='menubar-main'>
<div class='menubar-left'>
    <h4>Answers</h4>
</div>
<div class='menubar-right'>            
             <?php echo CHtml::form(array('admin/profilequestion/sa/adda'), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add New Answer"); ?>' />
                    <input type='hidden' name='action' value='modifyanswer' />
                    <input type='hidden' name='question_id' value='<?php echo $_GET['question_id']; ?>' />
                    <input type='hidden' name='short_title' value='<?php echo $mrw['short_title']; ?>' />
                    </form>                            
</div>
</div>
<script>    
    $(document).ready(function() {
        $('#listprofileanswer').dataTable({"sPaginationType": "full_numbers"});
    } );
    
</script>

<table id="listprofileanswer" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("ID"); ?></th>
            <th><?php $clang->eT("Title"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($answer_arr); $i++) {
            $usr = $answer_arr[$i];
            ?>
            <tr>

                <td style="padding:3px;">
                    <?php echo CHtml::form(array('admin/profilequestion/sa/moda'), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Answer"); ?>' />
                    <input type='hidden' name='action' value='modifyanswre' />
                    <input type='hidden' name='question_id' value='<?php echo $_GET['question_id']; ?>' />
                    <input type='hidden' name='answer_id' value='<?php echo $usr['id']; ?>' />
                    <input type='hidden' name='short_title' value='<?php echo $mrw['short_title']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['id']; ?></td>              
                <td><?php echo htmlspecialchars($usr['title']); ?></td>
                <td><?php $cstatus="Yes"; if($usr['IsActive']=="0") $cstatus="No"; echo $cstatus;?></td>
            </tr>
            <?php
            $row++;
        }
        ?>
    </tbody>
</table>