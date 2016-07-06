<?php

if (!defined('BASEPATH'))
    die('No direct script access allowed');

class Campaign extends LSActiveRecord {

    public static function model($class = __CLASS__) {
        return parent::model($class);
    }

    public function tableName() {
        return '{{campaign}}';
    }

    public function primaryKey() {
        return 'id';
    }

    public function rules() {
        return array(
            array('campaign_name', 'required'),
            array('campaign_code', 'required'),
            
        );
    }

    public function scopes() {
        return array(
           /* 'isactive' => array('condition' => "IsActive = '1'"),*/
        );
    }

    public function relations() {
        return array(
            /*'users' => array(self::HAS_ONE, 'users', 'u_id')*/
        );
    }

    function getRecords($condition=FALSE,  $params = array()) {


        $results = Yii::app()->db->createCommand()
                ->select('c.*, cs.status_name, st.name, s.source_name, u.full_name, cp.page_name')
                ->from('lime_campaign c')
                ->leftJoin('lime_campaign_status cs', 'c.campaign_status=cs.cs_id')
                ->leftJoin('lime_campaign_source_type st', 'c.campaign_cst_id=st.cst_id')
                ->leftJoin('lime_campaign_sources s', 'c.campaign_src_id=s.cmp_id')
                ->leftJoin('lime_users u', 'c.created_by=u.uid')
                ->leftJoin('lime_cms_page_master cp', 'c.page_id=cp.page_id')
                ->queryAll();
 
            foreach($results as $keyCmp=>$cmp){
                $users = $this->getcampaignuser($cmp['id']);
               // print_r($users); exit;
                $total_first_survey_sent_users = 0;    
                if(count($users))
                { 
                    $query_user_str = implode(",",$users);
                    $quesql = "Select date_format(created_date,'%Y%m%d%H%i%s') AS sent_date,COUNT(*) AS cnt from {{query_send_details}} where 1=1 ";
                    $quesql .= " And panellist_id in(".$query_user_str.") GROUP BY sent_date ORDER BY sent_date ASC LIMIT 1"; //filter not to send originally send 
                      
                    $qrydetail = Yii::app()->db->createCommand($quesql)->query()->readAll();
              
                    if(isset($qrydetail[0]["sent_date"]) && isset($qrydetail[0]["cnt"]) && $qrydetail[0]["cnt"] > 0){
                        $total_first_survey_sent_users = $qrydetail[0]["cnt"];        
                    }
                }
                
                $percentChange = 0;
                if(count($users) != 0){ 
                    $percentChange = ($total_first_survey_sent_users * 100 ) / count($users);
                    $percentChange = number_format($percentChange, 0);
                 }   

                $results[$keyCmp]["total_first_survey_sent_users"] = $percentChange;


            }
          
           /* $criteria = new CDbCriteria;
            $criteria->select = 't.*, tu.* ';
            $criteria->join = ' LEFT JOIN `lime_campaign_status` AS `tu` ON t.campaign_status = tu.cs_id';
            //$criteria->addCondition("display_name LIKE '%a%' and blocked_by='76'");
            $resultSet    =    Campaign::model()->findAll($criteria);
            //$arr=$resultSet->attributes;
            foreach ($resultSet as $value) {
               $new[] = $value->attributes;
            }*/
           
       /* $criteria = $this->getCommandBuilder()->createCriteria($condition, $params);
        $this->applyScopes($criteria);
        $command = $this->getCommandBuilder()->createFindCommand($this->getTableSchema(), $criteria);
        $results = $command->queryAll();*/
        return $results;
    }
    function getcampaignuser($cmp_id)
    {
        $results = Yii::app()->db->createCommand()
                ->select('plm.panel_list_id')
                ->from('lime_panel_list_master plm')
                ->where('cmp_id=:id', array(':id'=>$cmp_id))
                ->queryAll();

                foreach ($results as $key => $value) {
                        $res[$key] = $value['panel_list_id'];
                    }    
                return $res;
    }

    function getsingleRecord($id) {


           $results = Yii::app()->db->createCommand()
                    ->select('c.*')
                    ->from('lime_campaign c')
                    ->where('id=:id', array(':id'=>$id))
                    ->queryRow();
            return $results;
    }

    public static function instCamp($data) {

        $oUser = new self;
        $oUser->campaign_name = (isset($data['campaign_name'])) ? $data['campaign_name'] : '';
        $oUser->cost = (isset($data['cost'])) ? number_format($data['cost'], 2, '.', '') : '';
        $oUser->campaign_code = (isset($data['campaign_code'])) ? $data['campaign_code'] : '';
        $oUser->campaign_src_id = (isset($data['campaign_src_id'])) ? $data['campaign_src_id'] : '';
        $oUser->campaign_cst_id = (isset($data['campaign_cst_id'])) ? $data['campaign_cst_id'] : '';
        $oUser->campaign_status = (isset($data['campaign_status'])) ? $data['campaign_status'] : '';
        $oUser->notes = (isset($data['notes'])) ? $data['notes'] : '';
        $oUser->created_by = $data['add_id'];
        $oUser->created_date = date('Y/m/d h:i:s');
        $oUser->page_id = (isset($data['page_id'])) ? $data['page_id'] : '';
        if ($oUser->save()) {
            return $oUser->id;
        } else {
            return false;
        }
    }

    function updateRecords($data,$id) {
       // print_r($data); exit;
           // $ocmp = new self;
         
            $oUser = Campaign::findByPk($id);
            $oUser->campaign_name = (isset($data['campaign_name'])) ? $data['campaign_name'] : '';
            $oUser->cost = (isset($data['cost'])) ? number_format($data['cost'], 2, '.', '') : '';
            $oUser->campaign_code = (isset($data['campaign_code'])) ? $data['campaign_code'] : '';
            $oUser->campaign_src_id = (isset($data['campaign_src_id'])) ? $data['campaign_src_id'] : '';
            $oUser->campaign_cst_id = (isset($data['campaign_cst_id'])) ? $data['campaign_cst_id'] : '';
            $oUser->campaign_status = (isset($data['campaign_status'])) ? $data['campaign_status'] : '';
            $oUser->notes = (isset($data['notes'])) ? $data['notes'] : '';
            $oUser->page_id = (isset($data['page_id'])) ? $data['page_id'] : '';
           
            $oUser->update();
           
           if ($oUser->update()) {
                return true;
            } else {
                return false;
            }
    }
    
    function updateuniquehit($id)
    {
         $oUser = Campaign::findByPk($id);
         $oUser->unique_hit++;
        
           
           if ($oUser->update()) {
                return true;
            } else {
                return false;
            }
    }

    function deletecms($page_id) {
        $page_id = (int) $page_id;
        $oUser = $this->findByPk($page_id);
        return (bool) $oUser->delete();
    }

    public function beforeDelete() {

        // To Do validation
//        $project = projects::model()->findAll();
//        foreach ($project as $id => $item) {
//            $projects = explode('|', $item->country_id);
//            if (in_array($this->country_id, array_values($projects))) {
//                Yii::app()->setFlashMessage('you dont delete this country', 'error');
//                return false;
//            }
//        }
        return parent::beforeDelete();
    }

    function insertRecords($data) {

        return $this->db->insert('contact_master', $data);
    }

    function join($fields, $from, $condition=FALSE, $join=FALSE, $order=FALSE) {
        $user = Yii::app()->db->createCommand();
        foreach ($fields as $field) {
            $user->select($field);
        }

        $user->from($from);

        if ($condition != FALSE) {
            $user->where($condition);
        }

        if ($order != FALSE) {
            $user->order($order);
        }

        if (isset($join['where'], $join['on'])) {
            if (isset($join['left'])) {
                $user->leftjoin($join['where'], $join['on']);
            } else {
                $user->join($join['where'], $join['on']);
            }
        }

        $data = $user->queryRow();
        return $data;
    }

}
