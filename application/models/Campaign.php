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

        if(!empty($params))
        {
            
            $filterquery = "SELECT `c`.*, `cs`.`status_name`, `st`.`name`, `s`.`source_name`, `u`.`full_name`, `cp`.`page_name` FROM lime_campaign as c LEFT JOIN `lime_campaign_status` `cs` ON c.campaign_status = cs.cs_id LEFT JOIN `lime_campaign_source_type` `st` ON c.campaign_cst_id=st.cst_id LEFT JOIN `lime_campaign_sources` `s` ON c.campaign_src_id=s.cmp_id LEFT JOIN `lime_users` `u` ON c.created_by=u.uid LEFT JOIN `lime_cms_page_master` `cp` ON c.page_id=cp.page_id WHERE 1=1";
                if (isset($params['source']) && $params['source'] != '')
                {
                    $filterquery .= " AND c.campaign_src_id=".$params['source'];
                }
                if(isset($params['sourcetype']) && $params['sourcetype'] != '')
                {
                    $filterquery .= " AND c.campaign_cst_id=".$params['sourcetype'];
                }
                if(isset($params['status']) && $params['status'] != '')
                {
                    $filterquery .= " AND c.campaign_status=".$params['status'];
                }
                if(isset($params['campaign']) && $params['campaign'] != '')
                {
                    $filterquery .= " AND c.id=".$params['campaign'];
                }
                if(isset($params['from'], $params['to']) && $params['from'] != '' && $params['to'] != '')
                {
                    $filterquery .= " AND STR_TO_DATE(c.created_date , '%Y-%m-%d') BETWEEN STR_TO_DATE('".$params['from']."', '%Y-%m-%d') 
                    AND STR_TO_DATE('".$params['to']."', '%Y-%m-%d')";
                }
                if(isset($params['uniquehit']) && $params['uniquehit'] != '')
                {
                    $filterquery .= " AND c.unique_hit >= ".$params['uniquehit'];
                }
            /* $results = Yii::app()->db->createCommand();
                $results->select('c.*, cs.status_name, st.name, s.source_name, u.full_name, cp.page_name');
                $results->from('lime_campaign c');
                $results->leftJoin('lime_campaign_status cs', 'c.campaign_status=cs.cs_id');
                $results->leftJoin('lime_campaign_source_type st', 'c.campaign_cst_id=st.cst_id');
                $results->leftJoin('lime_campaign_sources s', 'c.campaign_src_id=s.cmp_id');
                $results->leftJoin('lime_users u', 'c.created_by=u.uid');
                $results->leftJoin('lime_cms_page_master cp', 'c.page_id=cp.page_id');
                
                if (isset($params['source']) && $params['source'] != '')
                {
                     $results->andwhere('c.campaign_src_id=:csid', array(':csid'=>$params['source']));
                }
                if(isset($params['sourcetype']) && $params['sourcetype'] != '')
                {
                     $results->andwhere('c.campaign_cst_id=:cstid', array(':cstid'=>$params['sourcetype']));
                }
                if(isset($params['status']) && $params['status'] != '')
                {
                     $results->andwhere('c.campaign_status=:csttid', array(':csttid'=>$params['status']));
                }
                if(isset($params['campaign']) && $params['campaign'] != '')
                {
                     $results->andwhere('c.id=:cid', array(':cid'=>$params['campaign']));
                }
                 $results->queryAll();*/
                 $results = Yii::app()->db->createCommand($filterquery)->query()->readAll();
        }
        else
        {

            $results = Yii::app()->db->createCommand()
                ->select('c.*, cs.status_name, st.name, s.source_name, u.full_name, cp.page_name')
                ->from('lime_campaign c')
                ->leftJoin('lime_campaign_status cs', 'c.campaign_status=cs.cs_id')
                ->leftJoin('lime_campaign_source_type st', 'c.campaign_cst_id=st.cst_id')
                ->leftJoin('lime_campaign_sources s', 'c.campaign_src_id=s.cmp_id')
                ->leftJoin('lime_users u', 'c.created_by=u.uid')
                ->leftJoin('lime_cms_page_master cp', 'c.page_id=cp.page_id')
                ->queryAll();
        } 
        if(!empty($results)){
            foreach($results as $keyCmp=>$cmp)
            {
                $users = array();
                $users = $this->getcampaignuser($cmp['id']);
                $total_servey_complete_by_campaign = 0;
                $total_first_survey_sent_users = 0;  
                $total_first_servey_complete_by_campaign = 0;
                $total_invite_users_per_campaign = 0;
                $total_servey_response_by_campaign_user = 0; 
                    
                if(count($users))
                { 
                    $query_user_str = implode(",",$users);
                    $quesql = "Select GROUP_CONCAT(project_id) as project_ids, GROUP_CONCAT(panellist_id) as panellist_ids,created_date, date_format(created_date,'%Y%m%d%H%i%s') AS sent_date,COUNT(*) AS cnt from {{query_send_details}} where 1=1 ";
                     $quesql .= " And panellist_id in(".$query_user_str.") GROUP BY sent_date ORDER BY sent_date ASC LIMIT 1"; //filter not to send originally send 
                      
                    $qrydetail = Yii::app()->db->createCommand($quesql)->query()->readAll();
              
                    if(isset($qrydetail[0]["sent_date"]) && isset($qrydetail[0]["cnt"]) && $qrydetail[0]["cnt"] > 0){
                        $total_first_survey_sent_users = $qrydetail[0]["cnt"];        
                    }
                        
                    // for complete 1st invite servey
                    if(isset($qrydetail[0]["project_ids"]) && isset($qrydetail[0]["panellist_ids"])){
                        $paliid = $qrydetail[0]['panellist_ids'];
                        $prjiid = $qrydetail[0]['project_ids'];
                                
                        $querevsql1 = "Select COUNT(*) AS totalfirstcompletecnt from {{panellist_redirects}} where 1=1 ";
                        $querevsql1 .= " And panellist_id in(".$paliid.") AND project_id in(".$prjiid.") AND redirect_status_id = '6'";
                        $qryrevdetail1 = Yii::app()->db->createCommand($querevsql1)->query()->readAll();
                    
                        if(isset($qryrevdetail1[0]["totalfirstcompletecnt"]) && $qryrevdetail1[0]["totalfirstcompletecnt"] > 0){
                            $total_first_servey_complete_by_campaign = $qryrevdetail1[0]["totalfirstcompletecnt"];        
                        }        
                    }
                    // end complete 1st servey

                    // for revenue cost per complete servey and total complete servey
                    $querevsql = "Select COUNT(*) AS totalcompletecnt from {{panellist_redirects}} where 1=1 ";
                    $querevsql .= " And panellist_id in(".$query_user_str.") AND redirect_status_id = '6'"; //filter not to send originally send 
                              
                    $qryrevdetail = Yii::app()->db->createCommand($querevsql)->query()->readAll();
                                               
                    if(isset($qryrevdetail[0]["totalcompletecnt"]) && $qryrevdetail[0]["totalcompletecnt"] > 0){
                        $total_servey_complete_by_campaign = $qryrevdetail[0]["totalcompletecnt"];        
                    }
                    // end revenue cost per complete and total servey

                        
                    // response rate from total invites and click on servey link any status
                    $quesql_total_invite = "Select GROUP_CONCAT(project_id) as project_ids, GROUP_CONCAT(panellist_id) as panellist_ids, COUNT(*) AS total_invite_cnt from {{query_send_details}} where 1=1 ";
                    $quesql_total_invite .= " And panellist_id in(".$query_user_str.")"; //filter not to send originally send 
                              
                    $qrydetail_total_invite = Yii::app()->db->createCommand($quesql_total_invite)->query()->readAll();
            
                    if(isset($qrydetail_total_invite[0]["total_invite_cnt"]) && $qrydetail_total_invite[0]["total_invite_cnt"] > 0){
                        $total_invite_users_per_campaign = $qrydetail_total_invite[0]["total_invite_cnt"];        
                    }

                    if(isset($qrydetail_total_invite[0]["project_ids"]) && isset($qrydetail_total_invite[0]["panellist_ids"])){
                        $paliid = $qrydetail_total_invite[0]['panellist_ids'];
                        $prjiid = $qrydetail_total_invite[0]['project_ids'];
                                
                        $querevsql_total_responses = "Select * from {{panellist_redirects}} where 1=1 ";
                        $querevsql_total_responses .= " And panellist_id in(".$paliid.") AND project_id in(".$prjiid.") GROUP BY panellist_id, project_id";
                             
                        $qryrevdetail_total_responses_per_campaign = Yii::app()->db->createCommand($querevsql_total_responses)->query()->readAll();
                                   
                        if(count($qryrevdetail_total_responses_per_campaign)){
                            $total_servey_response_by_campaign_user = count($qryrevdetail_total_responses_per_campaign);        
                        }        
                    }
                        //end of response rate 
                }
                    
                $percentChange = 0;
                if(count($users) != 0){ 
                    $percentChange = ($total_first_survey_sent_users * 100 ) / count($users);
                    $percentChange = number_format($percentChange, 0);
                }   

                $results[$keyCmp]["total_first_survey_sent_users"] = $total_first_survey_sent_users;
                //$results[$keyCmp]["total_first_survey_sent_users_per"] = $percentChange;
                $results[$keyCmp]["total_first_survey_sent_users_complete"] = $total_first_servey_complete_by_campaign;
                $results[$keyCmp]["total_revenue"] = $total_servey_complete_by_campaign;
                $results[$keyCmp]["total_invite_users_per_campaign"] = $total_invite_users_per_campaign;
                $results[$keyCmp]["total_servey_response_by_campaign_user"] = $total_servey_response_by_campaign_user;
                $results[$keyCmp]["initregcomplete"] = $this->getcompletedreguser($cmp['id'],'R');
                $results[$keyCmp]["complete"] = $this->getcompletedreguser($cmp['id'],'E');
                $results[$keyCmp]["cancle_account_user"] = $this->getcompletedreguser($cmp['id'],'C');
                $results[$keyCmp]["frod_user"] = $this->getfroduser($cmp['id']);
               //echo '<pre>'; 
                //print_r($params); 
               //exit;
                if(!empty($params))
                {
                    $finalrevenue = intval($results[$keyCmp]['cost'] * $total_servey_complete_by_campaign);
                    $finalroi = $finalrevenue - intval($results[$keyCmp]['cost']);
                    if (isset($params['revenue']) && $params['revenue'] != '')
                    {
                        if($finalrevenue <  intval($params['revenue']))
                        {
                            unset($results[$keyCmp]);
                        }
                    }
                    if (isset($params['roi']) && $params['roi'] != '')
                    {
                        if($finalroi <  intval($params['roi']))
                        {
                            unset($results[$keyCmp]);
                        }
                    }
                    if (isset($params['initreg']) && $params['initreg'] != '')
                    {
                        if($results[$keyCmp]["initregcomplete"] <  intval($params['initreg']))
                        {
                            unset($results[$keyCmp]);
                        }
                    }
                    if (isset($params['completereg']) && $params['completereg'] != '')
                    {
                        if($results[$keyCmp]["complete"] <  intval($params['completereg']))
                        {
                            unset($results[$keyCmp]);
                        }
                    }
                    if (isset($params['invitetofirst']) && $params['invitetofirst'] != '')
                    {
                        if($results[$keyCmp]["total_first_survey_sent_users"] <  intval($params['invitetofirst']))
                        {
                            unset($results[$keyCmp]);
                        }
                    }
                    if (isset($params['completefirstservery']) && $params['completefirstservery'] != '')
                    {
                        if($results[$keyCmp]["total_first_survey_sent_users_complete"] <  intval($params['completefirstservery']))
                        {
                            unset($results[$keyCmp]);
                        }
                    }
                    if (isset($params['totalcompleteservery']) && $params['totalcompleteservery'] != '')
                    {
                        if($results[$keyCmp]["total_revenue"] <  intval($params['totalcompleteservery']))
                        {
                            unset($results[$keyCmp]);
                        }
                    }
                    if (isset($params['responserate']) && $params['responserate'] != '')
                    {
                        if($results[$keyCmp]["total_servey_response_by_campaign_user"] <  intval($params['responserate']))
                        {
                            unset($results[$keyCmp]);
                        }
                    }
                    if (isset($params['fraud']) && $params['fraud'] != '')
                    {
                        if($results[$keyCmp]["frod_user"] <  intval($params['fraud']))
                        {
                            unset($results[$keyCmp]);
                        }
                    }
                    if (isset($params['canclemembership']) && $params['canclemembership'] != '')
                    {
                        if($results[$keyCmp]["cancle_account_user"] <  intval($params['canclemembership']))
                        {
                            unset($results[$keyCmp]);
                        }
                    }
                }
            }
        }//exit;
           
        return $results;
    }
    function getagentRecords($cmp_id) {
        $results = Yii::app()->db->createCommand()
                ->select('*')
                ->from('lime_panel_list_agetnt_master c')
                ->where('cmp_id=:id', array(':id'=>$cmp_id))
                ->queryAll();
        return $results;        
    }
    function getfroduser($cmp_id)
    {
        $results = Yii::app()->db->createCommand()
                ->select('count(*) as initcnt')
                ->from('lime_panel_list_master plm')
                ->where('cmp_id=:id', array(':id'=>$cmp_id))
                ->andwhere('is_fraud=:sid', array(':sid'=>1))
                ->queryAll();

              
                return $results[0]['initcnt'];
    }
    function getcompletedreguser($cmp_id,$status)
    {
        $results = Yii::app()->db->createCommand()
                ->select('count(*) as initcnt')
                ->from('lime_panel_list_master plm')
                ->where('cmp_id=:id', array(':id'=>$cmp_id))
                ->andwhere('status=:sid', array(':sid'=>$status))
                ->queryAll();

              
                return $results[0]['initcnt'];
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
        
        $oUser->campaign_src_id = (isset($data['campaign_src_id'])) ? $data['campaign_src_id'] : '';
        $oUser->campaign_cst_id = (isset($data['campaign_cst_id'])) ? $data['campaign_cst_id'] : '';
        $oUser->campaign_status = (isset($data['campaign_status'])) ? $data['campaign_status'] : '';
        $oUser->notes = (isset($data['notes'])) ? $data['notes'] : '';
        $oUser->created_by = $data['add_id'];
        $oUser->created_date = date('Y/m/d h:i:s');
        $oUser->page_id = (isset($data['page_id'])) ? $data['page_id'] : '';
        $oUser->project_id = (isset($data['project_id'])) ? $data['project_id'] : '';
       //echo print_r($oUser); exit();
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
            
            $oUser->campaign_src_id = (isset($data['campaign_src_id'])) ? $data['campaign_src_id'] : '';
            $oUser->campaign_cst_id = (isset($data['campaign_cst_id'])) ? $data['campaign_cst_id'] : '';
            $oUser->campaign_status = (isset($data['campaign_status'])) ? $data['campaign_status'] : '';
            $oUser->notes = (isset($data['notes'])) ? $data['notes'] : '';
            $oUser->page_id = (isset($data['page_id'])) ? $data['page_id'] : '';
            $oUser->project_id = (isset($data['project_id'])) ? $data['project_id'] : '';
           
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
