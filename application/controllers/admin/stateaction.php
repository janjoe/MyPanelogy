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
class stateaction extends Survey_Common_Action {

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
        $userlist = getStateandZoneandCountry();
        $countrylist = getCountry();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('region/state', 'view_addstate', $aData);
    }

// added by brain-gaurang on 2014-02-28

    function addstate() {
        $clang = Yii::app()->lang;

        if (!Permission::model()->hasGlobalPermission('Regions', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $new_state = flattenText(Yii::app()->request->getPost('new_state'), false, true);
        $country_name = (int) Yii::app()->request->getPost("country_name");
        $zonelist = Yii::app()->request->getPost("zonelist");
        $aData = array();
        if (empty($new_state)) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add State"), 'message' => $clang->gT("A state was not supplied or the state is invalid."), 'class' => 'warningheader');
        } elseif ($country_name == 0) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add State"), 'message' => $clang->gT("A country was not supplied or the country is invalid."), 'class' => 'warningheader');
        } elseif ($zonelist == 'zoneselect') {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add State"), 'message' => $clang->gT("A zone was not supplied or the zone is invalid."), 'class' => 'warningheader');
        } elseif ($zonelist == '0') {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add State"), 'message' => $clang->gT("A zone was not supplied or the zone is invalid."), 'class' => 'warningheader');
        } elseif (State::model()->findByAttributes(array('state_Name' => $new_state, 'zone_id' => $zonelist))) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add State"), 'message' => $clang->gT("The State already exists."), 'class' => 'warningheader');
        } else {
            $iNewUID = State::model()->instState($new_state, $zonelist);
            if ($iNewUID) {
                Yii::app()->setFlashMessage($clang->gT("State added successfully"));
                $this->getController()->redirect(array("admin/state/index"));
            } else {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to add state"), $clang->gT("The state already exists."), 'warningheader');
            }
        }

        $this->_renderWrappedTemplate('region/state', $aViewUrls, $aData);
    }

    function selectzone() {
        //$data = Zone::model()->findAll('country_id=:country_id', array(':country_id' => (int) $_POST['country_Name']));
        if (isset($_POST['isactive'])) {
            $data = Zone::model()->isactive()->findAllByAttributes(array('country_id' => (int) $_POST['country_name']));
        } else {
            $data = Zone::model()->findAllByAttributes(array('country_id' => (int) $_POST['country_name']));
        }
        $data = CHtml::listData($data, 'zone_id', 'zone_Name');
        if (count($data)) {
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            echo CHtml::tag('option', array('value' => '0'), CHtml::encode('No Zone available'), true);
        }
    }

    /**
     * Delete user
     */
    function delstate() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('Regions', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $state_id = (int) Yii::app()->request->getPost("state_id");
        $state_name = flattenText(Yii::app()->request->getPost("state_Name"));
        if ($state_id) {
            if ($action == "delstate") {
                $dresult = State::model()->deleteState($state_id);
                if ($dresult) {
                    Yii::app()->setFlashMessage($clang->gT("State delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("State does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/state/index"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete state. State was not supplied."), 'error');
            $this->getController()->redirect(array("admin/state/index"));
        }

        return $aViewUrls;
    }

    /**
     * Modify User
     */
    function modifystate() {
        if (isset($_POST['state_id'])) {
            $postuserid = (int) Yii::app()->request->getPost("state_id");
            $sresult = getStateandZoneandCountry($postuserid);
            //$sresult = State::model()->findAllByAttributes(array('state_id' => $postuserid));
            //$sresultcount = count($sresult);
            $aData['mur'] = $sresult;
            $this->_renderWrappedTemplate('region/state', 'view_editstate', $aData);
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
    function modstate() {
        $clang = Yii::app()->lang;
        $state_id = (int) Yii::app()->request->getPost("state_id");

        // old details

        $state_Name = flattenText(Yii::app()->request->getPost("state_Name"));
        $zone_id = (int) Yii::app()->request->getPost("zone_id");

        // new details

        $s_name = flattenText(Yii::app()->request->getPost("s_name"));
        $z_id = (int) Yii::app()->request->getPost("zonelistmod");
        $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
        $addsummary = '';
        $aViewUrls = array();
        $is_Active = 0;
        if ($IsActive) {
            $is_Active = 1;
        }
        //$sresult = State::model()->findAllByAttributes(array('state_id' => $state_id));
        $sresult = getStateandZoneandCountry($state_id);
        $sresultcount = count($sresult);

        if ((Permission::model()->hasGlobalPermission('superadmin', 'read') ||
                ($sresultcount > 0 && Permission::model()->hasGlobalPermission('Regions', 'update')))) {

            if ($s_name == '') {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing state"), $clang->gT("Could not modify state."), "warningheader", $clang->gT("State  name not be empty."), $this->getController()->createUrl('admin/state/modifystate'), $clang->gT("Back"), array('state_id' => $state_id));
            } elseif ($z_id == 'zoneselect') {
                $aViewUrls['message'] = array('title' => $clang->gT("Editing state"), 'message' => $clang->gT("A zone was not supplied or the zone is invalid."), 'class' => 'warningheader');
            } elseif ($z_id == '0') {
                $aViewUrls['message'] = array('title' => $clang->gT("Editing state"), 'message' => $clang->gT("A zone was not supplied or the zone is invalid."), 'class' => 'warningheader');
            } elseif (State::model()->findByAttributes(array('state_Name' => $s_name, 'zone_id' => $z_id, 'IsActive' => $is_Active))) {
                $aViewUrls['message'] = array('title' => $clang->gT("Editing state"), 'message' => $clang->gT("The State already exists."), 'class' => 'warningheader');
            } else {
                $oRecord = State::model()->findByPk($state_id);
                $oRecord->state_Name = $this->escape($s_name);
                $oRecord->zone_id = $this->escape($z_id);
                $oRecord->IsActive = $this->escape($is_Active);
                $uresult = $oRecord->save();    // store result of save in uresult

                if ($uresult) { // When saved successfully
                    Yii::app()->setFlashMessage($clang->gT("State updated successfully"));
                    $this->getController()->redirect(array("admin/state/index"));
                } else {
                    $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing state"), $clang->gT("Could not modify state."), 'warningheader');
                }
            }
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        }
        $this->_renderWrappedTemplate('region/state', $aViewUrls);
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
        $url = (!empty($url)) ? $url : $this->getController()->createUrl('admin/state/index');
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
    protected function _renderWrappedTemplate($sAction = 'region/state', $aViewUrls = array(), $aData = array()) {
        $aData['display']['menu_bars']['country'] = true;
        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
