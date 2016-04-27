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
class companytypeaction extends Survey_Common_Action {

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
        $userlist = getContactType();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('contacts/company_type', 'view_addcompany_type', $aData);
    }

    // added by brain-gaurang on 2014-02-28

    function addcompany_type() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('contacts', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $new_contact_type = flattenText(Yii::app()->request->getPost('new_contact_type'));
        $company_type = flattenText(Yii::app()->request->getPost("company_type"));
        $istitle = flattenText(Yii::app()->request->getPost("istitle"));
        $Is_Title = 0;
        if ($istitle) {
            $Is_Title = 1;
        }
        $aData = array();
        $aViewUrls = array();
        if (empty($new_contact_type)) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Company Type"), 'message' => $clang->gT("A Company Type was not supplied or the Company Type is invalid."), 'class' => 'warningheader');
        } elseif (Company_type::model()->findByAttributes(array('company_type_name' => $new_contact_type, 'company_type' => $company_type))) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Company Type"), 'message' => $clang->gT("The Company Type already exists."), 'class' => 'warningheader');
        } else {
            $iNewUID = Company_type::model()->instContactType($new_contact_type, $company_type, $Is_Title);
            if ($iNewUID) {
                Yii::app()->setFlashMessage($clang->gT("Company Type added successfully"));
                $this->getController()->redirect(array("admin/company_type"));
            } else {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to add Company Type"), $clang->gT("The Company Type already exists."), 'warningheader');
            }
        }

        $this->_renderWrappedTemplate('contacts/company_type', $aViewUrls, $aData);
    }

    /**
     * Delete user
     */
    function delcompany_type() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('contacts', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $company_type_id = (int) Yii::app()->request->getPost("company_type_id");
        $company_type_name = flattenText(Yii::app()->request->getPost("company_type_name"));
        if ($company_type_id) {
            if ($action == "delcompany_type") {
                $dresult = Company_type::model()->deletecontacttype($company_type_id);
                if ($dresult) {
                    Yii::app()->setFlashMessage($clang->gT("Company Type delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("Company Type does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/company_type/index"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete Company Type. Company Type was not supplied."), 'error');
            $this->getController()->redirect(array("admin/company_type/index"));
        }

        return $aViewUrls;
    }

    /**
     * Modify User
     */
    function modifycompany_type() {
        if (isset($_POST['company_type_id'])) {
            $company_type_id = (int) Yii::app()->request->getPost("company_type_id");
            $sresult = Company_type::model()->findAll(array('condition' => 'company_type_id = ' . $company_type_id));
            $sresultcount = count($sresult);
            $aData['mur'] = $sresult;
            $this->_renderWrappedTemplate('contacts/company_type', 'view_editcompany_type', $aData);
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
    function modcompany_type() {
        $clang = Yii::app()->lang;
        // old Details
        $company_type_id = (int) Yii::app()->request->getPost("company_type_id");
        $company_type_name = flattenText(Yii::app()->request->getPost("company_type_name"));
        $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
        $IsTitle = flattenText(Yii::app()->request->getPost("IsTitle"));
        $company_type = flattenText(Yii::app()->request->getPost("company_type"));

        //New Name
        $c_type_name = flattenText(Yii::app()->request->getPost("c_type_name"));
        $addsummary = '';
        $aViewUrls = array();

        // update defult value
        $is_Active = $Is_Title = 0;
        if ($IsActive) {
            $is_Active = 1;
        }
        if ($IsTitle) {
            $Is_Title = 1;
        }
        $sresult = Company_type::model()->findAllByAttributes(array('company_type_id' => $company_type_id));
        $sresultcount = count($sresult);
        if ((Permission::model()->hasGlobalPermission('superadmin', 'read') ||
                ($sresultcount > 0 && Permission::model()->hasGlobalPermission('contacts', 'update')))) {

            if ($c_type_name == '') {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing Company Type"), $clang->gT("Could not modify Company Type."), "warningheader", $clang->gT("Company Type  name not be empty."), $this->getController()->createUrl('admin/company_type/sa/modifycontact_group'), $clang->gT("Back"), array('company_type_id' => $company_type_id));
            } elseif (Company_type::model()->findByAttributes(array('company_type_name' => $c_type_name, 'IsActive' => $is_Active, 'company_type' => $company_type, 'Istitle' => $Is_Title))) {
                $aViewUrls['message'] = array('title' => $clang->gT("Failed to Edit Company Type"), 'message' => $clang->gT("The Company Type already exists."), 'class' => 'warningheader');
            } else {
                $oRecord = Company_type::model()->findByPk($company_type_id);
                $oRecord->company_type_name = $this->escape($c_type_name);
                $oRecord->company_type = $this->escape($company_type);
                $oRecord->Istitle = $this->escape($Is_Title);
                $oRecord->IsActive = $this->escape($is_Active);
                $uresult = $oRecord->save();    // store result of save in uresult

                if ($uresult) { // When saved successfully
                    Yii::app()->setFlashMessage($clang->gT("Company Type updated successfully"));
                    $this->getController()->redirect(array("admin/company_type/index"));
                } else {
                    $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing contact type"), $clang->gT("Could not modify contact type."), 'warningheader');
                }
            }
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $this->_renderWrappedTemplate('contacts/company_type', $aViewUrls);
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
        $url = (!empty($url)) ? $url : $this->getController()->createUrl('admin/company_type/index');
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
    protected function _renderWrappedTemplate($sAction = 'contacts/company_type', $aViewUrls = array(), $aData = array()) {
        $aData['display']['menu_bars']['contact_bars'] = true;
        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
