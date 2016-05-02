<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class profilecategory extends Survey_Common_Action {

    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('panellist', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = profilecategoryview();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('panellist/category', 'view_category', $aData);
    }

    public function add() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('panellist', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('', 'create')) {

            if ($action == "addcategory") {
                // Project details
                $category_title = flattenText($_POST['category_title'], false, true, 'UTF-8', true);
                $sort_order = flattenText($_POST['sort_order'], false, true, 'UTF-8', true);

                if ($category_title == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Category"), 'message' => $clang->gT("Category Name is invalid."), 'class' => 'warningheader');
                } else {

                    $NewCategory = category::model()->instCategory($category_title, $sort_order);
                    if ($NewCategory) {
                        Yii::app()->setFlashMessage($clang->gT("Profile Category added successfully"));
                        $this->getController()->redirect(array("admin/profilecategory/index"));
                    }
                }
            } else {
                $aViewUrls = 'addcategory_view';
            }
        }

        $this->_renderWrappedTemplate('panellist/category', $aViewUrls, $aData);
    }

    function mod() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('panellist', 'update')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();
        if (Permission::model()->hasGlobalPermission('', 'create')) {

            if ($action == "editcategory") {
                // Project details
                $category_id = (int) Yii::app()->request->getPost("category_id");
                $category_title = flattenText($_POST['category_title'], false, true, 'UTF-8', true);
                $sort_order = flattenText($_POST['sort_order'], false, true, 'UTF-8', true);
                $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
                $is_Active = 0;
                if ($IsActive)
                    $is_Active = 1;

                if ($category_title == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Category"), 'message' => $clang->gT("Category Name is invalid."), 'class' => 'warningheader');
                } else {
                    $oUser = category::model()->findByPk($category_id);
                    $oUser->title = $category_title;
                    $oUser->sorder = $sort_order;
                    $oUser->IsActive = $is_Active;
                    $oUser->modified_date = Date('y-m-d h:i:s');
                    $NewCategory = $oUser->save();
                    if ($NewCategory) {
                        Yii::app()->setFlashMessage($clang->gT("Profile Category Updated successfully"));
                        $this->getController()->redirect(array("admin/profilecategory/index"));
                    }
                }
            } else {
                if (isset($_POST['category_id'])) {

                    $aData['row'] = 0;
                    $aData['usr_arr'] = array();

                    // Project detail
                    $category_id = (int) Yii::app()->request->getPost("category_id");
                    $action = Yii::app()->request->getPost("action");
                    $sresult = profilecategoryview($category_id);
                    // only use in view_editcompany
                    $aData['category_id'] = $category_id;
                    $aData['mur'] = $sresult;
                    $this->_renderWrappedTemplate('panellist/category', 'editcategory_view', $aData);
                    return;
                }
            }
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/profilecategory/index"));
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
