<?php

class CronController extends LSYii_Controller {
    public $lang = null;

    protected function _init() {
        parent::_init();
        App()->getComponent('bootstrap');

        $this->_sessioncontrol();

        //unset(Yii::app()->session['FileManagerContext']);
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . "admin_core.js");
      
    }
    
        /**
     * Load and set session vars
     *
     * @access protected
     * @return void
     */
    protected function _sessioncontrol() {
        Yii::import('application.libraries.Limesurvey_lang');
        // From personal settings
        if (Yii::app()->request->getPost('action') == 'savepersonalsettings') {
            if (Yii::app()->request->getPost('lang') == 'auto') {
                $sLanguage = getBrowserLanguage();
            } else {
                $sLanguage = Yii::app()->request->getPost('lang');
            }
            Yii::app()->session['adminlang'] = $sLanguage;
        }

        if (empty(Yii::app()->session['adminlang']))
            Yii::app()->session["adminlang"] = Yii::app()->getConfig("defaultlang");

        global $clang; // Needed so EM can localize equation hints until a better solution is found
        $this->lang = $clang = new Limesurvey_lang(Yii::app()->session['adminlang']);
        Yii::app()->setLang($this->lang);

        if (!empty($this->user_id))
            $this->_GetSessionUserRights($this->user_id);
    }

    public function error($message, $sURL = array()) {
        $clang = $this->lang;
        die;
    }

    public function run($action) {
        return parent::run($action);
}

    public function getActionClasses() {
        return array(
            'execute' => 'index',
            'sendemail' => 'cmd',
            'cmd' => 'cmd',
        );
    }

    public function actions() {
        $aActions = $this->getActionClasses();

        foreach ($aActions as $action => $class) {
            $aActions[$action] = "application.controllers.cron.{$class}";
        }

        return $aActions;
    }

     protected function route($sa, array $get_vars) {
        $func_args = array();
        foreach ($get_vars as $k => $var)
            $func_args[$k] = Yii::app()->request->getQuery($var);

        return call_user_func_array(array($this, $sa), $func_args);
    }
    
}
