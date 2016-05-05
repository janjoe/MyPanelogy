<div class='header ui-widget-header'><?php $clang->eT("Manage Contact Title"); ?></div><br />
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
            <th><?php $clang->eT("Contact Title ID"); ?></th>
            <th><?php $clang->eT("Contact Title Name"); ?></th>
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
                    <?php echo CHtml::form(array('admin/contact_title/sa/modifycontact_title'), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this contact title"); ?>' />
                    <input type='hidden' name='action' value='modifycontact_title' />
                    <input type='hidden' name='contact_title_id' value='<?php echo $usr['contact_title_id']; ?>' />
                    </form>
                </td>
                <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/contact_title/sa/delcontact_title'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this contact title"); ?>' />
                    <input type='hidden' name='action' value='delcontact_title' />
                    <input type='hidden' name='contact_title_name' value='<?php echo htmlspecialchars($usr['contact_title_name']); ?>' />
                    <input type='hidden' name='contact_title_id' value='<?php echo $usr['contact_title_id']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['contact_title_id']; ?></td>
                <td><?php echo htmlspecialchars($usr['contact_title_name']); ?></td>
                <td><?php IF ($usr['IsActive'] == TRUE) {echo 'True';} ELSE {echo 'False';} ?></td>
            </tr>
            <?php $row++;
        } ?>
    </tbody>
</table>
<br />
<?php echo CHtml::form(array('admin/contact_title/sa/addcontact_title'), 'post'); ?>            
<table class='users'>
    <tr class='oddrow'>
        <th><?php $clang->eT("Contact Title Name:"); ?></th>
        <td style='width:20%'><input type='text' maxlength="50" name='new_contact_title' placeholder="Contact Title Name" /></td>
        <td style='width:15%'><input type='submit' value='<?php $clang->eT("Save"); ?>' />
            <input type='hidden' name='action' value='addcontact_title' />
        </td>
    </tr>
</table>
</form>