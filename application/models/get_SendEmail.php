<?php

class get_SendEmail extends LSActiveRecord {

    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{template_emails}}';
    }

    public function viewName() {
        return '{{view_email_template}}';
    }

    private function GetToEmailId($Email_Point, $to) {
        $eid = '';
        switch ($Email_Point) {
            case EMAIL_POINT_Admin_Edit_Password:
                $eid = '';
                break;

            case EMAIL_POINT_InquiryDetail:
                $eid = '';
                break;

            case EMAIL_POINT_QueryPullSend:
                $eid = '';
                break;

            case EMAIL_POINT_PL_Registration:
            case EMAIL_POINT_PL_ForgotPassword:
            case EMAIL_POINT_PL_EditEmail:
            case EMAIL_POINT_PL_EditPassword:
            case EMAIL_POINT_PL_RegistrationReSend:
            case EMAIL_POINT_PL_RewardRequest:
            case EMAIL_POINT_PL_RewardIssued:
                $eid = '';
                break;

            default:
                $eid = Yii::app()->getConfig("siteadminemail");
                break;
        }
    }

    private function GetFromEmailID() {
        //echo $test = Yii::app()->getConfig("siteadminname") . " <" . Yii::app()->getConfig("siteadminemail") . ">";
        return Yii::app()->getConfig("siteadminemail");
    }

    function SendEmailByTemplate($ToEmailID, $Email_Point, $ID = 0, array $parm=null) {
        //$ar = Get_template::model()->getEmailContent($Email_Point, $panel_listID);
        $ar = $this->getEmailContent($Email_Point, $ID);
        //echo '<pre>' . print_r($ar).'</pre>';
        //$to = $this->GetToEmailID($Email_Point, $ToEmailID);
        $to = $ToEmailID;
        $from = $this->GetFromEmailID();
        //$subject = $ar['SUBJECT'];//26/06/2014 Remove By Hari
        $subject = $body = $this->ReplaceBodyVariables($ar['SUBJECT'], $Email_Point, $ID, $parm);//26/06/2014 Add By Hari
        $whitelist = array(
            '127.0.0.1',
            '::1'
        );

        if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            $body = $this->ReplaceBodyVariables($ar['BODY'], $Email_Point, $ID, $parm);
        } else {
            return $body = $this->ReplaceBodyVariables($ar['BODY'], $Email_Point, $ID, $parm);
        }
        //exit('body-' . $body . '<br>subject-' . $subject . '<br>to-' . $to . '<br>from-' . $from . '<br>sitename-' . Yii::app()->getConfig("sitename") . '<br>true-' . true . '<br>siteadminbounce-' . Yii::app()->getConfig("siteadminbounce"));
        $sent = SendEmailMessage($body, $subject, $to, $from, Yii::app()->getConfig("sitename"), true, Yii::app()->getConfig("siteadminbounce"));
        return $sent;
    }

    private function ReplaceBodyVariables($content, $Email_Point, $ID, $parm) {
        $newPath = "application.views.admin.get";
        $newPath = YiiBase::getPathOfAlias($newPath);
        $xml = simplexml_load_file($newPath . '/emailparameter.xml') or die("Error: Cannot create object");
        switch ($Email_Point) {
            case EMAIL_POINT_Admin_Edit_Password:
                $content = '';
                break;

            case EMAIL_POINT_InquiryDetail:
                $content = '';
                break;

            case EMAIL_POINT_QueryPullSend:
                $sql = "SELECT pp.panellist_id,pp.project_id,pp.project_url,pp.points,pm.project_name,pm.friendly_name,
                        pm.expected_los,plm.full_name
                        FROM {{panellist_project}} pp
                        LEFT JOIN {{project_master}} pm ON pm.project_id = pp.project_id
                        LEFT JOIN {{view_panel_list_master}} plm ON plm.panel_list_id = pp.panellist_id
                        WHERE pp.panellist_id = '$ID' AND pp.project_id = '" . $parm['project_id'] . "'";
                $result = Yii::app()->db->createCommand($sql)->queryRow();
                $test = '';
                foreach ($xml->children() as $root) {
                    foreach ($root->children() as $usein => $data) {
                        if ($data->id == $Email_Point) {
                            $test = trim($data->para, '[[]]');
                            if (array_key_exists($test, $result)) {
                                $content = str_replace("[[$test]]", $result[$test], $content);
                            }
                        }
                    }
                }
                if ($result['friendly_name'] != '') {
                    $survey_name = $parm['project_id'] . ' - ' . $result['friendly_name'];
                } else {
                    $survey_name = $parm['project_id'] . ' - Survey';
                }
                $content = str_replace('[[SURVEY_NAME]]', $survey_name, $content);
                break;

            case EMAIL_POINT_PL_Registration:case EMAIL_POINT_PL_RegistrationReSend:
                $sql = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '$ID'";
                $result = Yii::app()->db->createCommand($sql)->queryRow();
                $test = '';
                foreach ($xml->children() as $root) {
                    foreach ($root->children() as $usein => $data) {
                        if ($data->id == $Email_Point) {
                            $test = trim($data->para, '[[]]');
                            if (array_key_exists($test, $result)) {
                                $content = str_replace("[[$test]]", $result[$test], $content);
                            }
                        }
                    }
                }
                $pwd = $parm['pwd'];
                $content = str_replace('[[PASSWORD]]', $pwd, $content);
                $content = str_replace('[[ACTIVATE_ACCOUNT_LINK]]', $parm['activation_link'], $content);
                break;
            case EMAIL_POINT_PL_ForgotPassword:
                $sql = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '$ID'";
                $result = Yii::app()->db->createCommand($sql)->queryRow();
                $test = '';
                foreach ($xml->children() as $root) {
                    foreach ($root->children() as $usein => $data) {
                        if ($data->id == $Email_Point) {
                            $test = trim($data->para, '[[]]');
                            if (array_key_exists($test, $result)) {
                                $content = str_replace("[[$test]]", $result[$test], $content);
                            }
                        }
                    }
                }
                $content = str_replace('[[ACTIVATE_PASSWORD_LINK]]', $parm['activation_link'], $content);
                break;
            case EMAIL_POINT_PL_EditEmail:
                $sql = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '$ID'";
                $result = Yii::app()->db->createCommand($sql)->queryRow();
                $test = '';
                foreach ($xml->children() as $root) {
                    foreach ($root->children() as $usein => $data) {
                        if ($data->id == $Email_Point) {
                            $test = trim($data->para, '[[]]');
                            if (array_key_exists($test, $result)) {
                                $content = str_replace("[[$test]]", $result[$test], $content);
                            }
                        }
                    }
                }
                $content = str_replace('[[ACTIVATE_EMAIL_LINK]]', $parm['activate_email_link'], $content);
                break;
            case EMAIL_POINT_PL_EditPassword:
                $sql = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '$ID'";
                $result = Yii::app()->db->createCommand($sql)->queryRow();
                $test = '';
                foreach ($xml->children() as $root) {
                    foreach ($root->children() as $usein => $data) {
                        if ($data->id == $Email_Point) {
                            $test = trim($data->para, '[[]]');
                            if (array_key_exists($test, $result)) {
                                $content = str_replace("[[$test]]", $result[$test], $content);
                            }
                        }
                    }
                }
                $pwd = $parm['pwd'];
                $content = str_replace('[[PASSWORD]]', $pwd, $content);
                $content = str_replace('[[CONFIRM_PASSWORD_LINK]]', $parm['password_link'], $content);
                break;
            case EMAIL_POINT_PL_RewardRequest:
                $content = '';
                break;
            case EMAIL_POINT_PL_RewardIssued:
                $content = '';
                break;

            default:
                $content = Yii::app()->getConfig("siteadminemail");
                break;
        }
        $content = str_replace('&#39', "'", $content);
        return $content;
    }

    public function getEmailContent($UseIn, $panelistID = 0) {
        $message_body = "";
        $subject = "";
        //if language and country are 0 then send in default language.
        //If country id not equal to 0 then
        $sql = "SELECT * FROM " . $this->viewName() . " WHERE use_in = $UseIn and isactive = 1 ";
		
        $cmd = Yii::app()->db->createCommand($sql);
        $cnt = $cmd->queryScalar();
        $rows = $cmd->queryAll();
        $dr = $cmd->query();

        if ($cnt > 0) {
            while (($r = $dr->read()) !== false) {
                //while ($r = mysql_fetch_array($dataReader)) {
                $message_body = $r['content_text'];
                $subject = $r['subject_text'];
                $email_bodyid = $r['email_bodyid'];
                $email_subjectid = $r['email_subjectid'];
            }
            $panelistID = (INT) $panelistID;
            $preferLanguageCode = Yii::app()->lang->langcode;

            //Get panel list communication language
            if ($panelistID > 0) {
                //$sql="SELECT id as question_id,short_title FROM {{profile_question}} WHERE short_title LIKE 'Prefered-Language'";
                $dr = questions::model()->communication_language()->findAll();
                if (count($dr) > 0) {
                    $question_id = (int) $dr[0]['id'];
                    //$question_id = (int) 1;
                } else {
                    $question_id = 0;
                }

                if ($question_id > 0) {
                    $columnname = "question_id_$question_id";
                    $qus_language_answer = Yii::app()->db->createCommand("select $columnname from {{panellist_answer}} WHERE panellist_id = $panelistID")->queryRow();
                    if (count($qus_language_answer) > 0) {
                        $preferLanguageCode = $qus_language_answer[$columnname];
                    } else {
                        $preferLanguageCode = Yii::app()->lang->langcode;
                    }
                }
            } else {
                //if there is no panel list then use the default language
                $preferLanguageCode = Yii::app()->lang->langcode;
            }

            if ($preferLanguageCode != "") {
                //$EmailLanguageCode = $preferLanguageCode;
                //$tempEmailLanguageCode = $preferLanguageCode;

                $sql = "SELECT * FROM {{translation_email_body}} WHERE language_code_dest = '$preferLanguageCode'
                    AND email_bodyid = $email_bodyid";
                $qur_translate = Yii::app()->db->createCommand($sql)->queryRow();
                if (count($qur_translate) > 0) {
                    $message_body = $qur_translate['translated_body'];
                } else {
                    //$message_body = 'Email body translation not found for language code = $preferLanguageCode, Please inform your webmaster';
                    $message_body = '';
                }

                $sql = "SELECT * FROM {{translation_email_subjects}} WHERE language_code_dest = '$preferLanguageCode'
                    AND email_subjectid = $email_subjectid";
                $qur_translate = Yii::app()->db->createCommand($sql)->queryRow();
                if (count($qur_translate) > 0) {
                    $subject = $qur_translate['translated_subject'];
                } else {
                    //$subject = 'Email subject translation not found for language code = $preferLanguageCode, Please inform your webmaster';
                    $subject = '';
                }
            }

            if (strlen($message_body) == 0) {
                throw new Exception("There is some problem regarding to get the email body");
            }
            if (strlen($subject) == 0) {
                throw new Exception("There is some problem regarding to get the email subject");
            }
        } else {
            throw new Exception("No Email Template found for these email point.");
        }

        return array("SUBJECT" => $subject, "BODY" => $message_body);
    }

}
