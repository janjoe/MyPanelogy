<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Registration extends PL_Common_Action {

    public function index() {

        $this->_redirectIfLoggedIn();
        $clang = Yii::app()->lang;
        if (!empty($_GET['sa']))
            $sa = $_GET['sa'];
        else
            $sa = '';
            
       
       if($sa == 'checkmail')
       {
            $email_address = $_GET['fieldValue'];
            $data =array();
            if (PL::model()->find("email=:email", array(':email' => $email_address))) {
                //echo ''; exit;
                echo '["email_address12",false]';
            }
            else
            {
               echo '["email_address12",true]';
            }
            exit;
       }

       if($sa == 'agentsave')
       {
            if(isset($_GET['agent_id']) && $_GET['agent_id'] != '')
            {
                $data = array();
                $sql_query_insert = "UPDATE {{panel_list_agetnt_master}} SET status='1' where id=".$_GET['agent_id'];
                $result = Yii::app()->db->createCommand($sql_query_insert)->query();
                if($result)
                    $data =  array('sucess' => true );

                echo json_encode($data);
                exit();
            }

            $email_address = $_POST['email_address'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            if(isset($_COOKIE["cmp_id"]))
            {   
                $cmp_id = base64_encode($_COOKIE["cmp_id"]); 
            }
            $per_id = '';
            if(isset($_COOKIE["per_id"]))
            {   
                $per_id = $_COOKIE["per_id"]; 
            }

            $sql_query_insert = "INSERT INTO {{panel_list_agetnt_master}} (cmp_id,per_id, first_name,last_name,email,status)
                        VALUES(".$_COOKIE['cmp_id'].",'".$per_id."','".$fname."','".$lname."','".$email_address."','0')";
            $result = Yii::app()->db->createCommand($sql_query_insert)->query();
            $lastinsert = Yii::app()->db->lastInsertID;

            if($result){
                $activation_link = Yii::app()->getBaseUrl(true).'/?pagename=JOIN%20NOW&fname=' . $fname . '&lname=' . $lname.'&email='.$email_address.'&cmp='.$cmp_id.'&per_id='.$per_id.'&type=agenthrough&rec='.base64_encode($lastinsert);

                $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_agent_Registration, '', array('activation_link' => "$activation_link",'name'=> "$fname $lname", 'email'=>"$email_address" ));

                if (!$send) {
                    echo 'Error';
                    Yii::app()->setFlashMessage($clang->gT("Error in mail send"));
                }
                else{
                   // Yii::app()->setFlashMessage($clang->gT("Email send successfully check your mail"));
                }

                $redirectlink = Yii::app()->getBaseUrl(true).'/?pagename=AGENT REGISTER&resucess=true';
                $this->getController()->redirect($redirectlink);
            }    

       }
            
		if(!empty($_POST) && @$_POST['first_step_register']=='1'){

			
			$_SESSION['userData']['fname'] = $_POST['fname'];
			$_SESSION['userData']['lname'] = $_POST['lname'];
			$_SESSION['userData']['email_address'] = $_POST['email_address'];
			$_SESSION['userData']['pwd'] = $_POST['pwd'];

			$this->getController()->redirect(array('/' . '?pagename=JOIN NOW'));
		}
        else if ($sa == 'save') {

            if (!empty($_POST)) {
				
                $this->DoRegistration();
                
                unset($_SESSION['userData']['fname']);
                
            } else {
                $aData = array();
                $asMessage = array(
                    $clang->gT('Warning'),
                    $clang->gT("No valid data passed to registration.")
                );
                App()->user->setFlash('registrationError', $asMessage);
                //$this->_redirectToIndex();
                $this->getController()->redirect(array('/' . '?pagename=JOIN NOW'));
            }
        } 
        else {
            $asMessage = array(
                $clang->gT('Warning'),
                $clang->gT("No proper parameters passed to registration.")
            );
            App()->user->setFlash('registrationError', $asmessage);
            $this->_niceExit($redata, __LINE__, null, $asMessage);
            //$this->_redirectToIndex();
            $this->getController()->redirect(array('/' . '?pagename=JOIN NOW'));
        }
        
        
        
    }

    public function assigndefaultproject($panel_list_id)
    {
        $uquery = "SELECT project_id FROM {{campaign}} as cmp JOIN {{panel_list_master}} as pl ON cmp.id=pl.cmp_id";
        $uquery .= " WHERE pl.panel_list_id =".$panel_list_id;
        $uresult = Yii::app()->db->createCommand($uquery)->query()->readAll();
        
        if(!empty($uresult))
        {
            $query_id = 0;
            $panel_list_id = (int) $panel_list_id;
            $user_id = '';
            $created_date = Date('y-m-d h:i:s');
            $send_date = Date('y-m-d h:i:s');
            $sid = getmaxsendid() + 1;
            $subjectid = 1;
            //$template_id = 1;
            $template_id = EMAIL_POINT_QueryPullSend;
            $is_send = 1;
            foreach ($uresult as $key) {
                $pid = (int) $key['project_id'];
                if($pid != 0){
                     $sql_insert = "insert into {{query_send_details}} (send_id,query_id,project_id,subjectt_id,template_id,panellist_id,send,created_date,send_date) values
                    ($sid,$query_id,$pid,$subjectid,$template_id,$panel_list_id, $is_send,'$created_date','$send_date')";
                    
                    $rString = Yii::app()->db->createCommand($sql_insert)->execute();
                }
            }
        }
    }
    function activate() {
        $code_id = explode('*', $_GET['c']);
        $aData['Error'] = false;
        $aData['Sucess'] = false;
        $aData['display'] = false;
        $code = $code_id[1];
        $panellist_id = $code_id[0];
        //$this->assigndefaultproject($panellist_id);
        
        
        if ($code != '' && $panellist_id != '') {
            if ($code == '') {
                $aData['Error'] = true;
            } else {
                $sql = "select * from {{activation_temp}} where panelllist_id = '" . $panellist_id . "' and code = '" . trim($code) . "' and IsActive = '1'";
                $result = Yii::app()->db->createCommand($sql)->query();
                $count = $result->rowCount;
                if ($count > 0) {
                    $data = $result->readAll();
                    if ($data[0]['activation_type'] == 'reg') {
                        $reg_no = 'EM-' . $panellist_id;
                        $todayDate = date("Y-m-d");
                        $oRecord = PL::model()->findByPk($panellist_id);
                        $oRecord->reg_no = $reg_no;
                        $oRecord->reg_date = $todayDate;
                        $oRecord->status = 'E';
                        $oRecord->remote_ip = $_SERVER['REMOTE_ADDR'];
                        $Panel_id = $oRecord->save();


                        $sql = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '$panellist_id'";
                        $sresult = Yii::app()->db->createCommand($sql)->query()->readAll();

                        Yii::app()->session['plid'] = $sresult[0]['panel_list_id'];
                        Yii::app()->session['plname'] = $sresult[0]['full_name'];
                        Yii::app()->session['plemail'] = $sresult[0]['email'];
                        Yii::app()->session['pluser'] = $sresult[0]['first_name'];
                        Yii::app()->session['session_hash'] = hash('sha256', getGlobalSetting('SessionName') . $sresult[0]['first_name'] . $sresult[0]['panel_list_id']);

                        $update_temp = "update {{activation_temp}} set IsActive = '0' where panelllist_id = '" . $panellist_id . "' and activation_type = 'reg'";
                        $result = Yii::app()->db->createCommand($update_temp)->query();
                         //assign default project
                            $this->assigndefaultproject($panellist_id);
                        //end assign default project
                        $aData['Sucess'] = true;
                    } elseif ($data[0]['activation_type'] == 'forget_pass') {
                        $aData['Panel_list_id'] = $panellist_id;
                        $update_temp = "update {{activation_temp}} set IsActive = '0' where panelllist_id = '" . $panellist_id . "' and activation_type = 'forget_pass'";
                        $result = Yii::app()->db->createCommand($update_temp)->query();
                        $this->_renderWrappedTemplate('', 'view_fpwd', $aData);
                        exit;
                    } elseif ($data[0]['activation_type'] == 'update_pass') {
                        $aData['display'] = false;
                        $aData['success'] = true;
                        $aData['sendmailtrue'] = false;
                        $spwd = $data[0]['password'];
                        $oRecord = PL::model()->findByPk($panellist_id);
                        $oRecord->password = $spwd;
                        $Panel_id = $oRecord->save();
                        $update_temp = "update {{activation_temp}} set IsActive = '0' where panelllist_id = '" . $panellist_id . "' and activation_type = 'update_pass'";
                        $result = Yii::app()->db->createCommand($update_temp)->query();
                        $this->_renderWrappedTemplate('', 'view_change_password', $aData);
                        exit;
                    } elseif ($data[0]['activation_type'] == 'update_email') {
                        $aData['display'] = false;
                        $aData['success'] = true;
                        $aData['sendmailtrue'] = false;
                        $spwd = $data[0]['email'];
                        $oRecord = PL::model()->findByPk($panellist_id);
                        $oRecord->email = $spwd;
                        $Panel_id = $oRecord->save();
                        $update_temp = "update {{activation_temp}} set IsActive = '0' where panelllist_id = '" . $panellist_id . "' and activation_type = 'update_email'";
                        $result = Yii::app()->db->createCommand($update_temp)->query();
                        $this->_renderWrappedTemplate('', 'view_change_email', $aData);
                        exit;
                    }
                }
            }
        }
        $this->_renderWrappedTemplate('', 'view_activate', $aData);
    }

    public function changepassword() {
        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData['display'] = true;
        $aData['success'] = false;
        $aData['sendmailtrue'] = false;
        if ($action == 'updatepassword') {
            $pwd = $_POST['password'];
            $spwd = urlencode(base64_encode($pwd));
            $panellist_id = $_POST['panellist_id'];
            $sql = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '$panellist_id'";
            $sresult = Yii::app()->db->createCommand($sql)->query()->readAll();
            $email_address = $sresult[0]['email'];

            $activation_id = generate_random(20);
            //$activation_link = Yii::app()->getBaseUrl(true) . '/index.php/pl/registration/sa/activate/c/' . $NewPanellist . '*' . $activation_id;
            $password_link = Yii::app()->createAbsoluteUrl('pl/registration/sa/activate/c/' . $panellist_id . '*' . $activation_id);
            $sql_code = "INSERT INTO {{activation_temp}}
                    (panelllist_id,code,password,activation_type)
                    VALUES('$panellist_id','$activation_id','$spwd','update_pass')";
            $result = Yii::app()->db->createCommand($sql_code)->query();
            $whitelist = array(
                '127.0.0.1',
                '::1'
            );
            if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_EditPassword, $panellist_id, array('pwd' => "$pwd", 'password_link' => "$password_link"));
                $aData['sendmailtrue'] = $send;
            } else {
                $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_EditPassword, $panellist_id, array('pwd' => "$pwd", 'password_link' => "$password_link"));
                //exit;
            }
        }
        $this->_renderWrappedTemplate('', 'view_change_password', $aData);
    }

    function selectemail() {
        $email = $_POST['fldval'];
        $sql = "select * from {{panel_list_master}} where email like '$email'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $count = $result->rowCount;
        if ($count > 0) {
            echo 'This email address has already been registered in our database.';
        } else {
            echo "Correct";
        }
    }

    public function changeemail() {
        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData['display'] = true;
        $aData['success'] = false;
        $aData['sendmailtrue'] = false;
        if ($action == 'updateemail') {
            $email = $_POST['email'];
            $panellist_id = $_POST['panellist_id'];
            $sql = "SELECT * FROM {{view_panel_list_master}} WHERE email = '$email' AND panel_list_id <> '$panellist_id'";
            $result = Yii::app()->db->createCommand($sql)->query();
            $count = $result->rowCount;
            if ($count > 0) {
                Yii::app()->setFlashMessage($clang->gT("This email address has already been registered in our database."));
                $this->getController()->redirect(array("pl/registration/sa/changeemail"));
            } else {
                $sqlp = "select * from {{view_panel_list_master}} where panel_list_id = '$panellist_id'";
                $sresult = Yii::app()->db->createCommand($sqlp)->query()->readAll();
                $email_address = $sresult[0]['email'];

                $activation_id = generate_random(20);
                //$activation_link = Yii::app()->getBaseUrl(true) . '/index.php/pl/registration/sa/activate/c/' . $NewPanellist . '*' . $activation_id;
                $activate_email_link = Yii::app()->createAbsoluteUrl('pl/registration/sa/activate/c/' . $panellist_id . '*' . $activation_id);
                $sql_code = "INSERT INTO {{activation_temp}}
                    (panelllist_id,code,email,activation_type)
                    VALUES('$panellist_id','$activation_id','$email','update_email')";
                $result = Yii::app()->db->createCommand($sql_code)->query();
                $whitelist = array(
                    '127.0.0.1',
                    '::1'
                );
                if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                    $send = get_SendEmail::model()->SendEmailByTemplate($email, EMAIL_POINT_PL_EditEmail, $panellist_id, array('activate_email_link' => "$activate_email_link"));
                    if ($send) {
                        $aData['sendmailtrue'] = true;
                    } else {
                        echo 'Error';
                    }
                } else {
                    $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_EditEmail, $panellist_id, array('activate_email_link' => "$activate_email_link"));
                }
            }
        }
        $this->_renderWrappedTemplate('', 'view_change_email', $aData);
    }

    public function DoRegistration() {
        $clang = Yii::app()->lang;
        $email_address = $_POST['email_address'];
        $pwd = $_POST['pwd'];
        //$spwd = hash('sha256', $pwd);
        $spwd = urlencode(base64_encode($pwd));
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $aData['display'] = false;
        $aViewUrls = array();
        $varifycontent = '';
        $varifycontent = file_get_contents('https://api.kickbox.io/v2/verify?email='.$email_address.'&apikey=KICKBOX_TEST');
        $varifycontent = json_decode($varifycontent);
        
        if(!empty($varifycontent)){

            if($varifycontent->result != 'deliverable' &&  $varifycontent->reason != 'accepted_email' )
            {
                $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to add Panellist"), $clang->gT("The Panellist Email is invalid"), "warningheader", "", $this->getController()->createUrl('/'), $clang->gT("Back"));//17/06/2014 Add By Hari
                $this->_renderWrappedTemplate('', $aViewUrls, $aData);
                exit;
            }    
        }
        
        if (PL::model()->find("email=:email", array(':email' => $email_address))) {
            //Yii::app()->setFlashMessage($clang->gT("Error in mail send"));
            //$aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to add Panellist"), $clang->gT("The Panellist Email already exists"), "warningheader", $clang->gT("The Panellist Email already exists"), $this->getController()->createUrl('/'), $clang->gT("Back"));//17/06/2014 Remove By Hari
            $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Failed to add Panellist"), $clang->gT("The Panellist Email already exists"), "warningheader", "", $this->getController()->createUrl('/'), $clang->gT("Back"));//17/06/2014 Add By Hari
            $this->_renderWrappedTemplate('', $aViewUrls, $aData);
            //Yii::app()->setFlashMessage($clang->gT("Error in mail send"));
            //$this->_redirectToIndex();
        }
       
        $cmp_id = '';
        if(isset($_COOKIE["cmp_id"]))
        {   
            $cmp_id = $_COOKIE["cmp_id"]; 
        }
        $per_id = '';
        if(isset($_COOKIE["per_id"]))
        {   
            $per_id = $_COOKIE["per_id"]; 
        }       
        
        if(isset($_POST['register_for']) && $_POST['register_for'] == 'agenthrough')
        {
            $staus = 'E';
        }
        else
        {
            $staus = 'R';
        }
        $NewPanellist = PL::model()->insertPanellist($email_address, $spwd, $lname, $fname, $cmp_id,$per_id,$staus);

        if ($NewPanellist) {
            $quelist = Question(get_question_categoryid('Registration'), '', true, false);
            $sql = "INSERT INTO {{panellist_answer}} SET panellist_id = '$NewPanellist' ";
            foreach ($quelist as $key => $value) {
                if ($value == 'CheckBox') {
                    $val = implode(',', $_POST[$key]);
                    $sql .= ", question_id_$key = '" . $val . "'";
                } elseif ($value == 'DOB') {
                    $birthdate = date('Y-m-d', strtotime($_POST[$key]));
                    $sql .= ", question_id_$key = '" . $birthdate . "'";
                } else {
                    $sql .= ", question_id_$key = '" . $_POST[$key] . "'";
                }
            }
            $result = Yii::app()->db->createCommand($sql)->query();

            if(isset($_POST['register_for']) && $_POST['register_for'] == '')
            {

                $activation_id = generate_random(20);
                //$activation_link = Yii::app()->getBaseUrl(true) . '/index.php/pl/registration/sa/activate/c/' . $NewPanellist . '*' . $activation_id;
                $activation_link = Yii::app()->createAbsoluteUrl('pl/registration/sa/activate/c/' . $NewPanellist . '*' . $activation_id);
                $sql_code = "INSERT INTO {{activation_temp}}
                        (panelllist_id,code,activation_type)
                        VALUES('$NewPanellist','$activation_id','reg')";
                $result = Yii::app()->db->createCommand($sql_code)->query();
                $whitelist = array(
                    '127.0.0.1',
                    '::1'
                );
                if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                    $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_Registration, $NewPanellist, array('pwd' => "$pwd", 'activation_link' => "$activation_link"));
                } else {
                    $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_Registration, $NewPanellist, array('pwd' => "$pwd", 'activation_link' => "$activation_link"));
                    //exit;
                }
                //$send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_Registration, $NewPanellist, array('pwd' => "$pwd", 'activation_link' => "$activation_link"));
                if (!$send) {
                    echo 'Error';
                    Yii::app()->setFlashMessage($clang->gT("Error in mail send"));
                }
            }
            else{
                        $reg_no = 'EM-' . $NewPanellist;
                        $todayDate = date("Y-m-d");
                        $oRecord = PL::model()->findByPk($NewPanellist);
                        $oRecord->reg_no = $reg_no;
                        $oRecord->reg_date = $todayDate;
                        $oRecord->status = 'E';
                        $oRecord->remote_ip = $_SERVER['REMOTE_ADDR'];
                        $Panel_id = $oRecord->save();
                        $this->assigndefaultproject($NewPanellist);
             } 

             $cookie_name = "loginregister";
             $cookie_value = "1";
             setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

            if(isset($_POST['register_for']) && $_POST['register_for'] == 'agenthrough')
            {
   
                $this->getController()->redirect(array("pl/registration/sa/agentprocess"));
            }
            else{
                    $this->getController()->redirect(array("pl/registration/sa/process"));
               }   
        }
    }
    function agentprocess(){

        $aData = array();
        $aData['display'] = true;
        $this->_renderWrappedTemplate('', 'view_agent_registration', $aData);

    }
    function process() {
        $aData = array();
        $clang = Yii::app()->lang;
        $aData['Pending'] = false;
        $aData['success'] = false;
        $aData['display'] = true;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        if ($action == 'resend') {
            $panellist_id = $_POST['panellist_id'];
            $sql = "SELECT * FROM {{activation_temp}} WHERE panelllist_id = '$panellist_id' AND activation_type='reg' AND IsActive = '1'";
            $result = Yii::app()->db->createCommand($sql)->query();
            $count = $result->rowCount;
            if ($count > 0) {
                $sresult = $result->readAll();
                $sql = "SELECT * FROM {{panel_list_master}} WHERE panel_list_id = '$panellist_id'";
                $result = Yii::app()->db->createCommand($sql)->query()->readAll();
                $email_address = $result[0]['email'];
                $pwd = $result[0]['password'];
                $pwd = base64_decode(urldecode($pwd));
                $activation_id = $sresult[0]['code'];
                //$activation_link = Yii::app()->getBaseUrl(true) . '/index.php/pl/registration/sa/activate/c/' . $panellist_id . '*' . $activation_id;
                $activation_link = Yii::app()->createAbsoluteUrl('pl/registration/sa/activate/c/' . $panellist_id . '*' . $activation_id);
                $whitelist = array(
                    '127.0.0.1',
                    '::1'
                );
                if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                    $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_RegistrationReSend, $panellist_id, array('pwd' => "$pwd", 'activation_link' => "$activation_link"));
                } else {
                    echo $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_RegistrationReSend, $panellist_id, array('pwd' => "$pwd", 'activation_link' => "$activation_link"));
                    exit;
                }
                //$send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_RegistrationReSend, $panellist_id, array('pwd' => "$pwd", 'activation_link' => "$activation_link"));
                if (!$send) {
                    echo 'Error';
                    Yii::app()->setFlashMessage($clang->gT("Error in mail send"));
                }
                $this->getController()->redirect(array("pl/registration/sa/process"));
            } else {
                $aData['success'] = true;
            }
        } elseif ($action == 'resetpassword') {
            $panellist_id = $_POST['panellist_id'];
            $new_pass = $_POST['password'];
            $spwd = urlencode(base64_encode($new_pass));

            $oRecord = PL::model()->findByPk($panellist_id);
            $oRecord->password = $spwd;
            $Panel_id = $oRecord->save();

            //            $sql = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '$panellist_id'";
            //            $sresult = Yii::app()->db->createCommand($sql)->query()->readAll();
            //
            //            Yii::app()->session['plid'] = $sresult[0]['panel_list_id'];
            //            Yii::app()->session['plname'] = $sresult[0]['full_name'];
            //            Yii::app()->session['plemail'] = $sresult[0]['email'];
            //            Yii::app()->session['pluser'] = $sresult[0]['first_name'];
            //            Yii::app()->session['session_hash'] = hash('sha256', getGlobalSetting('SessionName') . $sresult[0]['first_name'] . $sresult[0]['panel_list_id']);
            //$this->_doRedirect();
            $this->_redirectToLoginForm();
        }
        //$aData['display']['header'] = false;
        $this->_renderWrappedTemplate('', 'view_registration', $aData);
    }

    private function _sendRegistrationEmail($sEmailAddr, $aFields) {
        $clang = $this->getController()->lang;
        $sFrom = Yii::app()->getConfig("siteadminname") . " <" . Yii::app()->getConfig("siteadminemail") . ">";
        $sTo = $sEmailAddr;
        $sSubject = $clang->gT('User data');
        $sNewPass = createPassword();
        $sSiteName = Yii::app()->getConfig('sitename');
        $sSiteAdminBounce = Yii::app()->getConfig('siteadminbounce');

        $username = sprintf($clang->gT('Username: %s'), $aFields[0]['users_name']);
        $email = sprintf($clang->gT('Email: %s'), $sEmailAddr);
        $password = sprintf($clang->gT('New password: %s'), $sNewPass);

        $body = array();
        $body[] = sprintf($clang->gT('Your user data for accessing %s'), Yii::app()->getConfig('sitename'));
        $body[] = $username;
        $body[] = $password;
        $body = implode("\n", $body);

        if (SendEmailMessage($body, $sSubject, $sTo, $sFrom, $sSiteName, false, $sSiteAdminBounce)) {
            User::model()->updatePassword($aFields[0]['uid'], $sNewPass);
            $sMessage = $username . '<br />' . $email . '<br /><br />' . $clang->gT('An email with your login data was sent to you.');
        } else {
            $sTmp = str_replace("{NAME}", '<strong>' . $aFields[0]['users_name'] . '</strong>', $clang->gT("Email to {NAME} ({EMAIL}) failed."));
            $sMessage = str_replace("{EMAIL}", $sEmailAddr, $sTmp) . '<br />';
        }

        return $sMessage;
    }

    private function _getSummary($sMethod = 'login', $sSummary = '') {
        if (!empty($sSummary)) {
            return $sSummary;
        }

        $clang = $this->getController()->lang;

        switch ($sMethod) {
            case 'logout' :
                $sSummary = $clang->gT('Please log in first.');
                break;

            case 'login' :
            default :
                $sSummary = '<br />' . sprintf($clang->gT('Welcome %s!'), Yii::app()->session['plfname']) . '<br />&nbsp;';
                if (!empty(Yii::app()->session['redirect_after_login']) && strpos(Yii::app()->session['redirect_after_login'], 'logout') === FALSE) {
                    Yii::app()->session['metaHeader'] = '<meta http-equiv="refresh"'
                            . ' content="1;URL=' . Yii::app()->session['redirect_after_login'] . '" />';
                    $sSummary = '<p><font size="1"><i>' . $clang->gT('Reloading screen. Please wait.') . '</i></font>';
                    unset(Yii::app()->session['redirect_after_login']);
                }
                break;
        }

        return $sSummary;
    }

    private function _messageBoxWithRedirect($title, $message, $classMsg, $extra = "", $url = "", $urlText = "", $hiddenVars = array(), $classMbTitle = "header ui-widget-header") {
        $clang = Yii::app()->lang;
        $url = (!empty($url)) ? $url : $this->getController()->createUrl('/');
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

    private function _redirectIfLoggedIn() {
        if (!$this->getIsGuest()) {
            $this->_redirectToHome();
        }
    }

    public function _redirectToHome() {
        $this->getController()->redirect(array('/pl/home'));
    }

    public function _redirectToLoginForm() {
        $this->getController()->redirect(array('/pl/authentication/sa/login'));
    }

    public function _redirectToIndex() {
        $this->getController()->redirect(array('/'));
    }

    public function getIsGuest() {
        return Yii::app()->session['plid'] <= 0;
    }

    private function _userCanLogin() {
        $failed_login_attempts = FailedLoginAttempt::model();
        $failed_login_attempts->cleanOutOldAttempts();

        if ($failed_login_attempts->isLockedOut()) {
            return $this->_getAuthenticationFailedErrorMessage();
        } else {
            return true;
        }
    }

    private function _doRedirect() {
        $returnUrl = App()->user->getReturnUrl(array('/pl/home'));
        $this->getController()->redirect($returnUrl);
    }

    protected function _renderWrappedTemplate($sAction = 'authentication', $aViewUrls = array(), $aData = array()) {
        if (!$aData['display']) {
            $aData['display']['menu_bars'] = false;
        }
        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
