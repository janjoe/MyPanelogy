<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Authentication Controller
 *
 * This controller performs authentication
 *
 * @package       LimeSurvey
 * @subpackage    Backend
 */
class Authentication extends PL_Common_Action {

    /**
     * Show login screen and parse login data
     */
    public function index() {
        $this->_redirectIfLoggedIn();
        if (!is_null(App()->getRequest()->getPost('login_submit'))) {
            $this->DoLogin();
        } else {
            $aData = array();
            $this->_renderWrappedTemplate('authentication', 'login', $aData);
        }
    }

    public function DoLogin() {
        $clang = $this->getController()->lang;
        $user = $_POST['email'];
        $pass = $_POST['password'];
        //$spass = hash('sha256', $pass);
        $spwd = urlencode(base64_encode($pass));
        $sql = "SELECT * FROM {{view_panel_list_master}} WHERE email = '$user' AND password = '$spwd'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $count = $result->rowCount;
        if ($count > 0) {
            $sresult = $result->readAll();

            if ($sresult[0]['status'] == 'E') {
                Yii::app()->session['plid'] = $sresult[0]['panel_list_id'];
                Yii::app()->session['plname'] = $sresult[0]['full_name'];
                Yii::app()->session['plemail'] = $sresult[0]['email'];
                Yii::app()->session['pluser'] = $sresult[0]['first_name'];
                Yii::app()->session['session_hash'] = hash('sha256', getGlobalSetting('SessionName') . $sresult[0]['first_name'] . $sresult[0]['panel_list_id']);
                $this->_doRedirect();
            } elseif ($sresult[0]['status'] == 'C') {
                $message = $clang->gT('Your Account Is canceled');
                $message .= '<br/>';
                $message .= $clang->gT('Please contact support');
                App()->user->setFlash('loginError', $message);
                $this->getController()->redirect(array('/pl/authentication/sa/login'));
            } elseif ($sresult[0]['status'] == 'D') {
                $message = $clang->gT('Your Account Is Disable By Administrator');
                App()->user->setFlash('loginError', $message);
                $this->getController()->redirect(array('/pl/authentication/sa/login'));
            } else {
                $aData['Pending'] = true;
                $aData['success'] = false;
                $aData['panellist_id'] = $sresult[0]['panel_list_id'];
                $this->_renderWrappedTemplate('', 'view_registration', $aData);
            }
        } else {
            $message = $clang->gT('Incorrect username and/or password!');
            App()->user->setFlash('loginError', $message);
            $this->getController()->redirect(array('/pl/authentication/sa/login'));
        }
    }

    /**
     * Logout user
     */
    public function logout() {
        // Fetch the current user
        $plugin = App()->user->getState('plugin', null);    // Save for afterLogout, current user will be destroyed by then

        /* Adding beforeLogout event */
        $beforeLogout = new PluginEvent('beforeLogout');
        App()->getPluginManager()->dispatchEvent($beforeLogout, array($plugin));

        App()->user->logout();
        App()->user->setFlash('loginmessage', gT('Logout successful.'));

        /* Adding afterLogout event */
        $event = new PluginEvent('afterLogout');
        App()->getPluginManager()->dispatchEvent($event, array($plugin));

        $this->getController()->redirect(array('/pl/authentication/sa/login'));
    }

    /**
     * Forgot Password screen
     */
    public function forgotpassword() {
        $this->_redirectIfLoggedIn();

        if (!Yii::app()->request->getPost('action')) {
            $this->_renderWrappedTemplate('authentication', 'forgotpassword');
        } else {
            $sEmailAddr = Yii::app()->request->getPost('email');

            $aFields = PL::model()->findAllByAttributes(array('email' => $sEmailAddr));

            if (count($aFields) < 1) {
                // wrong or unknown username and/or email
                $aData['errormsg'] = $this->getController()->lang->gT('Email address not found. Please check the email address you have provided or register for a new account');
                $aData['maxattempts'] = '';
                $this->_renderWrappedTemplate('authentication', 'error', $aData);
            } else {
                $Panellist_id = $aFields[0]['panel_list_id'];
                $activation_id = generate_random(20);
                //$activation_link = Yii::app()->getBaseUrl(true) . '/index.php/pl/registration/sa/activate/c/' . $NewPanellist . '*' . $activation_id;
                $activation_link = Yii::app()->createAbsoluteUrl('pl/registration/sa/activate/c/' . $Panellist_id . '*' . $activation_id);
                $sql_code = "INSERT INTO {{activation_temp}}
                    (panelllist_id,code,activation_type)
                    VALUES('$Panellist_id','$activation_id','forget_pass')";
                $result = Yii::app()->db->createCommand($sql_code)->query();
                $whitelist = array(
                    '127.0.0.1',
                    '::1'
                );

                if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                    $send = get_SendEmail::model()->SendEmailByTemplate($sEmailAddr, EMAIL_POINT_PL_ForgotPassword, $Panellist_id, array('activation_link' => "$activation_link"));
                } else {
                    echo $send = get_SendEmail::model()->SendEmailByTemplate($sEmailAddr, EMAIL_POINT_PL_ForgotPassword, $Panellist_id, array('activation_link' => "$activation_link"));
                    exit;
                }
                //$send = get_SendEmail::model()->SendEmailByTemplate($sEmailAddr, EMAIL_POINT_PL_ForgotPassword, $Panellist_id, array('activation_link' => "$activation_link"));
                if (!$send) {
                    $aData['message'] = 'Error in sending mail';
                    echo 'Error';
                    Yii::app()->setFlashMessage($clang->gT("Error in mail send"));
                } else {
                    $aData['message'] = 'A request to reset your password has just been sent to your email address. This email will come from ' . Yii::app()->getConfig("siteadminemail") . '. Simply click on "Link" within that email to complete your password change.
                    Please take this time to add ' . Yii::app()->getConfig("siteadminemail") . ' to your trusted or safe sender list to ensure that our emails are delivered to your Inbox.
                    If you do not receive this email within 15 minutes, please check your junk/spam folder and Contact Us.';
                }
                $this->_renderWrappedTemplate('authentication', 'message', $aData);
            }
        }
    }

    /**
     * Send the forgot password email
     *
     * @param string $sEmailAddr
     * @param array $aFields
     */
    private function _sendPasswordEmail($sEmailAddr, $aFields) {
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

    /**
     * Get's the summary
     * @param string $sMethod login|logout
     * @param string $sSummary Default summary
     * @return string Summary
     */
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

    /**
     * Redirects a logged in user to the administration page
     */
    private function _redirectIfLoggedIn() {
        if (!$this->getIsGuest()) {
            $this->_redirectToHome();
        }
    }

    public function _redirectToLoginForm() {
        $this->getController()->redirect(array('/pl/authentication/sa/login'));
    }

    public function _redirectToHome() {
        $this->getController()->redirect(array('/pl/home'));
    }

    public function getIsGuest() {
        return Yii::app()->session['plid'] <= 0;
    }

    /**
     * Check if a user can log in
     * @return bool|array
     */
    private function _userCanLogin() {
        $failed_login_attempts = FailedLoginAttempt::model();
        $failed_login_attempts->cleanOutOldAttempts();

        if ($failed_login_attempts->isLockedOut()) {
            return $this->_getAuthenticationFailedErrorMessage();
        } else {
            return true;
        }
    }

    /**
     * Redirect after login
     */
    private function _doRedirect() {
        $returnUrl = App()->user->getReturnUrl(array('/pl/home'));
        $this->getController()->redirect($returnUrl);
    }

    /**
     * Renders template(s) wrapped in header and footer
     *
     * @param string $sAction Current action, the folder to fetch views from
     * @param string|array $aViewUrls View url(s)
     * @param array $aData Data to be passed on. Optional.
     */
    protected function _renderWrappedTemplate($sAction = 'authentication', $aViewUrls = array(), $aData = array()) {
        $aData['display']['menu_bars'] = false;
        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
