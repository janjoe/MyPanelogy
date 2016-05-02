<?php

class Contact_group extends LSActiveRecord {

    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{contact_group_master}}';
    }

    public function primaryKey() {
        return 'contact_group_id';
    }

    public function rules() {
        return array(
            array('contact_group_name', 'required'),
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

    public static function instContactGroup($new_contact_group) {
        $oUser = new self;
        $oUser->contact_group_name = $new_contact_group;
        if ($oUser->save()) {
            return $oUser->contact_group_id;
        } else {
            return false;
        }
    }

    function deletecontactgroup($contact_group_id) {
        $contact_group_id = (int) $contact_group_id;
        $oUser = $this->findByPk($contact_group_id);
        return (bool) $oUser->delete();
    }

    public function beforeDelete() {


        $contactgroup = Contact::model()->findAll();
        foreach ($contactgroup as $id => $item) {
            $contactgroups = explode('|', $item->contact_group_id);
            if (in_array($this->contact_group_id, array_values($contactgroups))) {
                Yii::app()->setFlashMessage('Can not delete these, Contact Group, it is used in contact master', 'error');
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
