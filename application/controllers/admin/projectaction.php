<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Projectaction extends Survey_Common_Action {

    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('projects', 'read')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $userlist = projectview();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'addvendor') {
                $project_id = (int) Yii::app()->request->getPost("project_id");
                $aData['project_id'] = $project_id;
                $this->_renderWrappedTemplate('projects', 'view_addvendor', $aData);
            }
        } else {
            $project_id = (int) Yii::app()->request->getPost("project_id");
            $aData['project_id'] = $project_id;
            $this->_renderWrappedTemplate('projects', 'view_project', $aData);
        }
    }

    public function showids() {

        $aData['row'] = 0;
        if (isset($_GET['vid']))
            $aData['vid'] = (int) $_GET['vid'];
        else
            $aData['prjid'] = $_REQUEST['prjid'];
        $aData['type'] = $_REQUEST['type'];
        $aData['name'] = $_REQUEST['name'];
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $aData['clang'] = $clang = Yii::app()->lang;
        $aData['sImageURL'] = Yii::app()->getConfig('adminimageurl');
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->getController()->renderPartial('/admin/projects/' . 'show_ids', $aData);
        }
    }

    public function allids() {
        $aData['clang'] = $clang = Yii::app()->lang;
        $aData['row'] = 0;
        $aData['prjid'] = $_REQUEST['prjid'];
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $aData['sImageURL'] = Yii::app()->getConfig('adminimageurl');
        $aData['askExt'] = $aData['askPrescreener'] = $aData['askLOI'] = $aData['askReferrer'] = 0;
        if (isset($_REQUEST['askExt'])) {
            $aData['askExt'] = $_REQUEST['askExt'];
        }
        if (isset($_REQUEST['askPrescreener'])) {
            $aData['askPrescreener'] = $_REQUEST['askPrescreener'];
        }
        if (isset($_REQUEST['askLOI'])) {
            $aData['askLOI'] = $_REQUEST['askLOI'];
        }
        if (isset($_REQUEST['askReferrer'])) {
            $aData['askReferrer'] = $_REQUEST['askReferrer'];
        }
        //echo $_REQUEST['askExt'];
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->getController()->renderPartial('/admin/projects/' . 'all_ids', $aData);
        }
    }

    public function uploadfile() {
        $aData['clang'] = $clang = Yii::app()->lang;
        $aData['row'] = 0;
        if (isset($_FILES["codefile"]) && $_FILES["codefile"]["error"] == UPLOAD_ERR_OK) {

            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                die();
            }


            //Is file size is less than allowed size.
            if ($_FILES["codefile"]["size"] > 5242880) {
                die("File size is too big!");
            }

            //allowed file type Server side check
            switch (strtolower($_FILES['codefile']['type'])) {
                //allowed file types
                case 'application/vnd.ms-excel':

                    break;
                default:
                    die('Only CSV file allowed'); //output error
            }

            $ok = 1;
            if ($ok == 1) {
                $query = 'CREATE TABLE IF NOT EXISTS {{temptranslate}} (ID INTEGER, ID2 INTEGER,
            PRIMARY  KEY (ID),INDEX (ID, ID2)) ENGINE=MyISAM';
                Yii::app()->db->createCommand($query)->query();
                //mysql_query($query) or die(mysql_error());

                $fp = fopen($_FILES['codefile']['tmp_name'], 'r') or die("error1");
                $rownum = 1;
                while ($csv_line = fgetcsv($fp, 1024)) {
                    if (is_numeric($csv_line[0])) {
                        $query = 'INSERT IGNORE  INTO  {{temptranslate}} (ID, ID2) values (' . $csv_line[0] . ',' . $rownum . ' )';
                        Yii::app()->db->createCommand($query)->query();
                        //mysql_query($query) or die(mysql_error());
                        $rownum++;
                    } else {
                        $ok = 0;
                        echo 'Row ' . $rownum . ' - ' . $csv_line[0] . ' is not valid ID';
                        break;
                    }
                }
                fclose($fp) or die("can't close file");
                /*
                 * if(unlink($target)){ echo "Error in unlink file".$target; exit;} else { $ok=0;}
                 */
                if ($ok == 1) {
                    die('Success! File Uploaded.');
                } else {
                    $query = 'DROP TABLE {{temptranslate}}';
                    Yii::app()->db->createCommand($query)->query();
                    //$result = mysql_query($query) or die(mysql_error());
                }
            }
            exit;
        } else {
            die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
        }
        //Yii::app()->getController()->renderPartial('/admin/projects/' . 'processupload', $aData);
        //$this->_renderWrappedTemplate('projects', 'processupload', $aData);
    }

    public function translateids() {
        $aData['clang'] = $clang = Yii::app()->lang;
        $aData['row'] = 0;
        $aData['prjid'] = $_REQUEST['prjid'];
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $aData['sImageURL'] = Yii::app()->getConfig('adminimageurl');

        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->getController()->renderPartial('/admin/projects/' . 'translated_ids', $aData);
        }
    }

    public function unique() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'update') && !Permission::model()->hasGlobalPermission('projects', 'update')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }

        $aData['row'] = 0;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $project_id = (int) $_GET['project_id'];
        $aData['project_id'] = $project_id;
        if ($project_id == 0 || $project_id == '') {
            Yii::app()->setFlashMessage($clang->gT("Can not find the project id, Please contact your web master."), 'error');
            $this->getController()->redirect(array("admin/project/index"));
        }


        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        if ($action == 'dellinks') {
            $qty = (int) $_POST['qty'];
            $q = 'DELETE FROM {{client_code}} WHERE status IS NULL AND project_id = "' . $project_id . '" ORDER BY id ASC LIMIT ' . $qty;
            $r = Yii::app()->db->createCommand($q)->query();
            Yii::app()->setFlashMessage($clang->gT("$qty links were removed"));
        }

        if ($action == 'uploadlinks') {
            $file = $_FILES['import_file'];
            $csvfile = fopen($file['tmp_name'], 'r');
            $theData = fgets($csvfile);
            $i = 0;
            while (($data = fgetcsv($csvfile)) !== FALSE) {
                $query = 'INSERT IGNORE  INTO  {{client_code}} (project_id, code) values (' . $project_id . ', "' . $data[0] . '")';
                Yii::app()->db->createCommand($query)->query();
            }
//            while ($csv_line = fgetcsv($fp, 1024)) {
//                if (is_numeric($csv_line[0])) {
//                    $query = 'INSERT IGNORE  INTO  {{temptranslate}} (ID, ID2) values (' . $csv_line[0] . ',' . $rownum . ' )';
//                    Yii::app()->db->createCommand($query)->query();
//                    //mysql_query($query) or die(mysql_error());
//                    $rownum++;
//                } else {
//                    $ok = 0;
//                    echo 'Row ' . $rownum . ' - ' . $csv_line[0] . ' is not valid ID';
//                    break;
//                }
//            }

            fclose($csvfile);
            Yii::app()->setFlashMessage($clang->gT("Links are uploaded!"));
        }
        $this->_renderWrappedTemplate('projects', 'view_unique_links', $aData);
    }

    public function rectify() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('projects', 'read')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');

        $userlist = projectview();
        $aData['row'] = 0;
        $aData['usr_arr'] = $userlist;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $project_id = (int) $_GET['project_id'];
        $aData['project_id'] = $project_id;
        if ($project_id == 0 || $project_id == '') {
            Yii::app()->setFlashMessage($clang->gT("Can not find the project id, Please contact your web master."), 'error');
            $this->getController()->redirect(array("admin/project/index"));
        }
        //23/06/2014 Add By Hari
        $dr = Project::model()->findAllByPk($project_id);
        $row = $dr[0];
        if (isset($row['parent_project_id'])) {
            if ($row['parent_project_id'] <> 0) {
                Yii::app()->setFlashMessage($clang->gT("Child project can not be Rectify."), 'error');
                $this->getController()->redirect(array("admin/project/sa/modifyproject/project_id/" . $project_id . "/action/modifyproject"));
            }
        }
        //23/06/2014 End
        $this->_renderWrappedTemplate('projects', 'view_rectify_redirects', $aData);
    }

    public function importcsv() {
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('projects', 'read')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $clang = Yii::app()->lang;
        $aData['row'] = 0;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $rectify_type = (int) Yii::app()->request->getPost("trueup_option");
        $project_id = (int) $_GET['project_id'];
        $aData['project_id'] = $project_id;
        $aData['file'] = $_FILES['import_file'];
        $currentdatetime = date('Y-m-d H:i:s');

        $file = $_FILES['import_file'];
        if ($file['size'] > 0) {
            $sql = 'CREATE TABLE IF NOT EXISTS {{tmp_import}} 
                (INDEX tmp_import_index (panellist_redirect_id, redirect_status_id,created_datetime))
                ( SELECT panellist_redirect_id,redirect_status_id,created_datetime FROM {{panellist_redirects}})';
            Yii::app()->db->createCommand($sql)->query();

            $sql = 'truncate {{tmp_import}} ';
            Yii::app()->db->createCommand($sql)->query();
            $csvfile = fopen($file['tmp_name'], 'r');
            $theData = fgets($csvfile);
            $i = 0;
            while (!feof($csvfile)) {
                $csv_data[] = fgets($csvfile, 1024);
                $csv_array = explode(",", $csv_data[$i]);
                if (count($csv_array) > 1) {
                    $sql = "INSERT INTO {{tmp_import}} (panellist_redirect_id,redirect_status_id,created_datetime)
                            VALUES('$csv_array[0]','$csv_array[1]','$currentdatetime')";
                    Yii::app()->db->createCommand($sql)->query();
                }
                $i++;
            }
            fclose($csvfile);
            // validation start
            $i = 0;
            $chkfinaltrupexist = Rectify::model()->findAll(array('condition' => 'project_id = ' . $project_id . ' AND rectify_type = 1'));
            if (count($chkfinaltrupexist) > 0) {
                $i = 1;
                Yii::app()->setFlashMessage($clang->gT("The Final RECTIFY is done on this project So you can not RECTIFY this project."));
                $this->getController()->redirect(array("admin/project/rectify/project_id/$project_id"));
            }
            //24/06/2014 Add By Hari
            //$ValSql = "SELECT pr.panellist_redirect_id FROM {{panellist_redirects}} pr
            //        left outer join {{project_master}} pm on pr.project_id=pm.project_id
            //        WHERE (pm.project_id='$project_id' OR pm.parent_project_id = '$project_id') AND pr.rectify_id IS NULL AND pr.redirect_status_id='" . getGlobalSetting('redirect_status_completed') . "'";
            $ValSqlResult = Project::model()->GetProjectCompletedNotRectify($project_id); //Yii::app()->db->createCommand($ValSql)->query()->readAll();
            $Data = '';
            $Exit = 0;
            foreach ($ValSqlResult as $ky => $val) {
                $tmpSql = "select * from {{tmp_import}} WHERE panellist_redirect_id ='" . $val['panellist_redirect_id'] . "'";
                $tempResult = Yii::app()->db->createCommand($tmpSql)->query()->readAll();
                if (count($tempResult) <= 0) {
                    $Exit = 1;
                    $Data .= $val['panellist_redirect_id'] . ",";
                }
            }
            $_SESSION['Data'] = $Data;
            if ($Exit == 1) {
                $sql = 'truncate {{tmp_import}} ';
                Yii::app()->db->createCommand($sql)->query();
                Yii::app()->setFlashMessage($clang->gT("There are certain redirects which are completed but yet not rectified. So can not import."), 'error');
                $this->getController()->redirect(array("admin/project/sa/rectify/project_id/" . $project_id . "/NotRectify/true"));
            }
            //24/06/2014 End
            $trerror = '';
            if ($i == 0) {
                $pl = "select * from {{tmp_import}}";
                $result_temp = Yii::app()->db->createCommand($pl)->query()->readAll();
                $odd = false;
                foreach ($result_temp as $ky => $val) {
                    $Error = '';
                    //check duplicate panellist redirect id exist in table
                    $sql = "SELECT COUNT(*) AS cnt FROM {{tmp_import}} WHERE panellist_redirect_id ='" . $val['panellist_redirect_id'] . "'";
                    $result = Yii::app()->db->createCommand($sql)->queryRow();
                    if ($result['cnt'] >= 2) {
                        $i = 1;
                        $Error .= 'Duplicate RedirectID found in CSV' . ' && ';
                    }

                    //chk panellist exist or not
                    $sql = "SELECT COUNT(*) AS cnt FROM {{panellist_redirects}} WHERE panellist_redirect_id ='" . $val['panellist_redirect_id'] . "'";
                    $result = Yii::app()->db->createCommand($sql)->queryRow();
                    if ($result['cnt'] == 0) {
                        $i = 1;
                        $Error .= 'RedirectID not found in database' . ' && ';
                    }

                    //chk trueupexist or not;
                    $sql = "SELECT COUNT(*) AS cnt FROM {{panellist_redirects}} WHERE panellist_redirect_id ='" . $val['panellist_redirect_id'] . "' AND rectify_id != ''";
                    $result = Yii::app()->db->createCommand($sql)->queryRow();
                    if ($result['cnt'] > 0) {
                        $i = 1;
                        $Error .= 'RedirectID Already trueup' . ' && ';
                    }

                    //chk status is correct or not
                    $sql_status = "select status_id,status_name from {{project_status_master}} where status_for = 'r'";
                    $result_project = Yii::app()->db->createCommand($sql_status)->query()->readAll();
                    $status = array();
                    foreach ($result_project as $vale => $st) {
                        $status[] = $st['status_id'];
                    }
                    //print_r($status);
                    if (!in_array($val['redirect_status_id'], $status)) {
                        $i = 1;
                        $Error .= 'Incorrect Status Found in CSV' . ' && ';
                    }
                    $sql_project = "SELECT status_id,status_name FROM {{project_status_master}} WHERE status_id = '" . $val['redirect_status_id'] . "'";
                    $result_project = Yii::app()->db->createCommand($sql_project)->queryRow();
                    if ($odd) {
                        $cls = 'class = "odd"';
                    } else {
                        $cls = 'class = "even"';
                    }
                    $trerror .= '<tr ' . $cls . '>
                                <td>' . $val['panellist_redirect_id'] . '</td>
                                <td nowrap="nowrap">' . $val['redirect_status_id'] . ' - ' . $result_project['status_name'] . '</td>
                                <td>' . rtrim($Error, " && ") . '</td>
                             </tr>';
                    $odd = !$odd;
                }
            }
            $aData['trerror'] = $trerror;
            $st_completed = getGlobalSetting('redirect_status_completed');
            $st_d = getGlobalSetting('redirect_status_disqual');
            $st_qf = getGlobalSetting('redirect_status_qf');
            $st_r = getGlobalSetting('redirect_status_redirected');
            $st_f = getGlobalSetting('redirect_status_rej_fail');
            $st_inc = getGlobalSetting('redirect_status_rej_incosist');
            $st_q = getGlobalSetting('redirect_status_rej_quality');
            $st_s = getGlobalSetting('redirect_status_rej_speed');
            $st_p = getGlobalSetting('redirect_status_rej_poor');
            if ($i == 0) {
                // import data
                $result = array();
                $sql1 = "SELECT rectify_no FROM {{rectify_redirects}} WHERE project_id = '$project_id'";
                $result = Yii::app()->db->createCommand($sql1)->queryRow();
                $rectify_no = $result['rectify_no'] + 1;
                $currentdate = date('Y-m-d');
                $rectify_id = Rectify::model()->insertrectify($project_id, $rectify_type, $rectify_no, $currentdate);
// Here is the problem 

                foreach ($result_temp as $key => $value) {
                    $query = Rectify::model()->updatePanellistRedirect($value['panellist_redirect_id'], $value['redirect_status_id'], $rectify_id);
                    $sql = "select * from {{panellist_redirects}} where panellist_redirect_id = '" . $value['panellist_redirect_id'] . "'";
                    $resultpr = Yii::app()->db->createCommand($sql)->queryRow();

                    switch ($value['redirect_status_id']) {
                        case $st_completed:
                            $status_pp = 'C';
                            break;
                        case $st_d:
                            $status_pp = 'D';
                            break;
                        case $st_qf:
                            $status_pp = 'Q';
                            break;
                        case $st_r:
                            $status_pp = 'R';
                            break;
                        case $st_f:
                            $status_pp = 'E1';
                            break;
                        case $st_inc:
                            $status_pp = 'E2';
                            break;
                        case $st_q:
                            $status_pp = 'E3';
                            break;
                        case $st_s:
                            $status_pp = 'E4';
                            break;
                        case $st_p:
                            $status_pp = 'E5';
                            break;
                        default:
                            break;
                    }
// or here 
                    if ($resultpr['vendor_id'] == getGlobalSetting('Own_Panel')) {
                        $update_pp = "update {{panellist_project}} set 
                            status = '$status_pp' 
                            where panellist_id = '" . $resultpr['panellist_id'] . "' and project_id ='" . $resultpr['project_id'] . "' ";
                        $result = Yii::app()->db->createCommand($update_pp)->query();
                        $select_pp = "select IFNULL(points,0) as points from {{panellist_project}} where panellist_id = '" . $resultpr['panellist_id'] . "' and  project_id = '" . $resultpr['project_id'] . "'";
                        $qry_pp = Yii::app()->db->createCommand($select_pp)->queryRow();
                        //echo 'status'.$status_pp;
                        if ($status_pp == 'C') {
                            $update_pm = "update  {{panel_list_master}} set 
                                earn_points = earn_points + " . $qry_pp['points'] . ",
                                balance_points = balance_points + " . $qry_pp['points'] . "
                                where panel_list_id = '" . $resultpr['panellist_id'] . "' ";
                            $result = Yii::app()->db->createCommand($update_pm)->query();
                        }
                    }
                } //end for loop
                $sqlProject = "SELECT project_id FROM {{project_master}} WHERE project_id = '$project_id' OR parent_project_id = '$project_id'";
                $qurProject = Yii::app()->db->createCommand($sqlProject)->query()->readAll();
                $st_error = $st_f . ',' . $st_inc . ',' . $st_q . ',' . $st_s . ',' . $st_p;
                if (count($qurProject) > 0) {
                    foreach ($qurProject as $key => $value) {
                        $temp_prjid = $value['project_id'];
                        // Updating status of the project based on trueup
                        $numQFull = getProjectStatusCount_Trueup($temp_prjid, $st_qf);
                        $numDisqualified = getProjectStatusCount_Trueup($temp_prjid, $st_d);
                        $numCompleted = getProjectStatusCount_Trueup($temp_prjid, $st_completed);
                        $numErrored = getProjectStatusCount_Trueup($temp_prjid, $st_error);

                        $numQFull = (int) $numQFull;
                        $numDisqualified = (int) $numDisqualified;
                        $numCompleted = (int) $numCompleted;
                        $numErrored = (int) $numErrored;
                        $sql = "update {{project_master}}
                        set
                        trueup = '" . $currentdatetime . "',
                        total_quota_full = '" . $numQFull . "',
                        total_disqualify = '" . $numDisqualified . "',
                        total_completed = '" . $numCompleted . "',
                        total_errors = '" . $numErrored . "'
                        where project_id = '" . $temp_prjid . "'";

                        $result = Yii::app()->db->createCommand($sql)->query();
                        $numQFull = 0;
                        $numDisqualified = 0;
                        $numCompleted = 0;
                        $numErrored = 0;
                    }
                }
                $sqlVendor = "SELECT vr.vendor_project_id,vr.project_id FROM {{project_master_vendors}} vr,{{project_master}} pm
                        WHERE pm.project_id = vr.project_id AND vr.project_id IN ($project_id)
                        UNION ALL
                        SELECT vr.vendor_project_id,vr.project_id FROM {{project_master_vendors}} vr,{{project_master}} pm
                        WHERE pm.project_id = vr.project_id AND pm.parent_project_id IN ($project_id) ";
                $getVstatus = Yii::app()->db->createCommand($sqlVendor)->query()->readAll();
                if (count($getVstatus) > 0) {
                    foreach ($getVstatus as $key => $value) {
                        $vendorid = $value['vendor_project_id'];
                        $temp_prjid = $value['project_id'];
                        $numQFull = getVStatusCount_Trueup($temp_prjid, $vendorid, $st_qf);
                        $numDisqualified = getVStatusCount_Trueup($temp_prjid, $vendorid, $st_d);
                        $numCompleted = getVStatusCount_Trueup($temp_prjid, $vendorid, $st_completed);
                        $numErrored = getVStatusCount_Trueup($temp_prjid, $vendorid, $st_error);

                        $updvendor = "update {{project_master_vendors}} set
                                      total_quota_full = '" . $numQFull . "',
                                      total_disqualified = '" . $numDisqualified . "',
                                      total_completed = '" . $numCompleted . "',
                                      total_errors = '" . $numErrored . "'
                                      where project_id = '" . $temp_prjid . "' and vendor_project_id = '" . $vendorid . "'";
                        $result = Yii::app()->db->createCommand($updvendor)->query();
                        $numQFull = 0;
                        $numDisqualified = 0;
                        $numCompleted = 0;
                        $numErrored = 0;
                    }
                }

                //add project_master update and project_vendor_master update for total_completed, total_*



                Yii::app()->setFlashMessage($clang->gT("File data successfully imported to database."));
                $sql = 'truncate {{tmp_import}} ';
                Yii::app()->db->createCommand($sql)->query();

                //$this->getController()->redirect(array("admin/project/sa/modifyproject/project_id/$project_id/action/modifyproject"));
            }
        } else {
            Yii::app()->setFlashMessage($clang->gT("No data found the selected file !!!"));
        }
        $this->_renderWrappedTemplate('projects', 'view_rectify_imports', $aData);
    }

    function delproject() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') && !Permission::model()->hasGlobalPermission('projects', 'delete')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = Yii::app()->request->getPost("action");
        $aViewUrls = array();
        $contact_id = (int) Yii::app()->request->getPost("contact_id");
        if ($contact_id) {
            if ($action == "delcontact") {
                $dresult = Contact::model()->deletecontact($contact_id);
                if ($dresult) {
                    $dlt = "DELETE FROM {{map_company_n_types}} WHERE contact_id = " . $contact_id;
                    $result = Yii::app()->db->createCommand($dlt)->query();
                    Yii::app()->setFlashMessage($clang->gT("Contact delete successfully"));
                }
            } else {
                Yii::app()->setFlashMessage($clang->gT("Contact does not deleted"), 'error');
            }
            $this->getController()->redirect(array("admin/project/index"));
        } else {
            Yii::app()->setFlashMessage($clang->gT("Could not delete Contact. Contact was not supplied."), 'error');
            $this->getController()->redirect(array("admin/project/index"));
        }

        return $aViewUrls;
    }

    function selectclientcontact() {
        if (isset($_POST['isactive'])) {
            $data = Contact::model()->isactive()->findAll(
                            array(
                                'select' => 'CONCAT_WS(" ",first_name,middle_name,last_name) AS first_name, contact_id',
                                'condition' => 'company_id = ' . (int) $_POST['client_id'] . '', 'order' => 'first_name'));
        } else {
            $data = Contact::model()->findAll(
                            array(
                                'select' => 'CONCAT_WS(" ",first_name,middle_name,last_name) AS first_name, contact_id',
                                'condition' => 'company_id = ' . (int) $_POST['client_id'] . '', 'order' => 'first_name'));
        }
        $data = CHtml::listData($data, 'contact_id', 'first_name');
        if (count($data)) {
            echo CHtml::tag('option', array('value' => '0'), CHtml::encode('Select Contact'), true);
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            echo CHtml::tag('option', array('value' => '0'), CHtml::encode('Select Contact'), true);
        }
        if (isset($_POST['vendor'])) {
            $sql = "SELECT * FROM {{contact_master}} WHERE contact_id = " . (int) $_POST['client_id'];
            $result = Yii::app()->db->createCommand($sql)->queryRow();
            extract($result);
            echo "<script language='javascript' type='text/javascript'>
                $('#completionlink').val('" . $completionlink . "');
                $('#quatafulllink').val('" . $quatafulllink . "');
                $('#disqualifylink').val('" . $disqualifylink . "');
            </script>";
        }
    }

    function fillcompletes() {
        $total_completes = (int) $_POST['total_completes'];
        $percent0 = $total_completes;
        $percent10 = $total_completes + ($total_completes * 0.1);
        $percent50 = $total_completes + ($total_completes * 0.5);
        $percent100 = $total_completes * 2;
        $maxcompletes_array = array("infinite" => "No Max", "percent0" => "0% (" . $percent0 . ")", "percent10" => "10% (" . $percent10 . ")", "percent50" => "50% (" . $percent50 . ")", "percent100" => "100% (" . $percent100 . ")");
        foreach ($maxcompletes_array as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    function filedownload() {
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $_POST['title'] . '.csv"');
        echo $_POST['filebody'];
    }

    public function add() {

        $clang = Yii::app()->lang;
        $action = (isset($_POST['action'])) ? $_POST['action'] : '';
        $aData = array();
        $aViewUrls = array();

        if (Permission::model()->hasGlobalPermission('projects', 'create')) {

            if ($action == "addproject") {
                // Project details
                $project_name = flattenText($_POST['project_name'], false, true, 'UTF-8', true);
                $project_friendly_name = flattenText($_POST['project_friendly_name'], false, true, 'UTF-8', true);
                $parent_project = (int) Yii::app()->request->getPost("parent_project");
                $client = (int) Yii::app()->request->getPost("client");
                $client_contact = (int) Yii::app()->request->getPost("client_contact");
                $project_manager = (int) Yii::app()->request->getPost("project_manager");
                $sales_person = (int) Yii::app()->request->getPost("sales_person");
                $country = (int) Yii::app()->request->getPost("country");
                $quota = flattenText($_POST['quota'], false, true, 'UTF-8', true);
                $maxcompletes = flattenText($_POST['maxcompletes'], false, true, 'UTF-8', true);
                if ($maxcompletes == 'percent0')
                    $QuotaBufferAmnt = $quota;
                if ($maxcompletes == 'percent10')
                    $QuotaBufferAmnt = $quota + ($quota * 0.1);
                if ($maxcompletes == 'percent50')
                    $QuotaBufferAmnt = $quota + ($quota * 0.5);
                if ($maxcompletes == 'percent100')
                    $QuotaBufferAmnt = $quota * 2;
                if ($maxcompletes == 'infinite')
                    $QuotaBufferAmnt = 0;
                $cpc = flattenText($_POST['cpc'], false, true, 'UTF-8', true);
                $los = flattenText($_POST['los'], false, true, 'UTF-8', true);
                $ir = flattenText($_POST['ir'], false, true, 'UTF-8', true);
                $points = flattenText($_POST['points'], false, true, 'UTF-8', true);
                $surveylink = flattenText($_POST['surveylink'], false, true, 'UTF-8', true);
                $notes = flattenText($_POST['notes'], false, true, 'UTF-8', true);
                $status = (int) Yii::app()->request->getPost("status");
                $RIDCheck = "Yes";
                $result = Yii::app()->db->createCommand("SELECT count(*) AS cnt FROM {{contact_master}} WHERE 
                            contact_id = '$client' AND RIDCheck = 'Yes'")->queryRow();
                if ($result['cnt'] <= 0) {
                    $RIDCheck = "No";
                }
                $internal_company = getGlobalSetting('Own_Panel');
                $CPC = ($points / 100);
                $current_datetime = date('y-m-d h:i:s');
                $int_cmp = Contact::model()->findAll(array('condition' => 'contact_id = ' . $internal_company));
                $completed_link = $int_cmp[0]['completionlink'];
                $disqualify_link = $int_cmp[0]['disqualifylink'];
                $quotafull_link = $int_cmp[0]['quatafulllink'];
                if ($project_name == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Project"), 'message' => $clang->gT("A Project Name was not supplied or the Project Name is invalid."), 'class' => 'warningheader');
                } elseif (Project::model()->findByAttributes(array('project_name' => $project_name))) {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Project"), 'message' => $clang->gT("The Project Name already exists."), 'class' => 'warningheader');
                } else {
                    $NewProject = Project::model()->instProject($project_name, $project_friendly_name, $parent_project
                                    , $client, $client_contact, $project_manager, $sales_person, $country, $quota
                                    , $QuotaBufferAmnt, $RIDCheck, $cpc, $los, $ir, $points, $surveylink, $notes, $status);
                    if ($NewProject) {
                        // create own panel

                        $newvendor = Project_vendor::model()->instvendor($NewProject, $internal_company, $internal_company, $status, 'Own Panel'
                                        , $CPC, $quota, $QuotaBufferAmnt, $completed_link, $disqualify_link, $quotafull_link, '0', $current_datetime);
                        $sql = "INSERT INTO {{project_master_vendors}} 
                        (project_id,vendor_id,vendor_contact_id,vendor_status_id,notes,CPC,required_completes
                        ,QuotaBuffer_Completes, completed_link,disQualified_link,QuotaFull_URL,max_redirects
                        ,created_datetime)
                        VALUES('$NewProject','$internal_company','$internal_company','$status','Own Panel'
                        ,'$CPC','$quota','$QuotaBufferAmnt','$completed_link','$disqualify_link'
                        ,'$quotafull_link','0','$current_datetime')";
                        //$result = Yii::app()->db->createCommand($sql)->query();
                        $loginuser = Yii::app()->session['user'];

                        // Update Message Table 
                        $query = 'INSERT INTO {{messages}} (type_id, body, receipid, chainid, created )
                        VALUES (10,"Created by ' . $loginuser . '",0,"' . $NewProject . '", "' . $current_datetime . '")';
                        $result = Yii::app()->db->createCommand($query)->query();

                        Yii::app()->setFlashMessage($clang->gT("Project added successfully"));
                        $this->getController()->redirect(array("admin/project/index"));
                    }
                }
            } elseif ($action == 'addvendor') {
                $project_id = (int) Yii::app()->request->getPost("project_id");
                $vendor_id = (int) Yii::app()->request->getPost("panel");
                $vendor_contact = (int) Yii::app()->request->getPost("vendor_contact");
                $cpc = flattenText($_POST['cpc'], false, true, 'UTF-8', true);
                $maxredirects = flattenText($_POST['maxredirects'], false, true, 'UTF-8', true);
                $completionlink = flattenText($_POST['completionlink'], false, true, 'UTF-8', true);
                $disqualifylink = flattenText($_POST['disqualifylink'], false, true, 'UTF-8', true);
                $quatafulllink = flattenText($_POST['quatafulllink'], false, true, 'UTF-8', true);
                $quota = flattenText($_POST['quota'], false, true, 'UTF-8', true);
                $current_datetime = date('y-m-d h:i:s');
                $maxcompletes = flattenText($_POST['maxcompletes'], false, true, 'UTF-8', true);
                if ($maxcompletes == 'percent0')
                    $QuotaBufferAmnt = $quota;
                if ($maxcompletes == 'percent10')
                    $QuotaBufferAmnt = $quota + ($quota * 0.1);
                if ($maxcompletes == 'percent50')
                    $QuotaBufferAmnt = $quota + ($quota * 0.5);
                if ($maxcompletes == 'percent100')
                    $QuotaBufferAmnt = $quota * 2;
                if ($maxcompletes == 'infinite')
                    $QuotaBufferAmnt = 0;
                $notes = flattenText($_POST['notes'], false, true, 'UTF-8', true);
                $status = (int) Yii::app()->request->getPost("status");
                $newvendor = Project_vendor::model()->instvendor($project_id, $vendor_id, $vendor_contact, $status, $notes
                                , $cpc, $quota, $QuotaBufferAmnt, $completionlink, $disqualifylink, $quatafulllink, $maxredirects, $current_datetime);
                $sql = "INSERT INTO {{project_master_vendors}}
                    (project_id,vendor_id,vendor_contact_id,vendor_status_id,notes,CPC,required_completes
                    ,QuotaBuffer_Completes,completed_link,disQualified_link,QuotaFull_URL, max_redirects
                    ,created_datetime)
                    VALUES('$project_id','$vendor_id','$vendor_contact','$status','$notes','$cpc', 
                    '$quota','$QuotaBufferAmnt','$completionlink','$disqualifylink', 
                    '$quatafulllink','$maxredirects','$current_datetime')";
                //$result = Yii::app()->db->createCommand($sql)->query();
                Yii::app()->setFlashMessage($clang->gT("Vendor added successfully"));
                $this->getController()->redirect(array("admin/project/sa/modifyproject/project_id/$project_id/action/modifyproject"));
                //$this->getController()->redirect(array("admin/project/index"));
            } else {
                $aViewUrls = 'view_addproject';
            }
        } else {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }

        $this->_renderWrappedTemplate('projects', $aViewUrls, $aData);
    }

    function modifyproject() {
        if (isset($_GET['project_id'])) {

            App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
            App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
            $aData = array();
            $aData['row'] = 0;
            $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
            // Project detail
            //$project_id = (int) Yii::app()->request->getPost("project_id");
            $project_id = (int) $_GET['project_id'];
            //$action = Yii::app()->request->getPost("action");
            $action = $_GET['action'];
            $sresult = projectview($project_id);

            $aData['project_id'] = $project_id;
            $aData['vendor_arr'] = project_vendor_view($project_id);
            $aData['mur'] = $sresult;
            if ($action == 'modifyvendor') {
                $userlist = queryview(null, $project_id);
                $aData['usr_arr'] = $userlist;
                $vendor_project_id = (int) $_GET["vid"];
                $aData['vendor_project_id'] = $vendor_project_id;
                $aData['vendor_arr_single'] = project_vendor_view('', $vendor_project_id);
                //echo "<pre>$vendor_project_id</pre>";
                $this->_renderWrappedTemplate('projects', 'view_editvendor', $aData);
            } elseif ($action == 'modifyproject') {
                $this->_renderWrappedTemplate('projects', 'view_editproject', $aData);
            }
            return;
        }
        Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        $this->getController()->redirect(array("admin/index"));
    }

    function add_query() {
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        $aData['clang'] = $clang = Yii::app()->lang;
        $aData['sImageURL'] = Yii::app()->getConfig('adminimageurl');
        $aData['sImageURL'] = Yii::app()->getConfig('adminimageurl');
        $aData['prjid'] = $_REQUEST['prjid'];
        //popup
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->getController()->renderPartial('/admin/panellist/query/' . 'addquery_view', $aData);
        }
    }

    function modproject() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('superadmin', 'read') || !Permission::model()->hasGlobalPermission('projects', 'update')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        $action = (isset($_GET['action'])) ? $_GET['action'] : '';
        if ($action == "editproject") {
            if ($_POST['submit'] == "Clone") {
                // Project details
                $project_name = "Clone-" . flattenText($_POST['project_name'], false, true, 'UTF-8', true);
                $project_friendly_name = flattenText($_POST['project_friendly_name'], false, true, 'UTF-8', true);
                $parent_project = (int) Yii::app()->request->getPost("parent_project");
                $client = (int) Yii::app()->request->getPost("client");
                $client_contact = (int) Yii::app()->request->getPost("client_contact");
                $project_manager = (int) Yii::app()->request->getPost("project_manager");
                $sales_person = (int) Yii::app()->request->getPost("sales_person");
                $country = (int) Yii::app()->request->getPost("country");
                $quota = flattenText($_POST['quota'], false, true, 'UTF-8', true);
                $maxcompletes = flattenText($_POST['maxcompletes'], false, true, 'UTF-8', true);
                if ($maxcompletes == 'percent0')
                    $QuotaBufferAmnt = $quota;
                if ($maxcompletes == 'percent10')
                    $QuotaBufferAmnt = $quota + ($quota * 0.1);
                if ($maxcompletes == 'percent50')
                    $QuotaBufferAmnt = $quota + ($quota * 0.5);
                if ($maxcompletes == 'percent100')
                    $QuotaBufferAmnt = $quota * 2;
                if ($maxcompletes == 'infinite')
                    $QuotaBufferAmnt = 0;
                $cpc = flattenText($_POST['cpc'], false, true, 'UTF-8', true);
                $los = flattenText($_POST['los'], false, true, 'UTF-8', true);
                $ir = flattenText($_POST['ir'], false, true, 'UTF-8', true);
                $points = flattenText($_POST['points'], false, true, 'UTF-8', true);
                $surveylink = flattenText($_POST['surveylink'], false, true, 'UTF-8', true);
                $notes = flattenText($_POST['notes'], false, true, 'UTF-8', true);
                $status = (int) getGlobalSetting('project_status_test');
                //$status = (int) Yii::app()->request->getPost("status");
                $RIDCheck = "Yes";
                $result = Yii::app()->db->createCommand("SELECT count(*) AS cnt FROM {{contact_master}} WHERE 
                            contact_id = '$client' AND RIDCheck = 'Yes'")->queryRow();
                if ($result['cnt'] <= 0) {
                    $RIDCheck = "No";
                }
                $internal_company = getGlobalSetting('Own_Panel');
                $CPC = ($points / 100);
                $current_datetime = date('y-m-d h:i:s');
                $int_cmp = Contact::model()->findAll(array('condition' => 'contact_id = ' . $internal_company));
                $completed_link = $int_cmp[0]['completionlink'];
                $disqualify_link = $int_cmp[0]['disqualifylink'];
                $quotafull_link = $int_cmp[0]['quatafulllink'];
                if ($project_name == '') {
                    $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Project"), 'message' => $clang->gT("A Project Name was not supplied or the Project Name is invalid."), 'class' => 'warningheader');
                } /* elseif (Project::model()->findByAttributes(array('project_name' => $project_name))) {
                  $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Project"), 'message' => $clang->gT("The Project Name already exists."), 'class' => 'warningheader');
                  } */ else {
                    $NewProject = Project::model()->instProject($project_name, $project_friendly_name, $parent_project
                                    , $client, $client_contact, $project_manager, $sales_person, $country, $quota
                                    , $QuotaBufferAmnt, $RIDCheck, $cpc, $los, $ir, $points, $surveylink, $notes, $status);
                    if ($NewProject) {
                        // create own panel
                        $newvendor = Project_vendor::model()->instvendor($NewProject, $internal_company, $internal_company, $status, 'Own Panel'
                                        , $CPC, $quota, $QuotaBufferAmnt, $completed_link, $disqualify_link, $quotafull_link, '0', $current_datetime);

                        $sql = "INSERT INTO {{project_master_vendors}} 
                        (project_id,vendor_id,vendor_contact_id,vendor_status_id,notes,CPC,required_completes
                        ,QuotaBuffer_Completes, completed_link,disQualified_link,QuotaFull_URL,max_redirects
                        ,created_datetime)
                        VALUES('$NewProject','$internal_company','$internal_company','$status','Own Panel'
                        ,'$CPC','$quota','$QuotaBufferAmnt','$completed_link','$disqualify_link'
                        ,'$quotafull_link','0','$current_datetime')";
                        //$result = Yii::app()->db->createCommand($sql)->query();
                        $loginuser = Yii::app()->session['user'];

                        // Update Message Table 
                        $query = 'INSERT INTO {{messages}} (type_id, body, receipid, chainid, created )
                        VALUES (10,"Created by ' . $loginuser . '",0,"' . $NewProject . '", "' . $current_datetime . '")';
                        $result = Yii::app()->db->createCommand($query)->query();

                        Yii::app()->setFlashMessage($clang->gT("Project Cloned successfully"));
                        $this->getController()->redirect(array("admin/project/index"));
                    }
                }
            } else {

                $project_id = (int) Yii::app()->request->getPost("project_id");

                $project_name = flattenText($_POST['project_name'], false, true, 'UTF-8', true);
                $project_friendly_name = flattenText($_POST['project_friendly_name'], false, true, 'UTF-8', true);
                $parent_project = (int) Yii::app()->request->getPost("parent_project");
                $client = (int) Yii::app()->request->getPost("client");
                $client_contact = (int) Yii::app()->request->getPost("client_contact");
                $project_manager = (int) Yii::app()->request->getPost("project_manager");
                $sales_person = (int) Yii::app()->request->getPost("sales_person");
                $country = (int) Yii::app()->request->getPost("country");
                $quota = flattenText($_POST['quota'], false, true, 'UTF-8', true);
                $maxcompletes = flattenText($_POST['maxcompletes'], false, true, 'UTF-8', true);
                if ($maxcompletes == 'percent0')
                    $QuotaBufferAmnt = $quota;
                if ($maxcompletes == 'percent10')
                    $QuotaBufferAmnt = $quota + ($quota * 0.1);
                if ($maxcompletes == 'percent50')
                    $QuotaBufferAmnt = $quota + ($quota * 0.5);
                if ($maxcompletes == 'percent100')
                    $QuotaBufferAmnt = $quota * 2;
                if ($maxcompletes == 'infinite')
                    $QuotaBufferAmnt = 0;
                $cpc = flattenText($_POST['cpc'], false, true, 'UTF-8', true);
                $los = flattenText($_POST['los'], false, true, 'UTF-8', true);
                $ir = flattenText($_POST['ir'], false, true, 'UTF-8', true);
                $points = flattenText($_POST['points'], false, true, 'UTF-8', true);
                $surveylink = flattenText($_POST['surveylink'], false, true, 'UTF-8', true);
                $notes = flattenText($_POST['notes'], false, true, 'UTF-8', true);
                $status = (int) Yii::app()->request->getPost("status");
                $old_status = (int) Yii::app()->request->getPost("old_status");
                //$RIDCheck = flattenText($_POST['RIDCheck'], false, true, 'UTF-8', true);
                $internal_company = getGlobalSetting('Own_Panel');
                $CPC = ($points / 100);
                $current_datetime = date('y-m-d h:i:s');
                $int_cmp = Contact::model()->findAll(array('condition' => 'contact_id = ' . $internal_company));
                $completed_link = $int_cmp[0]['completionlink'];
                $disqualify_link = $int_cmp[0]['disqualifylink'];
                $quotafull_link = $int_cmp[0]['quatafulllink'];
                if ((Permission::model()->hasGlobalPermission('superadmin', 'read') ||
                        (Permission::model()->hasGlobalPermission('projects', 'update')))) {

                    if ($project_name == '') {
                        $aViewUrls['message'] = array('title' => $clang->gT("Failed to edit Project"), 'message' => $clang->gT("A Project Name was not supplied or the Project Name is invalid."), 'class' => 'warningheader');
                    } elseif (Project::model()->findByAttributes(array('project_name' => $project_name), 'project_id !=' . $project_id . '')) {
                        $aViewUrls['message'] = array('title' => $clang->gT("Failed to add Project"), 'message' => $clang->gT("The Project Name already exists."), 'class' => 'warningheader');
                    } else {
                        $oRecord = Project::model()->findByPk($project_id);
                        $oRecord->project_name = $project_name;
                        $oRecord->friendly_name = $project_friendly_name;
                        $oRecord->parent_project_id = $parent_project;
                        $oRecord->client_id = $client;
                        $oRecord->contact_id = $client_contact;
                        $oRecord->manager_user_id = $project_manager;
                        $oRecord->sales_user_id = $sales_person;
                        $oRecord->country_id = $country;
                        $oRecord->required_completes = $quota;
                        $oRecord->QuotaBuffer_Completes = $QuotaBufferAmnt;
                        $oRecord->CPC = $cpc;
                        $oRecord->IR = $ir;
                        $oRecord->expected_los = $los;
                        $oRecord->reward_points = $points;
                        $oRecord->client_link = $surveylink;
                        $oRecord->notes = $notes;
                        //$oRecord->RIDCheck = $RIDCheck;
                        $oRecord->project_status_id = $status;
                        if (isset($_POST['cleanedup'])) {
                            $oRecord->cleanedup = $_POST['cleanedup'];
                        }
                        if ($status == getGlobalSetting('project_status_closed')) {
                            $oRecord->closed = $current_datetime;
                        }
                        $EditContact = $oRecord->save();

                        if ($EditContact) { // When saved successfully
                            //update cpc of project
                            $internal_company_id = getGlobalSetting('Own_Panel');
                            $sql = "UPDATE {{project_master_vendors}} SET CPC = '$CPC' 
                                WHERE project_id = '$project_id' and vendor_id = '$internal_company_id' and vendor_contact_id = '$internal_company_id'";
                            $result = Yii::app()->db->createCommand($sql)->query();
                            $loginuser = Yii::app()->session['user'];
                            // update message table
                            $query = 'INSERT INTO {{messages}} (type_id, body, receipid, chainid, created )
                            VALUES (10,"Information updated By ' . $loginuser . '","0","' . $project_id . '", "' . $current_datetime . '")';
                            $result = Yii::app()->db->createCommand($query)->query();
                            // if status is change
                            if ($old_status != $status) {
                                $sql = "SELECT status_name FROM {{project_status_master}} WHERE status_id = $status";
                                $result = Yii::app()->db->createCommand($sql)->queryRow();
                                $status_name = $result['status_name'];
                                $query = 'INSERT INTO {{messages}} (type_id, body, receipid, chainid, created )
                                VALUES (10,"Project status changed to ' . $status_name . ' by ' . $loginuser . '",0,"' . $project_id . '"," ' . $current_datetime . '" )';
                                $result = Yii::app()->db->createCommand($query)->query();
                            }
                            Yii::app()->setFlashMessage($clang->gT("Project updated successfully"));
                            $this->getController()->redirect(array("admin/project/sa/modifyproject/project_id/" . $project_id . "/action/modifyproject"));
                        } else {
                            $aViewUrls['mboxwithredirect'][] = $this->_messageBoxWithRedirect($clang->gT("Editing contact title"), $clang->gT("Could not modify contact title."), 'warningheader');
                        }
                    }
                } else {
                    Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
                    $this->getController()->redirect(array("admin/index"));
                }
            }
        } elseif ($action == "editvendor") {
            $project_id = (int) Yii::app()->request->getPost("project_id");
            $vendor_project_id = (int) Yii::app()->request->getPost("vendor_project_id");
            $vendor_id = (int) Yii::app()->request->getPost("panel");
            $vendor_contact = (int) Yii::app()->request->getPost("vendor_contact");
            $cpc = flattenText($_POST['cpc'], false, true, 'UTF-8', true);
            $maxredirects = flattenText($_POST['maxredirects'], false, true, 'UTF-8', true);
            $completionlink = flattenText($_POST['completionlink'], false, true, 'UTF-8', true);
            $disqualifylink = flattenText($_POST['disqualifylink'], false, true, 'UTF-8', true);
            $quatafulllink = flattenText($_POST['quatafulllink'], false, true, 'UTF-8', true);
            $quota = flattenText($_POST['quota'], false, true, 'UTF-8', true);
            $current_datetime = date('y-m-d h:i:s');
            $maxcompletes = flattenText($_POST['maxcompletes'], false, true, 'UTF-8', true);
            if ($maxcompletes == 'percent0')
                $QuotaBufferAmnt = $quota;
            if ($maxcompletes == 'percent10')
                $QuotaBufferAmnt = $quota + ($quota * 0.1);
            if ($maxcompletes == 'percent50')
                $QuotaBufferAmnt = $quota + ($quota * 0.5);
            if ($maxcompletes == 'percent100')
                $QuotaBufferAmnt = $quota * 2;
            if ($maxcompletes == 'infinite')
                $QuotaBufferAmnt = 0;
            $notes = flattenText($_POST['notes'], false, true, 'UTF-8', true);
            $status = (int) Yii::app()->request->getPost("status");
            $AskOnRedirect = "";
            if (isset($_POST['askEmail']))
                $AskOnRedirect.='Email';
            if (isset($_POST['askZip']))
                $AskOnRedirect.='Zip';
            if (isset($_POST['askAge']))
                $AskOnRedirect.='Age';
            if (isset($_POST['askGender']))
                $AskOnRedirect.='Gender';

            $oRecord = Project_vendor::model()->findByPk($vendor_project_id);
            $oRecord->vendor_id = $vendor_id;
            $oRecord->vendor_contact_id = $vendor_contact;
            $oRecord->vendor_status_id = $status;
            $oRecord->notes = $notes;
            $oRecord->CPC = $cpc;
            $oRecord->required_completes = $quota;
            $oRecord->QuotaBuffer_Completes = $QuotaBufferAmnt;
            $oRecord->completed_link = $completionlink;
            $oRecord->disQualified_link = $disqualifylink;
            $oRecord->QuotaFull_URL = $quatafulllink;
            $oRecord->max_redirects = $maxredirects;
            $oRecord->AskOnRedirect = $AskOnRedirect;
            $oRecord->created_datetime = $current_datetime;
            $EditContact = $oRecord->save();

            $sql = "UPDATE {{project_master_vendors}}
                    SET
                    vendor_id = '$vendor_id' , 
                    vendor_contact_id = '$vendor_contact' , 
                    vendor_status_id = '$status' , 
                    notes = '$notes' ,  
                    CPC = '$cpc' , 
                    required_completes = '$quota' , 
                    QuotaBuffer_Completes = '$QuotaBufferAmnt' , 
                    completed_link = '$completionlink' , 
                    disQualified_link = '$disqualifylink' , 
                    QuotaFull_URL = '$quatafulllink' , 
                    max_redirects = '$maxredirects' , 
                    AskOnRedirect = '$AskOnRedirect' , 
                    created_datetime = '$current_datetime'
                    WHERE
                    vendor_project_id = '$vendor_project_id'";
            //$result = Yii::app()->db->createCommand($sql)->query();
            Yii::app()->setFlashMessage($clang->gT("Vendor updated successfully"));
            $this->getController()->redirect(array("admin/project/sa/modifyproject/project_id/$project_id/action/modifyproject"));
            exit;
        } else {
            Yii::app()->setFlashMessage(Yii::app()->lang->gT("You do not have sufficient rights to access this page."), 'error');
        }
        $aViewUrls = array();
        $this->_renderWrappedTemplate('projects', $aViewUrls);
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

    /**
     * Renders template(s) wrapped in header and footer
     *
     * @param string $sAction Current action, the folder to fetch views from
     * @param string|array $aViewUrls View url(s)
     * @param array $aData Data to be passed on. Optional.
     */
    protected function _renderWrappedTemplate($sAction = 'projects', $aViewUrls = array(), $aData = array()) {

        $aData['display']['menu_bars']['projects'] = true;

        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
