<!DOCTYPE html>
<html lang="<?php echo $pllang; ?>"<?php echo $languageRTL; ?>>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <?php
        $themepath = Yii::app()->getConfig("plstyleurl") . Yii::app()->getConfig("pltheme") . "/assests/";
        ?>
        <?php echo $datepickerlang; ?>
        <title><?php echo $sitename; ?></title>
        <link rel="shortcut icon" href="<?php echo $baseurl; ?>styles/favicon.png" type="image/png" />
        <link rel="icon" href="<?php echo $baseurl; ?>styles/favicon.png" type="image/png" />
        <link rel="stylesheet" href="<?php echo $themepath; ?>css/all.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $themepath; ?>css/ie.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $themepath; ?>css/ui.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $themepath; ?>css/boxes.css" type="text/css" />
        <?php echo $firebug ?>
        <?php $this->widget('ext.LimeScript.LimeScript'); ?>
        <?php $this->widget('ext.LimeDebug.LimeDebug'); ?>
        <script>
            function showsubmenu(){
                if ($('#opernersub').is(':hidden')) {
                    $('#opernersub').show();
                } else {
                    $('#opernersub').hide();
                }
            }
            $(document).click(function() {
                $('.opener').click(function(e){
                    e.stopPropagation()
                });
                $('#opernersub').click(function(e){
                    e.stopPropagation()
                });
                $(document).click(function() {
                    $('#opernersub').hide();
                });
            });
        </script>
    </head>
    <body>
        <?php if (isset($formatdata)) { ?>
            <script type='text/javascript'>
                var userdateformat='<?php echo $formatdata['jsdate']; ?>';
                var userlanguage='<?php echo $pllang; ?>';
            </script>
        <?php } ?>

        <?php $this->widget('ext.FlashMessage.FlashMessage'); ?>
<!-- <div class='maintitle'><img src="<?php echo $baseurl; ?>styles/prod-logo.png" style="width: 20%" alt="SurveyOffice" title="GoWebSuravey" /></div> -->
        <div id='wrapper'>
            <div id="content">
                <div class="c1">

                    <div class="controls">

                        <?php
                        if (Yii::app()->session['plid']) {
                            $ok = 0;
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
                                    $ok = 1;
                                    break;
                                }
                            }
                            $quelist = Question(get_question_categoryid('Profile'), '', false, true);
                            foreach ($quelist as $key => $value) {
                                if ($plans_list['question_id_' . $key] == '') {
                                    $ok = 1;
                                    break;
                                }
                            }
                            if ($ok == 1) {
                                $sty = 'visibility: hidden;';
                            } else {
                                $sty = '';
                            }
                            $sts_test = getGlobalSetting('project_status_test');
                            $sts_run = getGlobalSetting('project_status_run');
                            $date = date('Y-m-d');
                            $sql = "SELECT COUNT(*) AS cnt FROM {{panellist_project}} pp 
                                    LEFT JOIN {{project_master}} pm ON pp.project_id = pm.project_id
                                    WHERE (project_status_id = '$sts_test' OR project_status_id = '$sts_run') 
                                    AND trueup IS NULL AND panellist_id = '" . $_SESSION['plid'] . "' AND STATUS = 'A'";
                            $uresult = Yii::app()->db->createCommand($sql)->queryRow();
                            

                            //$total_survey = count(availablesurvey(Yii::app()->session['plid']));
                            $total_survey = $uresult['cnt'];
                            $total_message = count(messagePanellist($_SESSION['plid'], 'Unread'));
                            ?>
                            <nav class="links" style="<?php echo $sty; ?>">
                                <ul>
                                    <li><a href="<?php echo CController::createUrl('pl/home/sa/asurveys') ?>" title="Available Survey" class="ico3">Surveys <span class="num"><?php echo $total_survey; ?></span></a></li>
                                    <li><a href="<?php echo CController::createUrl('pl/home/sa/messsage') ?>" class="ico1">Inbox <span class="num"><?php echo $total_message; ?></span></a></li>
                                </ul>
                            </nav>

                            <div style="width:27%;float: left; margin-left: 10%; text-align: center">
                                <img src="<?php echo $baseurl; ?>styles/prod-logo.png"></img>
                            </div>

                            <div class="profile-box">
                                <span class="profile">
                                    <div style="position: relative; min-height: 37px;">
                                        <a href="#" class="section">
                                            <img class="image" src="<?php echo $baseurl; ?>images/user.png" alt="image description" width="26" height="26" />
                                            <span class="text-box">
                                                Welcome
                                                <strong class="name"><?php echo Yii::app()->session['plname'] ?></strong>
                                            </span>
                                        </a>
                                        <a onclick="showsubmenu();" class="opener">opener</a>
                                    </div>
                                    <div class="profile" id="opernersub" style="position: absolute; width: 213px; display: none; z-index: 1;" >
                                        <ul style="list-style: none">
                                            <li><a href="<?php echo $this->createUrl("pl/registration/sa/changepassword"); ?>">Change Password</a></li>
                                            <li><a href="<?php echo $this->createUrl("pl/registration/sa/changeemail"); ?>">Change Email</a></li>
                                        </ul>
                                    </div>
                                </span>
                                <a href="<?php echo $this->createUrl("pl/authentication/sa/logout"); ?>" class="btn-on">On</a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>


