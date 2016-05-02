<?php
//17/06/2014 Page Add By Parth-Hari
//echo $panel_list_id;
$pl_query = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '" . $panel_list_id . "'";
$dr = Yii::app()->db->createCommand($pl_query)->query()->readAll();
$i = 0;
for ($i = 0; $i < count($dr); $i++) {
    $row = $dr[$i];
    $sql = "SELECT * FROM {{panellist_answer}} WHERE panellist_id = '" . $panel_list_id . "'";
    $result = Yii::app()->db->createCommand($sql)->query();
    $count = $result->rowCount;
    if ($count > 0) {
        print_pl_view_data($row);
    }
    $row++;
}

function print_pl_view_data($r) {
    ?>
    <div id="tabs-1" class="tabcontent ui-tabs-panel ui-widget-content ui-corner-bottom">
        <h4 id="popup_title" class="popup_title" style="text-align: center;">Viewing details of <?php echo $r['full_name']; ?></h4>
        <table width="100%" border="0" cellpadding="5" id="invDetail" class="InfoForm">
            <tbody>
                <tr class="even gradeC" style="font-weight:bold;">
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
