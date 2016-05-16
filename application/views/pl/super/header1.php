<!DOCTYPE html>
<html lang="<?php echo $pllang; ?>"<?php echo $languageRTL; ?>>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <?php
        $themepath = Yii::app()->getConfig("plstyleurl") . Yii::app()->getConfig("pltheme") . "/assests/";
        $templatepath = $baseurl . '/upload/templates/azure/';
        ?>
        <?php echo $datepickerlang; ?>
        <title><?php echo $sitename; ?></title>

        <meta charset="utf-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel="stylesheet" type="text/css" href="<?php echo $templatepath; ?>css/styles.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $templatepath; ?>css/slick.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $templatepath; ?>css/slick-theme.css">

        <!----> 
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
			
		  <div class="md-modal md-effect-16" id="modal-16">
    	<div class="md-content">
		<a class="md-close"><img src="<?php echo Yii::app()->baseUrl?>/upload/templates/azure/images/close-icon.png"></a>
            <?php echo CHtml::form(array('pl/authentication/sa/login'), 'post', array('id' => 'loginform', 'name' => 'loginform')); ?>
            <div class="signuptitle">
                <h4>Member <span>Login</span></h4>
            </div>
            <div class="loginpopup">
                <div class="inputlogin">
                    <input name="email" id="email" type="email" placeholder="Email Address">
                </div>
                <div class="inputlogin">
                    <input name="password" id="password" type="password" placeholder="Password">
                </div>
                <div class="remembercheck">
                    <input type="checkbox" name="check">
                    <label>Remember Password</label>
                </div>
                <div class="forgetpass">
                    <a href="<?php echo Yii::app()->createUrl('pl/authentication/sa/forgotpassword'); ?>">Forgot your password?</a>
                </div>
                <input type="submit" name="login_submit" value="Login">
            </div>

                <input type="hidden" value="Authdb" name="authMethod" id="authMethod">
                <input type="hidden" name="action" value="login">
            <?php echo CHtml::endForm(); ?>
		</div>
  </div>
<div class="md-overlay"></div>
			
        <?php if (isset($formatdata)) { ?>
            <script type='text/javascript'>
                var userdateformat='<?php echo $formatdata['jsdate']; ?>';
                var userlanguage='<?php echo $pllang; ?>';
            </script>
        <?php } ?>

        <?php $this->widget('ext.FlashMessage.FlashMessage'); ?>

        <div class="sb-slidebar sb-right">
            <ul class="sb-menu">
                {menu}
            </ul>
        </div>
        <div class="header sb-slide">
            <div class="container">
                <div class="logo"><a href="<?echo $baseurl; ?>"><img src="<?php echo $templatepath; ?>images/logo.png"></a></div>
                <div class="navigation">
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

                    <?php }else { ?>
                    
				
					<ul>
						<?php echo getMenuList();?>
						<li><a href="#" class="signup md-trigger" data-modal="modal-16">Sign in</a></li>
						<li>
						<a href="<?php echo Yii::app()->baseUrl;?>?pagename=JOIN NOW" class="join-now">Join Now</a>
						</li>
					</ul>
				
				<?php } ?>
                    
                </div>
                <div class="hamburger">
                    <div class="sb-toggle-right">
                        <div class="navicon-line"></div>
                        <div class="navicon-line"></div>
                        <div class="navicon-line"></div>
                    </div>
                </div>
                <div class="mobilelogin"><a href="#" class="signup md-trigger" data-modal="modal-16"><img src="<?php echo $templatepath; ?>images/sine-in-icn.png"></a></div>
            </div>
        </div>

        <div class="sb-slide" id="sb-site">
            <div class="innercontent">




