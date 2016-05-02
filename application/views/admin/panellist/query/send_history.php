<div class='header ui-widget-header'>
    <?php
    $project = Project::model()->findAllByPk($project_id);
    $clang->eT("History For Project :" . $project[0]['project_name'] . ' [' . $project_id . ']');
    ?>
</div><br />
<script>
    
    $(document).ready(function() {
        $('#listhistory').dataTable({"sPaginationType": "full_numbers"});
    } );
    
</script>

<table id="listhistory" style="width:100%">
    <thead>
        <tr>
            
            <th><?php $clang->eT("Type"); ?></th>
            <th><?php $clang->eT("Q.ID"); ?></th>
            <th><?php $clang->eT("Date"); ?></th>
            <th><?php $clang->eT("Queue Invites"); ?></th>
            <th><?php $clang->eT("Status"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($history); $i++) {
            $usr = $history[$i];
            ?>
            <tr>

                <td style="padding:3px;">
                    <?php echo $usr['type']; ?>            
                </td>
                <td><?php echo $usr['query_id']; ?></td>
                <td><?php echo $usr['dt']; ?></td>              
                <td><?php echo $usr['queue']; ?></td>
                <td><?php
                if ($usr['status'])
                    echo "Sent"; else
                    echo "In the queue";
                ?></td>
            </tr>
            <?php
            $row++;
        }
        ?>
    </tbody>
</table>
