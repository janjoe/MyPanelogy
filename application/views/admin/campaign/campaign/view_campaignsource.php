<div class='header ui-widget-header'><?php $clang->eT("Manage Sources"); ?></div><br />
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
            <th><?php $clang->eT("Source ID"); ?></th>
            <th><?php $clang->eT("Source Name"); ?></th>
            <th><?php $clang->eT("Created By"); ?></th>
            <th><?php $clang->eT("Create Date"); ?></th>
            <th><?php $clang->eT("Edited By"); ?></th>
            <th><?php $clang->eT("Edited Date"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            ?>
            <tr>

                <td style="padding:3px;">    
                    <?php echo CHtml::form(array('admin/campaign/sa/modifycampaignsource'), 'post'); ?>
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Page"); ?>' />
                    <input type='hidden' name='action' value='modifycampaign' />
                    <input type='hidden' name='cmp_id' value='<?php echo $usr['cmp_id']; ?>' />
                    </form>
                </td>
<?php /*               <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/cms/sa/delcms'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this Page"); ?>' />
                    <input type='hidden' name='action' value='delcms' />
                    <input type='hidden' name='page_id' value='<?php echo $usr['page_id']; ?>' />
                    </form>
                </td> */?>
                <td><?php echo $usr['cmp_id']; ?></td>
                <td><?php echo htmlspecialchars($usr['source_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['created_by']); ?></td>
                <td><?php echo $usr['created_date']; ?></td>
                <td><?php echo htmlspecialchars($usr['edited_by']); ?></td>
                <td><?php echo $usr['edited_date']; ?></td>
            </tr>
            <?php $row++;
        } ?>
    </tbody>
</table>