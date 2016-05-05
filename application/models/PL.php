<?php

if (!defined('BASEPATH'))
    die('No direct script access allowed');

class PL extends LSActiveRecord {

    protected $findByPkCache = array();

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function scopes() {
        return array(
            'active' => array('condition' => "IsActive = 1"),
            'reg_status' => array('condition' => "reg_status = 'A'"),
        );
    }

    public function rules() {
        return array(
            array('email', 'required'),
            array('email', 'unique', 'message' => 'Email already exists!'),
            array('password', 'required'),
            array('first_name', 'required'),
            array('last_name', 'required'),
        );
    }

    public function expire($surveyId = null) {
        
    }

    public function tableName() {
        return '{{panel_list_master}}';
    }

    public function primaryKey() {
        return 'panel_list_id';
    }

    public static function insertPanellist($email_address, $spwd, $lname, $fname) {
        //return '1';
        $oUser = new self;
        $oUser->email = $email_address;
        $oUser->password = $spwd;
        $oUser->first_name = $fname;
        $oUser->last_name = $lname;
        $oUser->status = 'R';
        $oUser->remote_ip = $_SERVER['REMOTE_ADDR'];
        if ($oUser->save()) {
            return $oUser->panel_list_id;
        } else {
            return false;
        }
    }

    public function afterSave() {
        $panellist_id = $this->panel_list_id;
        $sql = "UPDATE {{panellist_answer}} SET 
                status=(SELECT status FROM {{panel_list_master}} WHERE panel_list_id='$panellist_id'),
                is_fraud = (SELECT is_fraud FROM {{panel_list_master}} WHERE panel_list_id='$panellist_id')
                WHERE panellist_id='$panellist_id'";
        $result = Yii::app()->db->createCommand($sql)->query();
        return true;
        
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

    function getAllRecords($condition=FALSE) {
        $this->connection = Yii::app()->db;
        if ($condition != FALSE) {
            $where_clause = array("WHERE");

            foreach ($condition as $key => $val) {
                $where_clause[] = $key . '=\'' . $val . '\'';
            }

            $where_string = implode(' AND ', $where_clause);
        }

        $query = 'SELECT * FROM ' . $this->tableName() . ' ' . $where_string;

        $data = createCommand($query)->query()->resultAll();

        return $data;
    }

    public function findByPk($pk, $condition = '', $params = array()) {
        if (empty($condition) && empty($params)) {
            if (array_key_exists($pk, $this->findByPkCache)) {
                return $this->findByPkCache[$pk];
            } else {
                $result = parent::findByPk($pk, $condition, $params);
                if (!is_null($result)) {
                    $this->findByPkCache[$pk] = $result;
                }

                return $result;
            }
        }

        return parent::findByPk($pk, $condition, $params);
    }

}
