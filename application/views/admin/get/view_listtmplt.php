<div class='header ui-widget-header'><?php $clang->eT("Manage Email Templates"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listEmailTemplates').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>

<table id="listEmailTemplates" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("Delete"); ?></th>
            <th><?php $clang->eT("Template ID"); ?></th>
            <th><?php $clang->eT("Template Title"); ?></th>
            <th><?php $clang->eT("Use In"); ?></th>
            <th><?php $clang->eT("Email Subject"); ?></th>
            <th><?php $clang->eT("Email Body Content"); ?></th>
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
                    <?php echo CHtml::form(array('admin/get/sa/edit_tmplt'), 'post'); ?>
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Page"); ?>' />
                    <input type='hidden' name='template_emailid' value='<?php echo $usr['template_emailid']; ?>' />
                    </form>
                </td>
                <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/get/sa/del_tmplt'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this Page"); ?>' />
                    <input type='hidden' name='action' value='del_tmplt' />
                    <input type='hidden' name='template_emailid' value='<?php echo $usr['template_emailid']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['template_emailid']; ?></td>
                <td><?php echo ($usr['title_text']); ?></td>
                <td><?php echo getEmailUseInWord($usr['use_in']) ?></td>
                <td><?php echo ($usr['subject_text']); ?></td>
                <td><?php echo ($usr['content_text']); ?></td>
                <td><?php if ($usr['IsActive'] == True) {echo 'True';} else {echo 'False';} ?></td>
            </tr>
            <?php
            $row++;
        }
        ?>
    </tbody>
</table>