<div class='header ui-widget-header'><?php $clang->eT("Manage Company"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listContactGroup').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>

<table id="listContactGroup" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
<!--            <th><?php $clang->eT("Delete"); ?></th>-->
            <th><?php $clang->eT("ID"); ?></th>
            <th><?php $clang->eT("Company Name"); ?></th>
            <th><?php $clang->eT("Company Email ID"); ?></th>
            <th><?php $clang->eT("Company Type Name"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            if ($usr['company_id'] == -1) {
                ?>
                <tr>

                    <td style="padding:3px;">
                        <?php echo CHtml::form(array('admin/contact/sa/modifycontact/contact_id/' . $usr['contact_id'] . '/action/modifycompany'), 'post'); ?>            
                        <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this company"); ?>' />
                        <input type='hidden' name='action' value='modifycompany' />
                        <input type='hidden' name='contact_id' value='<?php echo $usr['contact_id']; ?>' />
                        </form>
                    </td>
<!--                    <td  style="padding:3px;">
                        <?php echo CHtml::form(array('admin/contact/sa/delcontact'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                        <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this company"); ?>' />
                        <input type='hidden' name='action' value='delcontact' />
                        <input type='hidden' name='contact_id' value='<?php echo $usr['contact_id']; ?>' />
                        </form>
                    </td>-->
                    <td><?php echo $usr['contact_id']; ?></td>
                    <td><?php echo htmlspecialchars($usr['company_name']); ?></td>
                    <td><?php echo htmlspecialchars($usr['primary_emailid']); ?></td>
                    <td><?php echo htmlspecialchars($usr['company_type_name']); ?></td>
                    <td><?php IF ($usr['IsActive'] == TRUE) {echo 'True';} ELSE {echo 'False';} ?></td>
                </tr>
                <?php
                $row++;
            }
        }
        ?>
    </tbody>
</table>