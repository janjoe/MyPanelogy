<div class='header ui-widget-header'><?php $clang->eT("Manage Zone"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listZone').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>
<table id="listZone" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("Delete"); ?></th>
            <th><?php $clang->eT("Zone ID"); ?></th>
            <th><?php $clang->eT("Zone Name"); ?></th>
            <th><?php $clang->eT("Country Name"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($zonelist); $i++) {
            $usr = $zonelist[$i];
            ?>
            <tr>

                <td style="padding:3px;">          
                    <?php echo CHtml::form(array('admin/zone/sa/modifyzone'), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this zone"); ?>' />
                    <input type='hidden' name='action' value='modifyzone' />
                    <input type='hidden' name='zone_id' value='<?php echo $usr['zone_id']; ?>' />
                    </form>
                </td>
                <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/zone/sa/delzone'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this zone"); ?>' />
                    <input type='hidden' name='action' value='delzone' />
                    <input type='hidden' name='zone_Name' value='<?php echo htmlspecialchars($usr['zone_Name']); ?>' />
                    <input type='hidden' name='zone_id' value='<?php echo $usr['zone_id']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['zone_id']; ?></td>
                <td><?php echo htmlspecialchars($usr['zone_Name']); ?></td>
                <td><?php echo htmlspecialchars($usr['country_name']); ?></td>
                <td><?php if ($usr['IsActive'] == True) {echo 'True';} else {echo 'False';} ?></td>
            </tr>
            <?php $row++;
        } ?>
    </tbody>
</table>
<br />
<?php echo CHtml::form(array('admin/zone/sa/addzone'), 'post'); ?>            
<table class='users'>
    <tr class='oddrow'>
        <th><?php $clang->eT("Add Zone:"); ?></th>
        <td style='width:20%'>
            <input type='text' maxlength="50" name='new_zone' placeholder="Zone Name"/>
        </td>
        <td style='width:20%'>
            <?php
            $region = Country::model()->isactive()->findAll(array('order'=>'country_name'));
            $reglist = CHtml::listData($region, 'country_id', 'country_name');
            echo CHtml::dropDownList('country_name', 'country_id', $reglist, array('prompt' => 'Select Country...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'));
            ?>
        </td>
        <td style='width:15%'><input type='submit' value='<?php $clang->eT("Save"); ?>' />
            <input type='hidden' name='action' value='addzone' /></td>
    </tr>
</table>
</form>