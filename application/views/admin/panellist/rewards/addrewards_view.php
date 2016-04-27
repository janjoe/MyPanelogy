<style type="text/css">
    .header,.ui-widget-header{
        background: none;
        border: none; 
    }
    div.menubar-title{
        background: #EDEDFB;
    }
</style>
<div class='header ui-widget-header'><?php $clang->eT("Add Reward"); ?></div>
<br />
<?php echo CHtml::form(array("admin/rewards/sa/add"), 'post', array('class' => 'form30', 'id' => 'newrewardform', 'enctype' => 'multipart/form-data')); ?>

<ul>
    <li>
        <label for='short_title'><?php $clang->eT("Short Title:"); ?></label>
        <input type='text' id='short_title' name='short_title' required="required"/>
    </li> 
    <li>
        <label for='title'><?php $clang->eT("Reward Title:"); ?></label>
        <input type='text' id='title' name='title' required="required"/>
    </li> 
    <li>
        <label for='type'><?php $clang->eT("Reward Type"); ?></label>
        <?php
        $qtype = "SELECT id,display_name  FROM {{reward_type}}";
        $r_type = Yii::app()->db->createCommand($qtype)->query();
        $typelist = CHtml::listData($r_type, 'id', 'display_name');
        echo CHtml::dropDownList('type', '', $typelist);
        ?>
    </li>   
    <li>
        <!--<label for='image'><?php //$clang->eT("Image");  ?></label>
        <input type="file"  id="image" name="image"><br>       -->
    </li>   
    <li>
        <label for='points'><?php $clang->eT("Points:"); ?></label>
        <input type='text' id='points' name='points' required="required"/>
    </li>
    <li>
        <label for='amount'><?php $clang->eT("Amount:"); ?></label>
        <input type='text' id='amount' name='amount' required="required"/>
    </li>
    <li>
        <label for='expiration_date'><?php $clang->eT("expiration_date:"); ?></label>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'expiration_date',
            // additional javascript options for the date picker plugin
            'options' => array(
                'dateFormat' => 'dd-M-yy',
                'showAnim' => 'blind',
                'changeMonth' => true,
                'changeYear' => true,
                'yearRange' => '1930:2020'
            ),
            'htmlOptions' => array(
                'style' => 'height:20px;
                ',
                'required' => true
            ),
        ));
        ?>        
    </li>
    <li>
        <label for='sorder'><?php $clang->eT("Sort Order:"); ?></label>
        <input type='text' id='sort_order' name='sorder' required="required"/> 
    </li>
    <li>
        <label for='IsActive'><?php $clang->eT("Is Active:"); ?></label>        
        <input type="checkbox" name="IsActive" id="IsActive" />             
    </li>               
</ul>
<p>
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addreward' />
</p>
</form>
