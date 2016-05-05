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
class Cityaction extends Survey_Common_Action {

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
        $aData['row'] = 0;
        $country = Country::model()->findAll();
        $countrylist = CHtml::listData($country, 'country_id', 'country_name');
        $firstcountry = array_keys($countrylist);
        if (count($firstcountry)) {
            $fc = $firstcountry[0];
        } else {
            $fc = 0;
        }
        if (isset(Yii::app()->request->cookies['Country'])) {
            if (isset($_POST['country_name'])) {
                $country_id = (int) $_POST['country_name'];
            } else {
                $country_id = Yii::app()->request->cookies['Country']->value;
            }
        } else {
            if (isset($_POST['country_name'])) {
                $country_id = (int) $_POST['country_name'];
            } else {
                $country_id = $fc;
            }
        }
        Yii::app()->request->cookies['Country'] = new CHttpCookie('Country', $country_id);
        $userlist = getCitylistcountry($country_id);
        $aData['usr_arr'] = $userlist;
        $aData['country_id'] = $country_id;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('region/city', 'view_addcity', $aData);
    }

    function selectstate() {
        //$data = State::model()->findAll('zone_id=:zone_id', array(':zone_id' => (int) $_POST['zonelist']));
        if (isset($_POST['isactive'])) {
            $data = State::model()->isactive()->findAllByAttributes(array('zone_id' => (int) $_POST['zonelist']));
        } else {
            $data = State::model()->findAllByAttributes(array('zone_id' => (int) $_POST['zonelist']));
        }
        $data = CHtml::listData($data, 'state_id', 'state_Name');
        if (count($data)) {
            foreach ($data as $value => $name) {
                //echo '<option value=' . $value . ' >' . $name . '</option>';
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            echo CHtml::tag('option', array('value' => '0'), CHtml::encode('No State available'), true);
        }
    }

    // added by brain-gaurang on 2014-02-28

    function addcity() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('Regions', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $new_city = flattenText(Yii::app()->request->getPost('new_city'), false, true);
        $state_id = flattenText(Yii::app()->request->getPost('statelist'));
        $country_id = (int) Yii::app()->request->getPost("country_name");
        $zone_id = (int) Yii::app()->request->getPost("zonelist");

        $aData = array();
        if (empty($new_city)) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add City"), 'message' => $clang->gT("A city was not supplied or the city is invalid."), 'class' => 'warningheader');
        } elseif ($country_id == 0) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add City"), 'message' => $clang->gT("A country was not supplied or the country is invalid."), 'class' => 'warningheader');
        } elseif ($zone_id == '0') {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add City"), 'message' => $clang->gT("A zone was not supplied or the zone is invalid."), 'class' => 'warningheader');
        } elseif ($state_id == 'stateselect') {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add City"), 'message' => $clang->gT("A state was not supplied or the state is invalid."), 'class' => 'warningheader');
        } elseif ($state_id == '0') {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add City"), 'message' => $clang->gT("A state was not supplied or the state is invalid."), 'class' => 'warningheader');
        } elseif (City::model()->findByAttributes(array('city_Name' => $new_city, 'state_id' => $state_id))) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to add City"), 'message' => $clang->gT("The City already exists."), 'class' => 'warningheader');
        } else {
            $iNewUID = City::model()->instCity($new_city, $state_id);
            if ($iNewUID) {
                Yii::app()->setFlashMessage($clang->gT("City added successfully"));
                $this->getController()->redirect(array("admin/city/index"));
            } else {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to add city"), $clang->gT("The City already exists."), 'warningheader');
            }
        }

        $this->_renderWrappedTemplate('region/city', $aViewUrls, $aData);
    }

    /**
     * Delete user
     */
    function delcity() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('Regions', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $city_id = (int) Yii::app()->request->getPost("city_id");
        $city_name = flattenText(Yii::app()->request->getPost("city_name"));
        if ($city_id) {
            if ($action == "delcity") {
                $dresult = City::model()->deletecity($city_id);
                if ($dresult) {
                    Yii::app()->setFlashMessage($clang->gT("City delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("City does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/city/index"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete city. City was not supplied."), 'error');
            $this->getController()->redirect(array("admin/city/index"));
        }

        return $aViewUrls;
    }

    /**
     * Modify User
     */
    function modifycity() {
        if (isset($_POST['city_id'])) {
            $city_id = (int) Yii::app()->request->getPost("city_id");
            //$sresult = City::model()->findAllByAttributes(array('city_id' => $city_id));
            $sresult = getCitylistcountryUpdate($city_id);
            $sresultcount = count($sresult);
            App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
            App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
            $aData['row'] = 0;
            $country_id = Yii::app()->request->cookies['Country']->value;
            $userlist = getCitylistcountry($country_id);
            $aData['usr_arr'] = $userlist;
            $aData['country_id'] = $country_id;
            $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
            $aData['mur'] = $sresult;
            $this->_renderWrappedTemplate('region/city', 'view_addcity', $aData);
            return;
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/index"));
    }

    /**
     * Modify User POST
     */
    function modcity() {
        $clang = Yii::app()->lang;
        $city_id = (int) Yii::app()->request->getPost("city_id");

        // old details
        $city_Name = flattenText(Yii::app()->request->getPost("city_Name"));
        $country_id = (int) Yii::app()->request->getPost("country_id");
        $zone_id = (int) Yii::app()->request->getPost("zone_id");
        $state_id = (int) Yii::app()->request->getPost("state_id");

        // new details
        $c_id = (int) Yii::app()->request->getPost("country_name");
        $z_id = (int) Yii::app()->request->getPost("zonelist");
        $s_id = (int) Yii::app()->request->getPost("statelist");
        $c_name = flattenText(Yii::app()->request->getPost("c_name"));

        $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
        $addsummary = '';
        $aViewUrls = array();
        $is_Active = 0;
        if ($IsActive) {
            $is_Active = 1;
        }

        $sresult = City::model()->findAllByAttributes(array('city_id' => $city_id));
        $sresultcount = count($sresult);

        if ((Permission::model()->hasGlobalPermission('superadmin', 'read') ||
                ($sresultcount > 0 && Permission::model()->hasGlobalPermission('Regions', 'update')))) {

            if ($c_name == '') {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing city"), $clang->gT("Could not modify city."), "warningheader", $clang->gT("City  name not be empty."), $this->getController()->createUrl('admin/city/modifycity'), $clang->gT("Back"), array('city_id' => $city_id));
            } elseif ($country_id == 0) {
                $aViewUrls['message'] = array('title' => $clang->gT("Editing city"), 'message' => $clang->gT("A country was not supplied or the country is invalid."), 'class' => 'warningheader');
            } elseif ($state_id == 'stateselect') {
                $aViewUrls['message'] = array('title' => $clang->gT("Editing city"), 'message' => $clang->gT("A state was not supplied or the state is invalid."), 'class' => 'warningheader');
            } elseif ($state_id == '0') {
                $aViewUrls['message'] = array('title' => $clang->gT("Editing city"), 'message' => $clang->gT("A state was not supplied or the state is invalid."), 'class' => 'warningheader');
            } elseif (City::model()->findByAttributes(array('city_Name' => $c_name, 'state_id' => $s_id, 'IsActive' => $is_Active))) {
                $aViewUrls['message'] = array('title' => $clang->gT("Editing city"), 'message' => $clang->gT("The City already exists."), 'class' => 'warningheader');
            } else {
                $oRecord = City::model()->findByPk($city_id);
                $oRecord->city_Name = $this->escape($c_name);
                $oRecord->state_id = $this->escape($s_id);
                $oRecord->IsActive = $this->escape($is_Active);
                $uresult = $oRecord->save();    // store result of save in uresult

                if ($uresult) { // When saved successfully
                    Yii::app()->setFlashMessage($clang->gT("City updated successfully"));
                    $this->getController()->redirect(array("admin/city/index"));
                } else {
                    $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing city"), $clang->gT("Could not modify city."), 'warningheader');
                }
            }
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        }
        $this->_renderWrappedTemplate('region/City', $aViewUrls);
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
        $url = (!empty($url)) ? $url : $this->getController()->createUrl('admin/city/index');
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
    protected function _renderWrappedTemplate($sAction = 'region/city', $aViewUrls = array(), $aData = array()) {
        $aData['display']['menu_bars']['country'] = true;
        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
