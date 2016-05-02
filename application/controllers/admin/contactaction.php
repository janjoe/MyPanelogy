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
class Contactaction extends Survey_Common_Action {

    /**
     * Load viewing of a user group screen.
     * @param bool $ugid
     * @param array|bool $header (type=success, warning)(message=localized message)
     * @return void
     */
    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('contacts', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = getCompanyDetail();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'addcompany') {
                $this->_renderWrappedTemplate('contacts/contact', 'view_addcomapny', $aData);
            } elseif ($_GET['action'] == 'addcontact') {
                $company_id = (int) Yii::app()->request->getPost("company_id");
                $aData['company_id'] = $company_id;
                $this->_renderWrappedTemplate('contacts/contact', 'view_addcontact', $aData);
            }
        } else {
            $this->_renderWrappedTemplate('contacts/contact', 'view_company', $aData);
        }
    }

    /**
     * Usergroups::delete()
     * Function responsible to delete a user group.
     * @return void
     */
    function delcontact() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('contacts', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $contact_id = (int) Yii::app()->request->getPost("contact_id");
        if ($contact_id) {
            if ($action == "delcontact") {
                $dresult = Contact::model()->deletecontact($contact_id);
                if ($dresult) {
                    $dlt = "DELETE FROM {{map_company_n_types}} WHERE contact_id = " . $contact_id;
                    $result = Yii::app()->db->createCommand($dlt)->query();
                    Yii::app()->setFlashMessage($clang->gT("Contact delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("Contact does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/contact/index"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete Contact. Contact was not supplied."), 'error');
            $this->getController()->redirect(array("admin/contact/index"));
        }

        return $aViewUrls;
    }

    function selectzone() {
        if (isset($_POST['isactive'])) {
            $data = Zone::model()->isactive()->findAll('country_id=:country_id', array(':country_id' => (int) $_POST['country_name']));
        } else {
            $data = Zone::model()->findAll('country_id=:country_id', array(':country_id' => (int) $_POST['country_name']));
        }
        $data = CHtml::listData($data, 'zone_id', 'zone_Name');
        if (count($data)) {
            echo CHtml::tag('option', array('value' => '0'), CHtml::encode('Select zone...'), true);
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            echo CHtml::tag('option', array('value' => '0'), CHtml::encode('No State available'), true);
        }
        $state = CHtml::tag('option', array('value' => '0'), CHtml::encode('Select state...'), true);
        $city = CHtml::tag('option', array('value' => '0'), CHtml::encode('Select city...'), true);
        echo "<script language='javascript' type='text/javascript'>
                $('#statelist').html('" . $state . "');
                $('#citylist').html('" . $city . "');
            </script>";
    }

    function selectstate() {
        if (isset($_POST['isactive'])) {
            $data = State::model()->isactive()->findAll('zone_id=:zone_id', array(':zone_id' => (int) $_POST['zone_name']));
        } else {
            $data = State::model()->findAll('zone_id=:zone_id', array(':zone_id' => (int) $_POST['zone_name']));
        }
        $data = CHtml::listData($data, 'state_id', 'state_Name');
        if (count($data)) {
            echo CHtml::tag('option', array('value' => '0'), CHtml::encode('Select state...'), true);
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            echo CHtml::tag('option', array('value' => '0'), CHtml::encode('No State available'), true);
        }
        $city = CHtml::tag('option', array('value' => '0'), CHtml::encode('Select city...'), true);
        echo "<script language='javascript' type='text/javascript'>
                $('#citylist').html('" . $city . "');
            </script>";
    }

    function selectcity() {
        if (isset($_POST['isactive'])) {
            $data = City::model()->isactive()->findAll('state_id=:state_id', array(':state_id' => (int) $_POST['state_name']));
        } else {
            $data = City::model()->findAll('state_id=:state_id', array(':state_id' => (int) $_POST['state_name']));
        }
        $data = CHtml::listData($data, 'city_id', 'city_Name');
        if (count($data)) {
            echo CHtml::tag('option', array('value' => '0'), CHtml::encode('Select city...'), true);
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            echo CHtml::tag('option', array('value' => '0'), CHtml::encode('No City available'), true);
        }
    }

    function chkdupl() {
        $companyname = $_POST['companyname'];
        if (Contact::model()->findByAttributes(array('company_name' => $companyname))) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function add() {

        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('contacts', 'create')) {

            if ($action == "addcontact") {
                // tis contact_id from contact master
                $company_id = flattenText($_POST['company_id'], false, true, 'UTF-8', true);

                // contac details
                $saluation = flattenText($_POST['saluation'], false, true, 'UTF-8', true);
                $contact_fname = flattenText($_POST['contact_fname'], false, true, 'UTF-8', true);
                $contact_mname = flattenText($_POST['contact_mname'], false, true, 'UTF-8', true);
                $contact_lname = flattenText($_POST['contact_lname'], false, true, 'UTF-8', true);
                $parent_contact_id = 0;
                $contact_group = (int) Yii::app()->request->getPost("contact_group");
                $IsListProvider = flattenText($_POST['IsListProvider'], false, true, 'UTF-8', true);
                $notes = flattenText($_POST['notes'], false, true, 'UTF-8', true);
                $gender = flattenText($_POST['gender'], false, true, 'UTF-8', true);
                $birthdate = date('Y-m-d', strtotime($_POST['birthdate']));
                $add1 = flattenText($_POST['add1'], false, true, 'UTF-8', true);
                $add2 = flattenText($_POST['add2'], false, true, 'UTF-8', true);
                $add3 = flattenText($_POST['add3'], false, true, 'UTF-8', true);
                $country = (int) Yii::app()->request->getPost("country");
                $zonelist = (int) Yii::app()->request->getPost("zonelist");
                $statelist = (int) Yii::app()->request->getPost("statelist");
                $citylist = (int) Yii::app()->request->getPost("citylist");
                $zip = flattenText($_POST['zip'], false, true, 'UTF-8', true);
                $fax = flattenText($_POST['fax'], false, true, 'UTF-8', true);
                $emsilp = flattenText($_POST['emailp'], false, true, 'UTF-8', true);
                $emails = flattenText($_POST['emails'], false, true, 'UTF-8', true);
                $phonep = flattenText($_POST['phonep'], false, true, 'UTF-8', true);
                $phones = flattenText($_POST['phones'], false, true, 'UTF-8', true);
                $company_type = array();
                if (isset($_POST['company_type'])) {
                    $company_type = $_POST['company_type'];
                }
                $ctype = Company_type::model()->findAll(array('select' => 'concat(company_type_id,"__", company_type) as company_type_id , company_type_name'));
                $ctypelist = CHtml::listData($ctype, 'company_type_id', 'company_type_name');
                if ($emsilp == '' || $phonep == '' || $contact_fname == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Contact"), 'message' => $clang->gT("A Contact Name or Email or Phone Number was not supplied or the Contact Name or Email or Phone Number is invalid."), 'class' => 'warningheader');
                } elseif (Contact::model()->findByAttributes(array('primary_emailid' => $emsilp))) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Contact "), 'message' => $clang->gT("The Contact Email already exists."), 'class' => 'warningheader');
                } else {
                    $NewContact = Contact::model()->instContact($contact_fname, $contact_mname, $contact_lname, $saluation
                            , $parent_contact_id, $contact_group, $IsListProvider, $notes, $gender, $birthdate, $add1, $add2, $add3
                            , $country, $zonelist, $statelist, $citylist, $zip, $fax, $emsilp, $emails, $phonep, $phones
                            , $company_id);
                    if ($NewContact) {
                        foreach ($ctypelist as $key => $value) {
                            foreach ($company_type as $ky => $vle) {
                                $test = explode('__', $vle);
                                if ($vle == $key) {
                                    $title_ID = (int) Yii::app()->request->getPost($value);
                                    $sql = "INSERT INTO {{map_contact_n_titles}} (contact_id,contact_title_id)
                                            VALUES('$NewContact', '$title_ID')";
                                    $result = Yii::app()->db->createCommand($sql)->query();
                                }
                            }
                        }
                        Yii::app()->setFlashMessage($clang->gT("Contact added successfully"));
                        $this->getController()->redirect(array("admin/contact/sa/modifycontact/contact_id/$company_id/action/modifycompany"));
                        //$this->getController()->redirect(array("admin/contact/index"));
                    }
                }
            } elseif ($action == 'addcompany') {
                $company_name = flattenText($_POST['company_name'], false, true, 'UTF-8', true);
                $parent_company = (int) Yii::app()->request->getPost("parent_company");
                $contact_group = (int) Yii::app()->request->getPost("contact_group");
                $IsListProvider = flattenText($_POST['IsListProvider'], false, true, 'UTF-8', true);
                $notes = flattenText($_POST['notes'], false, true, 'UTF-8', true);
                $add1 = flattenText($_POST['add1'], false, true, 'UTF-8', true);
                $add2 = flattenText($_POST['add2'], false, true, 'UTF-8', true);
                $add3 = flattenText($_POST['add3'], false, true, 'UTF-8', true);
                $country = (int) Yii::app()->request->getPost("country");
                $zonelist = (int) Yii::app()->request->getPost("zonelist");
                $statelist = (int) Yii::app()->request->getPost("statelist");
                $citylist = (int) Yii::app()->request->getPost("citylist");
                $zip = flattenText($_POST['zip'], false, true, 'UTF-8', true);
                $fax = flattenText($_POST['fax'], false, true, 'UTF-8', true);
                $emsilp = flattenText($_POST['emailp'], false, true, 'UTF-8', true);
                $emails = flattenText($_POST['emails'], false, true, 'UTF-8', true);
                $phonep = flattenText($_POST['phonep'], false, true, 'UTF-8', true);
                $phones = flattenText($_POST['phones'], false, true, 'UTF-8', true);
                $completionlink = flattenText($_POST['completionlink'], false, true, 'UTF-8', true);
                $disqualifylink = flattenText($_POST['disqualifylink'], false, true, 'UTF-8', true);
                $quatafulllink = flattenText($_POST['quatafulllink'], false, true, 'UTF-8', true);
                $company_type = array();
                if (isset($_POST['company_type'])) {
                    $company_type = $_POST['company_type'];
                }

                if ($emsilp == '' || $phonep == '' || $company_name == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Company"), 'message' => $clang->gT("A Company Name or Primary Email or Phone number was not supplied or the Company Name or Primary Email or Phone number is invalid."), 'class' => 'warningheader');
                } elseif (Contact::model()->findByAttributes(array('company_name' => $company_name))) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Company"), 'message' => $clang->gT("The Company Name already exist."), 'class' => 'warningheader');
                } elseif (Contact::model()->findByAttributes(array('primary_emailid' => $emsilp))) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Company"), 'message' => $clang->gT("The Company Primary Email already exists."), 'class' => 'warningheader');
                } else {
                    $NewCompany = Contact::model()->instCompany($company_name, $parent_company, $contact_group, $IsListProvider
                            , $notes, $add1, $add2, $add3, $country, $zonelist, $statelist, $citylist
                            , $zip, $fax, $emsilp, $emails, $phonep, $phones, $completionlink, $disqualifylink, $quatafulllink);
                    if ($NewCompany) {
                        foreach ($company_type as $ky => $vle) {
                            $test = explode('__', $vle);
                            $sql = "INSERT INTO {{map_company_n_types}} (company_id, company_type_id)
                                            VALUES('$NewCompany', '$test[0]')";
                            $result = Yii::app()->db->createCommand($sql)->query();
                        }
                        Yii::app()->setFlashMessage($clang->gT("Company added successfully"));
                        $this->getController()->redirect(array("admin/contact/index"));
                    }
                }
            } else {
                $aViewUrls = 'view_addcomapny';
            }
        } else {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }

        $this->_renderWrappedTemplate('contacts/contact', $aViewUrls, $aData);
    }

    /**
     * Contact::edit()
     * Load edit contact screen.
     * @param mixed $ugid
     * @return void
     */
    function modifycontact() {
        if (isset($_GET['contact_id'])) {

            // contact associate with company
            App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
            App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
            $userlist = getContactDetail();
            $aData['row'] = 0;
            $aData['usr_arr'] = $userlist;
            $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");

            // compnay detail
            //$contact_id = (int) Yii::app()->request->getPost("contact_id");
            $contact_id = (int) $_GET['contact_id'];
            //$action = Yii::app()->request->getPost("action");
            $action = $_GET['action'];
            $sresult = getCompanyDetail($contact_id);
            $contact_result = getContactDetail($contact_id);

            // only use in view_editcompany
            $aData['contact_id'] = $contact_id;
            $aData['mur'] = $sresult;
            $aData['mus'] = $contact_result;
            if ($action == 'modifycompany') {
                $this->_renderWrappedTemplate('contacts/contact', 'view_editcompany', $aData);
            } else {
                $this->_renderWrappedTemplate('contacts/contact', 'view_editcontact', $aData);
            }

            return;
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/index"));
    }

    /**
     * Modify User POST
     */
    function modcontact() {
        $clang = Yii::app()->lang;
        $action = (isset($_GET['action'])) ? $_GET['action'] : '';
        if ($action == "modcontact") {
            $contact_id = (int) Yii::app()->request->getPost("contact_id");

            $saluation = flattenText($_POST['saluation'], false, true, 'UTF-8', true);
            $contact_fname = flattenText($_POST['contact_fname'], false, true, 'UTF-8', true);
            $contact_mname = flattenText($_POST['contact_mname'], false, true, 'UTF-8', true);
            $contact_lname = flattenText($_POST['contact_lname'], false, true, 'UTF-8', true);
            $company_id = (int) Yii::app()->request->getPost("company_id");
            $contact_group = (int) Yii::app()->request->getPost("contact_group");
            $contact_group = ($contact_group == 0) ? '-1' : $contact_group;
            $IsListProvider = flattenText($_POST['IsListProvider'], false, true, 'UTF-8', true);
            $notes = flattenText($_POST['notes'], false, true, 'UTF-8', true);
            $gender = flattenText($_POST['gender'], false, true, 'UTF-8', true);
            $birthdate = date('Y-m-d', strtotime($_POST['birthdate']));
            $add1 = flattenText($_POST['add1'], false, true, 'UTF-8', true);
            $add2 = flattenText($_POST['add2'], false, true, 'UTF-8', true);
            $add3 = flattenText($_POST['add3'], false, true, 'UTF-8', true);
            $country = (int) Yii::app()->request->getPost("country");
            $zonelist = (int) Yii::app()->request->getPost("zonelist");
            $statelist = (int) Yii::app()->request->getPost("statelist");
            $citylist = (int) Yii::app()->request->getPost("citylist");
            $zip = flattenText($_POST['zip'], false, true, 'UTF-8', true);
            $fax = flattenText($_POST['fax'], false, true, 'UTF-8', true);
            $emsilp = flattenText($_POST['emailp'], false, true, 'UTF-8', true);
            $emails = flattenText($_POST['emails'], false, true, 'UTF-8', true);
            $phonep = flattenText($_POST['phonep'], false, true, 'UTF-8', true);
            $phones = flattenText($_POST['phones'], false, true, 'UTF-8', true);
            $company_type = array();
            if (isset($_POST['company_type'])) {
                $company_type = $_POST['company_type'];
            }
            $ctype = Company_type::model()->findAll(array('select' => 'concat(company_type_id,"__", company_type) as company_type_id , company_type_name'));
            $ctypelist = CHtml::listData($ctype, 'company_type_id', 'company_type_name');
            $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
            $addsummary = '';
            $aViewUrls = array();
            $is_Active = 0;
            if ($IsActive) {
                $is_Active = 1;
            }

            $sresult = Contact::model()->findAllByAttributes(array('contact_id' => $contact_id));
            $sresultcount = count($sresult);
            if ((Permission::model()->hasGlobalPermission('superadmin', 'read') ||
                    ($sresultcount > 0 && Permission::model()->hasGlobalPermission('contacts', 'update')))) {

                if ($emsilp == '' || $phonep == '' || $contact_fname == '') {
                    $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to edit Contact "), $clang->gT("Could not modify Contact."), "warningheader", $clang->gT("Contact Name or Primary Email or Phone not be empty."), $this->getController()->createUrl('admin/contact/sa/modifycontact'), $clang->gT("Back"), array('contact_id' => $contact_id));
                } elseif (Contact::model()->findByAttributes(array('primary_emailid' => $emsilp), 'contact_id != ' . $contact_id . '')) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to edit Contact "), 'message' => $clang->gT("The Contact Email already exists."), 'class' => 'warningheader');
                } else {
                    $oRecord = Contact::model()->findByPk($contact_id);
                    $oRecord->first_name = $contact_fname;
                    $oRecord->middle_name = $contact_mname;
                    $oRecord->last_name = $contact_lname;
                    $oRecord->saluation = $saluation;
                    $oRecord->contact_group_id = $contact_group;
                    $oRecord->is_list_provider = $IsListProvider;
                    $oRecord->notes = $notes;
                    $oRecord->gender = $gender;
                    $oRecord->birth_date = $birthdate;
                    $oRecord->address1 = $add1;
                    $oRecord->address2 = $add2;
                    $oRecord->address3 = $add3;
                    $oRecord->country_id = $country;
                    $oRecord->zone_id = $zonelist;
                    $oRecord->state_id = $statelist;
                    $oRecord->city_id = $citylist;
                    $oRecord->zip = $zip;
                    $oRecord->fax = $fax;
                    $oRecord->primary_emailid = $emsilp;
                    $oRecord->primary_contact_no = $phonep;
                    $oRecord->other_emailid = $emails;
                    $oRecord->other_contact_no = $phones;
                    $oRecord->IsActive = $is_Active;
                    $oRecord->contact_type_id = CONTACT;
                    $EditContact = $oRecord->save();

                    if ($EditContact) { // When saved successfully
                        $dlt = "DELETE FROM {{map_contact_n_titles}} WHERE contact_id = " . $contact_id;
                        $result = Yii::app()->db->createCommand($dlt)->query();
                        foreach ($ctypelist as $key => $value) {
                            foreach ($company_type as $ky => $vle) {
                                $test = explode('__', $vle);
                                if ($vle == $key) {
                                    $title_ID = (int) Yii::app()->request->getPost($value);
                                    $sql = "INSERT INTO {{map_contact_n_titles}} (contact_id,contact_title_id)
                                            VALUES('$contact_id`', '$title_ID')";
                                    $result = Yii::app()->db->createCommand($sql)->query();
                                }
                            }
                        }
                        Yii::app()->setFlashMessage($clang->gT("Contact updated successfully"));
                        $this->getController()->redirect(array("admin/contact/sa/modifycontact/contact_id/$company_id/action/modifycompany"));
                    } else {   //Saving the user failed for some reason, message about email is not helpful here
                        // Username and/or email adress already exists.
                        $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing contact title"), $clang->gT("Could not modify contact title."), 'warningheader');
                    }
                }
            } else {
                Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
            }
        } elseif ($action == "modcompany") {

            // table contact_id
            $contact_id = (int) Yii::app()->request->getPost("contact_id");

            // company filed value
            $company_name = flattenText($_POST['company_name'], false, true, 'UTF-8', true);
            $parent_contact = (int) Yii::app()->request->getPost("parent_contact");
            $contact_group = (int) Yii::app()->request->getPost("contact_group");
            $IsListProvider = flattenText($_POST['IsListProvider'], false, true, 'UTF-8', true);
            $notes = flattenText($_POST['notes'], false, true, 'UTF-8', true);
            $add1 = flattenText($_POST['add1'], false, true, 'UTF-8', true);
            $add2 = flattenText($_POST['add2'], false, true, 'UTF-8', true);
            $add3 = flattenText($_POST['add3'], false, true, 'UTF-8', true);
            $country = (int) Yii::app()->request->getPost("country");
            $zonelist = (int) Yii::app()->request->getPost("zonelist");
            $statelist = (int) Yii::app()->request->getPost("statelist");
            $citylist = (int) Yii::app()->request->getPost("citylist");
            $zip = flattenText($_POST['zip'], false, true, 'UTF-8', true);
            $fax = flattenText($_POST['fax'], false, true, 'UTF-8', true);
            $emsilp = flattenText($_POST['emailp'], false, true, 'UTF-8', true);
            $emails = flattenText($_POST['emails'], false, true, 'UTF-8', true);
            $phonep = flattenText($_POST['phonep'], false, true, 'UTF-8', true);
            $phones = flattenText($_POST['phones'], false, true, 'UTF-8', true);
            $RIDCheck = flattenText($_POST['RIDCheck'], false, true, 'UTF-8', true);
            $company_type = array();
            if (isset($_POST['company_type'])) {
                $company_type = $_POST['company_type'];
            }
            foreach ($company_type as $key => $value) {
                $test = explode('__', $value);
                $row[] = $test[1];
            }
            if (in_array('V', $row)) {
                $completionlink = flattenText($_POST['completionlink'], false, true, 'UTF-8', true);
                $disqualifylink = flattenText($_POST['disqualifylink'], false, true, 'UTF-8', true);
                $quatafulllink = flattenText($_POST['quatafulllink'], false, true, 'UTF-8', true);
            } else {
                $completionlink = '';
                $disqualifylink = '';
                $quatafulllink = '';
            }
            $ctype = Company_type::model()->findAll(array('select' => 'concat(company_type_id,"__", company_type) as company_type_id , company_type_name'));
            $ctypelist = CHtml::listData($ctype, 'company_type_id', 'company_type_name');
            $IsActive = flattenText(Yii::app()->request->getPost("IsActive"));
            $addsummary = '';
            $aViewUrls = array();
            $is_Active = 0;
            if ($IsActive) {
                $is_Active = 1;
            }
            $sresult = Contact::model()->findAllByAttributes(array('contact_id' => $contact_id));
            $sresultcount = count($sresult);
            if ((Permission::model()->hasGlobalPermission('superadmin', 'read') ||
                    ($sresultcount > 0 && Permission::model()->hasGlobalPermission('contacts', 'update')))) {

                if ($emsilp == '' || $phonep == '' || $company_name == '') {
                    $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to add Company"), $clang->gT("Could not modify company."), "warningheader", $clang->gT("Company Name or Primary Email or Phone no not be empty."), $this->getController()->createUrl('admin/contact/sa/modifycontact'), $clang->gT("Back"), array('contact_id' => $contact_id));
                } elseif (Contact::model()->findByAttributes(array('company_name' => $company_name), 'contact_id != ' . $contact_id . '')) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to edit Company"), 'message' => $clang->gT("The Company Name already exist."), 'class' => 'warningheader');
                } elseif (Contact::model()->findByAttributes(array('primary_emailid' => $emsilp), 'contact_id != ' . $contact_id . '')) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to edit Company"), 'message' => $clang->gT("The Company Email Id already exist."), 'class' => 'warningheader');
                } else {
                    $oRecord = Contact::model()->findByPk($contact_id);
                    $oRecord->company_name = $company_name;
                    $oRecord->parent_contact_id = $parent_contact;
                    $oRecord->contact_group_id = $contact_group;
                    $oRecord->is_list_provider = $IsListProvider;
                    $oRecord->notes = $notes;
                    $oRecord->address1 = $add1;
                    $oRecord->address2 = $add2;
                    $oRecord->address3 = $add3;
                    $oRecord->country_id = $country;
                    $oRecord->zone_id = $zonelist;
                    $oRecord->state_id = $statelist;
                    $oRecord->city_id = $citylist;
                    $oRecord->zip = $zip;
                    $oRecord->fax = $fax;
                    $oRecord->primary_emailid = $emsilp;
                    $oRecord->primary_contact_no = $phonep;
                    $oRecord->other_emailid = $emails;
                    $oRecord->other_contact_no = $phones;
                    $oRecord->completionlink = $completionlink;
                    $oRecord->disqualifylink = $disqualifylink;
                    $oRecord->quatafulllink = $quatafulllink;
                    $oRecord->IsActive = $is_Active;
                    $oRecord->contact_type_id = COMPANY;
                    $EditCompany = $oRecord->save();

                    if ($EditCompany) {
                        // When saved successfully change mapping table
                        $dlt = "DELETE FROM {{map_company_n_types}} WHERE company_id = " . $contact_id;
                        $result = Yii::app()->db->createCommand($dlt)->query();
                        foreach ($company_type as $ky => $vle) {
                            $test = explode('__', $vle);
                            $sql = "INSERT INTO {{map_company_n_types}} (company_id, company_type_id)
                                            VALUES('$contact_id', '$test[0]')";
                            $result = Yii::app()->db->createCommand($sql)->query();
                        }
                        Yii::app()->setFlashMessage($clang->gT("Company updated successfully"));
                        $this->getController()->redirect(array("admin/contact/index"));
                    } else {
                        $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing contact title"), $clang->gT("Could not modify contact title."), 'warningheader');
                    }
                }
            } else {
                Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
            }
        }
        $this->_renderWrappedTemplate('contacts/contact', $aViewUrls);
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
    protected function _renderWrappedTemplate($sAction = 'contact', $aViewUrls = array(), $aData = array()) {

        $aData['display']['menu_bars']['contact_bars'] = true;

        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
