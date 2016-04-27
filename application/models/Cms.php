<?php

if (!defined('BASEPATH'))
    die('No direct script access allowed');

class Cms extends LSActiveRecord {

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{cms_page_master}}';
    }

    public function primaryKey() {
        return 'page_id';
    }

    public function rules() {
        return array(
            array('page_name', 'required'),
            array('page_title', 'required')
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

    public static function instCms($page_name, $page_title, $page_type, $shw_menu) {

        $oUser = new self;
        $oUser->page_name = $page_name;
        $oUser->page_title = $page_title;
        $oUser->contenttype = $page_type;
        $oUser->showinmenu = $shw_menu;
        if ($oUser->save()) {
            return $oUser->page_id;
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
