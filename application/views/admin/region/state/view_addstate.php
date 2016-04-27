<div class='header ui-widget-header'><?php $clang->eT("Manage State"); ?></div><br />
<script>

    $(document).ready(function() {
        $('#listState').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>
<table id="listState" class="listState" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("Delete"); ?></th>
            <th><?php $clang->eT("State ID"); ?></th>
            <th><?php $clang->eT("State Name"); ?></th>
            <th><?php $clang->eT("Zone Name"); ?></th>
            <th><?php $clang->eT("Country Name"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            ?>
            <tr>

                <td style="padding:3px;">          
                    <?php echo CHtml::form(array('admin/state/sa/modifystate'), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this state"); ?>' />
                    <input type='hidden' name='action' value='modifystate' />
                    <input type='hidden' name='state_id' value='<?php echo $usr['state_id']; ?>' />
                    </form>
                </td>
                <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/state/sa/delstate'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this state"); ?>' />
                    <input type='hidden' name='action' value='delstate' />
                    <input type='hidden' name='state_name' value='<?php echo htmlspecialchars($usr['state_Name']); ?>' />
                    <input type='hidden' name='state_id' value='<?php echo $usr['state_id']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['state_id']; ?></td>
                <td><?php echo htmlspecialchars($usr['state_Name']); ?></td>
                <td><?php echo htmlspecialchars($usr['zone_Name']); ?></td>
                <td><?php echo htmlspecialchars($usr['country_name']); ?></td>
                <td><?php
                if ($usr['IsActive'] == True) {
                    echo 'True';
                } else {
                    echo 'False';
                }
                    ?></td>
            </tr>
            <?php $row++;
        } ?>
    </tbody>
</table>
<br />
<?php echo CHtml::form(array('admin/state/sa/addstate'), 'post'); ?>            
<table class='users' style="width: 100%;">
    <tr class='oddrow'>
        <th><?php $clang->eT("Add State"); ?></th>
        <td style='width:20%'>
            <input type='text' maxlength="50" name='new_state' placeholder="State Name"/>
        </td>
        <td style='width:20%'>
            <?php
            //$region = Country::model()->findAll(array('condition' => "IsActive = 1"));
            $region = Country::model()->isactive()->findAll(array('order' => 'country_name'));
            $reglist = CHtml::listData($region, 'country_id', 'country_name');
            echo CHtml::dropDownList('country_name', 'country_id', $reglist, array(
                'prompt' => 'Select Country...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                'ajax' => array(
                    'type' => 'POST',
                    'data' => array('action' => 'selectzone', 'country_name' => 'js:this.value', 'isactive' => '1'),
                    'url' => CController::createUrl('admin/state/sa/selectzone'),
                    'update' => '#zonelist',
                )
            ));
            ?>
        </td>
        <td>
            <?php
            echo CHtml::dropDownList('zonelist', '', array('zoneselect' => 'Select Zone...'));
            ?>
        </td>
        <td style='width:15%'><input type='submit' value='<?php $clang->eT("Save"); ?>' />
            <input type='hidden' name='action' value='addstate' /></td>
    </tr>
</table>
</form>