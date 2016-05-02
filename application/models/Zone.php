<?php

class Zone extends LSActiveRecord {

    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{zone_master}}';
    }

    public function primaryKey() {
        return 'zone_id';
    }

    public function rules() {
        return array(
            array('zone_Name', 'required'),
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

    public static function instZone($new_user, $country_id) {
        $oUser = new self;
        $oUser->zone_Name = $new_user;
        $oUser->country_id = $country_id;
        if ($oUser->save()) {
            return $oUser->zone_id;
        } else {
            return false;
        }
    }

    function deleteZone($ID) {
        $ID = (int) $ID;
        $oUser = $this->findByPk($ID);
        return (bool) $oUser->delete();
    }

    public function beforeDelete() {

        $state = State::model()->findAll();
        foreach ($state as $id => $item) {
            $states = explode('|', $item->zone_id);
            if (in_array($this->zone_id, array_values($states))) {
                Yii::app()->setFlashMessage('Can not delete, Zone in use in state master', 'error');
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
