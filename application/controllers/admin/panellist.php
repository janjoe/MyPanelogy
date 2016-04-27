<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class panellist extends Survey_Common_Action {

    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('panellist', 'read')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        //$userlist = profilecategoryview();
        $aData['row'] = 0;
        //$aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('panellist', 'view_panellist', $aData);
    }

    function changestatus() {
        $status = $_GET['s'];
        $pid = $_GET['pid'];
        $oRecord = PL::model()->findByPk($pid);
        $oRecord->status = $status;
        $Panel_id = $oRecord->save();
        $this->getController()->redirect(array("admin/panellist/index"));
    }

    function changefraud() {
        $status = $_GET['s'];
        $pid = $_GET['pid'];
        $oRecord = PL::model()->findByPk($pid);
        $oRecord->is_fraud = $status;
        $Panel_id = $oRecord->save();
        $this->getController()->redirect(array("admin/panellist/index"));
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

    //17/06/2014 Add By Parth-Hari
    function PanellistInfo() {
        $aData['clang'] = $clang = Yii::app()->lang;
        $aData['row'] = 0;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $aData['sImageURL'] = Yii::app()->getConfig('adminimageurl');
        $aData['panel_list_id'] = $_GET['panel_list_id'];
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->getController()->renderPartial('/admin/panellist/View_PanellistInfo', $aData);
        }
    }
    //17/06/2014 End

    /**
     * Renders template(s) wrapped in header and footer
     *
     * @param string $sAction Current action, the folder to fetch views from
     * @param string|array $aViewUrls View url(s)
     * @param array $aData Data to be passed on. Optional.
     */
    protected function _renderWrappedTemplate($sAction = 'panellist', $aViewUrls = array(), $aData = array()) {

        $aData['display']['menu_bars']['panellist'] = true;

        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}

