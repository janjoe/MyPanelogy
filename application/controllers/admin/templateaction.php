<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * LimeSurvey
 * Copyright (C) 2007-2011 The LimeSurvey Project Team / Carsten Schmitz
 * All rights reserved.
 * License: GNU/GPL License v2 or later, see LICENSE.php
 * LimeSurvey is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 */

/**
 * Usergroups
 *
 * @package LimeSurvey
 * @author
 * @copyright 2011
 * @access public
 */
class Templateaction extends Survey_Common_Action {

    /**
     * Load viewing of a user group screen.
     * @param bool $ugid
     * @param array|bool $header (type=success, warning)(message=localized message)
     * @return void
     */
    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('CMS', 'read')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $controllername = $this->getId();

        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('cms/template', 'view_addedittemplate', $aData);
    }

    /**
     * Usergroups::delete()
     * Function responsible to delete a user group.
     * @return void
     */
    public function add() {

        $clang = Yii::app()->lang;
        $aData = array();
        $aViewUrls = array();


        if (Permission::model()->hasGlobalPermission('CMS', 'create')) {
            //echo $test = getBasePath();
            $controllername = $this->getId();
            $newPath = "application.views.";
            $newPath = YiiBase::getPathOfAlias($newPath);
            //$filepath = $newPath . '\admin\cms\template\default.tpl.php';
            $filepath = $newPath . '/admin/cms/template/default.tpl.php';
            $page_content = $_POST['template_editor'];
//            $page_content = html_entity_decode($page_content, ENT_QUOTES, "UTF-8");
//            $page_content = fixCKeditorText($page_content);
            if (file_put_contents($filepath, $page_content, LOCK_EX)) {
                Yii::app()->setFlashMessage($clang->gT("Template updated successfully"));
                $this->getController()->redirect(array("admin/template/index"));
            }
        } else {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }

        $this->_renderWrappedTemplate('cms/cms', $aViewUrls, $aData);
    }

    function pagecontent() {
        $page_language = $_POST['page_language'];
        $page_id = (int) $_POST['page_id'];
        $sresult = getPagContentLanguage($page_id, $page_language);
        foreach ($sresult as $value) {
            echo $value['page_content'];
        }
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
    protected function _renderWrappedTemplate($sAction = 'cms/cms', $aViewUrls = array(), $aData = array()) {

        $aData['display']['menu_bars']['cms_bars'] = true;

        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
