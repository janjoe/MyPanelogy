<div class='header ui-widget-header'><?php $clang->eT("Manage content"); ?></div><br />
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
            <th><?php $clang->eT("Page ID"); ?></th>
            <th><?php $clang->eT("Page Name"); ?></th>
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
                    <?php echo CHtml::form(array('admin/cms/sa/modifycms'), 'post'); ?>
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Page"); ?>' />
                    <input type='hidden' name='action' value='modifycms' />
                    <input type='hidden' name='page_id' value='<?php echo $usr['page_id']; ?>' />
                    </form>
                </td>
<!--                <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/cms/sa/delcms'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this Page"); ?>' />
                    <input type='hidden' name='action' value='delcms' />
                    <input type='hidden' name='page_id' value='<?php echo $usr['page_id']; ?>' />
                    </form>
                </td>-->
                <td><?php echo $usr['page_id']; ?></td>
                <td><?php echo htmlspecialchars($usr['page_name']); ?></td>
                <td><?php if ($usr['IsActive'] == True) {echo 'True';} else {echo 'False';} ?></td>
            </tr>
            <?php $row++;
        } ?>
    </tbody>
</table>