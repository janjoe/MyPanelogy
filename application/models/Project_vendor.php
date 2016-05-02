<?php

if (!defined('BASEPATH'))
    die('No direct script access allowed');

class Project_vendor extends LSActiveRecord {

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{project_master_vendors}}';
    }

    public function primaryKey() {
        return 'vendor_project_id';
    }

    public function rules() {
        return array(
            array('project_id', 'required'),
            array('vendor_id', 'required'),
            array('CPC', 'required'),
            array('required_completes', 'required'),
            array('completed_link', 'required'),
        );
    }

    public function scopes() {
        return array(
            'isactive' => array('condition' => "IsActive = '1'"),
        );
    }

    public function relations() {
        return array(
            'Contact' => array(self::HAS_MANY, 'Contact', 'contact_id')
        );
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

    public static function instvendor($NewProject, $vendor_id, $vendor_contact_id, $vendor_status_id, $notes, $CPC, $required_completes
            , $QuotaBuffer_Completes, $completed_link, $disQualified_link, $QuotaFull_URL,$max_redirects, $current_datetime) {

        $oUser = new self;
        $oUser->project_id = $NewProject;
        $oUser->vendor_id = $vendor_id;
        $oUser->vendor_contact_id = $vendor_contact_id;
        $oUser->vendor_status_id = $vendor_status_id;
        $oUser->notes = $notes;
        $oUser->CPC = $CPC;
        $oUser->required_completes = $required_completes;
        $oUser->QuotaBuffer_Completes = $QuotaBuffer_Completes;
        $oUser->completed_link = $completed_link;
        $oUser->disQualified_link = $disQualified_link;
        $oUser->QuotaFull_URL = $QuotaFull_URL;
        $oUser->max_redirects = $max_redirects;
        $oUser->created_datetime = $current_datetime;
        if ($oUser->save()) {
            return $oUser->vendor_project_id;
        } else {
            return false;
        }
    }

    function deletecontact($contact_id) {
        $contact_id = (int) $contact_id;
        $oUser = $this->findByPk($contact_id);
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
