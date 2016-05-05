<style type="text/css">
    .header,.ui-widget-header{
        background: none;
        border: none; 
    }
    div.menubar-title{
        background: #EDEDFB;
    }
</style>
<div class='header ui-widget-header'><?php $clang->eT("Edit Reward -" . $_POST['id']); ?></div>
<br />
<?php echo CHtml::form(array("admin/rewards/sa/mod"), 'post', array('class' => 'form30', 'id' => 'editrewardform')); ?>
<?php
foreach ($mur as $mrw) {
    ?>

    <ul>
        <li>
            <label for='short_title'><?php $clang->eT("Short Title:"); ?></label>
            <input type='text' id='short_title' name='short_title' value="<?php echo $mrw['short_title']; ?>" required="required"/>
            <input type='hidden' id='reward_id' name='reward_id' value="<?php echo $mrw['id']; ?>" required="required"/>
        </li> 
        <li>
            <label for='title'><?php $clang->eT("Reward Title:"); ?></label>
            <input type='text' id='title' name='title' value="<?php echo $mrw['title']; ?>" required="required"/>
        </li> 
        <li>
            <label for='type'><?php $clang->eT("Reward Type"); ?></label>
            <?php
            $qtype = "SELECT id,display_name  FROM {{reward_type}}";
            $r_type = Yii::app()->db->createCommand($qtype)->query();
            $typelist = CHtml::listData($r_type, 'id', 'display_name');
            echo CHtml::dropDownList('type', $mrw['type'], $typelist);
            ?>
        </li>    
        <li> <?php //if ($mrw['image']!="") { ?>
            <!--<img src='<?php //echo Yii::app()->baseurl."/upload/images/".$mrw['image'];  ?>'/>   -->         
            <?php //} ?>
        </li>
        <li>
            <!--<label for='image'><?php //$clang->eT("Change Image");  ?></label>
            <input type="file"  id="image" name="image"><br> -->

        </li>   
        <li>
            <label for='points'><?php $clang->eT("Points:"); ?></label>
            <input type='text' id='points' name='points' value="<?php echo $mrw['points']; ?>" required="required"/>
        </li>
        <li>
            <label for='amount'><?php $clang->eT("Amount:"); ?></label>
            <input type='text' id='amount' name='amount' value ="<?php echo $mrw['amount']; ?>" required="required"/>
        </li>
        <li>
            <label for='expiration_date'><?php $clang->eT("expiration_date:"); ?></label>
            <?php
            $expiration_date = date('d-M-Y', strtotime($mrw['expiration_date']));
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'expiration_date',
                'value' => $expiration_date,
                // additional javascript options for the date picker plugin
                'options' => array(
                    'dateFormat' => 'dd-M-yy',
                    'showAnim' => 'blind',
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => '1930:2030'
                ),
                'htmlOptions' => array(
                    'style' => 'height:20px;',
                    'required' => true
                ),
            ));
            ?>
        </li>
        <li>
            <label for='sorder'><?php $clang->eT("Sort Order:"); ?></label>
            <input type='text' id='sort_order' name='sorder' value ="<?php echo $mrw['sorder']; ?>"required="required"/> 
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
        </li>               
    </ul>
<?php } ?>
<p>
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='editreward' />
</p>
</form>
