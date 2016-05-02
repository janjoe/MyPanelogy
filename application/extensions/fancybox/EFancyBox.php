<?php

class EFancyBox extends CWidget {

    public $id;
    public $target;
    public $easingEnabled = false;
    public $mouseEnabled = true;
    public $helpersEnabled = false;
    public $config = array();

    public function init() {
        if (!isset($this->id))
            $this->id = $this->getId();
        $this->publishAssets();
    }

    // function to run the widget
    public function run() {
        $config = CJavaScript::encode($this->config);
        Yii::app()->clientScript->registerScript($this->getId(), "
			$('$this->target').fancybox($config);
		");
    }

    // function to publish and register assets on page 
    public function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCssFile($baseUrl . '/css/jquery.fancybox.css');
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.fancybox.pack.js', CClientScript::POS_END);
            // if mouse actions enbled register the js
            if ($this->mouseEnabled) {
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.mousewheel-3.0.6.pack.js', CClientScript::POS_END);
            }
            // if easing enbled register the js
            if ($this->easingEnabled) {
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.easing-1.3.pack.js', CClientScript::POS_END);
            }
            // if easing enbled register the js & css
            if ($this->helpersEnabled) {
                Yii::app()->clientScript->registerCssFile($baseUrl . '/css/jquery.fancybox-buttons.css');
                Yii::app()->clientScript->registerCssFile($baseUrl . '/css/jquery.fancybox-thumbs.css');
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.fancybox-buttons.js', CClientScript::POS_END);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.fancybox-media.js', CClientScript::POS_END);
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.fancybox-thumbs.js', CClientScript::POS_END);
            }
        } else {
            throw new Exception('EFancyBox - Error: Couldn\'t find assets to publish.');
        }
    }

}