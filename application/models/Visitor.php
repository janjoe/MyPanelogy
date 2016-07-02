<?php
/**
 * This is the model class for table "alerts".
 *
 * The followings are the available columns in table 'alerts':
 * @property string $visitor_ip
*/
if (!defined('BASEPATH'))
    die('No direct script access allowed');

class Visitor extends LSActiveRecord {

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{visitor}}';
    }

    public function primaryKey() {
        return 'id';
    }

    public function rules() {
        return array(
            array('visitor_ip', 'required')
           
        );
    }

   function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
   
    function insertRecordsvisitor() {
        $ip = $this->get_client_ip();

        $oUser = new self;
        $oUser->visitor_ip = $ip;
        $oUser->visit_date = date('Y/m/d h:i:s');
       

        return $oUser->save();
    }


}
