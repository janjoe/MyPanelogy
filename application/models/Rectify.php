<?php

if (!defined('BASEPATH'))
    die('No direct script access allowed');

class Rectify extends LSActiveRecord {

    //protected $findByPkCache = array();
    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function scopes() {
        return array(
            'active' => array('condition' => "IsActive = 1"),
        );
    }

    public function rules() {
        return array(
            array('project_id', 'required'),
            array('rectify_type', 'required'),
        );
    }

    public function tableName() {
        return '{{rectify_redirects}}';
    }

    public function primaryKey() {
        return 'rectify_id';
    }

    public static function insertrectify($project_id, $rectify_type, $rectify_no, $rectify_date) {
        //return '1';
        $obj = new self;
        $obj->project_id = $project_id;
        $obj->rectify_type = $rectify_type;
        $obj->rectify_no = $rectify_no;
        $obj->rectify_date = $rectify_date;
        if ($obj->save()) {
            return $obj->rectify_id;
        } else {
            return false;
        }
    }

    public static function updatePanellistRedirect($panellist_redirect_id, $redirect_status_id, $rectify_id) {
        $sql = "update {{panellist_redirects}} set 
                prev_redirect_status_id=redirect_status_id, 
                redirect_status_id=$redirect_status_id,
                rectify_id = $rectify_id
                where panellist_redirect_id = '$panellist_redirect_id'";
        $data = Yii::app()->db->createCommand($sql)->query();
        //update points
    }

    public function beforeDelete() {
        // To Do validation
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