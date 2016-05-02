<div class='header ui-widget-header'><?php $clang->eT("Query and Count"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listprofilecategory').dataTable({"sPaginationType": "full_numbers"});
    } );
    
    function reloadpage(){
        return true;
    }
    
</script>

<table id="listprofilecategory" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("ID"); ?></th>
            <th><?php $clang->eT("Name"); ?></th>
            <th><?php $clang->eT("Project"); ?></th>
            <th><?php $clang->eT("Sending"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            $project = Project::model()->findAllByPk($usr['project_id']);
            ?>
            <tr>

                <td style="padding:3px;">
                    <?php echo CHtml::form(array('admin/pquery/sa/mod'), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Query"); ?>' />
                    <input type='hidden' name='action' value='modifyquery' />
                    <input type='hidden' name='query_id' value='<?php echo $usr['id']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['id']; ?></td>              
                <td><?php echo htmlspecialchars($usr['name']); ?></td>
                <td><?php echo $project[0]['project_name'] . ' [' . $usr['project_id'] . ']'; ?></td>

                <td>
                    <?php
                    echo "<div id='your-form-block-id'>";
                    echo CHtml::beginForm();
                    echo CHtml::link('Send Invitations', array('admin/pquery/sa/send/id/' . $usr['id'] . '/prjid/' . $usr['project_id'] . '/qname/' . $usr['name']), array('class' => 'class-link'));
                    echo " | ";
                    echo CHtml::link('Reminder', array('admin/pquery/sa/send/id/' . $usr['id'] . '/prjid/' . $usr['project_id'] . '/resend/1/qname/' . $usr['name']), array('class' => 'class-link'));
                    echo " | ";
                    echo CHtml::link('History', array('admin/pquery/sa/history/prjid/' . $usr['project_id'] . '/qname/' . $usr['name']), array('class' => 'class-link'));
                    echo CHtml::endForm();
                    echo "</div>";
                    ?> 

                </td>
            </tr>
            <?php
            $row++;
        }
        ?>
    </tbody>
</table>
