<?php

/**
 * This is the model class for table "{{cron_jobs}}".
 *
 * The followings are the available columns in table '{{cron_jobs}}':
 * @property integer $cron_id
 * @property string $cron_command
 * @property string $frequency
 * @property string $occur_day
 * @property string $occur_time
 * @property integer $IsActive
 * @property string $LastExecutedOn
 * @property string $LastExecutionRemark
 */
class CronJobs extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{cron_jobs}}';
    }

    public function viewName() {
        return '{{view_cron_jobs}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IsActive', 'numerical', 'integerOnly' => true),
            array('cron_command', 'length', 'max' => 1000),
            array('frequency', 'length', 'max' => 50),
            array('occur_day', 'length', 'max' => 25),
            array('occur_time', 'length', 'max' => 10),
            array('LastExecutedOn, LastExecutionRemark', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('cron_id, cron_command, frequency, occur_day, occur_time, IsActive, LastExecutedOn, LastExecutionRemark', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'cron_id' => 'Cron',
            'cron_command' => 'Cron Command',
            'frequency' => 'Frequency',
            'occur_day' => 'Occur Day',
            'occur_time' => 'Occur Time',
            'IsActive' => 'Is Active',
            'LastExecutedOn' => 'Last Executed On',
            'LastExecutionRemark' => 'Last Execution Remark',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('cron_id', $this->cron_id);
        $criteria->compare('cron_command', $this->cron_command, true);
        $criteria->compare('frequency', $this->frequency, true);
        $criteria->compare('occur_day', $this->occur_day, true);
        $criteria->compare('occur_time', $this->occur_time, true);
        $criteria->compare('IsActive', $this->IsActive);
        $criteria->compare('LastExecutedOn', $this->LastExecutedOn, true);
        $criteria->compare('LastExecutionRemark', $this->LastExecutionRemark, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function getQue() {
        return Yii::app()->db->createCommand("select * from " . $this->viewName() . " order by ord,occur_day,occur_time ")->query();
    }

    public function updateLastExecution($id, $rmk) {
        $sql = "update " . $this->tableName() . " set LastExecutedOn='" . date('Y-m-d H:i') . "' , LastExecutionRemark='$rmk' where cron_id=$id";
        $r = Yii::app()->db->createCommand($sql)->execute();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CronJobs the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

}
