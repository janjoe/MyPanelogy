<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class get_action extends Survey_Common_Action {

    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = getPagelist();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('get', 'view_listtmplt', $aData);
    }

// =========================== Email Subject BOF ===========================

    function list_subs() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $aData['row'] = 0;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        if (isset($_POST['subject_language'])) {
            $lanemailsub = $_POST['subject_language'];
        } else {
            $lanemailsub = 'en';
        }
        $userlist = getEmailSubject($lanemailsub);
        $aData['usr_arr'] = $userlist;
        $aData['lanemail'] = $lanemailsub;
        Yii::app()->request->cookies['Language-Email-Subject'] = new CHttpCookie('Language-Email-Subject', $lanemailsub);
        $this->_renderWrappedTemplate('get', 'view_addlistsubs', $aData);
    }

    function selectsubjectcontent() {
        $language_code = $_POST['language_code'];
        $subject_id = (int) $_POST['subject_id'];
        $sresult = getEmailSubject($language_code, $subject_id);

        echo '<input type="text" maxlength="500" id="translate_subject_text" name="translate_subject_text" required="required" value="' . $sresult[0]['translated_subject'] . '" />';
    }

    function ins_subs() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('emailTemp', 'create')) {

            if ($action == "addsubject") {
                $subject_text = flattenText($_POST['subject_text'], false, true, 'UTF-8', true);
                $email_sub_language = flattenText($_POST['email_sub_language'], false, true, 'UTF-8', true);
                $current_date = date('y-m-d h:i:s');
                if (empty($subject_text)) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Email Subject"), 'message' => $clang->gT("A subject was not supplied or the subject is invalid."), 'class' => 'warningheader');
                } elseif (Get_subject::model()->find("subject_text=:subject_text", array(':subject_text' => $subject_text))) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Email Subject"), 'message' => $clang->gT("The Email subject already exists."), 'class' => 'warningheader');
                } else {
                    $NewSubject = Get_subject::model()->insertSubect($subject_text, $current_date);
                    if ($NewSubject) {
                        // here go translation insert
                        $sql = "INSERT INTO {{translation_email_subjects}}
                        (email_subjectid,language_code_dest,translated_subject,created_datetime)
                        VALUES('$NewSubject','$email_sub_language','" . str_replace("'", "&#39", $subject_text) . "','$current_date')";
                        $result = Yii::app()->db->createCommand($sql)->query();
                        Yii::app()->setFlashMessage($clang->gT("Email Subject added successfully"));
                        $this->getController()->redirect(array("admin/get/sa/list_subs"));
                    }
                }
            }
        } else {
            $aViewUrls = 'view_addlistsubs';
        }

        $this->_renderWrappedTemplate('get', $aViewUrls, $aData);
    }

    //end ins_subs

    function edit_subs() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'update')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        if (isset($_POST['email_subjectid']) && isset($_POST['language_code_dest'])) {
            $email_subjectid = (int) Yii::app()->request->getPost("email_subjectid");
            $language_code_dest = flattenText($_POST['language_code_dest'], false, true, 'UTF-8', true);
            if ($action == 'modsubject') {
                $current_date = date('y-m-d h:i:s');
                $subject_language = flattenText($_POST['subject_language'], false, true, 'UTF-8', true);
                $translate_subject_text = flattenText($_POST['translate_subject_text'], false, true, 'UTF-8', true);
                $subject_text = flattenText($_POST['subject_text'], false, true, 'UTF-8', true);
                $Isactive = flattenText($_POST['IsActive'], false, true, 'UTF-8', true);
                $Is_active = 0;
                if ($Isactive) {
                    $Is_active = 1;
                }

                if ($translate_subject_text == '' || $subject_text == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to edit Email Subject"), 'message' => $clang->gT("A email subject or translated text was not supplied or the email subject or translated text is invalid."), 'class' => 'warningheader');
                } else {
                    $oRecord = Get_subject::model()->findByPk($email_subjectid);
                    $oRecord->subject_text = $subject_text;
                    $oRecord->updated_datetime = $current_date;
                    $oRecord->IsActive = $Is_active;
                    $EditEmailSubject = $oRecord->save();
                    if ($EditEmailSubject) {
                        $sql = "SELECT count(*) as cnt FROM {{translation_email_subjects}}
                            WHERE email_subjectid = '$email_subjectid' AND language_code_dest = '$subject_language'";
                        $result = Yii::app()->db->createCommand($sql)->queryRow();
                        if ($result['cnt'] > 0) {
                            $sqlupdate = "UPDATE {{translation_email_subjects}} SET
                                    translated_subject = '$translate_subject_text'
                                    ,updated_datetime = '$current_date'
                                    WHERE email_subjectid = '$email_subjectid' AND language_code_dest = '$subject_language'";
                            $result = Yii::app()->db->createCommand($sqlupdate)->query();
                        } else {
                            $sqlupdate = "INSERT INTO {{translation_email_subjects}}
                                (email_subjectid,language_code_dest,translated_subject,created_datetime) 
                                VALUES('$email_subjectid', '$subject_language', '$translate_subject_text', '$current_date');";
                            $result = Yii::app()->db->createCommand($sqlupdate)->query();
                        }
                        Yii::app()->setFlashMessage($clang->gT("Email Subject updated successfully"));
                        $this->getController()->redirect(array("admin/get/sa/list_subs"));
                    } else {
                        $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing Email Subject"), $clang->gT("Could not modify Email Subject."), 'warningheader');
                    }
                }
            } else {
                $sresult = getEmailSubject($language_code_dest, $email_subjectid);
                $aData['mur'] = $sresult;
                $aData['email_subjectid'] = $email_subjectid;
                $aData['language_code_dest'] = $language_code_dest;
                $this->_renderWrappedTemplate('get', 'view_editsubs', $aData);
                return;
            }
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/get/sa/list_subs"));
    }

    function del_subs() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('emailTemp', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $email_subjectid = (int) Yii::app()->request->getPost("email_subjectid");
        if ($email_subjectid) {
            if ($action == "del_subs") {
                $dresult = Get_subject::model()->deletesubject($email_subjectid);
                if ($dresult) {
                    $dlt = "DELETE FROM {{translation_email_subjects}} WHERE email_subjectid = " . $email_subjectid;
                    $result = Yii::app()->db->createCommand($dlt)->query();
                    Yii::app()->setFlashMessage($clang->gT("Email Subject delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("Email Subject does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/get/sa/list_subs"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete Email Subject. Email Subject was not supplied."), 'error');
            $this->getController()->redirect(array("admin/get/sa/list_subs"));
        }

        return $aViewUrls;
    }

// =========================== Email Subject EOF ===========================
// =========================== Email Body BOF ==============================    

    function list_body() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        if (isset($_POST['body_language_list'])) {
            $body_language_list = $_POST['body_language_list'];
        } else {
            $body_language_list = 'en';
        }
        $userlist = getEmailBody($body_language_list);
        Yii::app()->request->cookies['Language-Email-Body'] = new CHttpCookie('Language-Email-Body', $body_language_list);
        $aData['row'] = 0;
        $aData['lan_email_body'] = $body_language_list;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('get', 'view_listbody', $aData);
    }

    function form_add_body() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        Yii::app()->loadHelper("admin/htmleditor");
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('get', 'view_addbody', $aData);
    }

    function fetchtemplatevariable() {
        $emailUseIN = (int) $_POST['emailUseIN'];

        if ($emailUseIN <= 0) {
            echo '<strong>Please selecte Use In for get more information.</strong>';
            exit;
        }
        echo '<script type="text/javascript">';
        echo "function CopyToClipBoard(obj)
                {
                    data1 = CKEDITOR.instances.body_content.getData();
                    CKEDITOR.instances.body_content.setData(data1+obj.innerText);
                    data2 = CKEDITOR.instances.translated_content.getData();
                    CKEDITOR.instances.translated_content.setData(data2+obj.innerText);
                }";
        echo '</script>';
        echo '<table class="InfoForm" style="width: 50%;margin:0px auto;margin-top: 10px;">';
        echo '<tr><th style="text-align: center;">Parameter</th><th style="text-align: center;">Parameter Description</th></tr>';
        $newPath = "application.views.admin.get";
        $newPath = YiiBase::getPathOfAlias($newPath);
        $xml = simplexml_load_file($newPath . '/emailparameter.xml') or die("Error: Cannot create object");
        foreach ($xml->children() as $root) {
            $odd = FALSE;
            $id = 0;
            foreach ($root->children() as $usein => $data) {
                if ($data->id == $emailUseIN) {
                    if ($odd) {
                        $cls = 'class="odd"';
                    } else {
                        $cls = 'class="even"';
                    }
                    echo '<tr ' . $cls . '><td style="text-align:right;">' . $data->paradescription . '</td>
                              <td><a id="obj' . $id . '" style="display: inline-block; cursor: hand;" onclick="CopyToClipBoard(this);">' . $data->para . '</a></td></tr>';
                    $odd = !$odd;
                    $id++;
                }
                //echo $data->id;//echo $data->paradescription;//echo $data->para;//echo "<br />";
            }
        }
        echo '</table>';
    }

    function ins_body() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('emailTemp', 'create')) {

            if ($action == "addemailbody") {
                $email_body = $_POST['email_body'];
                $body_language = $_POST['body_language'];
                $current_date = date('y-m-d h:i:s');
                $email_body = str_replace("'", "&#39", $email_body);
                if (empty($email_body)) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Email Body"), 'message' => $clang->gT("A body was not supplied or the body is invalid."), 'class' => 'warningheader');
                } else {
                    $NewBody = Get_body::model()->insertBody($email_body, $current_date);
                    if ($NewBody) {
                        // here go translation insert
                        $sql = "INSERT INTO {{translation_email_body}}
                        (email_bodyid,language_code_dest,translated_body,created_datetime)
                        VALUES('$NewBody','$body_language','$email_body','$current_date')";
                        $result = Yii::app()->db->createCommand($sql)->query();
                        Yii::app()->setFlashMessage($clang->gT("Email Subject added successfully"));
                        $this->getController()->redirect(array("admin/get/sa/list_body"));
                    }
                }
            }
        } else {
            $aViewUrls = 'view_addbody';
        }

        $this->_renderWrappedTemplate('get', $aViewUrls, $aData);
    }

    function edit_body() {
        Yii::app()->loadHelper("admin/htmleditor");
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'update')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        //Yii::app()->session['FileManagerContext'] = "edit:survey:21";
        Yii::app()->loadHelper("admin/htmleditor");
        //initKcfinder();
        $aViewUrls['output'] = PrepareEditorScript(true, $this->getController());
        if (isset($_POST['email_body_id']) && isset($_POST['email_language_code'])) {
            $email_body_id = (int) Yii::app()->request->getPost("email_body_id");
            $email_language_code = flattenText($_POST['email_language_code'], false, true, 'UTF-8', true);
            if ($action == 'modbody') {
                $current_date = date('y-m-d h:i:s');
                $body_language = flattenText($_POST['body_language'], false, true, 'UTF-8', true);
                $translated_content = $_POST['translated_content'];
                $body_content = $_POST['body_content'];
                $translated_content = str_replace("'", "&#39", $translated_content);
                $body_content = str_replace("'", "&#39", $body_content);
                $Isactive = flattenText($_POST['IsActive'], false, true, 'UTF-8', true);
                $Is_active = 0;
                if ($Isactive) {
                    $Is_active = 1;
                }
                if ($translated_content == '' || $body_language == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to edit Email Body"), 'message' => $clang->gT("A email body or translated body was not supplied or the email body or translated body is invalid."), 'class' => 'warningheader');
                } else {
                    $oRecord = Get_body::model()->findByPk($email_body_id);
                    $oRecord->content_text = $body_content;
                    $oRecord->updated_datetime = $current_date;
                    $oRecord->IsActive = $Is_active;
                    $EditEmailSubject = $oRecord->save();
                    if ($EditEmailSubject) {
                        $sql = "SELECT count(*) as cnt FROM {{translation_email_body}}
                            WHERE email_bodyid = '$email_body_id' AND language_code_dest = '$body_language'";
                        $result = Yii::app()->db->createCommand($sql)->queryRow();
                        if ($result['cnt'] > 0) {
                            $sqlupdate = "UPDATE {{translation_email_body}} SET
                                    translated_body = '$translated_content'
                                    ,updated_datetime = '$current_date'
                                    WHERE email_bodyid = '$email_body_id' AND language_code_dest = '$body_language'";
                            $result = Yii::app()->db->createCommand($sqlupdate)->query();
                        } else {
                            $sqlupdate = "INSERT INTO {{translation_email_body}}
                                (email_bodyid,language_code_dest,translated_body,created_datetime) 
                                VALUES('$email_body_id', '$body_language', '$translated_content', '$current_date');";
                            $result = Yii::app()->db->createCommand($sqlupdate)->query();
                        }
                        Yii::app()->setFlashMessage($clang->gT("Email Body updated successfully"));
                        $this->getController()->redirect(array("admin/get/sa/list_body"));
                    } else {
                        $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing Email Body"), $clang->gT("Could not modify Email Body."), 'warningheader');
                    }
                }
            } else {
                $sresult = getEmailBody($email_language_code, $email_body_id);
                $aData['mur'] = $sresult;
                $aData['email_body_id'] = $email_body_id;
                $aData['email_language_code'] = $email_language_code;
                $this->_renderWrappedTemplate('get', 'view_editbody', $aData);
                return;
            }
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/get/sa/list_body"));
    }

    function del_body() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('emailTemp', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $email_bodyid = (int) Yii::app()->request->getPost("email_bodyid");
        if ($email_bodyid) {
            if ($action == "del_body") {
                $dresult = Get_body::model()->deletebody($email_bodyid);
                if ($dresult) {
                    $dlt = "DELETE FROM {{translation_email_body}} WHERE email_bodyid = " . $email_bodyid;
                    $result = Yii::app()->db->createCommand($dlt)->query();
                    Yii::app()->setFlashMessage($clang->gT("Email Body delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("Email Body does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/get/sa/list_body"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete Email Body. Email Body was not supplied."), 'error');
            $this->getController()->redirect(array("admin/get/sa/list_body"));
        }

        return $aViewUrls;
    }

    function bodycontent() {
        $body_language = $_POST['body_language'];
        $body_id = (int) $_POST['body_id'];
        $sresult = getEmailBody($body_language, $body_id);
        foreach ($sresult as $value) {
            echo $value['translated_body'];
        }
    }

// =========================== Email Body EOF ==============================    
// =========================== Email Template BOF ==============================    
    function list_tmplt() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = getEmailTemplate();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('get', 'view_listtmplt', $aData);
    }

    function add_template() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('get', 'view_addtemplate', $aData);
    }

    function templatecontent() {
        $body_id = (int) $_POST['body_id'];
        $sresult = Get_body::model()->findAll(array('condition' => 'email_bodyid = ' . $body_id));
        foreach ($sresult as $value) {
            echo $value['content_text'];
        }
    }

    function ins_template() {
        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        if (Permission::model()->hasGlobalPermission('emailTemp', 'create')) {

            if ($action == "addemailtemplate") {
                $email_title = flattenText($_POST['email_title'], false, true, 'UTF-8', true);
                $template_usein = (int) Yii::app()->request->getPost("template_usein");
                $email_subject = (int) Yii::app()->request->getPost("email_subject");
                $body_content = (int) Yii::app()->request->getPost("body_content");
                $current_date = date('y-m-d h:i:s');
                if (empty($email_title)) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Email Template"), 'message' => $clang->gT("A Email Title was not supplied or the Email Title is invalid."), 'class' => 'warningheader');
                } elseif ($template_usein <= 0 && $email_subject <= 0 && $body_content <= 0) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Email Template"), 'class' => 'warningheader');
                } else {
                    $NewTemplate = Get_template::model()->insertTemplate($email_title, $template_usein, $email_subject, $body_content, $current_date);
                    if ($NewTemplate) {
                        Yii::app()->setFlashMessage($clang->gT("Email template added successfully"));
                        $this->getController()->redirect(array("admin/get/sa/list_tmplt"));
                    }
                }
            }
        } else {
            $aViewUrls = 'view_addtemplate';
        }

        $this->_renderWrappedTemplate('get', $aViewUrls, $aData);
    }

    function edit_tmplt() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('emailTemp', 'update')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        if (isset($_POST['template_emailid'])) {
            $template_emailid = (int) Yii::app()->request->getPost("template_emailid");
            if ($action == 'modemailtemplate') {
                $current_date = date('y-m-d h:i:s');
                $email_title = flattenText($_POST['email_title'], false, true, 'UTF-8', true);
                $template_usein = (int) Yii::app()->request->getPost("template_usein");
                $email_subject = (int) Yii::app()->request->getPost("email_subject");
                $body_content = (int) Yii::app()->request->getPost("body_content");
                $Isactive = flattenText($_POST['IsActive'], false, true, 'UTF-8', true);
                $Is_active = 0;
                if ($Isactive) {
                    $Is_active = 1;
                }
                if (empty($email_title)) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to edit Email Template"), 'message' => $clang->gT("A Email Title was not supplied or the Email Title is invalid."), 'class' => 'warningheader');
                } elseif ($template_usein <= 0 && $email_subject <= 0 && $body_content <= 0) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to edit Email Template"), 'class' => 'warningheader');
                } else {
                    $oRecord = Get_template::model()->findByPk($template_emailid);
                    $oRecord->title_text = $email_title;
                    $oRecord->use_in = $template_usein;
                    $oRecord->email_subjectid = $email_subject;
                    $oRecord->email_bodyid = $body_content;
                    $oRecord->updated_datetime = $current_date;
                    $oRecord->IsActive = $Is_active;
                    $EditEmailSubject = $oRecord->save();
                    if ($EditEmailSubject) {
                        Yii::app()->setFlashMessage($clang->gT("Email Template updated successfully"));
                        $this->getController()->redirect(array("admin/get/sa/list_tmplt"));
                    } else {
                        $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing Email Template"), $clang->gT("Could not modify Email Template."), 'warningheader');
                    }
                }
            } else {
                $sresult = getEmailTemplate($template_emailid);
                $aData['mur'] = $sresult;
                $this->_renderWrappedTemplate('get', 'view_edittemplate', $aData);
                return;
            }
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/get/sa/list_tmplt"));
    }

    function del_tmplt() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('emailTemp', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $template_emailid = (int) Yii::app()->request->getPost("template_emailid");
        if ($template_emailid) {
            if ($action == "del_tmplt") {
                $dresult = Get_template::model()->deletetmplt($template_emailid);
                if ($dresult) {
                    Yii::app()->setFlashMessage($clang->gT("Email Template delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("Email Template does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/get/sa/list_tmplt"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete Email Template. Email Template was not supplied."), 'error');
            $this->getController()->redirect(array("admin/get/sa/list_tmplt"));
        }

        return $aViewUrls;
    }

    private function _messageBoxWithRedirect($title, $message, $classMsg, $extra = "", $url = "", $urlText = "", $hiddenVars = array(), $classMbTitle = "header ui-widget-header") {
        $clang = Yii::app()->lang;
        //$url = (!empty($url)) ? $url : $this->getController()->createUrl('admin/contact_group/index');
        $urlText = (!empty($urlText)) ? $urlText : $clang->gT("Continue");

        $aData['title'] = $title;
        $aData['message'] = $message;
        $aData['url'] = $url;
        $aData['urlText'] = $urlText;
        $aData['classMsg'] = $classMsg;
        $aData['classMbTitle'] = $classMbTitle;
        $aData['extra'] = $extra;
        $aData['hiddenVars'] = $hiddenVars;

        return $aData;
    }

    /**
     * Renders template(s) wrapped in header and footer
     *
     * @param string $sAction Current action, the folder to fetch views from
     * @param string|array $aViewUrls View url(s)
     * @param array $aData Data to be passed on. Optional.
     */
    protected function _renderWrappedTemplate($sAction = 'get', $aViewUrls = array(), $aData = array()) {

        $aData['display']['menu_bars']['get'] = true;

        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
