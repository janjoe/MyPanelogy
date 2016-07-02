<?php

if (!defined('BASEPATH'))
    die('No direct script access allowed');

class Campaignstatus extends LSActiveRecord {

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{campaign_status}}';
    }

    public function primaryKey() {
        return 'cs_id';
    }

    public function rules() {
        return array(
            array('status_name', 'required'),
            
        );
    }

    public function scopes() {
        return array(
           /* 'isactive' => array('condition' => "IsActive = '1'"),*/
        );
    }

    public function relations() {
        return array(
           /* 'users' => array(self::HAS_ONE, 'users', 'u_id')*/
        );
    }

    function getRecords($condition=FALSE,  $params = array()) {
        $criteria = $this->getCommandBuilder()->createCriteria($condition, $params);
        $this->applyScopes($criteria);
        $command = $this->getCommandBuilder()->createFindCommand($this->getTableSchema(), $criteria);
        $results = $command->queryAll();
        return $results;
    }

    public static function instCamp($data) {

        $oUser = new self;
        $oUser->status_name = (isset($data['status_name'])) ? $data['status_name'] : '';
        $oUser->status_code = (isset($data['status_code'])) ? $data['status_code'] : '';
       
        if ($oUser->save()) {
            return $oUser->cs_id;
        } else {
            return false;
        }
    }

    function updateRecords($data,$id) {
       // print_r($data); exit;
           // $ocmp = new self;
         
           $customer = Campaignstatus::findByPk($id);
            $customer->status_name = $data['status_name'];
            $customer->status_code = $data['status_code'];
            $customer->update();
           
           if ($customer->update()) {
                return true;
            } else {
                return false;
            }
    }

    function delstatus($page_id) {
        $page_id = (int) $page_id;
        $oUser = $this->findByPk($page_id);
        if($oUser->delete())
            return true;
        else
            return false;
    }

}