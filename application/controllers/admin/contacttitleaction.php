<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * LimeSurvey
 * Copyright (C) 2013 The LimeSurvey Project Team / Carsten Schmitz
 * All rights reserved.
 * License: GNU/GPL License v2 or later, see LICENSE.php
 * LimeSurvey is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

/**
 * User Controller
 *
 * This controller performs user actions
 *
 * @package        LimeSurvey
 * @subpackage    Backend
 */
class contacttitleaction extends Survey_Common_Action {

    function __construct($controller, $id) {
        parent::__construct($controller, $id);

        Yii::app()->loadHelper('database');
    }

    /**
     * Show users table
     */
    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('contacts', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = getContactTitle();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('contacts/contact_title', 'view_addcontact_title', $aData);
    }

    // added by brain-gaurang on 2014-02-28

    function addcontact_title() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('contacts', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $new_contact_title = flattenText(Yii::app()->request->getPost('new_contact_title'));
        $aData = array();
        $aViewUrls = array();
        if (empty($new_contact_title)) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Contact Title"), 'message' => $clang->gT("A Contact Title was not supplied or the Contact Title is invalid."), 'class' => 'warningheader');
        } elseif (Contact_title::model()->find("contact_title_name=:contact_title_name", array(':contact_title_name' => $new_contact_title))) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Contact Title"), 'message' => $clang->gT("The Contact Title already exists."), 'class' => 'warningheader');
        } else {
            $iNewUID = Contact_title::model()->instContactTitle($new_contact_title);
            if ($iNewUID) {
                Yii::app()->setFlashMessage($clang->gT("Contact Title added successfully"));
                $this->getController()->redirect(array("admin/contact_title/index"));
            } else {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to add Contact Title"), $clang->gT("The Contact Title already exists."), 'warningheader');
            }
        }

        $this->_renderWrappedTemplate('contacts/contact_title', $aViewUrls, $aData);
    }

    /**
     * Delete user
     */
    function delcontact_title() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('contacts', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $contact_title_id = (int) Yii::app()->request->getPost("contact_title_id");
        if ($contact_title_id) {
            if ($action == "delcontact_title") {
                $dresult = Contact_title::model()->deletecontactgroup($contact_title_id);
                if ($dresult) {
                    Yii::app()->setFlashMessage($clang->gT("Contact Title delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("Contact Title does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/contact_title/index"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete Contact title. Contact Title was not supplied."), 'error');
            $this->getController()->redirect(array("admin/contact_title/index"));
        }

        return $aViewUrls;
    }

    /**
     * Modify User
     */
    function modifycontact_title() {
        if (isset($_POST['contact_title_id'])) {
            $contact_title_id = (int) Yii::app()->request->getPost("contact_title_id");
            $sresult = Contact_title::model()->findAllByAttributes(array('contact_title_id' => $contact_title_id));
            $sresultcount = count($sresult);
            $aData['mur'] = $sresult;
            $this->_renderWrappedTemplate('contacts/contact_title', 'view_editcontact_title', $aData);
            return;
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/index"));
        //echo accessDenied('modifyuser');
        //die();
    }

    /**
     * Modify User POST
     */
    function modcontact_title() {
        $clang = Yii::app()->lang;
        // old Details
        $contact_title_id = (int) Yii::app()->request->getPost("contact_title_id");
        $contact_title_name = flattenText(Yii::app()->request->getPost("contact_title_name"));
        $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));

        //New Name
        $c_title_name = flattenText(Yii::app()->request->getPost("c_title_name"));
        $addsummary = '';
        $aViewUrls = array();
        $is_Active = 0;
        if ($IsActive) {
            $is_Active = 1;
        }
        $sresult = Contact_title::model()->findAllByAttributes(array('contact_title_id' => $contact_title_id));
        $sresultcount = count($sresult);
        if ((Permission::model()->hasGlobalPermission('superadmin', 'read') ||
                ($sresultcount > 0 && Permission::model()->hasGlobalPermission('contacts', 'update')))) {

            if ($c_title_name == '') {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing Contact Title"), $clang->gT("Could not modify Contact Title."), "warningheader", $clang->gT("Contact Title  name not be empty."), $this->getController()->createUrl('admin/contact_title/sa/modifycontact_title'), $clang->gT("Back"), array('contact_title_id' => $contact_title_id));
            } elseif (Contact_title::model()->findByAttributes(array('contact_title_name' => $c_title_name, 'IsActive' => $is_Active))) {
                $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Contact Title"), 'message' => $clang->gT("The Contact Title already exists."), 'class' => 'warningheader');
            } else {
                $oRecord = Contact_title::model()->findByPk($contact_title_id);
                $oRecord->contact_title_name = $this->escape($c_title_name);
                $oRecord->IsActive = $this->escape($is_Active);
                $uresult = $oRecord->save();    // store result of save in uresult

                if ($uresult) { // When saved successfully
                    Yii::app()->setFlashMessage($clang->gT("Contact title updated successfully"));
                    $this->getController()->redirect(array("admin/contact_title/index"));
                } else {   //Saving the user failed for some reason, message about email is not helpful here
                    // Username and/or email adress already exists.
                    $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing contact title"), $clang->gT("Could not modify contact title."), 'warningheader');
                }
            }
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $this->_renderWrappedTemplate('contacts/contact_title', $aViewUrls);
    }

    private function escape($str) {
        if (is_string($str)) {
            $str = $this->escape_str($str);
        } elseif (is_bool($str)) {
            $str = ($str === true) ? 1 : 0;
        } elseif (is_null($str)) {
            $str = 'NULL';
        }

        return $str;
    }

    private function escape_str($str, $like = FALSE) {
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $str[$key] = $this->escape_str($val, $like);
            }

            return $str;
        }

        // Escape single quotes
        $str = str_replace("'", "''", $this->remove_invisible_characters($str));

        return $str;
    }

    private function remove_invisible_characters($str, $url_encoded = TRUE) {
        $non_displayables = array();

        // every control character except newline (dec 10)
        // carriage return (dec 13), and horizontal tab (dec 09)

        if ($url_encoded) {
            $non_displayables[] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/'; // url encoded 16-31
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

        return $str;
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
    protected function _renderWrappedTemplate($sAction = 'contacts/contact_title', $aViewUrls = array(), $aData = array()) {
        $aData['display']['menu_bars']['contact_bars'] = true;
        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
