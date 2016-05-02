<?php

class Get_template extends LSActiveRecord {

    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{template_emails}}';
    }

    public function primaryKey() {
        return 'template_emailid';
    }

    public function rules() {
        return array(
            array('title_text', 'required'),
            array('use_in', 'required'),
            array('email_subjectid', 'required'),
            array('email_bodyid', 'required'),
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

    public static function insertTemplate($email_title, $template_usein, $email_subject, $body_content, $current_date) {
        $oUser = new self;
        $oUser->title_text = $email_title;
        $oUser->use_in = $template_usein;
        $oUser->email_subjectid = $email_subject;
        $oUser->email_bodyid = $body_content;
        $oUser->created_datetime = $current_date;
        if ($oUser->save()) {
            return $oUser->template_emailid;
        } else {
            return false;
        }
    }

    function deletetmplt($template_emailid) {
        $template_emailid = (int) $template_emailid;
        $oUser = $this->findByPk($template_emailid);
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
