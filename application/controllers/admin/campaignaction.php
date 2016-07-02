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
class Campaignaction extends Survey_Common_Action {

    /**
     * Load viewing of a user group screen.
     * @param bool $ugid
     * @param array|bool $header (type=success, warning)(message=localized message)
     * @return void
     */
    public function index() {

        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('Campaign', 'read')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = Campaign::model()->getRecords();
        // echo '<pre>'; print_r($userlist); exit;
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('campaign/campaign', 'view_campaign', $aData);
    }

    /*
    * action for the add form and post new form of campaign
    */

    public function addcampaign() {

        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('Campaign', 'create')) {

            if ($action == "addcamp") {
               //print_r($_POST); exit;
                $iUserID = Yii::app()->user->getId();
                $_POST['add_id'] = $iUserID;
                
                 
                $NewPage = Campaign::model()->instCamp($_POST);
                if($NewPage){

                    Yii::app()->setFlashMessage($clang->gT("Campaign added successfully"));
                    $this->getController()->redirect(array("admin/campaign/index"));
                }
                Yii::app()->setFlashMessage($clang->gT("Something wrong"));
            } else {
                $aViewUrls = 'view_addcampaign';
            }
        } else {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $aData['campaign_source'] = Campaignsource::model()->getRecords();
        $aData['campaign_source_type'] = Campaignsourcetype::model()->getRecords();
        $aData['campaign_status'] = Campaignstatus::model()->getRecords();
        $aData['page_data'] = getPagelist();
       // echo '<pre>';
        //print_r($aData['page_data']); exit;
        $this->_renderWrappedTemplate('campaign/campaign', $aViewUrls, $aData);
    }

    /**
     * Campaign edit action
    */
    
    function modifycampaign() {
        if (isset($_POST['cp_id'])) {
            $page_id = (int) Yii::app()->request->getPost("cp_id");
            $sresult =  Campaign::model()->getsingleRecord($page_id);
            //echo '<pre>'; print_r($sresult); exit;
            $aData['page_id'] = $page_id;
            $aData['mur'] = $sresult;
            $aData['campaign_source'] = Campaignsource::model()->getRecords();
            $aData['campaign_source_type'] = Campaignsourcetype::model()->getRecords();
            $aData['campaign_status'] = Campaignstatus::model()->getRecords();
            $aData['page_data'] = getPagelist();
            
            $this->_renderWrappedTemplate('campaign/campaign', 'view_editcampaign', $aData);
            return;
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/index"));
    }


    /**
     * update campaign
     */
    function modcampaign() {
        $clang = Yii::app()->lang;
        
        $cp_id = flattenText($_POST['cp_id'], false, true, 'UTF-8', true);
        
       
        if ( Permission::model()->hasGlobalPermission('superadmin', 'read') ||  Permission::model()->hasGlobalPermission('Campaign', 'update')) {

            if(!empty($_POST) && $cp_id != ''){
                
                $NewPage = Campaign::model()->updateRecords($_POST, $cp_id);

                if($NewPage){

                    Yii::app()->setFlashMessage($clang->gT("Campaign Update Successfully"));
                    $this->getController()->redirect(array("admin/campaign/index"));
                }
                Yii::app()->setFlashMessage($clang->gT("Something wrong"));     
            }
            
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        Yii::app()->setFlashMessage($clang->gT("Something wrong"));        
        $this->getController()->redirect(array("admin/campaign/index"));
    }


    /**
     * start campaing source code.
    */

    /*
    * action for the list of campaign sources
    */

    public function campaignsource() {

        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('Campaign', 'read')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = Campaignsource::model()->getRecords();
        
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;


        foreach ($aData['usr_arr'] as $key => $value) {
            if($value ['created_by'] != '')
            {
               $userarray= User::model()->getName($value['created_by']);

               if(!empty($userarray))
                    $aData['usr_arr'][$key]['created_by'] = $userarray[0]['full_name'];
                
            }
            if($value ['edited_by'] != '')
            {
               $userarray= User::model()->getName($value['edited_by']);

               if(!empty($userarray))
                   $aData['usr_arr'][$key]['edited_by'] = $userarray[0]['full_name'];
                
            }
        }


        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('campaign/campaign', 'view_campaignsource', $aData);
    }

    /*
    * action for the add form and post new form of campaign sources
    */

    public function addcampaignsource() {

        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('Campaign', 'create')) {

            if ($action == "addcamp") {
               // print_r($_POST); exit;
                $iUserID = Yii::app()->user->getId();
                $_POST['add_id'] = $iUserID;
                
                 
                $NewPage = Campaignsource::model()->instCamp($_POST);
                if($NewPage){

                    Yii::app()->setFlashMessage($clang->gT("Source added successfully"));
                    $this->getController()->redirect(array("admin/campaign/campaignsource"));
                }
                Yii::app()->setFlashMessage($clang->gT("Something wrong"));
            } else {
                $aViewUrls = 'view_addcampaignsource';
            }
        } else {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        
        $this->_renderWrappedTemplate('campaign/campaign', $aViewUrls, $aData);
    }

    /**
     * Campaign source edit action
    */
    
    function modifycampaignsource() {
        if (isset($_POST['cmp_id'])) {
            $page_id = (int) Yii::app()->request->getPost("cmp_id");
            $sresult =  Campaignsource::model()->getRecords('cmp_id='.$page_id);
            //echo '<pre>'; print_r($sresult); exit;
            $aData['page_id'] = $page_id;
            $aData['mur'] = $sresult;
            
            $this->_renderWrappedTemplate('campaign/campaign', 'view_editcampaignsource', $aData);
            return;
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/index"));
    }


    /**
     * update campaign source
     */
    function modcampaignsource() {
        $clang = Yii::app()->lang;
        //print_r($_POST); 
        $cmp_id = flattenText($_POST['cmp_id'], false, true, 'UTF-8', true);
        //$source_name = flattenText($_POST['source_name'], false, true, 'UTF-8', true);
        //$source_code = flattenText($_POST['source_code'], false, true, 'UTF-8', true);
        //$source_notes = flattenText($_POST['source_notes'], false, true, 'UTF-8', true);
        $iUserID = Yii::app()->user->getId();
        $_POST['edit_id'] = $iUserID;
       
        if ( Permission::model()->hasGlobalPermission('superadmin', 'read') ||  Permission::model()->hasGlobalPermission('Campaign', 'update')) {

            if(!empty($_POST) && $cmp_id != ''){
                
                $NewPage = Campaignsource::model()->updateRecords($_POST, $cmp_id);

                if($NewPage){

                    Yii::app()->setFlashMessage($clang->gT("Source Update Successfully"));
                    $this->getController()->redirect(array("admin/campaign/campaignsource"));
                }
                Yii::app()->setFlashMessage($clang->gT("Something wrong"));
                 
            }
            
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        
        $this->getController()->redirect(array("admin/campaign/campaignsource"));
    }



    /**
     * end campaing source code.
    */



    /**
     * start campaing source Type code.
    */

    
    public function campaignsourcetype() {

        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('Campaign', 'read')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = Campaignsourcetype::model()->getRecords();
         //echo '<pre>'; print_r($userlist); exit;
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        //$aData['display']['menu_bars']['sourcetype_bars'] = true;
        $this->_renderWrappedTemplate('campaign/campaign', 'view_campaignsourcetype', $aData);
    }

    function modifycampaignsourcetype() {
        if (isset($_POST['cst_id'])) {
            $page_id = (int) Yii::app()->request->getPost("cst_id");
            $sresult =  Campaignsourcetype::model()->getRecords('cst_id='.$page_id);
            //echo '<pre>'; print_r($sresult); exit;
            $aData['page_id'] = $page_id;
            $aData['mur'] = $sresult;
             
            $this->_renderWrappedTemplate('campaign/campaign', 'view_editcampaignsourcetype', $aData);
            return;
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/index"));
    }

    function modcampaignsourcetype() {
        $clang = Yii::app()->lang;
        
        $cst_id = flattenText($_POST['cst_id'], false, true, 'UTF-8', true);
       
        if ( Permission::model()->hasGlobalPermission('superadmin', 'read') ||  Permission::model()->hasGlobalPermission('Campaign', 'update')) {

            if(!empty($_POST) && $cst_id != ''){
                
                $NewPage = Campaignsourcetype::model()->updateRecords($_POST, $cst_id);

                if($NewPage){

                    Yii::app()->setFlashMessage($clang->gT("Source Type Update Successfully"));
                    $this->getController()->redirect(array("admin/campaign/campaignsourcetype"));
                }
                Yii::app()->setFlashMessage($clang->gT("Something wrong"));
                 
            }
            
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
       
        $this->getController()->redirect(array("admin/campaign/campaignsourcetype"));
    }

    public function addcampaignsourcetype() {

        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('Campaign', 'create')) {

            if ($action == "addcampsourcetype") {
                                
                $NewPage = Campaignsourcetype::model()->instCamp($_POST);
                if($NewPage){

                    Yii::app()->setFlashMessage($clang->gT("Source Type added successfully"));
                    $this->getController()->redirect(array("admin/campaign/campaignsourcetype"));
                }
                Yii::app()->setFlashMessage($clang->gT("Something wrong"));
            } else {
                $aViewUrls = 'view_addcampaignsourcetype';
            }
        } else {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
       
        $this->_renderWrappedTemplate('campaign/campaign', $aViewUrls, $aData);
    }

    /**
     * end campaing source Type code.
    */


    /**
     * start campaing status code.
    */

    
    public function campaignstatus() {

        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('Campaign', 'read')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = Campaignstatus::model()->getRecords();
         //echo '<pre>'; print_r($userlist); exit;
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        //$aData['display']['menu_bars']['status_bars'] = true;
        $this->_renderWrappedTemplate('campaign/campaign', 'view_campaignstatus', $aData);
    }

    function modifycampaignstatus() {
        if (isset($_POST['cs_id'])) {
            $page_id = (int) Yii::app()->request->getPost("cs_id");
            $sresult =  Campaignstatus::model()->getRecords('cs_id='.$page_id);
            //echo '<pre>'; print_r($sresult); exit;
            $aData['page_id'] = $page_id;
            $aData['mur'] = $sresult;
             
            $this->_renderWrappedTemplate('campaign/campaign', 'view_editcampaignstatus', $aData);
            return;
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/index"));
    }

    function modcampaignstatus() {
        $clang = Yii::app()->lang;
        
        $cs_id = flattenText($_POST['cs_id'], false, true, 'UTF-8', true);
       
        if ( Permission::model()->hasGlobalPermission('superadmin', 'read') ||  Permission::model()->hasGlobalPermission('Campaign', 'update')) {

            if(!empty($_POST) && $cs_id != ''){
                
                $NewPage = Campaignstatus::model()->updateRecords($_POST, $cs_id);

                if($NewPage){

                    Yii::app()->setFlashMessage($clang->gT("Stauts Update Successfully"));
                    $this->getController()->redirect(array("admin/campaign/campaignstatus"));
                }
                Yii::app()->setFlashMessage($clang->gT("Something wrong"));
                 
            }
            
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
       
        $this->getController()->redirect(array("admin/campaign/campaignstatus"));
    }

    public function addcampaignstatus() {

        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('Campaign', 'create')) {

            if ($action == "addcampaignstatus") {
                                
                $NewPage = Campaignstatus::model()->instCamp($_POST);
                if($NewPage){

                    Yii::app()->setFlashMessage($clang->gT("Status added successfully"));
                    $this->getController()->redirect(array("admin/campaign/campaignstatus"));
                }
                Yii::app()->setFlashMessage($clang->gT("Something wrong"));
            } else {
                $aViewUrls = 'view_addcampaignstatus';
            }
        } else {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
       
        $this->_renderWrappedTemplate('campaign/campaign', $aViewUrls, $aData);
    }

    public function delcampaignstatus()
    {
        $clang = Yii::app()->lang;
         if (isset($_POST['cs_id']) && $_POST['action'] === 'delcampaignstatus') {

            $page_id = (int) Yii::app()->request->getPost("cs_id");
            $sresult =  Campaignstatus::model()->delstatus($page_id);

            if($sresult){
                    Yii::app()->setFlashMessage($clang->gT("Stauts Deleted Successfully"));
                    $this->getController()->redirect(array("admin/campaign/campaignstatus"));
                }
                Yii::app()->setFlashMessage($clang->gT("Something wrong"));
        }
       $this->getController()->redirect(array("admin/campaign/campaignstatus"));

    }


    /**
     * end campaing status code.
    */


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
    protected function _renderWrappedTemplate($sAction = 'campaign/campaign', $aViewUrls = array(), $aData = array()) {

       $aData['display']['menu_bars']['campaignsource_bars'] = true;
       
        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
