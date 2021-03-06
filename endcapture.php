<?php
//ini_set('output_buffering', true);
//ob_start();
?>
<!--

clientredirectid = project_id - project number
foreignid = panellist_id the VALUE pass IN the capture.php link   (( the panellist id number placeholder FOR our vendor TO put their vendor id , NOT our internal panellist)
VendorRedirectID = vendor_project_id

required_completes = quota
quotabuffer_completes = QuotaBufferAmnt


cQuotaBufferAmnt = proj_quotabuffer_completes
cnumCompleted = proj_total_completed
cnumRedirected = proj_total_redirected

vnumRedirected = total_redirected
vMaxRedirects = max_redirect
vnumCompleted = total_completed 

numErrored = total_errors

-->
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
            body{font-family: Arial, Verdana;font-size: 12pt;color:darkblue;display:block;text-align: center;margin:10px;padding:10px;}
            .err{font-weight: bold;color:red}
            .wrn{text-decoration: underline; color:orange;}
            h1{font-size:16pt; letter-spacing: 2pt; font-weight: bold;}
            h2{font-size:14pt; letter-spacing: 1pt; font-weight: bold;}
        </style>
    </head>

    <body>
        <?php
//types of test url for capture.php
//capture.php?pid=1&vpid=1
//capture.php?pid=1&vpid=1&ext=abc

        /* test urls are as given below:- 
         * XDEBUG_SESSION_START=netbeans-xdebug
          http://localhost:8080/ryan-horne/limesurvey205plus-build140302/capture.php?pid=1&vpid=1
         * http://localhost:8080/ryan-horne/limesurvey205plus-build140302/capture.php?gid=Ny03
          http://localhost:8080/ryan-horne/limesurvey205plus-build140302/capture.php?pid=2&vpid=2
         * http://localhost:8080/ryan-horne/limesurvey205plus-build140302/capture.php?gid=MTQtMTQ%3D
         */

        /* $pid = 2*7;
          $vpid=2*7;
          $gid = $pid."-".$vpid;
          $gid = urlencode(base64_encode($gid));
          echo "http://localhost:8080/ryan-horne/limesurvey205plus-build140302/capture.php?gid=".$gid;
          exit(); */
        DEFINE("ISCAPTURE", true);
		global $dblink;
        require_once './application/config/config_masters.php';
        $dblink = connectdb();
        

        $tbl_panellist_project = $tblPrefix . 'panellist_project';
        $view_proj_mst = $tblPrefix . 'view_project_master';
        $view_ven_proj_mst = $tblPrefix . 'view_project_master_vendors';  //these is vendor_redirects view
        $view_pnl_red = $tblPrefix . 'view_panellist_redirects'; //these is panelistredirects view
        $view_pnl_graph = $tblPrefix . 'view_panellist_graph'; //these is panelistgraph view

        $view_rel_red = $tblPrefix . 'view_relevant_redirects'; //these is relevantredirects view
        $view_blo_red = $tblPrefix . 'view_blocked_redirects'; //these is bloackedredirects view
        $view_cli_cd = $tblPrefix . 'view_client_code'; //these is bloackedredirects view

        $tbl_proj_mst = $tblPrefix . 'panellist_project';
        $tbl_proj_mst = $tblPrefix . 'project_master';
        $tbl_ven_proj_mst = $tblPrefix . 'project_master_vendors';
        $tbl_panellist_mst = $tblPrefix . 'panel_list_master';
        $tbl_pnl_red = $tblPrefix . 'panellist_redirects'; //these is panelistredirects table
        $tbl_blo_red = $tblPrefix . 'blocked_redirects'; //these is bloackedredirects table
        $tbl_rel_red = $tblPrefix . 'relevant_redirects'; //these is relevantredirects view
        $tbl_cli_cd = $tblPrefix . 'client_code'; //these is bloackedredirects table
        $tbl_pnl_graph = $tblPrefix . 'panellist_graph'; //these is panelistgraph table

        /* if (isset($_SERVER['HTTP_COOKIE'])) {
          $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
          foreach ($cookies as $cookie) {
          $parts = explode('=', $cookie);
          echo $name = trim($parts[0]) ."<br>";
          }
          }

          if (isset($_COOKIE['GWSID'])) {
          exit("in");
          } else {
          exit("out");
          } */

        if (isset($_COOKIE['GWSID']) && isset($_GET['st']) && isset($_COOKIE['PROJECTID'])) {
            if (($_GET['st'] == "111") || ($_GET['st'] == "222") || ($_GET['st'] == "333")) {

                /*
                 * panellist project master status
                 * A == Assign to panellist
                 * PC == Pending Completed
                 * R == Redirected
                 * D == Disqualified
                 * Q == Quota-Full
                 * C == Completed
                 * what will be status for rejected ?ask
                 */

                if ($_GET['st'] == "111") {
                    $Linktype = "completed_link";
                    $newStatus = STATUS_REDIRECT_COMPLETED;
                    $incFieldC = "total_completed";
                    $incFieldV = "total_completed";
                    $instatus = 'PC'; //pending completed
                    $panellist_master_field = "no_completed";
                    $message = "Thanks for your time. We will review your input.";
                }
                if ($_GET['st'] == "222") {
                    $Linktype = "disQualified_link";
                    $newStatus = STATUS_REDIRECT_DISQUALIFIED;
                    $incFieldC = "total_disqualify";
                    $incFieldV = "total_disqualified";
                    $instatus = 'D'; //disqulify
                    $panellist_master_field = "no_disqualified";
                    $message = "Sorry, you are not qualified for the survey";
                }
                if ($_GET['st'] == "333") {
                    $Linktype = "QuotaFull_URL";
                    $newStatus = STATUS_REDIRECT_QUOTAFULL;
                    $incFieldC = "total_quota_full";
                    $incFieldV = "total_quota_full";
                    $instatus = 'Q'; //quotafull
                    $panellist_master_field = "no_qfull";
                    $message = "Sorry, you are not qualified for the survey";
                }

                //$query = "SELECT redirect_status_id, StartIP, project_id, vendor_project_id, panellist_id, foreign_misc, created_datetime, vendor_id  FROM $view_pnl_red WHERE panellist_redirect_id =" . $_COOKIE['GWSID']; //20/06/2014 Remove BY Hari
                $query = "SELECT redirect_status_id, StartIP, project_id, vendor_project_id, panellist_id, foreign_misc, created_datetime, vendor_id , project_status  FROM $view_pnl_red WHERE panellist_redirect_id =" . $_COOKIE['GWSID']; //20/06/06/2014 Add By Hari
                $result = mysqli_query($dblink, $query) or die(mysqli_error());
                extract(mysqli_fetch_assoc($result));

                //Start If Condition Add By Hari
                if ($project_status <> STATUS_PROJECT_TESTING) {
                    //Start If
                    //echo $redirect_status_id . $_COOKIE['GWSID'];
                    //echo $StartIP.$_SERVER['REMOTE_ADDR']; 
                    if (( $redirect_status_id == STATUS_REDIRECT_REDIRECTED) AND ($StartIP == $_SERVER['REMOTE_ADDR'])) {
                        $query = 'UPDATE ' . $tbl_pnl_red . '
                            SET 
                            EndIP = "' . $_SERVER['REMOTE_ADDR'] . '",
                            redirect_status_id = "' . $newStatus . '",
                            CompletedOn = NOW() 
                            WHERE panellist_redirect_id =' . $_COOKIE['GWSID'];

                        $result = mysqli_query($dblink, $query) or die(mysqli_error());

                        // assign point to panelist

                        if($panellist_id != '')
                        {
                            $defaultprojectid = '';
                            $uquery = "SELECT project_id FROM lime_campaign as cmp JOIN lime_panel_list_master as pl ON cmp.id=pl.cmp_id";
                            $uquery .= " WHERE pl.panel_list_id =".$panellist_id;
                            $uresult = mysqli_query($dblink, $uquery) or die(mysqli_error());
                            $uresult = mysqli_fetch_array($uresult);
                            
                            if(!empty($uresult)){
                                $defaultprojectid = $uresult['project_id'];
                            }
                            if($defaultprojectid == $project_id )
                            {  $status_pp = 'C';

                                $sql1 = "SELECT rectify_no FROM lime_rectify_redirects WHERE project_id = '".$project_id."'";
                                    $result12 = mysqli_query($dblink, $sql1) or die(mysqli_error());
                                    $qry_result12 = mysqli_fetch_array($result12);
                                    if(isset($qry_result12['rectify_no']))
                                        $rectify_no = $qry_result12['rectify_no'] + 1;
                                    else
                                        $rectify_no = 1;

                                    $currentdate = date('Y-m-d');

                                $insert_sql = "INSERT INTO `lime_rectify_redirects` (`project_id`, `rectify_type`, `rectify_no`, `rectify_date`) VALUES (".$project_id.", '1', '".$rectify_no."','".$currentdate."')";
                                $result123 = mysqli_query($dblink, $insert_sql) or die(mysqli_error());  

                                $rectify_id = mysqli_insert_id ($dblink);

                                $update_redirect = "update lime_panellist_redirects set 
                                        prev_redirect_status_id=redirect_status_id, 
                                        rectify_id = ".$rectify_id."
                                        where panellist_id = '" . $panellist_id . "' and project_id ='" . $project_id . "'";
                                $result = mysqli_query($dblink, $update_redirect) or die(mysqli_error());        

                                $update_pp = "update lime_panellist_project set 
                                    status = '".$status_pp."' 
                                    where panellist_id = '" . $panellist_id . "' and project_id ='" . $project_id . "' ";
                                $result = mysqli_query($dblink, $update_pp) or die(mysqli_error());
                                $select_pp = "select IFNULL(points,0) as points from lime_panellist_project where panellist_id = '" . $panellist_id . "' and  project_id = '" . $project_id . "'";
                                $qry_pp = mysqli_query($dblink, $select_pp) or die(mysqli_error());
                                $qry_pp = mysqli_fetch_array($qry_pp);
                                if ($status_pp == 'C') {
                                    $update_pm = "update  lime_panel_list_master set 
                                        earn_points = earn_points + " . $qry_pp['points'] . ",
                                        balance_points = balance_points + " . $qry_pp['points'] . "
                                        where panel_list_id = '" . $panellist_id . "' ";
                                    $result = mysqli_query($dblink, $update_pm) or die(mysqli_error());
                                }
                            }    
                        }



                        //calculating and fetching LOS
                        $query = 'SELECT (TIME_TO_SEC(TIMEDIFF(CompletedOn, created_datetime))/60) as LOS FROM ' . $tbl_pnl_red . ' WHERE panellist_redirect_id =' . $_COOKIE['GWSID'];
                        $result = mysqli_query($dblink, $query) or die(mysqli_error());
                        extract(mysqli_fetch_assoc($result));

                        $query = 'UPDATE ' . $tbl_pnl_red . ' 
                                SET 
                                LOS = ' . $LOS . '
                                WHERE panellist_redirect_id =' . $_COOKIE['GWSID'];

                        $result = mysqli_query($dblink, $query) or die(mysqli_error());
                        if ($_GET['st'] == "111") {
                            $query = 'UPDATE ' . $tbl_proj_mst . ' SET 
                                       ' . $incFieldC . ' = ' . $incFieldC . ' +1 , 
                                        total_los = total_los + ' . $LOS . '
                                        WHERE project_id =' . $project_id;
                        } else {
                            $query = 'UPDATE ' . $tbl_proj_mst . ' SET 
                                      ' . $incFieldC . ' = ' . $incFieldC . ' +1                      
                                        WHERE project_id =' . $project_id;
                        }

                        $result = mysqli_query($dblink, $query) or die(mysqli_error());


                        $query = 'UPDATE ' . $tbl_ven_proj_mst . ' SET 
                                    ' . $incFieldV . ' = ' . $incFieldV . ' +1
                                    WHERE vendor_project_id =' . $vendor_project_id;
                        $result = mysqli_query($dblink, $query) or die(mysqli_error());

                        //check if internal company (own vendor) 
                        $sql = 'SELECT * FROM ' . $tblPrefix . 'settings_global WHERE stg_name like "Own_Panel"';
                        $result = mysqli_query($dblink, $sql) or die(mysqli_error());
                        extract(mysqli_fetch_assoc($result));
                        if ($vendor_id == $stg_value) {

                            $sql_update = 'update ' . $tblPrefix . 'panellist_project set 
                                status = "' . $instatus . '"
                                Where  panellist_id  = "' . $panellist_id . '" and
                                project_id  = "' . $_COOKIE['PROJECTID'] . '"
                                and status = "R"';

                            $result = mysqli_query($dblink, $sql_update) or die(mysqli_error());

                            $query = "Update  $tbl_panellist_mst set 
                        $panellist_master_field = $panellist_master_field +1  Where  panel_list_id ='  $panellist_id '";
                            $reslt = mysqli_query($dblink, $query) or die(mysqli_error() . $query);

                            echo '<div style="border: 5px solid black; width: 800px; margin-left: 25%;color: green; padding: 10px;">'.$message.'</div>';
                        } else {

                            $query = 'SELECT ' . $Linktype . ' as EndLink FROM ' . $tbl_ven_proj_mst . ' WHERE vendor_project_id =' . $vendor_project_id;
                            $result = mysqli_query($dblink, $query) or die(mysqli_error());
                            extract(mysqli_fetch_assoc($result));

                            //$EndLink = str_replace("{{FOREIGNID}}", $panellist_id, $EndLink);//18/06/2014 Remove
                            $EndLink = str_replace("{{panellist_id}}", $panellist_id, $EndLink); //18/062014 Add
                            $EndLink = str_replace("{{PASSTHRU}}", $foreign_misc, $EndLink);

                            //ForeignMISC
                            ?>
                            <script type="text/javascript">
                                location.href = "<?php echo $EndLink; ?>";
                            </script>
                            <?php
                        }
                    } else {
                        exit('<h1 class="wrn">ERROR: 300 Parameters are not set properly.</h1><br/><h2>300 means:- Already Redirected and Same IP Found</h2>');
                    }//End If
                }
                //End If Condition Add By Hari
            } else {
                exit('<h1 class="wrn">ERROR: S1 Parameters are not set properly.</h1>');
            }
        } else {
            exit('<h1 class="wrn">ERROR: 400 Parameters are not set properly.</h1><br/><h2>400 means:- Either cookie not set or S1 parameter not found</h2>');
        }
        ?> 

    </body>
</html>
