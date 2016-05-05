<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function templatereplace($line, $replacements = array(), &$redata = array(), $debugSrc = 'Unspecified', $anonymized = false, $questionNum = NULL, $registerdata = array(), $bStaticReplacement = false) {
    $allowedvars = array(
        'answer',
        'assessments',
        'captchapath',
        'clienttoken',
        'completed',
        'errormsg',
        'groupdescription',
        'groupname',
        'help',
        'imageurl',
        'languagechanger',
        'loadname',
        'move',
        'navigator',
        'percentcomplete',
        'privacy',
        'question',
        's_lang',
        'saved_id',
        'showgroupinfo',
        'showqnumcode',
        'showxquestions',
        'sitename',
        'surveylist',
        'templatedir',
        'thissurvey',
        'token',
        'totalBoilerplatequestions',
        'totalquestions',
    );

    $varsPassed = array();

    foreach ($allowedvars as $var) {
        if (isset($redata[$var])) {
            $$var = $redata[$var];
            $varsPassed[] = $var;
        }
    }
    if (!isset($showgroupinfo)) {
        $showgroupinfo = Yii::app()->getConfig('showgroupinfo');
    }
    if (!isset($showqnumcode)) {
        $showqnumcode = Yii::app()->getConfig('showqnumcode');
    }
    $_surveyid = Yii::app()->getConfig('surveyID');
    if (!isset($showxquestions)) {
        $showxquestions = Yii::app()->getConfig('showxquestions');
    }
    if (!isset($s_lang)) {
        $s_lang = (isset(Yii::app()->session['survey_' . $_surveyid]['s_lang']) ? Yii::app()->session['survey_' . $_surveyid]['s_lang'] : 'en');
    }
    if ($_surveyid && !isset($thissurvey)) {
        $thissurvey = getSurveyInfo($_surveyid, $s_lang);
    }
    if (!isset($captchapath)) {
        $captchapath = '';
    }
    if (!isset($sitename)) {
        $sitename = Yii::app()->getConfig('sitename');
    }
    if (!isset($saved_id) && isset(Yii::app()->session['survey_' . $_surveyid]['srid'])) {
        $saved_id = Yii::app()->session['survey_' . $_surveyid]['srid'];
    }
    $clang = Yii::app()->lang;

    Yii::app()->loadHelper('surveytranslator');

    if (isset($thissurvey['sid'])) {
        $surveyid = $thissurvey['sid'];
    }

    // lets sanitize the survey template
    if (isset($thissurvey['templatedir'])) {
        $templatename = $thissurvey['templatedir'];
    } else {
        $templatename = Yii::app()->getConfig('defaulttemplate');
    }
    if (!isset($templatedir))
        $templatedir = getTemplatePath($templatename);
    if (!isset($templateurl))
        $templateurl = getTemplateURL($templatename) . "/";
    if (!$anonymized && isset($thissurvey['anonymized'])) {
        $anonymized = ($thissurvey['anonymized'] == "Y");
    }
    // TEMPLATECSS
    $_templatecss = "";
    if (stripos($line, "{TEMPLATECSS}")) {
        if (file_exists($templatedir . DIRECTORY_SEPARATOR . 'jquery-ui-custom.css')) {
            Yii::app()->getClientScript()->registerCssFile("{$templateurl}jquery-ui-custom.css");
        } elseif (file_exists($templatedir . DIRECTORY_SEPARATOR . 'jquery-ui.css')) {
            Yii::app()->getClientScript()->registerCssFile("{$templateurl}jquery-ui.css");
        } else {
            Yii::app()->getClientScript()->registerCssFile(Yii::app()->getConfig('publicstyleurl') . "jquery-ui.css");
        }

        Yii::app()->getClientScript()->registerCssFile("{$templateurl}template.css");
        if (getLanguageRTL($clang->langcode)) {
            Yii::app()->getClientScript()->registerCssFile("{$templateurl}template-rtl.css");
        }
    }
    // surveyformat
    if (isset($thissurvey['format'])) {
        $surveyformat = str_replace(array("A", "S", "G"), array("allinone", "questionbyquestion", "groupbygroup"), $thissurvey['format']);
    } else {
        $surveyformat = "";
    }
    if ((isset(Yii::app()->session['step']) && Yii::app()->session['step'] % 2) && $surveyformat != "allinone") {
        $surveyformat .= " page-odd";
    }

    if (isset($thissurvey['questionindex']) && $thissurvey['questionindex'] > 0 && $surveyformat != "allinone" && (isset(Yii::app()->session['step']) && Yii::app()->session['step'] > 0)) {
        $surveyformat .= " withindex";
    }
    if (isset($thissurvey['showprogress']) && $thissurvey['showprogress'] == "Y") {
        $surveyformat .= " showprogress";
    }
    if (isset($thissurvey['showqnumcode'])) {
        $surveyformat .= " showqnumcode-" . $thissurvey['showqnumcode'];
    }
    // real survey contact
    if (isset($surveylist) && isset($surveylist['contact'])) {
        $surveycontact = $surveylist['contact'];
    } elseif (isset($surveylist) && isset($thissurvey['admin']) && $thissurvey['admin'] != "") {
        $surveycontact = sprintf($clang->gT("Please contact %s ( %s ) for further assistance."), $thissurvey['admin'], $thissurvey['adminemail']);
    } else {
        $surveycontact = "";
    }
    // If there are non-bracketed replacements to be made do so above this line.
    // Only continue in this routine if there are bracketed items to replace {}
    if (strpos($line, "{") === false) {
        // process string anyway so that it can be pretty-printed
        return LimeExpressionManager::ProcessString($line, $questionNum, NULL, false, 1, 1, true);
    }

    if (
            $showgroupinfo == 'both' ||
            $showgroupinfo == 'name' ||
            ($showgroupinfo == 'choose' && !isset($thissurvey['showgroupinfo'])) ||
            ($showgroupinfo == 'choose' && $thissurvey['showgroupinfo'] == 'B') ||
            ($showgroupinfo == 'choose' && $thissurvey['showgroupinfo'] == 'N')
    ) {
        $_groupname = isset($groupname) ? $groupname : '';
    } else {
        $_groupname = '';
    };
    if (
            $showgroupinfo == 'both' ||
            $showgroupinfo == 'description' ||
            ($showgroupinfo == 'choose' && !isset($thissurvey['showgroupinfo'])) ||
            ($showgroupinfo == 'choose' && $thissurvey['showgroupinfo'] == 'B') ||
            ($showgroupinfo == 'choose' && $thissurvey['showgroupinfo'] == 'D')
    ) {
        $_groupdescription = isset($groupdescription) ? $groupdescription : '';
    } else {
        $_groupdescription = '';
    };

    if (isset($question) && is_array($question)) {
        $_question = $question['all'];
        $_question_text = $question['text'];
        $_question_help = $question['help'];
        $_question_mandatory = $question['mandatory'];
        $_question_man_message = $question['man_message'];
        $_question_valid_message = $question['valid_message'];
        $_question_file_valid_message = $question['file_valid_message'];
        $question['sgq'] = (isset($question['sgq']) ? $question['sgq'] : '');
        $_question_essentials = $question['essentials'];
        $_getQuestionClass = $question['class'];
        $_question_man_class = $question['man_class'];
        $_question_input_error_class = $question['input_error_class'];
        $_question_number = $question['number'];
        $_question_code = $question['code'];
        $_question_type = $question['type'];
        if ($question['sgq']) // Not sure it can happen today ? But if set : allways sXgXq
            list($question['sid'], $question['gid'], $question['qid']) = explode("X", $question['sgq']);
        else
            list($question['sid'], $question['gid'], $question['qid']) = array('', '', '');
        $question['aid'] = (isset($question['aid']) ? $question['aid'] : '');
    }
    else {
        $_question = isset($question) ? $question : '';
        $_question_text = '';
        $_question_help = '';
        $_question_mandatory = '';
        $_question_man_message = '';
        $_question_valid_message = '';
        $_question_file_valid_message = '';
        $_question_essentials = '';
        $_getQuestionClass = '';
        $_question_man_class = '';
        $_question_input_error_class = '';
        $_question_number = '';
        $_question_code = '';
        $_question_type = '';
        $question = array_fill_keys(array('sid', 'gid', 'qid', 'aid', 'sgq'), '');
    };

    if ($_question_type == '*') {
        $_question_text = '<div class="em_equation">' . $_question_text . '</div>';
    }

    if (!(
            $showqnumcode == 'both' ||
            $showqnumcode == 'number' ||
            ($showqnumcode == 'choose' && !isset($thissurvey['showqnumcode'])) ||
            ($showqnumcode == 'choose' && $thissurvey['showqnumcode'] == 'B') ||
            ($showqnumcode == 'choose' && $thissurvey['showqnumcode'] == 'N')
            )) {
        $_question_number = '';
    };
    if (!(
            $showqnumcode == 'both' ||
            $showqnumcode == 'code' ||
            ($showqnumcode == 'choose' && !isset($thissurvey['showqnumcode'])) ||
            ($showqnumcode == 'choose' && $thissurvey['showqnumcode'] == 'B') ||
            ($showqnumcode == 'choose' && $thissurvey['showqnumcode'] == 'C')
            )) {
        $_question_code = '';
    }

    if (!isset($totalquestions))
        $totalquestions = 0;
    $_totalquestionsAsked = $totalquestions;
    if (
            $showxquestions == 'show' ||
            ($showxquestions == 'choose' && !isset($thissurvey['showxquestions'])) ||
            ($showxquestions == 'choose' && $thissurvey['showxquestions'] == 'Y')
    ) {
        if ($_totalquestionsAsked < 1) {
            $_therearexquestions = $clang->gT("There are no questions in this survey"); // Singular
        } elseif ($_totalquestionsAsked == 1) {
            $_therearexquestions = $clang->gT("There is 1 question in this survey"); //Singular
        } else {
            $_therearexquestions = $clang->gT("There are {NUMBEROFQUESTIONS} questions in this survey.");    //Note this line MUST be before {NUMBEROFQUESTIONS}
        };
    } else {
        $_therearexquestions = '';
    };


    if (isset($token)) {
        $_token = $token;
    } elseif (isset($clienttoken)) {
        $_token = htmlentities($clienttoken, ENT_QUOTES, 'UTF-8');  // or should it be URL-encoded?
    } else {
        $_token = '';
    }

    // Expiry
    if (isset($thissurvey['expiry'])) {
        $dateformatdetails = getDateFormatData($thissurvey['surveyls_dateformat']);
        Yii::import('application.libraries.Date_Time_Converter', true);
        $datetimeobj = new Date_Time_Converter($thissurvey['expiry'], "Y-m-d");
        $_dateoutput = $datetimeobj->convert($dateformatdetails['phpdate']);
    } else {
        $_dateoutput = '-';
    }

    $_submitbutton = "<input class='submit' type='submit' value=' " . $clang->gT("Submit") . " ' name='move2' onclick=\"javascript:document.limesurvey.move.value = 'movesubmit';\" />";

    if (isset($thissurvey['surveyls_url']) and $thissurvey['surveyls_url'] != "") {
        if (trim($thissurvey['surveyls_urldescription']) != '') {
            $_linkreplace = "<a href='{$thissurvey['surveyls_url']}'>{$thissurvey['surveyls_urldescription']}</a>";
        } else {
            $_linkreplace = "<a href='{$thissurvey['surveyls_url']}'>{$thissurvey['surveyls_url']}</a>";
        }
    } else {
        $_linkreplace = '';
    }

    if (isset($thissurvey['sid']) && isset($_SESSION['survey_' . $thissurvey['sid']]['srid']) && $thissurvey['active'] == 'Y') {
        $iscompleted = SurveyDynamic::model($surveyid)->isCompleted($_SESSION['survey_' . $thissurvey['sid']]['srid']);
    } else {
        $iscompleted = false;
    }
    if (isset($surveyid) && !$iscompleted) {
        $_clearall = CHtml::htmlButton($clang->gT("Exit and clear survey"), array('type' => 'submit', 'id' => "clearall", 'value' => 'clearall', 'name' => 'clearall', 'class' => 'clearall button', 'data-confirmedby' => 'confirm-clearall', 'title' => $clang->gT("This action need confirmation.")));
        $_clearall.=CHtml::checkBox("confirm-clearall", false, array('id' => 'confirm-clearall', 'value' => 'confirm', 'class' => 'hide jshide'));
        $_clearall.=CHtml::label($clang->gT("Are you sure you want to clear all your responses?"), 'confirm-clearall', array('class' => 'hide jshide'));
    } else {
        $_clearall = "";
    }

    if (isset(Yii::app()->session['datestamp'])) {
        $_datestamp = Yii::app()->session['datestamp'];
    } else {
        $_datestamp = '-';
    }
    if (isset($thissurvey['allowsave']) and $thissurvey['allowsave'] == "Y") {
        $_saveall = doHtmlSaveAll(isset($move) ? $move : NULL);
    } else {
        $_saveall = "";
    }

    if (!isset($help))
        $help = "";
    if (flattenText($help, true, true) != '') {
        if (!isset($helpicon)) {
            if (file_exists($templatedir . '/help.gif')) {
                $helpicon = $templateurl . 'help.gif';
            } elseif (file_exists($templatedir . '/help.png')) {
                $helpicon = $templateurl . 'help.png';
            } else {
                $helpicon = Yii::app()->getConfig('imageurl') . "/help.gif";
            }
        }
        $_questionhelp = "<img src='{$helpicon}' alt='Help' align='left' />" . $help;
    } else {
        $_questionhelp = $help;
    }

    if (isset($thissurvey['allowprev']) && $thissurvey['allowprev'] == "N") {
        $_strreview = "";
    } else {
        $_strreview = $clang->gT("If you want to check any of the answers you have made, and/or change them, you can do that now by clicking on the [<< prev] button and browsing through your responses.");
    }

    if (isset($surveyid)) {
        $restartparam = array();
        if ($_token)
            $restartparam['token'] = sanitize_token($_token); // urlencode with needed with sanitize_token
        if (Yii::app()->request->getQuery('lang'))
            $restartparam['lang'] = sanitize_languagecode(Yii::app()->request->getQuery('lang'));
        elseif ($s_lang)
            $restartparam['lang'] = $s_lang;
        $restartparam['newtest'] = "Y";
        $restarturl = Yii::app()->getController()->createUrl("survey/index/sid/$surveyid", $restartparam);
        $_restart = "<a href='{$restarturl}'>" . $clang->gT("Restart this Survey") . "</a>";
    }
    else {
        $_restart = "";
    }

    if (isset($thissurvey['anonymized']) && $thissurvey['anonymized'] == 'Y') {
        $_savealert = $clang->gT("To remain anonymous please use a pseudonym as your username, also an email address is not required.");
    } else {
        $_savealert = "";
    }

    if (isset($surveyid)) {
        if ($_token) {
            $returnlink = Yii::app()->getController()->createUrl("survey/index/sid/{$surveyid}", array('token' => sanitize_token($_token)));
        } else {
            $returnlink = Yii::app()->getController()->createUrl("survey/index/sid/{$surveyid}");
        }
        $_return_to_survey = "<a href='{$returnlink}'>" . $clang->gT("Return to survey") . "</a>";
    } else {
        $_return_to_survey = "";
    }

    // Save Form
    $_saveform = "<table><tr><td align='right'>" . $clang->gT("Name") . ":</td><td><input type='text' name='savename' value='";
    if (isset($_POST['savename'])) {
        $_saveform .= HTMLEscape(autoUnescape($_POST['savename']));
    }
    $_saveform .= "' /></td></tr>\n"
            . "<tr><td align='right'>" . $clang->gT("Password") . ":</td><td><input type='password' name='savepass' value='";
    if (isset($_POST['savepass'])) {
        $_saveform .= HTMLEscape(autoUnescape($_POST['savepass']));
    }
    $_saveform .= "' /></td></tr>\n"
            . "<tr><td align='right'>" . $clang->gT("Repeat password") . ":</td><td><input type='password' name='savepass2' value='";
    if (isset($_POST['savepass2'])) {
        $_saveform .= HTMLEscape(autoUnescape($_POST['savepass2']));
    }
    $_saveform .= "' /></td></tr>\n"
            . "<tr><td align='right'>" . $clang->gT("Your email address") . ":</td><td><input type='text' name='saveemail' value='";
    if (isset($_POST['saveemail'])) {
        $_saveform .= HTMLEscape(autoUnescape($_POST['saveemail']));
    }
    $_saveform .= "' /></td></tr>\n";
    if (isset($thissurvey['usecaptcha']) && function_exists("ImageCreate") && isCaptchaEnabled('saveandloadscreen', $thissurvey['usecaptcha'])) {
        $_saveform .="<tr><td align='right'>" . $clang->gT("Security question") . ":</td><td><table><tr><td valign='middle'><img src='" . Yii::app()->getController()->createUrl('/verification/image/sid/' . ((isset($surveyid)) ? $surveyid : '')) . "' alt6='' /></td><td valign='middle' style='text-align:left'><input type='text' size='5' maxlength='3' name='loadsecurity' value='' /></td></tr></table></td></tr>\n";
    }
    $_saveform .= "<tr><td align='right'></td><td></td></tr>\n"
            . "<tr><td></td><td><input type='submit'  id='savebutton' name='savesubmit' class='button' value='" . $clang->gT("Save Now") . "' /></td></tr>\n"
            . "</table>";

    // Load Form
    $_loadform = "<table><tr><td align='right'>" . $clang->gT("Saved name") . ":</td><td><input type='text' name='loadname' value='";
    if (isset($loadname)) {
        $_loadform .= HTMLEscape(autoUnescape($loadname));
    }
    $_loadform .= "' /></td></tr>\n"
            . "<tr><td align='right'>" . $clang->gT("Password") . ":</td><td><input type='password' name='loadpass' value='";
    if (isset($loadpass)) {
        $_loadform .= HTMLEscape(autoUnescape($loadpass));
    }
    $_loadform .= "' /></td></tr>\n";
    if (isset($thissurvey['usecaptcha']) && function_exists("ImageCreate") && isCaptchaEnabled('saveandloadscreen', $thissurvey['usecaptcha'])) {
        $_loadform .="<tr><td align='right'>" . $clang->gT("Security question") . ":</td><td><table><tr><td valign='middle'><img src='" . Yii::app()->getController()->createUrl('/verification/image/sid/' . ((isset($surveyid)) ? $surveyid : '')) . "' alt='' /></td><td valign='middle'><input type='text' size='5' maxlength='3' name='loadsecurity' value='' alt=''/></td></tr></table></td></tr>\n";
    }
    $_loadform .="<tr><td align='right'></td><td></td></tr>\n"
            . "<tr><td></td><td><input type='submit' id='loadbutton' class='button' value='" . $clang->gT("Load now") . "' /></td></tr></table>\n";

    // Registration Form
    if (isset($surveyid) || (isset($registerdata) && $debugSrc == 'register.php')) {
        if (isset($surveyid))
            $tokensid = $surveyid;
        else
            $tokensid = $registerdata['sid'];

        $_registerform = CHtml::form(array("/register/index/surveyid/{$tokensid}"), 'post');

        if (!isset($_REQUEST['lang'])) {
            $_reglang = Survey::model()->findByPk($tokensid)->language;
        } else {
            $_reglang = returnGlobal('lang');
        }

        $_registerform .= "\n<input type='hidden' name='lang' value='" . $_reglang . "' />\n";
        $_registerform .= "<input type='hidden' name='sid' value='$tokensid' id='sid' />\n";

        $_registerform.="<table class='register' summary='Registrationform'>\n"
                . "<tr><td align='right'>"
                . $clang->gT("First name") . ":</td>"
                . "<td align='left'><input class='text' type='text' name='register_firstname'";
        if (isset($_POST['register_firstname'])) {
            $_registerform .= " value='" . htmlentities(returnGlobal('register_firstname'), ENT_QUOTES, 'UTF-8') . "'";
        }
        $_registerform .= " /></td></tr>"
                . "<tr><td align='right'>" . $clang->gT("Last name") . ":</td>\n"
                . "<td align='left'><input class='text' type='text' name='register_lastname'";
        if (isset($_POST['register_lastname'])) {
            $_registerform .= " value='" . htmlentities(returnGlobal('register_lastname'), ENT_QUOTES, 'UTF-8') . "'";
        }
        $_registerform .= " /></td></tr>\n"
                . "<tr><td align='right'>" . $clang->gT("Email address") . ":</td>\n"
                . "<td align='left'><input class='text' type='text' name='register_email'";
        if (isset($_POST['register_email'])) {
            $_registerform .= " value='" . htmlentities(returnGlobal('register_email'), ENT_QUOTES, 'UTF-8') . "'";
        }
        $_registerform .= " /></td></tr>\n";
        foreach ($thissurvey['attributedescriptions'] as $field => $attribute) {
            if (empty($attribute['show_register']) || $attribute['show_register'] != 'Y')
                continue;

            $_registerform .= '
            <tr>
            <td align="right">' . $thissurvey['attributecaptions'][$field] . ($attribute['mandatory'] == 'Y' ? '*' : '') . ':</td>
            <td align="left"><input class="text" type="text" name="register_' . $field . '" /></td>
            </tr>';
        }
        if ((count($registerdata) > 1 || isset($thissurvey['usecaptcha'])) && function_exists("ImageCreate") && isCaptchaEnabled('registrationscreen', $thissurvey['usecaptcha'])) {
            $_registerform .="<tr><td align='right'>" . $clang->gT("Security Question") . ":</td><td><table><tr><td valign='middle'><img src='" . Yii::app()->getController()->createUrl('/verification/image/sid/' . $surveyid) . "' alt='' /></td><td valign='middle'><input type='text' size='5' maxlength='3' name='loadsecurity' value='' /></td></tr></table></td></tr>\n";
        }
        $_registerform .= "<tr><td></td><td><input id='registercontinue' class='submit button' type='submit' value='" . $clang->gT("Continue") . "' />"
                . "</td></tr>\n"
                . "</table>\n";

        if (count($registerdata) > 1 && $registerdata['sid'] != NULL && $debugSrc == 'register.php') {
            $_registerform .= "<input name='startdate' type ='hidden' value='" . $registerdata['startdate'] . "' />";
            $_registerform .= "<input name='enddate' type ='hidden' value='" . $registerdata['enddate'] . "' />";
        }


        $_registerform .= "</form>\n";
    } else {
        $_registerform = "";
    }

    // Assessments
    $assessmenthtml = "";
    if (isset($surveyid) && !is_null($surveyid) && function_exists('doAssessment')) {
        $assessmentdata = doAssessment($surveyid, true);
        $_assessment_current_total = $assessmentdata['total'];
        if (stripos($line, "{ASSESSMENTS}")) {
            $assessmenthtml = doAssessment($surveyid, false);
        }
    } else {
        $_assessment_current_total = '';
    }

    if (isset($thissurvey['googleanalyticsapikey']) && trim($thissurvey['googleanalyticsapikey']) != '') {
        $_googleAnalyticsAPIKey = trim($thissurvey['googleanalyticsapikey']);
    } else {
        $_googleAnalyticsAPIKey = trim(getGlobalSetting('googleanalyticsapikey'));
    }
    $_googleAnalyticsStyle = (isset($thissurvey['googleanalyticsstyle']) ? $thissurvey['googleanalyticsstyle'] : '0');
    $_googleAnalyticsJavaScript = '';

    if ($_googleAnalyticsStyle != '' && $_googleAnalyticsStyle != 0 && $_googleAnalyticsAPIKey != '') {
        switch ($_googleAnalyticsStyle) {
            case '1':
                // Default Google Tracking
                $_googleAnalyticsJavaScript = <<<EOD
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '$_googleAnalyticsAPIKey']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
EOD;
                break;
            case '2':
                // SurveyName-[SID]/[GSEQ]-GroupName - create custom GSEQ based upon page step
                $moveInfo = LimeExpressionManager::GetLastMoveResult();
                if (is_null($moveInfo)) {
                    $gseq = 'welcome';
                } else if ($moveInfo['finished']) {
                    $gseq = 'finished';
                } else if (isset($moveInfo['at_start']) && $moveInfo['at_start']) {
                    $gseq = 'welcome';
                } else if (is_null($_groupname)) {
                    $gseq = 'printanswers';
                } else {
                    $gseq = $moveInfo['gseq'] + 1;
                }
                $_trackURL = htmlspecialchars($thissurvey['name'] . '-[' . $surveyid . ']/[' . $gseq . ']-' . $_groupname);
                $_googleAnalyticsJavaScript = <<<EOD
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '$_googleAnalyticsAPIKey']);
  _gaq.push(['_trackPageview','$_trackURL']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
EOD;
                break;
        }
    }

    $_endtext = '';
    if (isset($thissurvey['surveyls_endtext']) && trim($thissurvey['surveyls_endtext']) != '') {
        $_endtext = $thissurvey['surveyls_endtext'];
    }
    if (isset($surveyid) && isset($_SESSION['survey_' . $surveyid]) && isset($_SESSION['survey_' . $surveyid]['register_errormsg'])) {
        $register_errormsg = $_SESSION['survey_' . $surveyid]['register_errormsg'];
    }


    // Set the array of replacement variables here - don't include curly braces
    $coreReplacements = array();
    $coreReplacements['ACTIVE'] = (isset($thissurvey['active']) && !($thissurvey['active'] != "Y"));
    $coreReplacements['AID'] = $question['aid'];
    $coreReplacements['ANSWER'] = isset($answer) ? $answer : '';  // global
    $coreReplacements['ANSWERSCLEARED'] = $clang->gT("Answers cleared");
    $coreReplacements['ASSESSMENTS'] = $assessmenthtml;
    $coreReplacements['ASSESSMENT_CURRENT_TOTAL'] = $_assessment_current_total;
    $coreReplacements['ASSESSMENT_HEADING'] = $clang->gT("Your assessment");
    $coreReplacements['CHECKJAVASCRIPT'] = "<noscript><span class='warningjs'>" . $clang->gT("Caution: JavaScript execution is disabled in your browser. You may not be able to answer all questions in this survey. Please, verify your browser parameters.") . "</span></noscript>";
    $coreReplacements['CLEARALL'] = $_clearall;
    $coreReplacements['CLOSEWINDOW'] = "<a href='javascript:%20self.close()'>" . $clang->gT("Close this window") . "</a>";
    $coreReplacements['COMPLETED'] = isset($redata['completed']) ? $redata['completed'] : '';    // global
    $coreReplacements['DATESTAMP'] = $_datestamp;
    $coreReplacements['ENDTEXT'] = $_endtext;
    $coreReplacements['EXPIRY'] = $_dateoutput;
    $coreReplacements['GID'] = ($question['gid']) ? $question['gid'] : Yii::app()->getConfig('gid', ''); // Use the gid of the question, except if we are not in question (Randomization group name)
    $coreReplacements['GOOGLE_ANALYTICS_API_KEY'] = $_googleAnalyticsAPIKey;
    $coreReplacements['GOOGLE_ANALYTICS_JAVASCRIPT'] = $_googleAnalyticsJavaScript;
    $coreReplacements['GROUPDESCRIPTION'] = $_groupdescription;
    $coreReplacements['GROUPNAME'] = $_groupname;
    $coreReplacements['LANG'] = $clang->getlangcode();
    $coreReplacements['LANGUAGECHANGER'] = isset($languagechanger) ? $languagechanger : '';    // global
    $coreReplacements['LOADERROR'] = isset($errormsg) ? $errormsg : ''; // global
    $coreReplacements['LOADFORM'] = $_loadform;
    $coreReplacements['LOADHEADING'] = $clang->gT("Load a previously saved survey");
    $coreReplacements['LOADMESSAGE'] = $clang->gT("You can load a survey that you have previously saved from this screen.") . "<br />" . $clang->gT("Type in the 'name' you used to save the survey, and the password.") . "<br />";
    $coreReplacements['NAVIGATOR'] = isset($navigator) ? $navigator : '';    // global
    $coreReplacements['NOSURVEYID'] = (isset($surveylist)) ? $surveylist['nosid'] : '';
    $coreReplacements['NUMBEROFQUESTIONS'] = $_totalquestionsAsked;
    $coreReplacements['PERCENTCOMPLETE'] = isset($percentcomplete) ? $percentcomplete : '';    // global
    $coreReplacements['PRIVACY'] = isset($privacy) ? $privacy : '';    // global
    $coreReplacements['PRIVACYMESSAGE'] = "<span style='font-weight:bold; font-style: italic;'>" . $clang->gT("A Note On Privacy") . "</span><br />" . $clang->gT("This survey is anonymous.") . "<br />" . $clang->gT("The record of your survey responses does not contain any identifying information about you, unless a specific survey question explicitly asked for it.") . ' ' . $clang->gT("If you used an identifying token to access this survey, please rest assured that this token will not be stored together with your responses. It is managed in a separate database and will only be updated to indicate whether you did (or did not) complete this survey. There is no way of matching identification tokens with survey responses.");
    $coreReplacements['QID'] = $question['qid'];
    $coreReplacements['QUESTION'] = $_question;
    $coreReplacements['QUESTIONHELP'] = $_questionhelp;
    $coreReplacements['QUESTIONHELPPLAINTEXT'] = strip_tags(addslashes($help)); // global
    $coreReplacements['QUESTION_CLASS'] = $_getQuestionClass;
    $coreReplacements['QUESTION_CODE'] = $_question_code;
    $coreReplacements['QUESTION_ESSENTIALS'] = $_question_essentials;
    $coreReplacements['QUESTION_FILE_VALID_MESSAGE'] = $_question_file_valid_message;
    $coreReplacements['QUESTION_HELP'] = $_question_help;
    $coreReplacements['QUESTION_INPUT_ERROR_CLASS'] = $_question_input_error_class;
    $coreReplacements['QUESTION_MANDATORY'] = $_question_mandatory;
    $coreReplacements['QUESTION_MAN_CLASS'] = $_question_man_class;
    $coreReplacements['QUESTION_MAN_MESSAGE'] = $_question_man_message;
    $coreReplacements['QUESTION_NUMBER'] = $_question_number;
    $coreReplacements['QUESTION_TEXT'] = $_question_text;
    $coreReplacements['QUESTION_VALID_MESSAGE'] = $_question_valid_message;
    $coreReplacements['REGISTERERROR'] = isset($register_errormsg) ? $register_errormsg : '';    // global
    $coreReplacements['REGISTERFORM'] = $_registerform;
    $coreReplacements['REGISTERMESSAGE1'] = $clang->gT("You must be registered to complete this survey");
    $coreReplacements['REGISTERMESSAGE2'] = $clang->gT("You may register for this survey if you wish to take part.") . "<br />\n" . $clang->gT("Enter your details below, and an email containing the link to participate in this survey will be sent immediately.");
    $coreReplacements['RESTART'] = $_restart;
    $coreReplacements['RETURNTOSURVEY'] = $_return_to_survey;
    $coreReplacements['SAVE'] = $_saveall;
    $coreReplacements['SAVEALERT'] = $_savealert;
    $coreReplacements['SAVEDID'] = isset($saved_id) ? $saved_id : '';   // global
    $coreReplacements['SAVEERROR'] = isset($errormsg) ? $errormsg : ''; // global - same as LOADERROR
    $coreReplacements['SAVEFORM'] = $_saveform;
    $coreReplacements['SAVEHEADING'] = $clang->gT("Save your unfinished survey");
    $coreReplacements['SAVEMESSAGE'] = $clang->gT("Enter a name and password for this survey and click save below.") . "<br />\n" . $clang->gT("Your survey will be saved using that name and password, and can be completed later by logging in with the same name and password.") . "<br /><br />\n" . $clang->gT("If you give an email address, an email containing the details will be sent to you.") . "<br /><br />\n" . $clang->gT("After having clicked the save button you can either close this browser window or continue filling out the survey.");
    $coreReplacements['SGQ'] = $question['sgq'];
    $coreReplacements['SID'] = Yii::app()->getConfig('surveyID', ''); // Allways use surveyID from config
    $coreReplacements['SITENAME'] = isset($sitename) ? $sitename : '';  // global
    $coreReplacements['SUBMITBUTTON'] = $_submitbutton;
    $coreReplacements['SUBMITCOMPLETE'] = "<strong>" . $clang->gT("Thank you!") . "<br /><br />" . $clang->gT("You have completed answering the questions in this survey.") . "</strong><br /><br />" . $clang->gT("Click on 'Submit' now to complete the process and save your answers.");
    $coreReplacements['SUBMITREVIEW'] = $_strreview;
    $coreReplacements['SURVEYCONTACT'] = $surveycontact;
    $coreReplacements['SURVEYDESCRIPTION'] = (isset($thissurvey['description']) ? $thissurvey['description'] : '');
    $coreReplacements['SURVEYFORMAT'] = isset($surveyformat) ? $surveyformat : '';  // global
    $coreReplacements['SURVEYLANGAGE'] = $clang->langcode;
    $coreReplacements['SURVEYLANGUAGE'] = $clang->langcode;
    $coreReplacements['SURVEYLIST'] = (isset($surveylist)) ? $surveylist['list'] : '';
    $coreReplacements['SURVEYLISTHEADING'] = (isset($surveylist)) ? $surveylist['listheading'] : '';
    $coreReplacements['SURVEYNAME'] = (isset($thissurvey['name']) ? $thissurvey['name'] : '');
    $coreReplacements['TEMPLATECSS'] = $_templatecss;
    $coreReplacements['TEMPLATEJS'] = CHtml::tag('script', array('type' => 'text/javascript', 'src' => $templateurl . 'template.js'), '');
    $coreReplacements['TEMPLATEURL'] = $templateurl;
    $coreReplacements['THEREAREXQUESTIONS'] = $_therearexquestions;
    $coreReplacements['TOKEN'] = (!$anonymized ? $_token : ''); // Silently replace TOKEN by empty string
    $coreReplacements['URL'] = $_linkreplace;
    $coreReplacements['WELCOME'] = (isset($thissurvey['welcome']) ? $thissurvey['welcome'] : '');
    $coreReplacements['PANEL_LIST_ADD_FORM'] = getPanelListAddForm();
    $coreReplacements['menu'] = getMenuList();
    $coreReplacements['content'] = (isset($_REQUEST['pagename']) ? getPageContent($_REQUEST['pagename']) : getPageContent('home'));
    $coreReplacements['php'] = getphpcode();

    if (!is_null($replacements) && is_array($replacements)) {
        $doTheseReplacements = array_merge($coreReplacements, $replacements);   // so $replacements overrides core values
    } else {
        $doTheseReplacements = $coreReplacements;
    }

    // Now do all of the replacements - In rare cases, need to do 3 deep recursion, that that is default
    $line = LimeExpressionManager::ProcessString($line, $questionNum, $doTheseReplacements, false, 3, 1, false, true, $bStaticReplacement);

    return $line;
}

//added by tarak on 18/03/2014
function getPanelListAddForm() {
    $test = '<script>
        function Validationnew(){
            var email = $("#email_address").val()
            var reemail = $("#remail_address").val()
            if(email != reemail){
                alert("Email address not match". test);
                return false;
            }else{
                return true;
            }
        }
        </script>';

    $html = $test . CHtml::form(array("pl/registration/sa/save"), 'post', array('id' => 'usergroupform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')) .
            '<table style="width: 100%; margin: 0px auto;color: black;">
        <tr>
            <td style="text-align: left;padding-left: 4%;">Email Address</td>
        </tr>
        <tr>
            <td>
                <input type="email" placeholder="Email Address" name="email_address" id="email_address" required="required" />
            </td>
        </tr>
        <tr>
            <td style="text-align: left;padding-left: 4%;">ReEmail Address</td>
        </tr>
        <tr>
            <td>
                <input type="email" placeholder="Re-Enter Email Address" name="remail_address" id="remail_address" required="required" />
            </td>
        </tr>
        <tr>
            <td style="text-align: left;padding-left: 4%;">Password</td>
        </tr>
        <tr>
            <td>
                <input type="password" placeholder="Password"  name="pwd" required="required" />
            </td>
        </tr>
        <tr>
            <td style="text-align: left;padding-left: 4%;">First Name</td>
        </tr>
        <tr>
            <td>
                <input type="text" placeholder="First Name"  name="fname" required="required" />
            </td>
        </tr>
        <tr>
            <td style="text-align: left;padding-left: 4%;">Last Name</td>
        </tr>
        <tr>
            <td>
                <input type="text" placeholder="Last Name"  name="lname" required="required" />
            </td>
        </tr>';
    $style = 'style="text-align: left;padding-left: 4%;"';
    $html .= Question(get_question_categoryid('Registration'), $style);
    $html .= '<tr>
            <td>&nbsp</td>
            </tr>
            <tr>
            <td>
                <input type="checkbox" required="required" />
                I agree to the <a target = "_blank" href="index.php?pagename=Term" title="Term" style="background:rgba(0, 0, 0, 0.28)">Terms & Conditions</a> and <a target = "_blank" href="index.php?pagename=Privacy policy" title="Privacy policy" style="background:rgba(0, 0, 0, 0.28)">Privacy policy</a>
            </td>
        </tr>';
    $html .= '</table>';
    //include 'pljs.php';
    $html .= '<input id="signup" name="signup" type="submit" class="cta-btn" value="Sign Up" />';
    return $html;
}

//added by Gaurang on 03/04/2014
function getMenuList() {
    $sql = "SELECT * FROM {{cms_page_master}} WHERE IsActive = 1 AND showinmenu=1";
    $result = Yii::app()->db->createCommand($sql)->query()->readAll();
    $data = CHtml::listData($result, 'page_id', 'page_name');
    $html = "";
    foreach ($data as $key => $value) {
        $html .= '<a class="nav-btn" href="' . Yii::app()->getBaseUrl(true) . '/index.php?pagename=' . $value . '" title="' . $value . '">' . $value . '</a>';
    }
    return $html;
}

function getphpcode() {
    $html = '<?php
$this->widget("application.extensions.Brain.BrainPopupContentWidget", array(
    "popup_box_id" => "popup_box",
    "popup_link_id" => "popup_link",
    "popup_on_load" => "false",
    "popup_title" => "These is the popup title...",
    "height" => "300px;",
    "width" => "600px;",
));
?>';
//    ob_start();
//    eval('' . $html);
//    $output = ob_get_contents();
//    ob_end_clean();
//    return $output;
    return $html;
}

function getPageContent($pagename) {
    $LaN = Yii::app()->lang->langcode;
    $sql = "SELECT page_content FROM {{cms_page_master}} pm LEFT JOIN {{cms_page_content}} pc ON pc.page_id = pm.page_id
            WHERE page_name = '$pagename' AND pc.language_code = '$LaN'";
    $result = Yii::app()->db->createCommand($sql)->queryRow();
    $html = '';
    return $result["page_content"];
}

//end getPanelListAddForm
// This function replaces field names in a text with the related values
// (e.g. for email and template functions)
function ReplaceFields($text, $fieldsarray, $bReplaceInsertans=true, $staticReplace=true) {

    if ($bReplaceInsertans) {
        $replacements = array();
        foreach ($fieldsarray as $key => $value) {
            $replacements[substr($key, 1, -1)] = $value;
        }
        $text = LimeExpressionManager::ProcessString($text, NULL, $replacements, false, 2, 1, false, false, $staticReplace);
    } else {
        foreach ($fieldsarray as $key => $value) {
            $text = str_replace($key, $value, $text);
        }
    }
    return $text;
}

/**
 * passthruReplace() takes a string and looks for {PASSTHRU:myarg} variables
 *  which it then substitutes for parameter data sent in the initial URL and stored
 *  in the session array containing responses
 *
 * @param mixed $line   string - the string to iterate, and then return
 * @param mixed $thissurvey     string - the string containing the surveyinformation
 * @return string This string is returned containing the substituted responses
 *
 */
function PassthruReplace($line, $thissurvey) {
    while (strpos($line, "{PASSTHRU:") !== false) {
        $p1 = strpos($line, "{PASSTHRU:"); // startposition
        $p2 = $p1 + 10; // position of the first arg char
        $p3 = strpos($line, "}", $p1); // position of the last arg char

        $cmd = substr($line, $p1, $p3 - $p1 + 1); // extract the complete passthru like "{PASSTHRU:myarg}"
        $arg = substr($line, $p2, $p3 - $p2); // extract the arg to passthru (like "myarg")
// lookup for the fitting arg
        $sValue = '';
        if (isset($_SESSION['survey_' . $thissurvey['sid']]['urlparams'][$arg])) {
            $sValue = urlencode($_SESSION['survey_' . $thissurvey['sid']]['urlparams'][$arg]);
        }
        $line = str_replace($cmd, $sValue, $line); // replace
    }

    return $line;
}

/**
 * doHtmlSaveAll return HTML part of saveall button in survey
 * */
function doHtmlSaveAll($move="") {
    $surveyid = Yii::app()->getConfig('surveyID');
    $thissurvey = getsurveyinfo($surveyid);
    $clang = Yii::app()->lang;
    $aHtmlOptionsLoadall = array('type' => 'submit', 'id' => 'loadallbtn', 'value' => 'loadall', 'name' => 'loadall', 'class' => "saveall submit button");
    $aHtmlOptionsSaveall = array('type' => 'submit', 'id' => 'saveallbtn', 'value' => 'saveall', 'name' => 'saveall', 'class' => "saveall submit button");
    if ($thissurvey['active'] != "Y") {
        $aHtmlOptionsLoadall['disabled'] = 'disabled';
        $aHtmlOptionsSaveall['disabled'] = 'disabled';
    }
    $_saveall = "";
// Find out if the user has any saved data
    if ($thissurvey['format'] == 'A') {
        if ($thissurvey['tokenanswerspersistence'] != 'Y' || !isset($surveyid) || !tableExists('tokens_' . $surveyid)) {
            $_saveall .= CHtml::htmlButton($clang->gT("Load unfinished survey"), $aHtmlOptionsLoadall);
        }
        $_saveall .= CHtml::htmlButton($clang->gT("Resume later"), $aHtmlOptionsSaveall);
    } elseif ($surveyid && (!isset($_SESSION['survey_' . $surveyid]['step']) || !$_SESSION['survey_' . $surveyid]['step'])) {//First page, show LOAD (but not save)
        if ($thissurvey['tokenanswerspersistence'] != 'Y' || !isset($surveyid) || !tableExists('tokens_' . $surveyid)) {
            $_saveall .= CHtml::htmlButton($clang->gT("Load unfinished survey"), $aHtmlOptionsLoadall);
        }
    } elseif ($surveyid && (isset($_SESSION['survey_' . $surveyid]['maxstep']) && $_SESSION['survey_' . $surveyid]['maxstep'] == 1) && $thissurvey['showwelcome'] == "N") {//First page, show LOAD and SAVE  //First page, show LOAD
        if ($thissurvey['tokenanswerspersistence'] != 'Y' || !isset($surveyid) || !tableExists('tokens_' . $surveyid)) {
            $_saveall .= CHtml::htmlButton($clang->gT("Load unfinished survey"), $aHtmlOptionsLoadall);
        }
        $_saveall .= CHtml::htmlButton($clang->gT("Resume later"), $aHtmlOptionsSaveall);
    } elseif (!isset($_SESSION['survey_' . $surveyid]['scid']) || $move == "movelast") { // Not on last page or submited survey
        $_saveall .= CHtml::htmlButton($clang->gT("Resume later"), $aHtmlOptionsSaveall);
    }
    return $_saveall;
}

// Closing PHP tag intentionally omitted - yes, it is okay
?>
