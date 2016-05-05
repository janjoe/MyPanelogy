<?php

class City extends LSActiveRecord {

    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{city_master}}';
    }

    public function primaryKey() {
        return 'city_id';
    }

    public function rules() {
        return array(
            array('city_Name', 'required'),
            array('state_id', 'required'),
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

    public static function instCity($new_city, $state_id) {
        $oUser = new self;
        $oUser->city_Name = $new_city;
        $oUser->state_id = $state_id;
        if ($oUser->save()) {
            return $oUser->city_id;
        } else {
            return false;
        }
    }

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

    public function getShareSetting() {
        $this->db->where(array("uid" => $this->session->userdata('loginID')));
        $result = $this->db->get('users');
        return $result->row();
    }

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

    public function relations() {
        return array(
            'permissions' => array(self::HAS_MANY, 'Permission', 'uid')
        );
    }

}
