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
class message extends Survey_Common_Action {

    function __construct($controller, $id) {
        parent::__construct($controller, $id);

        Yii::app()->loadHelper('database');
    }

    /**
     * Show users table
     */
    public function index() {
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        if (Permission::model()->hasGlobalPermission('superadmin', 'read')) {
            $msglist = Supoort_center::model()->findAll(array('condition' => 'parent = 0'));
        } else {
            $msglist = Supoort_center::model()->findAll(array('condition' => 'email_to = ' . Yii::app()->session['loginID'] . ' AND parent = 0'));
        }
        //$msglist = getCountry();
        $aData['row'] = 0;
        $aData['msglist'] = $msglist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('message', 'view_addmessage', $aData);
    }

    function message_history() {
        $clang = Yii::app()->lang;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->getController()->renderPartial('/admin/message/' . 'view_message_history', $aData);
        }
    }

    public function message_status() {
        $status = $_GET['s'];
        $id = $_GET['id'];
        $oRecord = Supoort_center::model()->findByPk($id);
        $oRecord->status = $status;
        $Panel_id = $oRecord->save();
        $arr = explode(Yii::app()->user->returnUrl.'?r=', Yii::app()->request->urlReferrer);
        //$url = $arr[1];//Remove
        //$this->getController()->redirect(array($url));//Remove
        $this->getController()->redirect(array('admin/message/index'));//Add
    }

    // added by brain-gaurang on 2014-02-28

    function addmessage() {
        $clang = Yii::app()->lang;
//        if (!Permission::model()->hasGlobalPermission('Regions', 'create')) {
//            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
//            $this->getController()->redirect(array("admin/country/index"));
//        }

        $email_to = (int) Yii::app()->request->getPost("email_to");
        $subject = flattenText(Yii::app()->request->getPost('subject'));
        $message = flattenText(Yii::app()->request->getPost('message'));
        $parent = flattenText(Yii::app()->request->getPost('parent'));
        $chat = flattenText(Yii::app()->request->getPost('chat'));

        $aData = array();
        $aViewUrls = array();
        if (empty($email_to)) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to send message"), 'message' => $clang->gT("Message was not supplied or the Message is invalid."), 'class' => 'warningheader');
        } elseif (empty($subject)) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to send message"), 'message' => $clang->gT("Subject was not supplied or the Subject is invalid."), 'class' => 'warningheader');
        } elseif (empty($message)) {
            $aViewUrls['message'] = array('title' => $clang->gT("Failed to send message"), 'message' => $clang->gT("Message was not supplied or the Message is invalid."), 'class' => 'warningheader');
        } else {
            $NewMessage = Supoort_center::model()->instAdminMessage($email_to, $subject, $message, $parent, $chat);
            if ($NewMessage) {
                Yii::app()->setFlashMessage($clang->gT("message send successfully"));
                $this->getController()->redirect(array("admin/message/index"));
            } else {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to send message"), $clang->gT("Error in sending message."), 'warningheader');
            }
        }

        $this->_renderWrappedTemplate('message', $aViewUrls, $aData);
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
        $url = (!empty($url)) ? $url : $this->getController()->createUrl('admin/message/index');
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

    protected function _renderWrappedTemplate($sAction = 'message', $aViewUrls = array(), $aData = array()) {
        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
