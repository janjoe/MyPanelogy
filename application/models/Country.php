<?php

class Country extends LSActiveRecord {

    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{country_master}}';
    }

    public function primaryKey() {
        return 'country_id';
    }

    public function rules() {
        return array(
            array('country_name', 'required'),
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

    public static function instCountry($new_user, $continent) {
        $oUser = new self;
        $oUser->country_name = $new_user;
        $oUser->continent = $continent;
        if ($oUser->save()) {
            return $oUser->country_id;
        } else {
            return false;
        }
    }

    function deletecountry($iUserID) {
        $iUserID = (int) $iUserID;
        $oUser = $this->findByPk($iUserID);
        return (bool) $oUser->delete();
    }

    public function beforeDelete() {

        $project = project::model()->findAll();
        $zone = Zone::model()->findAll();
        foreach ($project as $id => $item) {
            $projects = explode('|', $item->country_id);
            if (in_array($this->country_id, array_values($projects))) {
                Yii::app()->setFlashMessage('Can not delete, Country in use in project master', 'error');
                return false;
            }
        }
        foreach ($zone as $id => $item) {
            $zones = explode('|', $item->country_id);
            if (in_array($this->country_id, array_values($zones))) {
                Yii::app()->setFlashMessage('Can not delete, Country in use in zone master', 'error');
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
