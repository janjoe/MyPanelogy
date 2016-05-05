<section class="container w90_per">
    <div class="box w98_per effect7">
        <h3>My Surveys</h3>
        <p style="display: inline-block">
        <table class="InfoForm" style="width: 95%; margin: 0px auto;">
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Point</th>
                <th>Point Awarded</th>
                <th>Note</th>
            </tr>

            <?php
            $status_test = getGlobalSetting('project_status_run');
            $status_run = getGlobalSetting('project_status_test');
            $status_completed = getGlobalSetting('project_status_completed');
            //$sql = "select * from {{panellist_project}} where panellist_id = '" . $_SESSION['plid'] . "' and status <> 'A' order by project_id desc";
            $sql = "SELECT project_id,project_url,points,status FROM {{panellist_project}}
                    WHERE panellist_id = '" . $_SESSION['plid'] . "' AND status <> 'A'
                    UNION ALL 
                    SELECT project_id,project_url,points,status FROM {{panellist_project}}
                    WHERE panellist_id = '" . $_SESSION['plid'] . "' AND status = 'A' 
                    AND project_id IN (SELECT project_id FROM {{project_master}} WHERE project_status_id NOT IN ('$status_test','$status_run'))
                    ORDER BY project_id DESC";
            $query_details = Yii::app()->db->createCommand($sql)->query()->readAll();

            if (count($query_details) > 0) {
                foreach ($query_details as $key => $value) {
                    //$ProjectSql = "SELECT Name,PFName,points,Status,trueup from projects_master where ID = '" . $value['project_id'] . "'";
                    $ProjectSql = "select project_name, friendly_name,reward_points,project_status_id,trueup from {{project_master}} where project_id = '" . $value['project_id'] . "' ";
                    $valueproj = Yii::app()->db->createCommand($ProjectSql)->queryRow();
                    $serveryStatus = $value['status'];
                    $recordTrueup = FALSE;
                    $check_trueup_exist_sql = "SELECT pp.project_id,pp.project_url,prd.rectify_id
                                    from {{panellist_project}} pp, {{panellist_redirects}} prd
                                    where pp.panellist_id = prd.panellist_id
                                    and pp.project_id = prd.project_id
                                    and pp.panellist_id = '" . $_SESSION['plid'] . "'
                                    and pp.project_id = '" . $value['project_id'] . "'
                                    and prd.rectify_id <> '' 
                                    order by project_id desc;";
                    $check_trueup_exist_qur = Yii::app()->db->createCommand($check_trueup_exist_sql)->query()->readAll();
                    $rows = 0;
                    if (count($check_trueup_exist_qur) > 0) {
                        $recordTrueup = TRUE;
                    }
                    $str = '';
                    $status = '';
                    $apoints = 0;
                    $rows++;

                    $cssclass = (($rows / 2) == floor($rows / 2)) ? 'even' : 'odd';
                    if (!$recordTrueup) {
                        switch ($serveryStatus) {
                            case "A"://Assign to panellist
                                $status = "Available";
                                $survey = '<a href="' . $value['project_url'] . '" target="_blank">TakeSurvey</a>';
                                //$hover = "Please click <strong>$TakeSurvey</strong> to begin.";
                                //$hover = "Please click Take Survey to begin.";//Remove
                                $hover = "Never clicked on survey link"; //Add
                                break;
                            case "R"://Redirected
                                $status = "Attempted";
                                $survey = "Survey Taken";
                                //$hover = "This study had been taken. Once this study has closed, your account will be credited upon validation of your responses.";//Remove
                                //$hover = "Those that abandon 'redirect' Status."; //Add//30/06/2014 Add By Hari
                                $hover = "Our records indicate you have attempted this survey."; //30/06/2014 Add By Hari
                                break;
                            case "D"://Disqualified
                                $status = "Did Not Qualify";
                                $survey = "Survey Taken";
                                $hover = "Unfortunately you did not qualify for this particular survey, but we'll be sure to send you another very soon. To improve your chances of successfully qualifying, please be sure your Profile is up-to-date.";
                                break;
                            case "Q"://Quota-Full
                                $status = "Did Not Qualify";
                                $survey = "Survey Taken";
                                $hover = "Unfortunately you did not qualify for this particular survey, but we'll be sure to send you another very soon. To improve your chances of successfully qualifying, please be sure your Profile is up-to-date.";
                                break;
                            case "PC"://Pending Completed
                                if ($valueproj['project_status_id'] == "$status_test" || $valueproj['project_status_id'] == "$status_run") {
                                    $status = "Completed - open study";
                                    $survey = "Survey Taken";
                                    $hover = "Once this study has closed, your account will be credited upon validation of your responses.";
                                }
                                if ($valueproj['project_status_id'] != "$status_test" && $valueproj['project_status_id'] != "$status_run") {
                                    $status = "Completed - pending validation";
                                    $survey = "Survey Taken";
                                    //$hover = "Upon validation of your responses, your Reward will be credited to your account. This may take one to two weeks.";//Remove
                                    //$hover = "Complete pre-true up."; //Add//30/06/2014 Remove By Hari
                                    $hover = "Your survey is being validated. Once validated your points will be awarded."; //30/06/2014 Add By Hari
                                }
                                break;
                                
                            case "C"://Completed
                                $status = "Completed & not rectified";
                                $survey = $valueproj['points'] . '  ';
                                $survey .= "Points Not Awarded";
                                $hover = "If these survey validates then you will be credited with reward points";
                                $apoints = $valueproj['points'];
                                break;

                            default:
                                $status = "Not a fit"; //Add
                                $survey = ""; //Add
                                $hover = "Term / QF"; //Add
                                break;
                        }
                    } else {
                        switch ($serveryStatus) {
                            case "C"://Completed
                                $status = "Completed & rectified";
                                $survey = $valueproj['reward_points'] . '  ';
                                $survey .= "Points Credited";
                                $hover = "These survey has been validated your account has been credited with reward points";
                                $apoints = $valueproj['reward_points'];
                                break;
                            //Add
                            case "PC"://Pending Completed
                                if ($valueproj['project_status_id'] != "$status_test" && $valueproj['project_status_id'] != "$status_run") {
                                    $status = "Completes - validated";
                                    $survey = "";
                                    $hover = "Those that were were set to status 6 - completed- validated via true up. ";
                                }
                                break;
                            //End
                            default:
                                $status = "Not a fit - study closed"; //Add
                                $survey = ""; //Add
                                $hover = "For any not a fit (Term or QF) that do not get changed via true up"; //Add
                                break;
                        }
                        if (strpos($serveryStatus, "E") != False) {
                            $status = "Failed Validation";
                            $survey = $serveryStatus;
                            //$hover = "Our client has indicated that you did not complete the survey to their data quality requirements. Please be sure to read each question carefully and provide thoughtful, accurate responses to each question in the future.";//REmove
                            $hover = "Those that were completes before true up that now get rejected."; //Add
                        } else {
                            if ($serveryStatus != "C") {
                                //$status = "Failed Validation";//Remove
                                $status = "Study Closed"; //Add//20/06/2014 Remove BY Hari
                                $survey = "Survey Closed";
                                $hover = "The study is now closed and your record/survey was not accepted.";//Remove
                                //$status = "Completed - Verified"; //Add//20/06/2014 Add BY Hari
                                //$hover = "Used for all previous available and those that were attempted that did not turn to complete via true up."; //Add
                            }
                        }
                    }

                    if (($valueproj['project_status_id'] != "$status_test" && $valueproj['project_status_id'] != "$status_run") && $serveryStatus == "A") {
                        $status = "Survey Closed";
                        $survey = "Survey Closed";
                        $hover = "This survey is now closed. We look forward to your participation on the next study!";
                    }
                    $survey_name = ($valueproj['friendly_name'] == "") ? $value['project_id'] . " - Survey" : $value['project_id'] . " - " . stripslashes($valueproj['friendly_name']);
                    echo '<tr class="' . $cssclass . '">
                            <td align="left">' . $survey_name . '</td>
                            <td align="left"><a class="bittooltip">' . $status . '
                                <span class="classic">' . $hover . '</span></a>
                            </td>
                            <td align="left">' . $value['points'] . '</td>
                            <td align="left">' . $apoints . '</td>
                            <td align="left">' . $survey . '</td>
                          </tr>';
                }
            } else {
                echo '<tr class="odd"><td colspan="5">No new surveys</td></tr>';
            }
            ?>
        </table>
        </p>
    </div>
</section>