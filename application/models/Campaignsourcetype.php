<?php

if (!defined('BASEPATH'))
    die('No direct script access allowed');

class Campaignsourcetype extends LSActiveRecord {

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{campaign_source_type}}';
    }

    public function primaryKey() {
        return 'cst_id';
    }

    public function rules() {
        return array(
            array('name', 'required'),
            
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
        $oUser->name = (isset($data['name'])) ? $data['name'] : '';
       
        if ($oUser->save()) {
            return $oUser->cst_id;
        } else {
            return false;
        }
    }

    function updateRecords($data,$id) {
       // print_r($data); exit;
           // $ocmp = new self;
         
           $customer = Campaignsourcetype::findByPk($id);
            $customer->name = $data['name'];
            $customer->update();
           
           if ($customer->update()) {
                return true;
            } else {
                return false;
            }
    }

    function deletecms($page_id) {
        $page_id = (int) $page_id;
        $oUser = $this->findByPk($page_id);
        return (bool) $oUser->delete();
    }

    public function beforeDelete() {

        // To Do validation
//        $project = projects::model()->findAll();
//        foreach ($project as $id => $item) {
//            $projects = explode('|', $item->country_id);
//            if (in_array($this->country_id, array_values($projects))) {
//                Yii::app()->setFlashMessage('you dont delete this country', 'error');
//                return false;
//            }
//        }
        return parent::beforeDelete();
    }

    

}
