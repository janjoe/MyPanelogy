<?php

class Contact_title extends LSActiveRecord {

    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{contact_title_master}}';
    }

    public function primaryKey() {
        return 'contact_title_id';
    }

    public function rules() {
        return array(
            array('contact_title_name', 'required'),
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

    public static function instContactTitle($new_contact_title) {
        $oUser = new self;
        $oUser->contact_title_name = $new_contact_title;
        if ($oUser->save()) {
            return $oUser->contact_title_id;
        } else {
            return false;
        }
    }

    function deletecontactgroup($contact_title_id) {
        $contact_title_id = (int) $contact_title_id;
        $oUser = $this->findByPk($contact_title_id);
        return (bool) $oUser->delete();
    }

    public function beforeDelete() {

        $contact_title = getContactTitleDelete();
        foreach ($contact_title as $id => $item) {
            $contact_titles = explode('|', $item["contact_title_id"]);
            if (in_array($this->contact_title_id, array_values($contact_titles))) {
                Yii::app()->setFlashMessage('Can not delete, Contact Title in use in contact master', 'error');
                return false;
            }
        }
        return parent::beforeDelete();
    }

    

    public function relations() {
        return array(
            'permissions' => array(self::HAS_MANY, 'Permission', 'uid')
        );
    }

}
