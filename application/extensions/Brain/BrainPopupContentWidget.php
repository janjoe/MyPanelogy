<?php

/*
 * Created on 30th April 2014

 *  Copyright: Tarak Gandhi
 *  Updated by: Tarak Gandhi ( tarakgandhi@gmail.com)
 *  Usage Sample Below:-
    $this->widget("application.extensions.Brain.BrainPopupContentWidget", array(
        "popup_box_id" => "popup_box",
        "popup_link_id" => "popup_link",
        "popup_on_load" => "false",
        "popup_title" => "These is the popup title...",
        "uid" => "0",
        "height" => "300px;",
        "width" => "600px;",
    ));
 */

class BrainPopupContentWidget extends CWidget {

    public $popup_box_id;
    public $popup_link_id;
    public $container_id;
    public $popup_on_load;
    public $popup_title;
    public $uid;
    public $height;
    public $width;

    public function init() {
        if ($this->popup_box_id === "" || $this->popup_link_id === "") {
            throw new CException(" Popup Box ID and Popup Box Link ID not passed");
        }
    }

    public function run() {
        $cs = Yii::app()->getClientScript();
        $assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets');
        $cs->registerCssFile($assets . "/BrainPopup" . '.css');

        $this->render('BrainPopUpContentWidget', array(
            'popup_box_id' => $this->popup_box_id,
            'popup_link_id' => $this->popup_link_id,
            'container_id' => $this->container_id,
            'popup_on_load' => $this->popup_on_load,
            'popup_title' => $this->popup_title,
            'uid' => $this->uid,
            'height' => $this->height,
            'width' => $this->width,
        ));
    }

}

?>
