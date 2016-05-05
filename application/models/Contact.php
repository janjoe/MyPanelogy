<?php

if (!defined('BASEPATH'))
    die('No direct script access allowed');

class Contact extends LSActiveRecord {

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{contact_master}}';
    }

    public function primaryKey() {
        return 'contact_id';
    }

    public function rules() {
        return array(
            array('primary_emailid', 'required'),
            array('primary_contact_no', 'required'),
            array('country_id', 'required'),
            array('zone_id', 'required'),
            array('zone_id', 'required'),
            array('city_id', 'required'),
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

    public static function instCompany($company_name, $parent_company, $contact_group, $IsListProvider
    , $notes, $add1, $add2, $add3, $country, $zonelist, $statelist, $citylist
    , $zip, $fax, $emsilp, $emails, $phonep, $phones, $completionlink, $disqualifylink, $quatafulllink) {

        $oUser = new self;
        $oUser->company_name = $company_name;
        $oUser->parent_contact_id = $parent_company;
        $oUser->contact_group_id = $contact_group;
        $oUser->is_list_provider = $IsListProvider;
        $oUser->notes = $notes;
        $oUser->address1 = $add1;
        $oUser->address2 = $add2;
        $oUser->address3 = $add3;
        $oUser->country_id = $country;
        $oUser->zone_id = $zonelist;
        $oUser->state_id = $statelist;
        $oUser->city_id = $citylist;
        $oUser->zip = $zip;
        $oUser->fax = $fax;
        $oUser->primary_emailid = $emsilp;
        $oUser->primary_contact_no = $phonep;
        $oUser->other_emailid = $emails;
        $oUser->other_contact_no = $phones;
        $oUser->completionlink = $completionlink;
        $oUser->disqualifylink = $disqualifylink;
        $oUser->quatafulllink = $quatafulllink;
        $oUser->company_id = -1;
        $oUser->contact_type_id = COMPANY;
        if ($oUser->save()) {
            return $oUser->contact_id;
        } else {
            return false;
        }
    }

    public static function instContact($contact_fname, $contact_mname, $contact_lname, $saluation, $parent_contact_id, $contact_group
    , $IsListProvider, $notes, $gender, $birthdate, $add1, $add2, $add3, $country, $zonelist, $statelist, $citylist
    , $zip, $fax, $emsilp, $emails, $phonep, $phones, $company_id) {

        $oUser = new self;
        $oUser->first_name = $contact_fname;
        $oUser->middle_name = $contact_mname;
        $oUser->last_name = $contact_lname;
        $oUser->saluation = $saluation;
        $oUser->parent_contact_id = $parent_contact_id;
        $oUser->contact_group_id = $contact_group;
        $oUser->is_list_provider = $IsListProvider;
        $oUser->notes = $notes;
        $oUser->gender = $gender;
        $oUser->birth_date = $birthdate;
        $oUser->address1 = $add1;
        $oUser->address2 = $add2;
        $oUser->address3 = $add3;
        $oUser->country_id = $country;
        $oUser->zone_id = $zonelist;
        $oUser->state_id = $statelist;
        $oUser->city_id = $citylist;
        $oUser->zip = $zip;
        $oUser->fax = $fax;
        $oUser->primary_emailid = $emsilp;
        $oUser->primary_contact_no = $phonep;
        $oUser->other_emailid = $emails;
        $oUser->other_contact_no = $phones;
        $oUser->company_id = $company_id;
        $oUser->contact_type_id = CONTACT;
        if ($oUser->save()) {
            return $oUser->contact_id;
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
