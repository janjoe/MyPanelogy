<?php //30/06/2014 Page Create By Hari  ?>
<script>
    function reloadpage(){
        return true;
    }
</script>
<table class="InfoForm" style="width: 95%; margin: 0px auto;">
    <tr>
        <th>Project ID</th>
        <th>Project Name</th>
        <th>Points</th>
    </tr>
    <?php
    $TotalPoints = 0;
    $sql = "SELECT pp.project_id,pm.project_name,IFNULL(pp.points,0) as points FROM {{panellist_project}} pp 
            left outer join {{project_master}} pm on pp.project_id=pm.project_id
            WHERE status not in ('A','C','D') AND panellist_id = '" . $panellist_id . "'";
    $query_details = Yii::app()->db->createCommand($sql)->query()->readAll();
    if (count($query_details) > 0) {
        foreach ($query_details as $key => $value) {
            $TotalPoints = $TotalPoints + $value['points'];
            echo '<tr class="even">
                  <td>' . $value['project_id'] . '</td>
                  <td>' . $value['project_name'] . '</td>
                  <td>' . $value['points'] . '</td></tr>';
        }
        echo "<tr class='even' style='font-weight:bold;'><td colspan='2'>Total Pending Points</td><td>$TotalPoints</td></tr>";
    } else {
        echo "<tr class='even'><td colspan='3'>No Records</td></tr>";
    }
    ?>
</table>
