<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class rewards extends Survey_Common_Action {

    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('rewards', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = rewardsview();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('panellist/rewards', 'view_rewards', $aData);
    }

    public function rewardprocess() {
        $aData['clang'] = $clang = Yii::app()->lang;
        $aData['row'] = 0;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $aData['sImageURL'] = Yii::app()->getConfig('adminimageurl');
        $aData['type'] = $_GET['type'];
        if (isset($_POST['chk'])) {
            $test = array();
            foreach ($_POST['chk'] as $key => $value) {
                $test[] = $value;
            }
        } else {
            echo 'Please select atleast one reward';
            exit;
        }
        $aData['id_ary'] = $test;
        $aData['ids'] = implode(",", $test);
        
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->getController()->renderPartial('/admin/panellist/rewards/' . 'process_reward', $aData);
        }
    }

    function rewardrequest() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('rewards', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        //$userlist = rewardsview();
        $aData['row'] = 0;
        //$aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('panellist/rewards', 'view_requestreward', $aData);
    }

    public function add() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('rewards', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('rewards', 'create')) {

            if ($action == "addreward") {
                // Project details
                $short_title = flattenText($_POST['short_title'], false, true, 'UTF-8', true);
                $title = flattenText($_POST['title'], false, true, 'UTF-8', true);
                $type = flattenText($_POST['type'], false, true, 'UTF-8', true);
                $image = "";
                if (isset($_FILES['image']) && count($_FILES['image']['error']) == 1 && $_FILES['image']['error'][0] > 0) {
                    //file not selected
                } else if (isset($_FILES['image'])) { //this is just to check if isset($_FILE). Not required.
                    //file selected
                    $random = rand(100, 999);
                    $filename = $random . $_FILES["image"]["name"];
                    move_uploaded_file($_FILES["image"]["tmp_name"], Yii::app()->basepath . "/../upload/images/" . $filename);
                    $image = $filename;
                }

                $points = (int) $_POST['points'];
                $amount = (int) $_POST['amount'];
                $expiration_date = date('Y-m-d', strtotime($_POST['expiration_date']));
                $sorder = flattenText($_POST['sorder'], false, true, 'UTF-8', true);
                $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
                $is_Active = 0;
                if ($IsActive)
                    $is_Active = 1;

                if ($short_title == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Reward"), 'message' => $clang->gT("Reward Title is invalid."), 'class' => 'warningheader');
                } else {

                    $NewReward = reward::model()->instReward($short_title, $title, $type, $image, $points, $amount, $sorder, $is_Active, $expiration_date);
                    if ($NewReward) {
                        Yii::app()->setFlashMessage($clang->gT("Reward added successfully"));
                        $this->getController()->redirect(array("admin/rewards/index"));
                    }
                }
            } else {
                $aViewUrls = 'addrewards_view';
            }
        }

        $this->_renderWrappedTemplate('panellist/rewards', $aViewUrls, $aData);
    }

    function mod() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('rewards', 'update')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }

        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();
        if (Permission::model()->hasGlobalPermission('', 'create')) {

            if ($action == "editreward") {
                // Project details
                $id = (int) Yii::app()->request->getPost("reward_id");
                $short_title = flattenText($_POST['short_title'], false, true, 'UTF-8', true);
                $title = flattenText($_POST['title'], false, true, 'UTF-8', true);
                $type = flattenText($_POST['type'], false, true, 'UTF-8', true);

                $image = "";
                if (isset($_FILES['image']) && count($_FILES['image']['error']) == 1 && $_FILES['image']['error'][0] > 0) {
                    //file not selected
                } else if (isset($_FILES['image'])) { //this is just to check if isset($_FILE). Not required.
                    //file selected
                    $random = rand(100, 999);
                    $filename = $random . $_FILES["image"]["name"];
                    move_uploaded_file($_FILES["image"]["tmp_name"], Yii::app()->baseurl . "/upload/images/" . $filename);
                    $image = $filename;
                }

                $points = (int) $_POST['points'];
                $amount = (int) $_POST['amount'];
                $sorder = flattenText($_POST['sorder'], false, true, 'UTF-8', true);
                $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
                $is_active = 0;
                if ($IsActive)
                    $is_active = 1;

                if ($short_title == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to Edit Reward"), 'message' => $clang->gT("Reward Title is invalid."), 'class' => 'warningheader');
                } else {
                    $oUser = reward::model()->findbypk($id);
                    $oUser->short_title = $short_title;
                    $oUser->title = $title;
                    $oUser->type = $type;
                    if ($image != "")
                        $oUser->image = $image;
                    $oUser->points = $points;
                    $oUser->amount = $amount;
                    $oUser->IsActive = $is_active;
                    $oUser->sorder = $sorder;
                    $oUser->expiration_date = date('Y-m-d', strtotime($_POST['expiration_date']));
                    $oUser->modified_date = Date('y-m-d h:i:s');
                    $NewReward = $oUser->save();
                    if ($NewReward) {
                        Yii::app()->setFlashMessage($clang->gT("Reward Updated successfully"));
                        $this->getController()->redirect(array("admin/rewards/index"));
                    }
                }
            } else {
                if (isset($_POST['id'])) {

                    $aData['row'] = 0;
                    $aData['usr_arr'] = array();

                    // Project detail
                    $id = (int) Yii::app()->request->getPost("id");
                    $action = Yii::app()->request->getPost("action");
                    $sresult = rewardsview($id);
                    // only use in view_editcompany
                    $aData['id'] = $id;
                    $aData['mur'] = $sresult;
                    $this->_renderWrappedTemplate('panellist/rewards', 'editreward_view', $aData);
                    return;
                }
            }
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/rewards/index"));
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
