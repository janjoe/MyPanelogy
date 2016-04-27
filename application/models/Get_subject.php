<?php

class Get_subject extends LSActiveRecord {

    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{template_email_subjects}}';
    }

    public function primaryKey() {
        return 'email_subjectid';
    }

    public function rules() {
        return array(
            array('subject_text', 'required'),
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

    public static function insertSubect($subject_text, $current_date) {
        $oUser = new self;
        $oUser->subject_text = $subject_text;
        $oUser->created_datetime = $current_date;
        if ($oUser->save()) {
            return $oUser->email_subjectid;
        } else {
            return false;
        }
    }

    function deletesubject($email_subjectid) {
        $email_subjectid = (int) $email_subjectid;
        $oUser = $this->findByPk($email_subjectid);
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
