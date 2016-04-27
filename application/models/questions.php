<?php

/*
 * LimeSurvey
 * Copyright (C) 2011 The LimeSurvey Project Team / Carsten Schmitz
 * All rights reserved.
 * License: GNU/GPL License v2 or later, see LICENSE.php
 * LimeSurvey is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 */

class questions extends LSActiveRecord {

    /**
     * @var string Default value for user language
     */
    public $lang = 'auto';

    /**
     * Returns the static model of Settings table
     *
     * @static
     * @access public
     * @param string $class
     * @return User
     */
    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    /**
     * Returns the setting's table name to be used by the model
     *
     * @access public
     * @return string
     */
    public function tableName() {
        return '{{profile_question}}';
    }

    /**
     * Returns the primary key of this table
     *
     * @access public
     * @return string
     */
    public function primaryKey() {
        return 'id';
    }

    /**
     * Defines several rules for this table
     *
     * @access public
     * @return array
     */
    public function rules() {
        return array(
            array('title', 'required'),
            array('sorder', 'required'),
        );
    }

    public function scopes() {
        return array(
            'isactive' => array('condition' => "IsActive = '1'"),
            'profileque' => array('condition' => "category_id = '1'"),
            'communication_language' => array('condition' => "short_title like 'Communication-Language'"),
        );
    }

    /**
     * Returns all users
     *
     * @access public
     * @return string
     */
    public function getAllRecords($condition=FALSE) {
        $criteria = new CDbCriteria;

        if ($condition != FALSE) {
            foreach ($condition as $item => $value) {
                $criteria->addCondition($item . '=' . Yii::app()->db->quoteValue($value));
            }
        }

        $data = $this->findAll($criteria);

        return $data;
    }

    /**
     * Creates new user
     *
     * @access public
     * @return string
     */
    public static function instQuestion($category_id, $short_title, $title, $field_type, $is_other, $is_other_field_type, $outdate_threshold, $priority, $is_profile, $is_project, $sort_order, $is_active) {
        $oUser = new self;
        $oUser->category_id = $category_id;
        $oUser->short_title = $short_title;
        $oUser->title = $title;
        $oUser->field_type = $field_type;
        $oUser->is_other = $is_other;
        $oUser->is_other_field_type = $is_other_field_type;
        $oUser->outdate_threshold = $outdate_threshold;
        $oUser->priority = $priority;
        $oUser->is_profile = $is_profile;
        $oUser->is_project = $is_project;
        $oUser->IsActive = $is_active;
        $oUser->sorder = $sort_order;
        $oUser->user_id = Yii::app()->user->id;
        $oUser->created_date = Date('y-m-d h:i:s');
        $oUser->modified_date = Date('y-m-d h:i:s');

        if ($oUser->save()) {
            return $oUser->id;
        } else {
            return false;
        }
    }

    /**
     * Delete user
     *
     * @param int $iUserID The User ID to delete
     * @return mixed
     */
    function deletecity($iUserID) {
        $iUserID = (int) $iUserID;
        $oUser = $this->findByPk($iUserID);
        return (bool) $oUser->delete();
    }

    public function beforeDelete() {

        $suppliers = Contact::model()->findAll();
        foreach ($suppliers as $id => $item) {
            $vehicles = explode('|', $item->city_id);
            if (in_array($this->city_id, array_values($vehicles))) {
                Yii::app()->setFlashMessage('Can not delete, City in use in contact master', 'error');
                return false;
            }
        }
        return parent::beforeDelete();
    }

    /**
     * Returns user share settings
     *
     * @access public
     * @return string
     */
    public function getShareSetting() {
        $this->db->where(array("uid" => $this->session->userdata('loginID')));
        $result = $this->db->get('users');
        return $result->row();
    }

    /**
     * Returns full name of user
     *
     * @access public
     * @return string
     */
    public function getName($userid) {
        static $aOwnerCache = array();

        if (array_key_exists($userid, $aOwnerCache)) {
            $result = $aOwnerCache[$userid];
        } else {
            $result = Yii::app()->db->createCommand()->select('full_name')->from('{{users}}')->where("uid = :userid")->bindParam(":userid", $userid, PDO::PARAM_INT)->queryAll();
            $aOwnerCache[$userid] = $result;
        }

        return $result;
    }

    public function getuidfromparentid($parentid) {
        return Yii::app()->db->createCommand()->select('uid')->from('{{users}}')->where('parent_id = :parent_id')->bindParam(":parent_id", $parentid, PDO::PARAM_INT)->queryRow();
    }

    /**
     * Returns id of user
     *
     * @access public
     * @return string
     */
    public function getID($sUserName) {
        $oUser = User::model()->findByAttributes(array(
            'users_name' => $sUserName
                ));
        if ($oUser) {
            return $oUser->uid;
        }
    }

    /**
     * Updates user password hash
     * 
     * @param int $iUserID The User ID
     * @param string $sPassword The clear text password
     */
    public function updatePassword($iUserID, $sPassword) {
        return $this->updateByPk($iUserID, array('password' => hash('sha256', $sPassword)));
    }

    /**
     * Adds user record
     *
     * @access public
     * @return string
     */
    public function insertRecords($data) {

        return $this->db->insert('users', $data);
    }

    /**
     * Returns User ID common in Survey_Permissions and User_in_groups
     *
     * @access public
     * @return CDbDataReader Object
     */
    public function getCommonUID($surveyid, $postusergroupid) {
        $query2 = "SELECT b.uid FROM (SELECT uid FROM {{permissions}} WHERE entity_id = :surveyid AND entity = 'survey') AS c RIGHT JOIN {{user_in_groups}} AS b ON b.uid = c.uid WHERE c.uid IS NULL AND b.ugid = :postugid";
        return Yii::app()->db->createCommand($query2)->bindParam(":surveyid", $surveyid, PDO::PARAM_INT)->bindParam(":postugid", $postusergroupid, PDO::PARAM_INT)->query(); //Checked
    }

    public function relations() {
        return array(
            'permissions' => array(self::HAS_MANY, 'Permission', 'uid')
        );
    }

}
