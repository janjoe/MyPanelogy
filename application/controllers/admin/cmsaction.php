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
class Cmsaction extends Survey_Common_Action {

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
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = getPagelist();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('cms/cms', 'view_cms', $aData);
    }

    /**
     * Usergroups::delete()
     * Function responsible to delete a user group.
     * @return void
     */
    function delcms() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('CMS', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $page_id = (int) Yii::app()->request->getPost("page_id");
        if ($page_id) {
            if ($action == "delcms") {
                $dresult = Cms::model()->deletecms($page_id);
                if ($dresult) {
                    $dlt = "DELETE FROM {{cms_page_content}} WHERE page_id = " . $page_id;
                    $result = Yii::app()->db->createCommand($dlt)->query();
                    Yii::app()->setFlashMessage($clang->gT("Page delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("Page does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/cms/index"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete cms. cms was not supplied."), 'error');
            $this->getController()->redirect(array("admin/cms/index"));
        }

        return $aViewUrls;
    }

    public function add() {

        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('CMS', 'create')) {

            if ($action == "addcms") {
                $page_name = flattenText($_POST['page_name'], false, true, 'UTF-8', true);
                $page_title = flattenText($_POST['page_title'], false, true, 'UTF-8', true);
                $page_meta = flattenText($_POST['page_meta'], false, true, 'UTF-8', true);
                $page_language = Yii::app()->request->getPost("page_language");
                $page_type = flattenText($_POST['page_type'], false, true, 'UTF-8', true);
                $shwmenu = flattenText(Yii::app()->request->getPost("shwmenu"));
                $shw_menu = 0;
                if ($shwmenu) {
                    $shw_menu = 1;
                }
                if ($page_type == 1) {
                    $page_content = $_POST['page_content'];
                } elseif ($page_type == 2) {
                    $page_content = flattenText($_POST['redirectlink'], false, true, 'UTF-8', true);
                }

                $sql = "SELECT COUNT(*) as cnt
                        FROM {{cms_page_master}} cpm WHERE cpm.page_name = '$page_name'";
                $result = Yii::app()->db->createCommand($sql)->queryRow();
                if ($page_name == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Page"), 'message' => $clang->gT("A Page Name was not supplied or the Page Name is invalid."), 'class' => 'warningheader');
                } elseif ($result['cnt'] > 0) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Page"), 'message' => $clang->gT("The Page already exists For language."), 'class' => 'warningheader');
                } else {
                    $NewPage = Cms::model()->instCms($page_name, $page_title, $page_type, $shw_menu);
                    if ($NewPage) {
                        $sql = "INSERT INTO {{cms_page_content}} (page_id,language_code,page_content,meta_tags) 
                                VALUES('$NewPage', '$page_language','" . str_replace("'", "&#39", $page_content) . "', '$page_meta');";
                        $result = Yii::app()->db->createCommand($sql)->query();
                        Yii::app()->setFlashMessage($clang->gT("Page added successfully"));
                        $this->getController()->redirect(array("admin/cms/index"));
                    }
                }
            } else {
                $aViewUrls = 'view_addcms';
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

    /**
     * Contact::edit()
     * Load edit contact screen.
     * @param mixed $ugid
     * @return void
     */
    function modifycms() {
        if (isset($_POST['page_id'])) {
            $page_id = (int) Yii::app()->request->getPost("page_id");
            $sresult = getPageDetail($page_id);
            $aData['page_id'] = $page_id;
            $aData['mur'] = $sresult;
            $this->_renderWrappedTemplate('cms/cms', 'view_editcms', $aData);
            return;
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/index"));
    }

    /**
     * Modify User POST
     */
    function modcms() {
        $clang = Yii::app()->lang;

        $page_id = flattenText($_POST['page_id'], false, true, 'UTF-8', true);
        $page_name = flattenText($_POST['page_name'], false, true, 'UTF-8', true);
        $page_title = flattenText($_POST['page_title'], false, true, 'UTF-8', true);
        $page_meta = flattenText($_POST['page_meta'], false, true, 'UTF-8', true);
        $page_language = Yii::app()->request->getPost("page_language");
        $page_type = flattenText($_POST['page_type'], false, true, 'UTF-8', true);
        $shwmenu = flattenText(Yii::app()->request->getPost("shwmenu"));
        $shw_menu = 0;
        if ($shwmenu) {
            $shw_menu = 1;
        }
        if ($page_type == 1) {
            $page_content = $_POST['page_content'];
        } elseif ($page_type == 2) {
            $page_content = flattenText($_POST['redirectlink'], false, true, 'UTF-8', true);
        }
        $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
        $aViewUrls = array();
        $is_Active = 0;
        if ($IsActive) {
            $is_Active = 1;
        }
        $sresult = Cms::model()->findAllByAttributes(array('page_id' => $page_id));
        $sresultcount = count($sresult);
        if ((Permission::model()->hasGlobalPermission('superadmin', 'read') ||
                ($sresultcount > 0 && Permission::model()->hasGlobalPermission('CMS', 'update')))) {
            if ($page_name == '') {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing page"), $clang->gT("Could not modify Contact."), "warningheader", $clang->gT("Page name not be empty."), $this->getController()->createUrl('admin/cms/sa/modifycms'), $clang->gT("Back"), array('page_id' => $page_id));
            } else {
                $oRecord = Cms::model()->findByPk($page_id);
                $oRecord->page_name = $page_name;
                $oRecord->page_title = $page_title;
                $oRecord->contenttype = $page_type;
                $oRecord->showinmenu = $shw_menu;
                $oRecord->IsActive = $is_Active;
                $EditContact = $oRecord->save();

                if ($EditContact) { // When saved successfully
                    $sql = "SELECT count(*) as cnt FROM {{cms_page_content}}
                            WHERE page_id = '$page_id' AND language_code = '$page_language'";
                    $result = Yii::app()->db->createCommand($sql)->queryRow();
                    if ($result['cnt'] > 0) {
                        $sqlupdate = "UPDATE {{cms_page_content}} SET
                                    page_content = '" . str_replace("'", "&#39", $page_content) . "'
                                    , meta_tags = '$page_meta'
                                    WHERE page_id = '$page_id' AND language_code = '$page_language'";
                        $result = Yii::app()->db->createCommand($sqlupdate)->query();
                    } else {
                        $sqlupdate = "INSERT INTO {{cms_page_content}} (page_id,language_code,page_content,meta_tags) 
                                VALUES('$page_id', '$page_language', '" . str_replace("'", "&#39", $page_content) . "', '$page_meta');";
                        $result = Yii::app()->db->createCommand($sqlupdate)->query();
                    }
                    Yii::app()->setFlashMessage($clang->gT("Page updated successfully"));
                    $this->getController()->redirect(array("admin/cms/index"));
                } else {
                    $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing Page"), $clang->gT("Could not modify Page."), 'warningheader');
                }
            }
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $this->_renderWrappedTemplate('cms', $aViewUrls);
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
