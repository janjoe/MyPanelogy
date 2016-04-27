<div class='header ui-widget-header'><?php $clang->eT("Manage Contacts"); ?></div><br />
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
            <th><?php $clang->eT("Contact ID"); ?></th>
            <th><?php $clang->eT("Contact Name"); ?></th>
            <th><?php $clang->eT("Contact Group Name"); ?></th>
            <th><?php $clang->eT("Contact Title Name"); ?></th>
            <th><?php $clang->eT("Contact Type Name"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            if ($usr['company_type'] != 'O') {
                ?>
                <tr>

                    <td style="padding:3px;">
                        <?php echo CHtml::form(array('admin/contact/sa/modifycontact/contact_id/' . $usr['contact_id'] . '/action/modifycontact'), 'post'); ?>            
                        <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this user"); ?>' />
                        <input type='hidden' name='action' value='modifycontact' />
                        <input type='hidden' name='contact_id' value='<?php echo $usr['contact_id']; ?>' />
                        </form>
                    </td>
                    <td  style="padding:3px;">
                        <?php echo CHtml::form(array('admin/contact/sa/delcontact'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                        <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this user"); ?>' />
                        <input type='hidden' name='action' value='delcontact' />
                        <input type='hidden' name='contact_id' value='<?php echo $usr['contact_id']; ?>' />
                        </form>
                    </td>
                    <td><?php echo $usr['contact_id']; ?></td>
                    <td><?php echo htmlspecialchars($usr['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($usr['contact_group_name']); ?></td>
                    <td><?php echo htmlspecialchars($usr['contact_title_name']); ?></td>
                    <td><?php echo htmlspecialchars($usr['company_type_name']); ?></td>
                </tr>
                <?php
                $row++;
            }
        }
        ?>
    </tbody>
</table>