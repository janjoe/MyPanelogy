<?php

if (!defined('BASEPATH'))
    die('No direct script access allowed');

class Project extends LSActiveRecord {

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{project_master}}';
    }

    public function primaryKey() {
        return 'project_id';
    }

    public function rules() {
        return array(
            array('project_name', 'required'),
            array('client_id', 'required'),
            array('manager_user_id', 'required'),
            array('sales_user_id', 'required'),
            array('reward_points', 'required'),
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

    public static function instProject($project_name, $project_friendly_name, $parent_project
    , $client, $client_contact, $project_manager, $sales_person, $country, $quota
    , $QuotaBufferAmnt, $RIDCheck, $cpc, $los, $ir, $points, $surveylink, $notes, $status) {

        $oUser = new self;
        $oUser->project_name = $project_name;
        $oUser->friendly_name = $project_friendly_name;
        $oUser->parent_project_id = $parent_project;
        $oUser->client_id = $client;
        $oUser->contact_id = $client_contact;
        $oUser->manager_user_id = $project_manager;
        $oUser->sales_user_id = $sales_person;
        $oUser->country_id = $country;
        $oUser->required_completes = $quota;
        $oUser->QuotaBuffer_Completes = $QuotaBufferAmnt;
        $oUser->CPC = $cpc;
        $oUser->IR = $ir;
        $oUser->expected_los = $los;
        $oUser->avg_los = 0;
        $oUser->total_los = 0;
        $oUser->reward_points = $points;
        $oUser->client_link = $surveylink;
        $oUser->total_redirected = 0;
        $oUser->total_completed = 0;
        $oUser->total_quota_full = 0;
        $oUser->total_disqualify = 0;
        $oUser->total_rejected = 0;
        $oUser->extra_completes = 0;
        $oUser->total_errors = 0;
        $oUser->notes = $notes;
        $oUser->RIDCheck = $RIDCheck;
        $oUser->project_status_id = $status;
        if ($oUser->save()) {
            return $oUser->project_id;
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

    //24/06/2014 Add By Hari
    function GetProjectCompletedNotRectify($project_id) {
        $sql = "SELECT pr.panellist_redirect_id,pr.vendor_project_id,pr.panellist_id,pr.project_id,pm.parent_project_id FROM {{panellist_redirects}} pr
        left outer join {{project_master}} pm on pr.project_id=pm.project_id
        WHERE (pm.project_id='" . $project_id . "' OR pm.parent_project_id = '" . $project_id . "') AND ifnull(pr.rectify_id,0)=0 AND pr.redirect_status_id=" . getGlobalSetting('redirect_status_completed');
        $data = Yii::app()->db->createCommand($sql)->query()->readAll();
        return $data;
    }
    //24/069/2014 End

}
