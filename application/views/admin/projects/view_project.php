<div class='header ui-widget-header'><?php $clang->eT("Manage Project"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listproject').dataTable({"sPaginationType": "full_numbers","iDisplayLength": 25});
    } );
    
</script>

<table id="listproject" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
<!--            <th><?php $clang->eT("Delete"); ?></th>-->
            <th><?php $clang->eT("Project ID"); ?></th>
            <th><?php $clang->eT("Parent"); ?></th>
            <th><?php $clang->eT("Project Name"); ?></th>
            <th><?php $clang->eT("Client"); ?></th>
            <th><?php $clang->eT("Contact"); ?></th>
            <th><?php $clang->eT("Project Manager"); ?></th>
            <th><?php $clang->eT("Sales Person"); ?></th>
            <th><?php $clang->eT("Completed"); ?></th>
            <th><?php $clang->eT("Quota"); ?></th>
            <th><?php $clang->eT("Project status"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            ?>
            <tr>

                <td style="padding:3px;">
                    <?php //echo CHtml::form(array('admin/project/sa/modifyproject'), 'post'); ?>
                    <?php echo CHtml::form(array('admin/project/sa/modifyproject/project_id/' . $usr['project_id'] . '/action/modifyproject'), 'post'); ?>
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Project"); ?>' />
                    <input type='hidden' name='action' value='modifyproject' />
                    <input type='hidden' name='project_id' value='<?php echo $usr['project_id']; ?>' />
                    </form>
                </td>
    <!--                <td  style="padding:3px;">
                <?php echo CHtml::form(array('admin/project/sa/delproject'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this project"); ?>' />
                    <input type='hidden' name='action' value='delproject' />
                    <input type='hidden' name='project_id' value='<?php echo $usr['project_id']; ?>' />
                    </form>
                </td>-->
                <td><?php echo $usr['project_id']; ?></td>
                <!--<td><?php echo $usr['parent_project_id']; ?></td>-->
                <td>
                    <?php if ($usr["parent_project_id"] <> 0) { ?>
                        <a href='<?php echo CController::createUrl('admin/project/sa/modifyproject/project_id/' . $usr["parent_project_id"] . '/action/modifyproject'); ?>'><?php echo $usr['parent_project_id']; ?></a>
                    <?php } else { echo '0'; } ?>
                </td>
                <td><?php echo htmlspecialchars($usr['project_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['client_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['contact_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['manager_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['sales_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['total_completed']); ?></td>
                <td><?php echo htmlspecialchars($usr['required_completes']); ?></td>
                <td><?php echo display_project_status($usr['project_status_id']); ?></td>
            </tr>
            <?php
            $row++;
        }
        ?>
    </tbody>
</table>