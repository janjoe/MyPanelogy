<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronaction extends Survey_Common_Action {

    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('cron', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        //$userlist = profilecategoryview();
        $aData['row'] = 0;
        //$aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('cron', 'view_cron', $aData);
    }

    // Start by Parth 19-06-2014
    function delcron() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('cron', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = $_GET["action"];

        if ($action == "Clear_Previous_Data") {
            $sql = "DELETE FROM {{CronLog}} WHERE Start_DateTime < DATE_SUB(NOW(), INTERVAL 2 DAY) ";
            $result = Yii::app()->db->createCommand($sql)->execute();
            if ($result > 0) {
                Yii::app()->setFlashMessage($clang->gT("Cron delete successfully"));
            } else {
                Yii::app()->setFlashMessage($clang->gT("Cron does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/cron/view_cron"));
            return true;
        }
    }

    // End by Parth 19-06-2014

    protected function _renderWrappedTemplate($sAction = 'panellist/profilecategory', $aViewUrls = array(), $aData = array()) {

        $aData['display']['menu_bars']['panellist'] = true;

        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}