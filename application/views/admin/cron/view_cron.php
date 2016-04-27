<div class='header ui-widget-header'><?php $clang->eT("Cron Status"); ?></div><br />
<script>
    $(document).ready(function() {
        var oTable = $('#listCron').dataTable({"sPaginationType": "full_numbers","order": [ [0,"desc"] ]});
        // Sort immediately with columns 0 and 1
        //oTable.fnSort( [ [1,'desc'],[0,'asc'] ] );
        oTable.fnSort( [ [1,'desc'] ] );
    } );
    function reloadpage(){
        return true;
    }
</script>

<?php
echo "<div id='your-form-block-id'>";
echo "<table id='listCron' style='width:100%'>";
$sql = "select * from {{CronLog}} ORDER BY Start_DateTime DESC";
$result = Yii::app()->db->createCommand($sql)->query()->readAll();
echo "<thead>
        <tr>
            <th>CronLog ID</td>
            <th>Start Date Time</th>
            <th>End Date Time</th>
        </tr>
      </thead>
      <tbody>";
foreach ($result as $key => $value) {
    echo "<tr>
            <td>" . $value['CronLogID'] . "</td>
            <td>" . $value['Start_DateTime'] . "</td>
            <td>" . $value['End_DateTime'] . "</td>
          </tr>";
}
echo "</tbody>
</table>";
echo '<br/><br/><br/>';
echo CHtml::beginForm();
if (count($result) > 0) {
    ?>
    <!-- Start by Parth 19-06-2014-->
    <a href="<?php echo CController::createUrl('admin/cron/sa/delcron/action/Clear_Previous_Data'); ?>" class="limebutton" style=" margin-left: 40%;">
        Clear Previous Data
    </a>
    <!-- End by Parth 19-06-2014-->
    <?php
}
echo CHtml::endForm();
echo "</div>";
?>