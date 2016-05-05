<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class profilequestion extends Survey_Common_Action {

    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('panellist', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = profilequestionview();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;


        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");

        $this->_renderWrappedTemplate('panellist/questions', 'view_question', $aData);
    }

    public function add() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('panellist', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('panellist', 'create')) {

            if ($action == "addquestion") {
                // Project details
                $category_id = (int) Yii::app()->request->getPost("category");
                $short_title = flattenText($_POST['question_short_title'], false, true, 'UTF-8', true);
                $title = flattenText($_POST['question_title'], false, true, 'UTF-8', true);
                $field_type = flattenText($_POST['question_field_type'], false, true, 'UTF-8', true);
                $IsOther = flattenText(Yii::app()->request->getPost("question_is_other"));
                $is_other = 0;
                if ($IsOther)
                    $is_other = 1;
                $is_other_field_type = flattenText($_POST['question_field_other_type'], false, true, 'UTF-8', true);
                $outdate_threshold = (int) $_POST['question_outdate_threshold'];
                $priority = flattenText($_POST['question_priority'], false, true, 'UTF-8', true);
                $IsProfile = flattenText(Yii::app()->request->getPost("question_is_profile"));
                $is_profile = 0;
                if ($IsProfile)
                    $is_profile = 1;
                $IsProject = flattenText(Yii::app()->request->getPost("question_is_project"));
                $is_project = 0;
                if ($IsProject)
                    $is_project = 1;
                $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
                $is_active = 0;
                if ($IsActive)
                    $is_active = 1;
                $sort_order = flattenText($_POST['sort_order'], false, true, 'UTF-8', true);

                if ($short_title == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Question"), 'message' => $clang->gT("Question Name is invalid."), 'class' => 'warningheader');
                } else {

                    $NewQuestion = questions::model()->instQuestion($category_id, $short_title, $title, $field_type, $is_other, $is_other_field_type, $outdate_threshold, $priority, $is_profile, $is_project, $sort_order, $is_active);
                    if ($NewQuestion) {
                        ///adding a new column in panellist_answer table based on field type
                        $col_name = "question_id_" . $NewQuestion;
                        /*
                          $fld_type = " INT(4) ";
                          if ($field_type == 'Text' || $is_other == 1)  $fld_type = " text ";
                          if ($field_type == "CheckBox" || $field_type == "TextArea")  $fld_type = " text ";
                         */
                        $fld_type = "varchar(255)";
                        $alterq = "ALTER TABLE {{panellist_answer}} ADD $col_name $fld_type  NULL";
                        Yii::app()->db->createCommand($alterq)->query();

                        Yii::app()->setFlashMessage($clang->gT("Question is added successfully"));

                        $this->getController()->redirect(array("admin/profilequestion/index"));
                    }
                }
            } else {
                $aViewUrls = 'addquestion_view';
            }
        }

        $this->_renderWrappedTemplate('panellist/questions', $aViewUrls, $aData);
    }

    function mod() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('panellist', 'update')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $clang = Yii::app()->lang;
        $action = (isset($_GET['action'])) ? $_GET['action'] : '';
        $aData = array();
        $aViewUrls = array();
        if (Permission::model()->hasGlobalPermission('panellist', 'update')) {

            if ($action == "editquestion") {
                // Project details
                $question_id = (int) Yii::app()->request->getPost("question_id");
                $category_id = (int) Yii::app()->request->getPost("category");
                $short_title = flattenText($_POST['question_short_title'], false, true, 'UTF-8', true);
                $title = flattenText($_POST['question_title'], false, true, 'UTF-8', true);
                $field_type = flattenText($_POST['question_field_type'], false, true, 'UTF-8', true);
                $IsOther = flattenText(Yii::app()->request->getPost("question_is_other"));
                $is_other = 0;
                if ($IsOther)
                    $is_other = 1;
                $is_other_field_type = flattenText($_POST['question_field_other_type'], false, true, 'UTF-8', true);
                $outdate_threshold = (int) $_POST['question_outdate_threshold'];
                $priority = flattenText($_POST['question_priority'], false, true, 'UTF-8', true);
                $IsProfile = flattenText(Yii::app()->request->getPost("question_is_profile"));
                $is_profile = 0;
                if ($IsProfile)
                    $is_profile = 1;
                $IsProject = flattenText(Yii::app()->request->getPost("question_is_project"));
                $is_project = 0;
                if ($IsProject)
                    $is_project = 1;
                $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
                $is_active = 0;
                if ($IsActive)
                    $is_active = 1;
                $sort_order = flattenText($_POST['sort_order'], false, true, 'UTF-8', true);

                if ($short_title == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Question"), 'message' => $clang->gT("Question Name is invalid."), 'class' => 'warningheader');
                } else {
                    $oUser = questions::model()->findByPk($question_id);
                    $oUser->category_id = $category_id;
                    $oUser->short_title = $short_title;
                    $oUser->title = $title;
                    $oUser->field_type = $field_type;
                    $oUser->is_other = $is_other;
                    $oUser->is_other_field_type = $is_other_field_type;
                    $oUser->outdate_threshold = $outdate_threshold;
                    $oUser->priority = $priority;
                    $oUser->is_profile = $is_profile;
                    $oUser->is_project = $is_project;
                    $oUser->IsActive = $is_active;
                    $oUser->sorder = $sort_order;
                    $oUser->modified_date = Date('y-m-d h:i:s');
                    $NewQuestion = $oUser->save();
                    if ($NewQuestion) {
                        Yii::app()->setFlashMessage($clang->gT("Profile Question Updated successfully"));

                        $this->getController()->redirect(array("admin/profilequestion/index"));
                    }
                }
            } else {
                if (isset($_GET['question_id'])) {

                    $aData['row'] = 0;
                    $aData['usr_arr'] = array();

                    // Project detail
                    $question_id = (int) $_GET['question_id'];
                    $action = $_GET['action'];
                    $sresult = profilequestionview($question_id);
                    // only use in view_editcompany
                    $aData['question_id'] = $question_id;
                    $aData['mur'] = $sresult;
                    $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
                    $answerlist = profileanswer_view($question_id);
                    $aData['answer_arr'] = $answerlist;

                    $this->_renderWrappedTemplate('panellist/questions', 'editquestion_view', $aData);
                    return;
                }
            }
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/profilequestion/index"));
    }

    public function adda() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('panellist', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $question_id = (int) Yii::app()->request->getPost("question_id");
        $aData = array();
        $aViewUrls = array();
        $answerlist = profileanswerview($question_id, 0);
        $aData['answer_arr'] = $answerlist;

        if (Permission::model()->hasGlobalPermission('panellist', 'create')) {

            if ($action == "addanswer") {
                // Project details
                $question_id = (int) Yii::app()->request->getPost("question_id");
                $category_id = (int) Yii::app()->request->getPost("category_id");
                $title = flattenText($_POST['answer_title'], false, true, 'UTF-8', true);
                $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
                $is_active = 0;
                if ($IsActive)
                    $is_active = 1;
                $sort_order = flattenText($_POST['sort_order'], false, true, 'UTF-8', true);

                if ($title == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Answer"), 'message' => $clang->gT("Answer Name is invalid."), 'class' => 'warningheader');
                } else {

                    $NewAnswer = answers::model()->instAnswer($question_id, $category_id, $title, $sort_order, $is_active);
                    if ($NewAnswer) {
                        Yii::app()->setFlashMessage($clang->gT("Answer is added successfully"));
                        $aData['row'] = 0;
                        $aData['usr_arr'] = array();

                        $question_id = (int) Yii::app()->request->getPost("question_id");
                        $action = Yii::app()->request->getPost("action");
                        $sresult = profilequestionview($question_id);

                        $aData['question_id'] = $question_id;
                        $aData['mur'] = $sresult;
                        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
                        $answerlist = profileanswerview($question_id, 0, 1);
                        $aData['answer_arr'] = $answerlist;
                        $this->getController()->redirect(array("admin/profilequestion/sa/mod/action/modifyquestion/question_id/$question_id"));
                    }
                }
            } else {
                $aViewUrls = 'addanswer_view';
            }
        }

        $this->_renderWrappedTemplate('panellist/questions', $aViewUrls, $aData);
    }

    function moda() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('panellist', 'update')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();
        if (Permission::model()->hasGlobalPermission('', 'create')) {

            if ($action == "editanswer") {
                // Project details
                $answer_id = (int) Yii::app()->request->getPost("answer_id");
                $title = flattenText($_POST['answer_title'], false, true, 'UTF-8', true);
                $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
                $is_active = 0;
                if ($IsActive)
                    $is_active = 1;
                $sort_order = flattenText($_POST['sort_order'], false, true, 'UTF-8', true);

                if ($title == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to Add Answer"), 'message' => $clang->gT("Answer Name is invalid."), 'class' => 'warningheader');
                } else {
                    $oUser = answers::model()->findByPk($answer_id);
                    $oUser->title = $title;
                    $oUser->IsActive = $is_active;
                    $oUser->sorder = $sort_order;
                    $oUser->modified_date = Date('y-m-d h:i:s');
                    $NewAnswer = $oUser->save();
                    if ($NewAnswer) {
                        Yii::app()->setFlashMessage($clang->gT("Profile Answer Updated successfully"));
                        //$this->getController()->redirect(array("admin/profilequestion/sa/mod")); 
                        $aData['row'] = 0;
                        $aData['usr_arr'] = array();

                        // Project detail
                        $question_id = (int) Yii::app()->request->getPost("question_id");
                        $action = Yii::app()->request->getPost("action");
                        $sresult = profilequestionview($question_id);
                        // only use in view_editcompany
                        $aData['question_id'] = $question_id;
                        $aData['mur'] = $sresult;
                        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
                        $answerlist = profileanswerview($question_id, 0, 1);
                        $aData['answer_arr'] = $answerlist;
                        $this->getController()->redirect(array("admin/profilequestion/sa/mod/action/modifyquestion/question_id/$question_id"));
                        //$this->_renderWrappedTemplate('panellist/questions', 'editquestion_view', $aData);
                        return;
                    }
                }
            } else {

                if (isset($_POST['answer_id'])) {

                    $aData['row'] = 0;
                    $aData['usr_arr'] = array();

                    // Project detail
                    $answer_id = (int) Yii::app()->request->getPost("answer_id");
                    $action = Yii::app()->request->getPost("action");
                    $sresult = profileanswerview(0, $answer_id);
                    // only use in view_editcompany
                    $aData['answer_id'] = $answer_id;
                    $aData['mur'] = $sresult;
                    $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");

                    $this->_renderWrappedTemplate('panellist/questions', 'editanswer_view', $aData);
                    return;
                }
            }
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/profilequestion/index"));
    }

    private function _messageBoxWithRedirect($title, $message, $classMsg, $extra = "", $url = "", $urlText = "", $hiddenVars = array(), $classMbTitle = "header ui-widget-header") {
        $clang = Yii::app()->lang;
        $url = (!empty($url)) ? $url : $this->getController()->createUrl('admin/contact_group/index');
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
    protected function _renderWrappedTemplate($sAction = 'panellist/profilecategory', $aViewUrls = array(), $aData = array()) {

        $aData['display']['menu_bars']['panellist'] = true;

        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
