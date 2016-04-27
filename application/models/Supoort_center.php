<?php

if (!defined('BASEPATH'))
    die('No direct script access allowed');

class Supoort_center extends LSActiveRecord {

    protected $findByPkCache = array();

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

//    public function scopes() {
//        return array(
//            'active' => array('condition' => "IsActive = 1"),
//            'reg_status' => array('condition' => "reg_status = 'A'"),
//        );
//    }

    public function rules() {
        return array(
            array('email_id', 'required'),
            array('body', 'required'),
            array('email_to', 'required'),
            array('email_from', 'required'),
        );
    }

    public function tableName() {
        return '{{email_message}}';
    }

    public function primaryKey() {
        return 'id';
    }

    public static function insertTicket($subject, $body, $email_to, $parent) {
        //return '1';
        $oUser = new self;
        $oUser->subject = $subject;
        $oUser->body = $body;
        $oUser->email_to = $email_to;
        $oUser->email_from = $_SESSION['plid'];
        $oUser->email_id = $_SESSION['plemail'];
        $oUser->parent = $parent;
        $oUser->sender = 'P';
        $oUser->created_datetime = date('Y-m-d H:i:s');
        if ($oUser->save()) {
            return $oUser->id;
        } else {
            return false;
        }
    }

    public static function instAdminMessage($email_to, $subject, $message, $parent, $chat) {
        //return '1';
        $oUser = new self;
        $oUser->subject = $subject;
        $oUser->body = $message;
        $oUser->email_to = $email_to;
        $oUser->email_from = $_SESSION['loginID'];
        $oUser->email_id = $_SESSION['useremail'];
        $oUser->parent = $parent;
        $oUser->sender = 'B';
        $oUser->created_datetime = date('Y-m-d H:i:s');
        if ($oUser->save()) {
            return $oUser->id;
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
