<script>
    $(document).ready(function() {
        $('#listPanellist').dataTable({"sPaginationType": "full_numbers"});
    });
    //17/06/2014 Add By Parth-Hari
    function reloadpage(){
        return true;
    }
    //17/06/2014 End
</script>

<script type="text/javascript">
    function confirmSubmit(state){
        if(state == 'D'){
            msg = "Are you sure you want Disable Panelist?";
        }else{
            msg = "Are you sure you want Enable Panelist?";
        }
        var agree=confirm(msg);
        if (agree)
            return true;
        else
            return false;
    }
    
    function confirmSubmitFraud(state){
        if(state == 'D'){
            msg = "Are you sure you want to mark Panelist as Fraud?";
        }else{
            msg = "Are you sure you want to mark Panelist as Safe?";
        }
        var agree=confirm(msg);
        if (agree)
            return true;
        else
            return false;
    }
</script>

<?php

function print_pl_view_data($r) { ?>
    <div id="tabs-1" class="tabcontent ui-tabs-panel ui-widget-content ui-corner-bottom">
        <table width="100%" border="0" cellpadding="5" id="invDetail" class="InfoForm">
            <tbody><tr class="even gradeC" style="font-weight:bold;">
    <!--                    <td>Max No of Study</td>
                    <td>Balance Study</td>-->
                    <td>RR%</td>
                    <td>Invited</td>
                    <td>Redirected</td>
                    <td>Completed</td>
                    <td>Disqualified</td>
                    <td>Quota Full</td>
                    <td>Earned Points</td>
                    <td>Balance Points</td>
                </tr>
                <tr class="odd gradeC">
    <!--                    <td><?php echo $r['max_no_study']; ?></td>
                    <td><?php echo $r['balance_no_study']; ?></td>-->
                    <td><?php echo getPanelRR($r['panel_list_id']); ?>%</td>
                    <td><?php echo $r['no_invited']; ?></td>
                    <td><?php echo $r['no_redirected']; ?></td>
                    <td><?php echo $r['no_completed']; ?></td>
                    <td><?php echo $r['no_disqualified']; ?></td>
                    <td><?php echo $r['no_qfull']; ?></td>
                    <td><?php echo $r['earn_points']; ?></td>
                    <td><?php echo $r['balance_points']; ?></td>
                </tr>
            </tbody>
        </table>
        <br>
        <h2>General Information</h2>
        <?php
        $pl_details = $plans_list = array();
        $sql = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '" . $r['panel_list_id'] . "'";
        $pl_details = Yii::app()->db->createCommand($sql)->query()->readAll();

        $sql = "SELECT * FROM {{panellist_answer}} WHERE panellist_id = '" . $r['panel_list_id'] . "'";
        $pl_answer = Yii::app()->db->createCommand($sql)->query()->readAll();
        foreach ($pl_answer as $key => $value) {
            foreach ($value as $ky => $val) {
                $plans_list[$ky] = $val;
            }
        }
        ?>
        <table class="InfoForm" style="width: 100%">
            <tr class="even">
                <td>Email Address</td>
                <td><?php echo $pl_details[0]['email'] ?></td>
            </tr>
            <tr class="odd">
                <td>Name</td>
                <td><?php echo $pl_details[0]['full_name'] ?></td>
            </tr>
            <tr class="even">
                <td>Status</td>
                <td>
                    <?php
                    if ($pl_details[0]['status'] == 'E') {
                        echo 'Enabled';
                    }
                    if ($pl_details[0]['status'] == 'D') {
                        echo 'Disabled';
                    }
                    if ($pl_details[0]['status'] == 'C') {
                        echo 'Cancelled';
                    }
                    if ($pl_details[0]['status'] == 'R') {
                        echo 'Registered';
                    }
                    ?>
                </td>
            </tr>
            <tr class="odd">
                <td>Fraud</td>
                <td>
                    <?php
                    if ($pl_details[0]['is_fraud'] == '0') {
                        echo 'No';
                    }
                    if ($pl_details[0]['is_fraud'] == '1') {
                        echo 'Yes';
                    }
                    ?>
                </td>
            </tr>
            <?php
            $odd = FALSE;
            $quelist = Question(get_question_categoryid('Registration'), '', false, true);
            $quetype = Question(get_question_categoryid('Registration'), '', true, false);
            foreach ($quelist as $key => $value) {
                if ($odd) {
                    $cls = 'class="odd"';
                } else {
                    $cls = 'class="even"';
                }
                echo '<tr ' . $cls . '>
                        <td>' . $value . '</td>';
                if ($quetype[$key] == 'Text' || $quetype[$key] == 'DOB' || $quetype[$key] == 'TextArea') {
                    echo '<td>' . $plans_list['question_id_' . $key] . '</td>';
                } else {
                    echo '<td>' . get_answer($plans_list['question_id_' . $key], $key) . '</td>';
                }
                echo '<tr>';
                $odd = !$odd;
            }
            ?>
        </table>
        <br>
        <h2>Professional Information</h2>
        <table class="InfoForm" style="width: 100%">
            <?php
            $odd = FALSE;
            $quelist = Question(get_question_categoryid('Profile'), '', false, true);
            $quetype = Question(get_question_categoryid('Profile'), '', true, false);
            foreach ($quelist as $key => $value) {
                if ($odd) {
                    $cls = 'class="odd"';
                } else {
                    $cls = 'class="even"';
                }
                echo '<tr ' . $cls . '>
                        <td>' . $value . '</td>';
                if ($quetype[$key] == 'Text' || $quetype[$key] == 'DOB' || $quetype[$key] == 'TextArea') {
                    echo '<td>' . $plans_list['question_id_' . $key] . '</td>';
                } else {
                    echo '<td>' . get_answer($plans_list['question_id_' . $key], $key) . '</td>';
                }
                echo '<tr>';
                $odd = !$odd;
            }
            ?>
        </table> 
    </div>
<?php } ?>
<div class='header ui-widget-header'><?php $clang->eT("Manage Panel Lists"); ?></div><br />

<table id="listPanellist" style="width:100%">
    <thead>
        <tr>
            <th width="25px"><?php $clang->eT("View"); ?></th>
            <th width="auto"><?php $clang->eT("Panel list ID"); ?></th>
            <th width="auto"><?php $clang->eT("Email ID"); ?></th>
            <th width="auto"><?php $clang->eT("Full Name"); ?></th>
            <th width="auto"><?php $clang->eT("Remote IP"); ?></th>
            <th width="auto"><?php $clang->eT("Status"); ?></th>
            <th width="auto"><?php $clang->eT("Is Fraud ?"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $pl_query = "SELECT * FROM {{view_panel_list_master}} order by full_name";
        $dr = Yii::app()->db->createCommand($pl_query)->query()->readAll();
        //$dr = PL::model()->findAll();
        for ($i = 0; $i < count($dr); $i++) {
            $row = $dr[$i];
            ?>
            <tr>
                <td  style="padding:3px; width:25px">
                    <?php
                    //17/06/2014 Add By Parth-Hari
                    echo "<div id='your-form-block-id'>";
                    echo CHtml::beginForm();
                    //echo CHtml::link($row['panel_list_id'], array('admin/panellist/PanellistInfo/panel_list_id/' . $row['panel_list_id']), array('class' => 'class-link'));
                    echo CHtml::link("<img src='" . $imageurl . "icon-view.png' width='24px;' alt='View Panel List Profile Details'/>", array('admin/panellist/PanellistInfo/panel_list_id/' . $row['panel_list_id']), array('class' => 'class-link'));
                    echo CHtml::endForm();
                    echo "</div>";
                    //17/06/2014 End
                    ?> 
                </td>
                <td><?php echo $row['panel_list_id']; ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['remote_ip']); ?></td>
                <td>
                    <?php
                    if ($row['status'] == 'E') {
                        echo '<a class="bittooltip" href="' . CController::createUrl('admin/panellist/sa/changestatus/s/D/pid/' . $row['panel_list_id']) . '" style="color:#447BF7;" onClick="return confirmSubmit(\'D\');">Enabled<span class="classic">Click to Disable</span></a>';
                    }
                    if ($row['status'] == 'D') {
                        echo '<a class="bittooltip" href="' . CController::createUrl('admin/panellist/sa/changestatus/s/E/pid/' . $row['panel_list_id']) . '" style="color:#447BF7;" onClick="return confirmSubmit(\'E\');">Disabled<span class="classic">Click to Enable</span></a>';
                    }
                    if ($row['status'] == 'C') {
                        echo '<a class="bittooltip" href="' . CController::createUrl('admin/panellist/sa/changestatus/s/E/pid/' . $row['panel_list_id']) . '" style="color:#447BF7;" onClick="return confirmSubmit(\'E\');">Cancelled<span class="classic">Click to Enable</span></a>';
                    }
                    if ($row['status'] == 'R') {
                        echo '<a class="bittooltip" style="color:#447BF7;" >Registered<span class="critical">Can\'t change Status</span></a>';
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['is_fraud'] == '0') {

                        echo '<a href="' . CController::createUrl('admin/panellist/sa/changefraud/s/1/pid/' . $row['panel_list_id']) . '" style="color:#447BF7;" onClick="return confirmSubmitFraud(\'D\');">
                                <img src="' . $imageurl . 'false.gif" title="No" alt="No"></img>
                             </a>';
                    }
                    if ($row['is_fraud'] == '1') {

                        echo '<a href="' . CController::createUrl('admin/panellist/sa/changefraud/s/0/pid/' . $row['panel_list_id']) . '" style="color:#447BF7;" onClick="return confirmSubmitFraud(\'E\');">
                                <img src="' . $imageurl . 'true.gif" title="Yes" alt="Yes"></img>
                             </a>';
                    }
                    ?>
                </td>
            </tr>
            <?php
            $row++;
        }
        ?>
    </tbody>
</table>
<!-- generating div boxes for view and edit pop ups. Not possible in table's tr td. -->
<?php
/*
  $i = 0;
  for ($i = 0; $i < count($dr); $i++) {
  $row = $dr[$i];
  ?>
  <div id="box_view_<?php echo $row['panel_list_id'] ?>">

  <?php
  //echo "box_view_" . $row['panel_list_id'];
  $sql = "SELECT * FROM {{panellist_answer}} WHERE panellist_id = '" . $row['panel_list_id'] . "'";
  $result = Yii::app()->db->createCommand($sql)->query();
  $count = $result->rowCount;
  if ($count > 0) {
  print_pl_view_data($row);
  }
  ?>

  </div>
  <?php
  $row++;
  } */
?>
