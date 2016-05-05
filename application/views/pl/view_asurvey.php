<section class="container w90_per">
    <div class="box w98_per effect7">
        <h3>Available Surveys</h3>
        <p style="display: inline-block">
        <table class="InfoForm" style="width: 95%; margin: 0px auto;">
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Point</th>
                <th>Note</th>
            </tr>
            <?php
            //$sql = "select * from {{panellist_project}} where panellist_id = '" . $_SESSION['plid'] . "' and status = 'A' order by created_date desc";
            $asurvey = availablesurvey($_SESSION['plid']);
            //$uresult = Yii::app()->db->createCommand($sql)->query();
            //$count = $uresult->rowCount;
            $sts_test = getGlobalSetting('project_status_test');
            $sts_run = getGlobalSetting('project_status_run');
            $odd = FALSE;
            $i = 0;
            if (count($asurvey) > 0) {
                //$uresult = $uresult->readAll();
                foreach ($asurvey as $key => $value) {
                    if ($odd) {
                        $cls = 'class="odd"';
                    } else {
                        $cls = 'class="even"';
                    }
                    $project = Project::model()->findAllByPk($value['project_id']);
                    foreach ($project as $key => $val) {

                        if (($val['project_status_id'] == $sts_test || $val['project_status_id'] == $sts_run) && ($val['trueup'] == '' || $val['trueup'] == '0000-00-00 00:00:00') ) {
                         $i++;
                            if($val['friendly_name'] != ''){
                                $prname = $val['friendly_name'];
                            }else{
                                $prname = 'New Survey';
                            }
                            echo '<tr ' . $cls . '>
                                    <td>' . $value['project_id'] . '-' . $prname . '</td>
                                    <td>Available</td>
                                    <td>' . $value['points'] . '</td>
                                    <td><a href=' . $value['project_url'] . ' target="_blank">Take Survey</a></td>
                                  <tr>';
                            $odd = !$odd;
                        }
                    }
                }
                if ($i == 0) {
                    echo '<tr class="even"><td colspan="4">No new surveys</td></tr>';
                }
            } else {
                echo '<tr class="even"><td colspan="4">No new surveys</td></tr>';
            }
            ?>
        </table>
        </p>
    </div>
</section>