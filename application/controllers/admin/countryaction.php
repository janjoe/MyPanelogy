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
class countryaction extends Survey_Common_Action {

    function __construct($controller, $id) {
        parent::__construct($controller, $id);

        Yii::app()->loadHelper('database');
    }

    /**
     * Show users table
     */
    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('Regions', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = getCountry();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('region/country', 'view_addcountry', $aData);
    }

    // added by brain-gaurang on 2014-02-28

    function addcountry() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('Regions', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin"));
        }
        $new_region = flattenText(Yii::app()->request->getPost('new_country'));
        $continent = flattenText(Yii::app()->request->getPost('continent'));
        $aData = array();
        $aViewUrls = array();
        if (empty($new_region)) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Country"), 'message' => $clang->gT("A country was not supplied or the country is invalid."), 'class' => 'warningheader');
        } elseif (Country::model()->find("country_name=:country_name", array(':country_name' => $new_region))) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Country"), 'message' => $clang->gT("The Country already exists."), 'class' => 'warningheader');
        } else {
            $iNewUID = Country::model()->instCountry($new_region, $continent);
            if ($iNewUID) {
                Yii::app()->setFlashMessage($clang->gT("Country added successfully"));
                $this->getController()->redirect(array("admin/country/index"));
            } else {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to add Country"), $clang->gT("The Country already exists."), 'warningheader');
            }
        }

        $this->_renderWrappedTemplate('region/country', $aViewUrls, $aData);
    }

    /**
     * Delete user
     */
    function delcountry() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('Regions', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $countries_id = (int) Yii::app()->request->getPost("country_id");
        $countries_name = flattenText(Yii::app()->request->getPost("country_name"));
        if ($countries_id) {
            if ($action == "delcountry") {
                $dresult = Country::model()->deleteCountry($countries_id);
                if ($dresult) {
                    Yii::app()->setFlashMessage($clang->gT("Country delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("Country does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/country/index"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete country. Country was not supplied."), 'error');
            $this->getController()->redirect(array("admin/country/index"));
        }

        return $aViewUrls;
    }

    /**
     * Modify User
     */
    function modifycountry() {
        if (isset($_POST['country_id'])) {
            $postuserid = (int) Yii::app()->request->getPost("country_id");
            $sresult = Country::model()->findAllByAttributes(array('country_id' => $postuserid));
            $sresultcount = count($sresult);
            $aData['mur'] = $sresult;
            $this->_renderWrappedTemplate('region/country', 'view_editcountry', $aData);
            return;
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin"));
        //echo accessDenied('modifyuser');
        //die();
    }

    /**
     * Modify User POST
     */
    function modcountry() {
        $clang = Yii::app()->lang;
        $countries_id = (int) Yii::app()->request->getPost("country_id");
        $countries_name = flattenText(Yii::app()->request->getPost("country_name"));
        $continent_name = flattenText(Yii::app()->request->getPost("continent_name"));
        $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
        $c_name = flattenText(Yii::app()->request->getPost("c_name"));
        $addsummary = '';
        $aViewUrls = array();
        $is_Active = 0;
        if ($IsActive) {
            $is_Active = 1;
        }
        $sresult = Country::model()->findAllByAttributes(array('country_id' => $countries_id));
        $sresultcount = count($sresult);
        if ((Permission::model()->hasGlobalPermission('superadmin', 'read') ||
                ($sresultcount > 0 && Permission::model()->hasGlobalPermission('Regions', 'update')))) {

            if ($c_name == '') {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing country"), $clang->gT("Could not modify country."), "warningheader", $clang->gT("Country  name not be empty."), $this->getController()->createUrl('admin/country/modifycountry'), $clang->gT("Back"), array('country_id' => $countries_id));
            } elseif (Country::model()->findByAttributes(array('country_name' => $c_name, 'continent' => $continent_name, 'IsActive' => $is_Active))) {
                $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Contry"), 'message' => $clang->gT("The Country already exists."), 'class' => 'warningheader');
            } else {
                $oRecord = Country::model()->findByPk($countries_id);
                $oRecord->country_name = $this->escape($c_name);
                $oRecord->continent = $this->escape($continent_name);
                $oRecord->IsActive = $this->escape($is_Active);
                $uresult = $oRecord->save();    // store result of save in uresult

                if ($uresult) { // When saved successfully
                    Yii::app()->setFlashMessage($clang->gT("Country updated successfully"));
                    $this->getController()->redirect(array("admin/country/index"));
                } else {   //Saving the user failed for some reason, message about email is not helpful here
                    // Username and/or email adress already exists.
                    $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing country"), $clang->gT("Could not modify country."), 'warningheader');
                }
            }
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $this->_renderWrappedTemplate('region/country', $aViewUrls);
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
        $url = (!empty($url)) ? $url : $this->getController()->createUrl('admin/country/index');
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
    protected function _renderWrappedTemplate($sAction = 'region/country', $aViewUrls = array(), $aData = array()) {
        $aData['display']['menu_bars']['country'] = true;
        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
