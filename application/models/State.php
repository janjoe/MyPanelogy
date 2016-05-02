<?php

class State extends LSActiveRecord {

    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{state_master}}';
    }

    public function primaryKey() {
        return 'state_id';
    }

    public function rules() {
        return array(
            array('state_Name', 'required'),
            array('zone_id', 'required'),
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

    public static function instState($new_user, $zone_id) {
        $oUser = new self;
        $oUser->state_Name = $new_user;
        $oUser->zone_id = $zone_id;
        if ($oUser->save()) {
            return $oUser->state_id;
        } else {
            return false;
        }
    }

    function deleteState($iUserID) {
        $iUserID = (int) $iUserID;
        $oUser = $this->findByPk($iUserID);
        return (bool) $oUser->delete();
    }

    public function beforeDelete() {

        $city = City::model()->findAll();
        foreach ($city as $id => $item) {
            $citys = explode('|', $item->state_id);
            if (in_array($this->state_id, array_values($citys))) {
                Yii::app()->setFlashMessage('Can not delete, State in use in city master', 'error');
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
