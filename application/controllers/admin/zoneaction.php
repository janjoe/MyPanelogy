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
class zoneaction extends Survey_Common_Action {

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
        $zonelist = getZoneandCountry();
        $countrylist = getCountry();
        $aData['row'] = 0;
        $aData['zonelist'] = $zonelist;
        $aData['countrylist'] = $countrylist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('region/zone', 'view_addzone', $aData);
    }

// added by brain-gaurang on 2014-02-28

    function addzone() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('Regions', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }

        $new_zone = flattenText(Yii::app()->request->getPost('new_zone'), false, true);
        $country_name = flattenText(Yii::app()->request->getPost('country_name'), false, true);

        $aData = array();
        if (empty($new_zone)) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Zone"), 'message' => $clang->gT("A zone was not supplied or the zone is invalid."), 'class' => 'warningheader');
        } elseif (empty($country_name)) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Zone"), 'message' => $clang->gT("Please select country"), 'class' => 'warningheader');
        } elseif (Zone::model()->findByAttributes(array('zone_Name' => $new_zone), 'country_id=:country_id', array(':country_id' => $country_name))) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Zone"), 'message' => $clang->gT("The Zone already exists."), 'class' => 'warningheader');
        } else {
            $iNewUID = Zone::model()->instZone($new_zone, $country_name);
            if ($iNewUID) {
                Yii::app()->setFlashMessage($clang->gT("Zone added successfully"));
                $this->getController()->redirect(array("admin/zone/index"));
            } else {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to add zone"), $clang->gT("The Zone already exists."), 'warningheader');
            }
        }

        $this->_renderWrappedTemplate('region/zone', $aViewUrls, $aData);
    }

    /**
     * Delete user
     */
    function delzone() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('Regions', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $zone_id = (int) Yii::app()->request->getPost("zone_id");
        $zone_name = flattenText(Yii::app()->request->getPost("zone_Name"));
        if ($zone_id) {
            if ($action == "delzone") {
                $dresult = Zone::model()->deleteZone($zone_id);
                if ($dresult) {
                    Yii::app()->setFlashMessage($clang->gT("Zone delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("Zone does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/zone/index"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete zone. Zone was not supplied."), 'error');
            $this->getController()->redirect(array("admin/zone/index"));
        }

        return $aViewUrls;
    }

    /**
     * Modify User
     */
    function modifyzone() {
        if (isset($_POST['zone_id'])) {
            $postuserid = (int) Yii::app()->request->getPost("zone_id");
            $sresult = Zone::model()->findAllByAttributes(array('zone_id' => $postuserid));
            $sresultcount = count($sresult);
            $aData['mur'] = $sresult;
            $this->_renderWrappedTemplate('region/zone', 'view_editzone', $aData);
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
    function modzone() {
        $clang = Yii::app()->lang;
        $zone_name = flattenText(Yii::app()->request->getPost("zone_name"));
        $z_name = flattenText(Yii::app()->request->getPost("z_name"));
        $zone_id = (int) Yii::app()->request->getPost("zone_id");
        $z_id = (int) Yii::app()->request->getPost("z_id");
        $c_id = (int) Yii::app()->request->getPost("c_id");
        $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
        $addsummary = '';
        $aViewUrls = array();
        $is_Active = 0;
        if ($IsActive) {
            $is_Active = 1;
        }
        $sresult = Zone::model()->findAllByAttributes(array('zone_id' => $zone_id));
        $sresultcount = count($sresult);

        if ((Permission::model()->hasGlobalPermission('superadmin', 'read') ||
                ($sresultcount > 0 && Permission::model()->hasGlobalPermission('Regions', 'update')))) {

            if ($z_name == '') {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing zone"), $clang->gT("Could not modify zone."), "warningheader", $clang->gT("Zone  name not be empty."), $this->getController()->createUrl('admin/zone/modifyzone'), $clang->gT("Back"), array('zone_id' => $zone_id));
            } elseif (Zone::model()->findByAttributes(array('zone_Name' => $z_name, 'IsActive' => $is_Active), 'country_id=:country_id', array(':country_id' => $c_id))) {
                $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Zone"), 'message' => $clang->gT("The Zone already exists."), 'class' => 'warningheader');
            } else {
                $oRecord = Zone::model()->findByPk($zone_id);
                $oRecord->zone_Name = $this->escape($z_name);
                $oRecord->country_id = $this->escape($c_id);
                $oRecord->IsActive = $this->escape($is_Active);
                $uresult = $oRecord->save();    // store result of save in uresult

                if ($uresult) { // When saved successfully
                    Yii::app()->setFlashMessage($clang->gT("Zone updated successfully"));
                    $this->getController()->redirect(array("admin/zone/index"));
                } else {   //Saving the user failed for some reason, message about email is not helpful here
// Username and/or email adress already exists.
                    $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing zone"), $clang->gT("Could not modify zone."), 'warningheader');
                }
            }
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        }
        $this->_renderWrappedTemplate('region/Zone', $aViewUrls);
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
        $url = (!empty($url)) ? $url : $this->getController()->createUrl('admin/zone/index');
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
    protected function _renderWrappedTemplate($sAction = 'region/zone', $aViewUrls = array(), $aData = array()) {
        $aData['display']['menu_bars']['country'] = true;
        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
