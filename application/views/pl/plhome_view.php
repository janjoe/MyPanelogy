<?php
$plans_list = array();
$sql = "SELECT * FROM {{panellist_answer}} WHERE panellist_id = '" . $_SESSION['plid'] . "'";
$pl_answer = Yii::app()->db->createCommand($sql)->query()->readAll();
foreach ($pl_answer as $key => $value) {
    foreach ($value as $ky => $val) {
        $plans_list[$ky] = $val;
    }
}
$quelist = Question(get_question_categoryid('Registration'), '', false, true);
foreach ($quelist as $key => $value) {
    if ($plans_list['question_id_' . $key] == '') {
        $message = $clang->gT('Thank you for joining our survey panel. Please complete the profile questions before moving ahead.');
        App()->user->setFlash('Error', $message);
        //$this->getController()->redirect(array('/pl/home/sa/edit_profile'));
        echo '<script>location.href = "' . CController::createUrl('pl/home/sa/edit_profile') . '";</script>';
    }
}
$quelist = Question(get_question_categoryid('Profile'), '', false, true);
foreach ($quelist as $key => $value) {
    if ($plans_list['question_id_' . $key] == '') {
        $message = $clang->gT('Thank you for joining our survey panel. Please complete the profile questions before moving ahead.');
        App()->user->setFlash('Error', $message);
        //$this->getController()->redirect(array('/pl/home/sa/edit_profile'));
        echo '<script>location.href = "' . CController::createUrl('pl/home/sa/edit_profile') . '";</script>';
    }
}
?>
<div id="tab-1">
    <section class="container w100_per">
        <div class="box w43_per effect7">
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
                            if (($val['project_status_id'] == $sts_test || $val['project_status_id'] == $sts_run) && ($val['trueup'] == '' || $val['trueup'] == '0000-00-00 00:00:00')) {
                                $i++;
                                if ($val['friendly_name'] != '') {
                                    $prname = $val['friendly_name'];
                                } else {
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
        &nbsp;&nbsp;
        <div class="box w43_per effect7">
            <h3>Available Rewards</h3>
            <p style="display: inline-block">
            <table class="InfoForm" style="width: 95%; margin: 0px auto;">
                <tr>
                    <th>Reward</th>
                    <th>Status</th>
                </tr>
                <?php
                $date = date('Y-m-d');
                $rewarlist = rewardsview('', $date);
                foreach ($rewarlist as $val) {
                    if ($val['IsActive'] == 1) {
                        $row++;
                        if (($row / 2) == floor($row / 2)) {
                            $cssclass = 'odd';
                        } else {
                            $cssclass = 'even';
                        }
                        echo '<tr class="' . $cssclass . '">
                            <td>' . $val['title'] . '</td>
                            <td>available</td>
                        </tr>';
                    }
                }
                ?>
            </table>
            </p>
        </div>
        <div class="box w43_per effect7">
            <h3>Latest News</h3>
            <p style="display: inline-block">
            <table class="InfoForm" style="width: 95%; margin: 0px auto;">
                <tr class="even">
                    <td>
                        <?php
                        $sql = "select * from {{cms_page_master}} where page_name = 'news'";
                        $uresult = Yii::app()->db->createCommand($sql)->query();
                        $count = $uresult->rowCount;
                        if ($count > 0) {
                            $uresult = $uresult->readAll();
                            $sql_content = "select page_content from {{cms_page_content}} where page_id = '" . $uresult[0]['page_id'] . "' and language_code = '" . Yii::app()->lang->langcode . "'";
                            $sql_content = "select page_content from {{cms_page_content}} where page_id = '" . $uresult[0]['page_id'] . "' and language_code = '" . Yii::app()->lang->langcode . "'";
                            $result = Yii::app()->db->createCommand($sql_content)->query()->readAll();
                            echo $result[0]['page_content'];
                        } else {
                            echo 'No news to report';
                        }
                        ?>
                    </td>
                </tr>
            </table>
            </p>
        </div>
        &nbsp;&nbsp;
        <div class="box w43_per effect7">
            <h3>Points Detail</h3>
            <p style="display: inline-block">
            <table class="InfoForm" style="width: 95%; margin: 0px auto;">

                <?php
                $sql = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $_SESSION['plid'] . ''));
                //print_r($sql);
                echo '<tr class = "even"><td><a class="bittooltip">Total Earned Points: ' . $sql[0]['earn_points'] . '<span class="classic">This is the total amount of point you have earned (does not including pending points)</span></a></td></tr>';
                echo '<tr class = "even"><td><a class="bittooltip">Available Points: ' . $sql[0]['balance_points'] . '<span class="classic">The total amount of points you have currently available to redeem rewards</span></a></td></tr>';
                //$sql = 'SELECT SUM(points) AS points FROM {{panellist_project}} WHERE status != \'A\' AND status != \'C\' AND panellist_id = ' . $_SESSION['plid'] . ' GROUP BY panellist_id';//28/06/2014 Remove By Hari
                $sql = "SELECT SUM(points) AS points FROM {{panellist_project}} WHERE status not in ('A','C','D') AND panellist_id = '" . $_SESSION['plid'] . "' GROUP BY panellist_id"; //28/06/2014 Add By Hari
                $result = Yii::app()->db->createCommand($sql)->queryRow();
                if ($result['points'] == '') {
                    $point = 0;
                } else {
                    $point = $result['points'];
                }
                //30/06/2014 Remove By Hari
                //echo '<tr class = "even"><td><a class="bittooltip" href="' . CController::createUrl('pl/home/sa/surveys') . '">Pending Points: ' . $point . '<span class="classic">Points associated with studies where we are awaiting validation of your completed survey before we can release those points to Available Points</span></a></a></td></tr>';
                //30/06/2014 Add By Hari
                echo "<tr class = 'even'><td>";
                echo "<div id='your-form-block-id'>";
                echo CHtml::beginForm();
                echo '<a class = "class-link" href="' . CController::createUrl('pl/home/sa/pendingpoints/panellist_id/' . $_SESSION['plid']) . '">Pending Points: ' . $point . '</a>';
                echo CHtml::endForm();
                echo "</div>";
                echo "</td></tr>";
                //360/06/2014 End
                ?>
            </table>
            </p>
        </div>        
    </section>
</div>
