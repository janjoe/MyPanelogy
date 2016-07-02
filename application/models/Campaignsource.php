<?php

if (!defined('BASEPATH'))
    die('No direct script access allowed');

class Campaignsource extends LSActiveRecord {

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{campaign_sources}}';
    }

    public function primaryKey() {
        return 'cmp_id';
    }

    public function rules() {
        return array(
            array('source_name', 'required'),
            
        );
    }

    public function scopes() {
        return array(
           /* 'isactive' => array('condition' => "IsActive = '1'"),*/
        );
    }

    public function relations() {
        return array(
            'users' => array(self::HAS_ONE, 'users', 'u_id')
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
        $oUser->source_name = (isset($data['source_name'])) ? $data['source_name'] : '';
        //$oUser->source_code = (isset($data['source_code'])) ? $data['source_code'] : '';
        $oUser->source_notes = (isset($data['source_notes'])) ? $data['source_notes'] : '';
        $oUser->created_by = $data['add_id'];
        $oUser->created_date = date('Y/m/d h:i:s');
        if ($oUser->save()) {
            return $oUser->cmp_id;
        } else {
            return false;
        }
    }

     function updateRecords($data,$id) {
       // print_r($data); exit;
           // $ocmp = new self;
         
           $customer = Campaignsource::findByPk($id);
            $customer->source_name = $data['source_name'];
            //$customer->source_code = $data['source_code'];
            $customer->source_notes = $data['source_notes'];
            $customer->edited_by = $data['edit_id'];
            $customer->edited_date = date('Y/m/d h:i:s');
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

    function insertRecords($data) {

        return $this->db->insert('contact_master', $data);
    }

    function join($fields, $from, $condition=FALSE, $join=FALSE, $order=FALSE) {
        $user = Yii::app()->db->createCommand();
        foreach ($fields as $field) {
            $user->select($field);
        }

        $user->from($from);

        if ($condition != FALSE) {
            $user->where($condition);
        }

        if ($order != FALSE) {
            $user->order($order);
        }

        if (isset($join['where'], $join['on'])) {
            if (isset($join['left'])) {
                $user->leftjoin($join['where'], $join['on']);
            } else {
                $user->join($join['where'], $join['on']);
            }
        }

        $data = $user->queryRow();
        return $data;
    }

}
