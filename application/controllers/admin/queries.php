<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class queries extends Survey_Common_Action {

    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('panellist', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = queryview();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $this->_renderWrappedTemplate('/panellist/query', 'view_query', $aData);
    }

    public function send() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('panellist', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $project_id = (isset($_GET['prjid'])) ? $_GET['prjid'] : '';
        $aData['prjid'] = $project_id;
        $vid = (isset($_GET['vid'])) ? $_GET['vid'] : '';
        $aData['vid'] = $vid;
        if ($action == "Send") {

            $query_id = (int) Yii::app()->request->getPost("query_id");
            $project_id = (int) Yii::app()->request->getPost("project_id");
            $stack = " LIMIT " . (int) Yii::app()->request->getPost("stack");
            $getpids = GetPanellistIDsForSend($query_id, $project_id, $action, $stack);
            $user_id = Yii::app()->user->id;
            $created_date = Date('y-m-d h:i:s');
            $send_date = Date('y-m-d h:i:s');
            $sid = getmaxsendid() + 1;
            $subjectid = 1;
            //$template_id = 1;
            $template_id = EMAIL_POINT_QueryPullSend;
            $is_send = 1;
            foreach ($getpids as $key) {
                $pid = (int) $key['panellist_id'];

                $sql_insert = "insert into {{query_send_details}} (send_id,query_id,project_id,subjectt_id,template_id,panellist_id,send,userid,created_date,send_date) values
                ($sid,$query_id,$project_id,$subjectid,$template_id,$pid, $is_send,$user_id,'$created_date','$send_date')";
                $rString = Yii::app()->db->createCommand($sql_insert)->execute();
            }
            $msg = $clang->gT('Send was queued successfully');
            $msg .='<br/>';
            $msg .=$clang->gT('Invitations shall be send on next cron execute');

            Yii::app()->setFlashMessage($msg);
            // if pid vid condition
            if ($_POST['pid'] != '' && $_POST['vid'] != '') {
                $pid = $_POST['pid'];
                $vid = $_POST['vid'];
                //echo '<script>$.fancybox.close()</script>;';
                $this->getController()->redirect(array("admin/project/sa/modifyproject/project_id/$pid/action/modifyvendor/vid/$vid"));
            } else {
                $this->getController()->redirect(array("admin/pquery/index"));
            }
        }
        if ($action == "Resend") {
            $query_id = (int) Yii::app()->request->getPost("query_id");
            $project_id = (int) Yii::app()->request->getPost("project_id");
            $stack = " LIMIT " . (int) Yii::app()->request->getPost("stack");
            $getpids = GetPanellistIDsForSend($query_id, $project_id, $action, $stack);
            $user_id = Yii::app()->user->id;
            $created_date = Date('y-m-d h:i:s');
            $send_date = Date('y-m-d h:i:s');
            $subjectid = 1;
            $template_id = EMAIL_POINT_QueryPullSend;
            $is_send = 0;
            foreach ($getpids as $key) {
                $pid = (int) $key['panellist_id'];

                $sql_insert = "update  {{query_send_details}} 
               set reminder = 1,
               status = 0
               where project_id = $project_id               
               and panellist_id = $pid";
                $rString = Yii::app()->db->createCommand($sql_insert)->execute();
            }

            Yii::app()->setFlashMessage($clang->gT("Reminder was queued successfully"));
            if ($_POST['pid'] != '' && $_POST['vid'] != '') {
                $pid = $_POST['pid'];
                $vid = $_POST['vid'];
                //echo '<script>$.fancybox.close()</script>;';
                $this->getController()->redirect(array("admin/project/sa/modifyproject/project_id/$pid/action/modifyvendor/vid/$vid"));
            } else {
                $this->getController()->redirect(array("admin/pquery/index"));
            }
        }

        $aData['row'] = 0;
        $aData['query_id'] = $_REQUEST['id'];
        $aData['project_id'] = $_REQUEST['prjid'];
        $aData['query_name'] = $_REQUEST['qname'];
        if(isset($_REQUEST['res'])){
            $aData['res'] = $_REQUEST['res'];
            $aData['est'] = $_REQUEST['est'];
        }
        if (isset($_REQUEST['resend']))
            $aData['type'] = 'Resend';
        else
            $aData['type'] = 'Send';
        //echo $_REQUEST['id'];exit();
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $aData['clang'] = $clang = Yii::app()->lang;
        $aData['sImageURL'] = Yii::app()->getConfig('adminimageurl');
        //popup
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->getController()->renderPartial('/admin/panellist/query/' . 'send_query', $aData);
        }
    }

    public function history() {
        $aData['row'] = 0;
        //$aData['query_id'] = 1;
        $aData['project_id'] = $_REQUEST['prjid'];
        $aData['type'] = 'History';
        $aData['history'] = getsendinghistory($_REQUEST['prjid']);
        //echo $_REQUEST['id'];exit();
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $aData['clang'] = $clang = Yii::app()->lang;
        $aData['sImageURL'] = Yii::app()->getConfig('adminimageurl');
        //popup
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->getController()->renderPartial('/admin/panellist/query/' . 'send_history', $aData);
        }
    }

    public function add() {

        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('panellist', 'create')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('panellist', 'create')) {
            $project_id = (isset($_GET['prjid'])) ? $_GET['prjid'] : '';
            $aData['prjid'] = $project_id;
            $vid = (isset($_GET['vid'])) ? $_GET['vid'] : '';
            $aData['vid'] = $vid;
            if ($action == "addquery") {

                $title = flattenText($_POST['query_title'], false, true, 'UTF-8', true);
                $project_id = (int) Yii::app()->request->getPost("project_id");
                $qstring = addslashes($_POST['query_sql']);
                $zip = trim($_POST['zipcode']);
                //$age = $_POST['toage'] . "," . $_POST['fromage'];//14/06/2014 Remove BY Hari
                $age = isset($_POST['toage']) ? $_POST['toage'] : '0'; //14/06/2014 Add BY Hari
                $age.= ","; //14/06/2014 Add BY Hari
                $age.=isset($_POST['fromage']) ? $_POST['fromage'] : '0'; //14/06/2014 Add BY Hari
                $country = (int) Yii::app()->request->getPost("country");
                $total_panellists = (int) Yii::app()->request->getPost("total_panellists");
                $user_id = Yii::app()->user->id;
                $created_date = Date('y-m-d h:i:s');
                $modified_date = Date('y-m-d h:i:s');
                $sql_insert = "insert into {{query_master}} (name,qstring,project_id,zip,age, country,total_panellists,user_id,created_date,modified_date) values
                ('$title','$qstring',$project_id,'$zip','$age',$country,$total_panellists,$user_id,'$created_date','$modified_date')";
                $rString = Yii::app()->db->createCommand($sql_insert)->execute();
                $query_id = Yii::app()->db->getLastInsertID('{{query_master}}');
                if (isset($_POST['query_detail'])) {
                    foreach ($_POST['query_detail'] as $question_id => $answer_id) {
                        if (is_array($answer_id)) {
                            foreach ($answer_id as $answer) {
                                $insertQuery = "insert into {{query_detail}} set
                                                        query_id = '" . $query_id . "',
                                                        question_id = '" . $question_id . "',
                                                        answer_id = '" . $answer . "'";
                                $rString = Yii::app()->db->createCommand($insertQuery)->query();
                            }
                        } else {
                            $insertQuery = "insert into {{query_detail}} set
                                                        query_id = '" . $query_id . "',
                                                        question_id = '" . $question_id . "',
                                                        answer_id = '" . $answer_id . "'";
                            $rString = Yii::app()->db->createCommand($insertQuery)->query();
                        }
                    }
                }

                Yii::app()->setFlashMessage($clang->gT("Query added successfully"));
                if ($_POST['pid'] != '') {
                    $pid = $_POST['pid'];
                    $vid = $_POST['vid'];
                    //echo '<script>$.fancybox.close()</script>;';
                    $this->getController()->redirect(array("admin/project/sa/modifyproject/project_id/$pid/action/modifyvendor/vid/$vid"));
                } else {
                    $this->getController()->redirect(array("admin/pquery/index"));
                }
            } else {
                $aViewUrls = 'addquery_view';
            }

            $this->_renderWrappedTemplate('panellist/query', $aViewUrls, $aData);
        }
    }

    function mod() {
        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();
        if (Permission::model()->hasGlobalPermission('panellist', 'update')) {
            $query_id = (int) Yii::app()->request->getPost("query_id");
            if ($action == "editquery") {
                if (isquerysent($query_id)) {
                    Yii::app()->setFlashMessage(Yii::app()->lang->gT("You can not update this query. This query has been sent."), 'error');
                    $this->getController()->redirect(array("admin/pquery/index"));
                    return 1;
                }
                // $query_id = (int) Yii::app()->request->getPost("query_id");
                $title = flattenText($_POST['query_title'], false, true, 'UTF-8', true);
                $project_id = (int) Yii::app()->request->getPost("project_id");
                $qstring = addslashes($_POST['query_sql']);
                $zip = trim($_POST['zipcode']);
                $age = $_POST['toage'] . "," . $_POST['fromage'];
                $country = (int) Yii::app()->request->getPost("country");
                $total_panellists = (int) Yii::app()->request->getPost("total_panellists");
                $user_id = Yii::app()->user->id;
                $created_date = Date('y-m-d h:i:s');
                $modified_date = Date('y-m-d h:i:s');
                $sql_insert = "update {{query_master}} set
                        name ='$title'
                        ,qstring ='$qstring'
                        ,project_id= $project_id
                        ,zip='$zip'
                        ,age ='$age'
                        ,country=$country
                        ,total_panellists=$total_panellists
                        ,modified_date ='$modified_date'
                        where id= $query_id";

                $rString = Yii::app()->db->createCommand($sql_insert)->query();


                $delquery = "Delete from {{query_detail}} where query_id = " . $query_id;
                $dString = Yii::app()->db->createCommand($delquery)->query();

                if (isset($_POST['query_detail'])) {
                    foreach ($_POST['query_detail'] as $question_id => $answer_id) {
                        if (is_array($answer_id)) {
                            foreach ($answer_id as $answer) {
                                $insertQuery = "insert into {{query_detail}} set
                                                            query_id = '" . $query_id . "',
                                                            question_id = '" . $question_id . "',
                                                            answer_id = '" . $answer . "'";
                                $rString = Yii::app()->db->createCommand($insertQuery)->query();
                            }
                        } else {
                            $insertQuery = "insert into {{query_detail}} set
                                                            query_id = '" . $query_id . "',
                                                            question_id = '" . $question_id . "',
                                                            answer_id = '" . $answer_id . "'";
                            $rString = Yii::app()->db->createCommand($insertQuery)->query();
                        }
                    }
                }

                Yii::app()->setFlashMessage($clang->gT("Query Updated successfully"));
                if ($_POST['pid'] != '') {
                    $pid = $_POST['pid'];
                    $vid = $_POST['vid'];
                    //echo '<script>$.fancybox.close()</script>;';
                    $this->getController()->redirect(array("admin/project/sa/modifyproject/project_id/$pid/action/modifyvendor/vid/$vid"));
                } else {
                    $this->getController()->redirect(array("admin/pquery/index"));
                }
                //$this->getController()->redirect(array("admin/pquery/index"));
            } else {
                if (isset($_POST['query_id'])) {

                    $aData['row'] = 0;
                    $aData['usr_arr'] = array();

                    $project_id = (isset($_GET['prjid'])) ? $_GET['prjid'] : '';
                    $aData['prjid'] = $project_id;
                    $vid = (isset($_GET['vid'])) ? $_GET['vid'] : '';
                    $aData['vid'] = $vid;

                    $query_id = (int) Yii::app()->request->getPost("query_id");
                    $action = Yii::app()->request->getPost("action");
                    $sresult = queryview($query_id);

                    $aData['query_id'] = $query_id;
                    $aData['mur'] = $sresult;
                    $this->_renderWrappedTemplate('panellist/query', 'editquery_view', $aData);
                    return;
                }
            }
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/pquery/index"));
    }

    private function _messageBoxWithRedirect($title, $message, $classMsg, $extra = "", $url = "", $urlText = "", $hiddenVars = array(), $classMbTitle = "header ui-widget-header") {
        $clang = Yii::app()->lang;
        $url = (!empty($url)) ? $url : $this->getController()->createUrl('admin/contact_group/index');
        $urlText = (!empty($urlText)) ? $urlText : $clang->gT("Continue");

        $aData['title'] = $title;
        $aData['message'] = $message;
        $aData['url'] = $url;
        $aData['urlText'] = $urlText;
        $aData['classMsg'] = $classMsg;
        $aData['classMbTitle'] = $classMbTitle;
        $aData['extra'] = $extra;
        $aData['hiddenVars'] = $hiddenVars;

        return $aData;
    }

    function fillquestions() {
        $sValue = $_REQUEST['sValue'];
        $qString = "SELECT DISTINCT id, short_title
            FROM (SELECT distinct q.id, q.short_title FROM {{profile_answer}} a, {{profile_question}} q, {{profile_category}} c
            where a.question_id = q.id and a.category_id = c.id and q.IsActive=1
            and (q.short_title like '%" . $sValue . "%' or q.title like '%" . $sValue . "%' or c.title like '%" . $sValue . "%' or a.`title` like '%" . $sValue . "%') order by a.category_id,a.question_id) s";

        $rString = Yii::app()->db->createCommand($qString)->query()->readAll();


        $qList = "";
        if (count($rString) > 0) {
            for ($i = 0; $i < count($rString); $i++) {
                $qrs = $rString[$i];
                $buttonname = "question_id_" . $qrs['id'];
                $qList .= '<div style="" class="inline_block"><a id="' . $buttonname . '" class="qfilter"  onclick="add_filter(this.id, this.rel);" rel="' . $qrs['short_title'] . '">+</a>&nbsp;' . $qrs['short_title'] . '</div>';
            }
            echo $qList;
        } else {
            echo "No question found for your search criteria!!!";
        }
    }

    function disp_query_questions() {
        $sValue = $_REQUEST['sValue'];
        $tValue = $_REQUEST['tValue'];
        $qID = str_replace("question_id_", "", $sValue);
        $sValue = $_REQUEST['sValue'];
        $qString = "select * from {{profile_answer}} where question_id = '" . $qID . "'";

        $rString = Yii::app()->db->createCommand($qString)->query()->readAll();

        // $qSql = mysql_query($qString) or die("There is error in your Sql Query !!!<br />" . $qString);
        $anslist = "";

        if (count($rString) > 0) {
            for ($i = 0; $i < count($rString); $i++) {
                $ars = $rString[$i];
                $anslist .= '<label style="cursor: pointer;" for="' . $sValue . '_' . $i . '">
                     <input style="vertical-align: middle;" type="checkbox" name="' . $sValue . '[]" id="' . $sValue . '_' . $i . '" value="' . $ars['id'] . '">
                     <span style="vertical-align: middle;">' . $ars['title'] . '</span></label><br />';
            }

            echo '<div class="' . $sValue . '_div" style="float:left; background:#e5e5e5; margin:4px; border: 1px solid #000;width:22%;">
                <div style="background:white; width:100%;font-weight: bold;margin-bottom: 6px;">' . $tValue . '</div>
                <div style="padding:5px; ">' . $anslist . '</div>
                <div style="padding:5px; ">
                    <span style="width:100%; background:red; color:white; padding:0px 4px; cursor:pointer;" 
                        onclick=removeNode("' . $sValue . '_div");>X</span>
                </div>
                </div>';
        }
    }

    function disp_query_result() {
        $whr = "";
        $dwhr = "";
        $qarr = "";
        $zip = "";

        foreach ($_REQUEST as $key => $value) {
            $pos = strpos($key, "question_id");
            if ($pos !== false) {
                if (is_array($_REQUEST[$key])) {
                    $total = count($_REQUEST[$key]);
                    $i = 1;
                    $str = "";
                    // $dstr = "";
                    $str = $key . " in(";
                    $dkey = str_replace('question_id_', '', $key);
                    //$q_title = getQuestionShortText($dkey);
                    //$dstr = $q_title . " in(";
                    foreach ($_REQUEST[$key] as $qkey => $qvalue) {
                        $qarr .= '<input type="hidden" name="query_detail[' . $dkey . '][]" value="' . $qvalue . '">';
                        if ($i == $total) {
                            $str .= $qvalue . ")";
                            //$dstr .= getAnswerText($qvalue) . ")";
                        } else {
                            $str .= $qvalue . ",";
                            //$dstr .= getAnswerText($qvalue) . ",";
                        }
                        $i++;
                    }
                    $whr .= " AND " . $str;
                    //$dwhr .= " AND " . $dstr;
                    $str = "";
                    //$dstr = "";
                } else {
                    if ($value != "") :
                        $zipid = my_question_id('Zip');
                        $zips = explode(",", $value);

                        if (count($zips) == 1) :
                            $zvalue = "'" . $value . "'";
                        else :
                            foreach ($zips as $zkey => $zvalue) :
                                $zips[$zkey] = "'" . $zvalue . "'";
                            endforeach;
                            $zvalue = implode(",", $zips);
                        endif;

                        $str = $key . " in (" . $zvalue . ") ";
                        $dkey = str_replace('question_id_', '', $key);
                        if ($dkey == $zipid) :
                            $zip = $zvalue;
                        else :
                            //$q_title = getQuestionShortText($dkey);
                            $qarr .= '<input type="hidden" name="query_detail[' . $dkey . ']" value="' . $zvalue . '">';
                            //$dstr = $q_title . ' in ("' . $zvalue . '") ';
                            $whr .= " AND " . $str;
                        //$dwhr .= " AND " . $dstr;
                        endif;
                        $str = "";
                        $dstr = "";
                    endif;
                }
            }
        }

        //14/06/2014 Add isset condtion by hari
        if (isset($_REQUEST['country']) && isset($_REQUEST['zipcode'])) {
            if (trim($_REQUEST['country']) == "" && trim($_REQUEST['zipcode']) == "") {
                echo'<script>alert("Please select a Geographical filer. ");</script>';
                exit();
            } else {
                if (trim($_REQUEST['zipcode']) != "")
                    $whr .= " AND question_id_" . my_question_id('Zip') . " in (" . $_REQUEST['zipcode'] . ") ";
                if ($_REQUEST['country'] != "")
                    $whr .= " AND question_id_" . my_question_id('Country') . " = " . $_REQUEST['country'] . " ";
            }
        }
        //14/06/2014 End
        //Age details
        //14/06/2014 Add isset condtion by hari
        if (isset($_REQUEST['toage']) && isset($_REQUEST['fromage'])) {
            if (($_REQUEST['toage'] != "") && ($_REQUEST['fromage'] != "" )) {
                $toage = (int) $_REQUEST['toage'];
                $fromage = (int) $_REQUEST['fromage'];
                //floor(datediff(curdate(), question_id_" . my_question_id('DOB') . ")/365.25)  
                $whr .= " AND floor(datediff(curdate(), question_id_" . my_question_id('DOB') . ")/365.25)   Between " . $fromage . " and  " . $toage . " ";
                $dwhr .= " AND floor(datediff(curdate(), question_id_" . my_question_id('DOB') . ")/365.25)   Between " . $fromage . " and  " . $toage . " ";
            } else {
                if (($_REQUEST['toage'] != "") && ($_REQUEST['fromage'] == "" )) {
                    echo'<script>alert("Please check Age range");</script>';
                    exit();
                }
                if (($_REQUEST['toage'] == "") && ($_REQUEST['fromage'] != "" )) {
                    echo'<script>alert("Please check Age range");</script>';
                    exit();
                }
            }
        }
        //14/06/2014 End
        $whr .= ' AND is_fraud=0 AND status =\'E\' ';
        $sql_return = "Select * from {{panellist_answer}} where status='E' $whr";

        //Echo $sql_return;
        $rString = Yii::app()->db->createCommand($sql_return)->query()->readAll();
        $total_invite_users_per_project = 0;
        $total_panellists_average = 0;
        $assigned_panellists_average = 0;
        $total_estimation_completed = 0;      
        $assigned_estimation_completed = 0; 

        $panalistids = '';
        foreach ($rString as $pl) {
            $panalistids .= $pl['panellist_id'].',' ;
        }
        
        $ir = $total_invite_users_per_project = $total_servey_response_by_project_user = 0;
        if(isset($_REQUEST['project_id']) && $_REQUEST['project_id'] != '')
        {
            $projectid = $_REQUEST['project_id'];
            $sresult = projectview($projectid);
            $ir = $sresult[0]['IR'];

            if(!empty($panalistids)){
               $panalistids = rtrim($panalistids,',');
            

                $quesql_total_invite = "Select COUNT(*) AS total_invite_cnt, GROUP_CONCAT(panellist_id) AS assigned_panellists from {{query_send_details}} where 1=1 ";
                $quesql_total_invite .= " And panellist_id in(".$panalistids.")  AND project_id =".$projectid; //filter not to send originally send 
                                  
                $qrydetail_total_invite = Yii::app()->db->createCommand($quesql_total_invite)->query()->readAll();
                
                if(isset($qrydetail_total_invite[0]["total_invite_cnt"]) && $qrydetail_total_invite[0]["total_invite_cnt"] > 0){
                   $total_invite_users_per_project = $qrydetail_total_invite[0]["total_invite_cnt"];        
                }

                $ar_panalistids = explode(',', $panalistids);
                $ar_pen_panalistids = array();

                $total_pending_users_per_project = 0;
                if(isset($qrydetail_total_invite[0]["assigned_panellists"]) && $qrydetail_total_invite[0]["assigned_panellists"] != ''){
                    $assigned_panellists = $qrydetail_total_invite[0]["assigned_panellists"];   
                    $ar_assigned_panalistids = explode(',', $assigned_panellists);

                    $arr_assigned_panellists = array_diff($ar_panalistids, $ar_assigned_panalistids);
                    $assigned_panellists = implode(",",$arr_assigned_panellists);

                    $total_invite_users_per_project = count($arr_assigned_panellists);

                }else{
                   $total_invite_users_per_project = count($rString);
                   $assigned_panellists = $panalistids;
                }    
                        
                    if($assigned_panellists != "")
                    {  
                        $query_average_assigned = " select (((
                                                    Select COUNT(*) 
                                                    from {{panellist_redirects}} 
                                                    where panellist_id in('".str_replace(",","','",$assigned_panellists)."') 
                                                )*100)/    
                                                (
                                                    Select COUNT(*) 
                                                    from {{query_send_details}} 
                                                    where panellist_id in('".str_replace(",","','",$assigned_panellists)."')
                                                )) AS total_average"; 
                        $assigned_panellists_res = Yii::app()->db->createCommand($query_average_assigned)->query()->readAll();
                        if(isset($assigned_panellists_res[0]["total_average"]) && $assigned_panellists_res[0]["total_average"] > 0){
                            $assigned_panellists_average = round(round($assigned_panellists_res[0]["total_average"])/$total_invite_users_per_project);        
                        }     
                    }     
                

                    $query_average_total = " select (((
                                            Select COUNT(*) 
                                            from {{panellist_redirects}} 
                                            where panellist_id in('".str_replace(",","','",$panalistids)."')
                                        )*100)/    
                                        (
                                            Select COUNT(*) 
                                            from {{query_send_details}} 
                                            where panellist_id in('".str_replace(",","','",$panalistids)."')
                                        )) AS total_average"; 
                
                $query_average_total_res = Yii::app()->db->createCommand($query_average_total)->query()->readAll();
                if(isset($query_average_total_res[0]["total_average"]) && $query_average_total_res[0]["total_average"] > 0){
                    $total_panellists_average = round(round($query_average_total_res[0]["total_average"])/count($rString));        
                }
            }

            $totalresponse = 0;    
            if($total_invite_users_per_project != 0){    
                $totalresponse = ($total_servey_response_by_project_user * 100 ) / $total_invite_users_per_project;
                $totalresponse = number_format($totalresponse, 0);
            }
        }
            
        $total_estimation_completed = number_format(count($rString)*($total_panellists_average/100)*($ir/100), 3, '.', '');      
        $assigned_estimation_completed = number_format($total_invite_users_per_project*($assigned_panellists_average/100)*($ir/100), 3, '.', ''); 


        echo 'Total count for the selected filter is: <strong>' . count($rString);
        echo '</strong><input type="hidden" name="total_panellists" id="total_panellists" value="' . count($rString) . '"> ('.$total_invite_users_per_project.') Average Response Rate: '.$total_panellists_average.'% ('.$assigned_panellists_average.'%)  Estimated completes based on IR listed : '.$total_estimation_completed.' ('.$assigned_estimation_completed.') <input type="hidden" name="query_sql" id="query_sql" value="' . $whr . '">';
        echo $qarr;
    }

    /**
     * Renders template(s) wrapped in header and footer
     *
     * @param string $sAction Current action, the folder to fetch views from
     * @param string|array $aViewUrls View url(s)
     * @param array $aData Data to be passed on. Optional.
     */
    protected function _renderWrappedTemplate($sAction = 'panellist/profilecategory', $aViewUrls = array(), $aData = array()) {

        $aData['display']['menu_bars']['panellist'] = true;

        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
