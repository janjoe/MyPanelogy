<?php

class Company_type extends LSActiveRecord {

    public $lang = 'auto';

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{company_type_master}}';
    }

    public function primaryKey() {
        return 'company_type_id';
    }

    public function rules() {
        return array(
            array('company_type_name', 'required'),
            array('company_type', 'required'),
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

    public static function instContactType($new_contact_type, $company_type, $istitle) {
        $oUser = new self;
        $oUser->company_type_name = $new_contact_type;
        $oUser->company_type = $company_type;
        $oUser->Istitle = $istitle;
        if ($oUser->save()) {
            return $oUser->company_type_id;
        } else {
            return false;
        }
    }

    function deletecontacttype($company_type_id) {
        $company_type_id = (int) $company_type_id;
        $oUser = $this->findByPk($company_type_id);
        return (bool) $oUser->delete();
    }

    public function beforeDelete() {

        $contact_type = getContactTypeDelete();
        foreach ($contact_type as $id => $item) {
            $contact_types = explode('|', $item["company_type_id"]);
            if (in_array($this->company_type_id, array_values($contact_types))) {
                Yii::app()->setFlashMessage('Can not delete these, Company Type, it is used in contact master', 'error');
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

    public function getuidfromparentid($parentid) {
        return Yii::app()->db->createCommand()->select('uid')->from('{{users}}')->where('parent_id = :parent_id')->bindParam(":parent_id", $parentid, PDO::PARAM_INT)->queryRow();
    }

    public function relations() {
        return array(
            'permissions' => array(self::HAS_MANY, 'Permission', 'uid')
        );
    }

}
