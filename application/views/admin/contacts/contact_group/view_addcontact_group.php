<div class='header ui-widget-header'><?php $clang->eT("Manage Contact Group"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listContactGroup').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>

<table id="listContactGroup" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("Delete"); ?></th>
            <th><?php $clang->eT("Contact Group ID"); ?></th>
            <th><?php $clang->eT("Contact Group Name"); ?></th>
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
                    <?php echo CHtml::form(array('admin/contact_group/sa/modifycontact_group'), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this contact group"); ?>' />
                    <input type='hidden' name='action' value='modifycontact_group' />
                    <input type='hidden' name='contact_group_id' value='<?php echo $usr['contact_group_id']; ?>' />
                    </form>
                </td>
                <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/contact_group/sa/delcontact_group'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this contact group"); ?>' />
                    <input type='hidden' name='action' value='delcontact_group' />
                    <input type='hidden' name='contact_group_name' value='<?php echo htmlspecialchars($usr['contact_group_name']); ?>' />
                    <input type='hidden' name='contact_group_id' value='<?php echo $usr['contact_group_id']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['contact_group_id']; ?></td>
                <td><?php echo htmlspecialchars($usr['contact_group_name']); ?></td>
                <td><?php IF ($usr['IsActive'] == TRUE) {echo 'True';} ELSE {echo 'False';} ?></td>
            </tr>
            <?php $row++;
        } ?>
    </tbody>
</table>
<br />
<?php echo CHtml::form(array('admin/contact_group/sa/addcontact_group'), 'post'); ?>            
<table class='users'>
    <tr class='oddrow'>
        <th><?php $clang->eT("Add Contact Group Name:"); ?></th>
        <td style='width:20%'><input type='text' maxlength="50" name='new_contact_group' placeholder="Contact Group Name" /></td>
        <td style='width:15%'><input type='submit' value='<?php $clang->eT("Save"); ?>' />
            <input type='hidden' name='action' value='addcontact_group' />
        </td>
    </tr>
</table>
</form>