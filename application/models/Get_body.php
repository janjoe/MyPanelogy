<?php

class Get_body extends LSActiveRecord {

    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{template_email_body}}';
    }

    public function primaryKey() {
        return 'email_bodyid';
    }

    public function rules() {
        return array(
            array('content_text', 'required'),
        );
    }

    public function scopes() {
        return array(
            'isactive' => array('condition' => "IsActive = '1'"),
        );
    }

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

    public static function insertBody($email_body, $current_date) {
        $oUser = new self;
        $oUser->content_text = $email_body;
        $oUser->created_datetime = $current_date;
        if ($oUser->save()) {
            return $oUser->email_bodyid;
        } else {
            return false;
        }
    }

    function deletebody($email_bodyid) {
        $email_bodyid = (int) $email_bodyid;
        $oUser = $this->findByPk($email_bodyid);
        return (bool) $oUser->delete();
    }

    public function beforeDelete() {

//        $project = projects::model()->findAll();
//        $zone = Zone::model()->findAll();
//        foreach ($project as $id => $item) {
//            $projects = explode('|', $item->country_id);
//            if (in_array($this->country_id, array_values($projects))) {
//                Yii::app()->setFlashMessage('Can not delete, Country in use in project master', 'error');
//                return false;
//            }
//        }
        return parent::beforeDelete();
    }

    public function relations() {
        return array(
            'permissions' => array(self::HAS_MANY, 'Permission', 'uid')
        );
    }

}
