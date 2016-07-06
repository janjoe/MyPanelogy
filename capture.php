<?php
ob_start();
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
        <script>
            function setCookie(cname,cvalue,exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                var expires = "expires=" + d.toGMTString();
                document.cookie = cname+"="+cvalue+"; "+expires;
                //document.cookie = cname+"="+cvalue+"; "+expires+"; path='/'; domain='http://survey-office.com/'";
                //document.cookie = cname+"="+cvalue+"; "+expires+"; path='/'; domain='http://www.survey-office.com/'";
                //document.cookie = cname+"="+cvalue+"; "+expires+"; domain=survey-office.com";
                //document.cookie = cname+"="+cvalue+"; "+expires+"; domain=<?php //echo $_SERVER['HTTP_HOST'];    ?>";
            }
        </script>
    </head>

    <!--<body onload="checkCookie()">-->
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

        //echo "<script>checkCookie()</script>";
        // Multi-langual script removed by Nilesh on 20/4/2014

        /* if (isset($_GET['redirect'])) {
          echo '
          <script>
          function changelink()
          {
          window.open("' . $_GET['redirect'] . '","_self","",false);
          }
          </script>
          <br/>
          <br/>
          <center>
          <table style="border-right: solid 1px #A9A9A9;border-left: solid 1px #ECECEC;border-bottom: solid 1px #A9A9A9;border-top: solid 1px #ECECEC;width: 1200px;">
          <tr>
          <td align="center"><br/>';
          echo '﻿<h4 style="color:#5090CD" lang="en">Please click the arrow button to begin the survey.</h4>

          <h4 style="color:#5090CD">Por favor, haga clic en el bot&oacute;n de la flecha para comenzar la encuesta.</h4>
          <!-- spanish special codes - http://webdesign.about.com/od/localization/l/blhtmlcodes-sp.htm -->
          <h4 style="color:#5090CD">S\'il vous pla&icirc;t cliquer sur la fl&egrave;che pour commencer l\'enqu&ecirc;te.</h4>
          <!-- http://webdesign.about.com/od/localization/l/blhtmlcodes-fr.htm-->
          <h4 style="color:#5090CD">Bitte klicken Sie auf den Pfeil, um die Umfrage zu starten.</h4>
          <h4 style="color:#5090CD">Si prega di fare clic sul pulsante freccia per avviare l\'indagine.</h4>
          <h4 style="color:#5090CD">調査を開始するには、矢印ボタンをクリックしてください。</h4>
          <h4 style="color:#5090CD">请点击箭头按钮开始调查。</h4>

          <h4 style="color:#5090CD">Por favor, clique no bot&atilde;o de seta para iniciar a pesquisa.</h4>
          <!--http://symbolcodes.tlt.psu.edu/bylanguage/portuguese.html -->
          <h4 style="color:#5090CD" lang="hi">सर्वेक्षण शुरू करने के लिए तीर बटन पर क्लिक करें.</h4>';

          echo '<img src="images/arrow.png" height="100" width="100" onclick="changelink();" alt="arrow"><br/>
          </td>
          </tr>
          </table>
          </center></body></html>';
          } else { */

        //capturing URL variables for further logic process
        //setting table names to be used in the queries below
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

        if (isset($_GET['int'])) {
            $internal = base64_decode(urldecode($_GET['int']));
            $var = explode("-", $internal);
            $proj_id = intval($var[0]) / 7;
            $vp_id = intval($var[1]) / 7;
            $pl_id = intval($var[2]) / 7; //panellist_id
            $ext = ( isset($_GET['ext'])) ? $_GET['ext'] : ""; //extension
            $referrer = '';
            $query = "SELECT * FROM $tbl_panellist_project WHERE status = 'A' and project_id = $proj_id and panellist_id =  $pl_id ";
            $resPP = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
            if (mysqli_num_rows($resPP) > 0) {
                //$query = "Update  $tbl_panellist_project set status = 'R' Where status = 'A' and project_id = $proj_id and panellist_id =  $pl_id ";
                //$reslt = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
               // $query = "Update  $tbl_panellist_mst set no_redirected = no_redirected +1  Where  panel_list_id =  $pl_id ";
                // $reslt = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
            }
        } else {
            $gid = ( isset($_GET['gid'])) ? $_GET['gid'] : "0";
            if ($gid == "0")
                exit('<h1 class="wrn">ERROR: S1 Parameters are not set properly, please check survey link.</h1>');
            else {
                $gid = base64_decode(urldecode($gid));
                $var = explode("-", $gid);
                $proj_id = intval($var[0]) / 7;
                $vp_id = intval($var[1]) / 7;
                $pl_id = ( isset($_GET['pid'])) ? $_GET['pid'] : "0"; //panellist_id
                $ext = ( isset($_GET['ext'])) ? $_GET['ext'] : ""; //extension
                $referrer = '';
            }
        }
        //?ask nilesh what if the pl_id is not passed then how to generate the below variable
        $panellist_id = $pl_id;

//
//validation of the URL Checking compulsory parameters are passed in the url or not
        /*
          if (empty($_GET)) {
          mysql_close();
          exit('<h1 class="wrn">Invalid URL, no parameters passed at all</h1>');
          } else {
          if (!isset($_GET['check'])) {
          if (!isset($_GET['vpid']) || !isset($_GET['pid'])) {
          mysql_close();
          exit('<h1 class="wrn">Invalid URL, no valid and compulsory parameters passed !!!</h1>');
          }
          }// checking the check parameter
          }
          //end url validation */

        if (isset($_SERVER['HTTP_REFERER']))
            $referrer = $_SERVER['HTTP_REFERER'];

        ///Step 1. checking vendor_status and project_status 
        //$query = 'SELECT VendorID, ClientRedirectID, Status as vStatus, QFullURL, MaxRedirects as vMaxRedirects, numRedirected as vnumRedirected, numCompleted AS vnumCompleted,  QuotaBufferAmnt AS vQuotaBufferAmnt, Quota AS vQuota, AskOnRedirect FROM vendor_redirects WHERE ID = ' . $vp_id;
        $query = 'SELECT project_id, project_status_id, vendor_project_id, vendor_id, vendor_status_id, max_redirects, total_redirected, total_completed, quotabuffer_completes as quotabuffer_completes, required_completes
                , client_id, client_link, proj_total_completed, proj_total_redirected, proj_required_completes, proj_quotabuffer_completes as proj_quotabuffer_completes, sales_user_id, manager_user_id
                ,AskOnRedirect, QuotaFull_URL
                FROM ' . $view_ven_proj_mst . ' WHERE vendor_project_id = ' . $vp_id;
        $resVendor = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
        if (mysqli_num_rows($resVendor) <= 0) {
            mysqli_close($dblink);
            exit('<h1 class="wrn">No such project exists, or the parameters passed are in-correct !!!</h1>');
        } else {
            //cross verifying the url's project id and vendor master's project id
            extract(mysqli_fetch_assoc($resVendor));
            if ($proj_id <> $project_id) {
                mysqli_close($dblink);
                exit('<h1 class="wrn">ERROR: S1 Parameters are not set properly, please check survey link.</h1>');
            }
        }

        //Checking projects status and vendors status whether they are in running or testing mode.
        //if ((($vendor_status_id <> "testing") AND ($vendor_status <> "running")) || (($project_status <> "testing") AND ($project_status <> "running"))) {
        if ((($vendor_status_id <> STATUS_PROJECT_TESTING ) AND ($vendor_status_id <> STATUS_PROJECT_RUNNING)) || (($project_status_id <> STATUS_PROJECT_TESTING) AND ($project_status_id <> STATUS_PROJECT_RUNNING))) {
            mysqli_close($dblink);
            exit('<h1 class="wrn">Project is not live now. Please check back later.</h1>');
        }

        //if passthru parameter is passed and ext parameter is not passed then stop and exit
        if (( strpos($client_link, "{{PASSTHRU}}", 0) > 0) && (!isset($_GET['ext']))) {
            mysqli_close($dblink);
            exit('<h1 class="wrn">ERROR: S2 Parameters are not set properly, please check survey link.</h1>');
        } else {
            $client_link = str_replace("{{PASSTHRU}}", $ext, $client_link);
            $client_link = str_replace("*", "&", $client_link);
        }

        // Orginal coding starts from here 
        if ($project_status_id == STATUS_PROJECT_RUNNING) {
            //$query = 'SELECT ForeignID,ID, StartIP FROM panelistredirects WHERE  ClientRedirectID = ' . $ClientRedirectID . '  AND ( ForeignID="' . $proj_id . '" OR StartIP = "' . $_SERVER['REMOTE_ADDR'] . '" and status <> "Redirected")';
            $query = 'SELECT panellist_redirect_id, panellist_id, StartIP FROM ' . $view_pnl_red . ' WHERE project_id = ' . $project_id . ' AND (panellist_id = "' . $pl_id . '" OR StartIP = "' . $_SERVER['REMOTE_ADDR'] . '" and redirect_status_id <> ' . STATUS_REDIRECT_REDIRECTED . ' )';
            $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
            if ((mysqli_num_rows($result) > 0) || ($pl_id == "0")) {
                if (mysqli_num_rows($result) > 0) {
                    extract(mysqli_fetch_assoc($result));
                    if (($project_id == $proj_id) && ($StartIP == $_SERVER['REMOTE_ADDR'])) {
                        $updstatus = "DupeIPID";
                    } else {
                        if ($project_id == $proj_id)
                            $updstatus = "DupeID";
                        else
                            $updstatus = "DupeIP";
                    }
                    echo '<h1 class="wrn">Our records indicate that you have already attempted this survey. Please look for other future opportunities in the near future.</h1>';
                    /*
                      $query = 'INSERT INTO bloackedredirects
                      ( ClientRedirectID, VendorRedirectID, ForeignID, Status, Created, StartIP, panelistredirectsID)
                      VALUES
                      (' . $ClientRedirectID . ', ' . $vp_id . ', "' . $proj_id . '",  "' . $status . '" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '",' . $ID . ')';
                     */
                    $query = 'INSERT INTO ' . $tbl_blo_red . '
                    ( project_id, vendor_project_id, panellist_id, status, created_datetime, StartIP, panellist_redirect_id)
                    VALUES
                    (' . $project_id . ', ' . $vp_id . ', "' . $panellist_id . '",  "' . $updstatus . '" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '",' . strval($panellist_redirect_id) . ')';
                    $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
                    UpdateErrors($project_id, $vp_id);
                    mysqli_close($dblink);
                    exit();
                } //check only row count else error
            } //check row count and pid
        } else {
            //$query = 'SELECT ForeignID,ID FROM panelistredirects WHERE  ClientRedirectID = ' . $ClientRedirectID . '  AND ForeignID="' . $proj_id . '" and status <> "Redirected" ';
            $query = 'SELECT panellist_id, panellist_redirect_id FROM ' . $view_pnl_red . ' WHERE project_id = ' . $project_id . '  AND panellist_id = "' . $panellist_id . '" and redirect_status_id <> ' . STATUS_REDIRECT_REDIRECTED;
            $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
            if ((mysqli_num_rows($result) > 0) || ($pl_id == "0")) {
                if (mysqli_num_rows($result) > 0) {
                    extract(mysqli_fetch_assoc($result));
                    echo '<h1 class="wrn">You have already taken this survey. Please look for our other opportunities.</h1>';
                    //            $query = 'INSERT INTO bloackedredirects
                    //                    ( ClientRedirectID, VendorRedirectID, ForeignID, Status, Created, StartIP, panelistredirectsID)
                    //                    VALUES
                    //                    (' . $ClientRedirectID . ', ' . $vp_id . ', "' . $proj_id . '",  "DupeID" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '",' . $ID . ')';
                    $query = 'INSERT INTO ' . $tbl_blo_red . '
                        ( project_id, vendor_project_id, panellist_id, status, created_datetime, StartIP, panellist_redirect_id)
                        VALUES
                        (' . $project_id . ', ' . $vp_id . ', "' . $panellist_id . '",  "DupeID" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '",' . $panellist_redirect_id . ')';
                    $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
                    UpdateErrors($project_id, $vp_id);
                    mysqli_close($dblink);
                    exit;
                } //checking row count
            }
        }


        $DataOnRedirect = "";

        $POSTEmail = "0";
        $POSTZip = "0";
        $POSTAge = "0";
        $POSTGender = "0";
        //start change by gaurang 2014-05-27
        //Removed by Nilesh on Ask on REdirect Structure for now 22/4/14
        if (isset($_POST['ACT'])) {
            $POSTEmail = mysqli_real_escape_string(trim($_POST['Email']));
            $POSTZip = mysqli_real_escape_string(trim($_POST['Zip']));
            $POSTAge = mysqli_real_escape_string(trim($_POST['Age']));
            $POSTGender = mysqli_real_escape_string(trim($_POST['Gender']));
            $DataOnRedirect.=$POSTEmail . ";";
            $DataOnRedirect.=$POSTZip . ";";
            $DataOnRedirect.=$POSTAge . ";";
            $DataOnRedirect.=$POSTGender . ";";
        }


        //Step 4 pre-screenner form

        if (( strlen($AskOnRedirect) > 0 ) && !(isset($_POST['ACT']))) {
            // Form for pre-screener
            ?>
            <script type="text/javascript">
                function validateForm()
                {
                    var fault = 0;

                    if (document.getElementById("Email").value == "")
                    {
                        fault = 1;
                    }

                    if ((document.getElementById("Zip").value == "0") || (document.getElementById("Zip").value == ""))
                    {
                        fault = 1;
                    }

                    if ((document.getElementById("Age").value <= 0) || (document.getElementById("Age").value == ""))
                    {
                        fault = 1;
                    }

                    if (fault == 1)
                    {
                        document.getElementById("msg").innerHTML = "Please enter the below information.";
                        return false;
                    }
                    else
                    {
                        return true;
                    }

                    return true;

                }
            </script>

            <br/>
            <form id="form1" method="POST" onsubmit="return validateForm()">
                <center>
                    <p style="padding: 5px;color: #6a6a6a;font-size: 17px;font-weight: bold;background: antiquewhite"/>Please provide the information below:</p>

                    <br/>
                    <div id="msg" style="color: red;font-size: 15px;padding: 10px;"></div>
                    <br/>

                    <table style="background: #ecfbd6;border: 2px solid rgb(219, 212, 201);border-radius: 15px;width: 500px;color: #666;font-size: 14px;font-weight: bold;" >
                        <tr>
                            <td colspan="2" align="center" >
                                &nbsp;
                            </td>
                        </tr>
                        <?php
                        if (strpos($AskOnRedirect, 'Email') === false) {
                            ?>
                            <input type="hidden" name="Email" id ="Email"  value="NULL">
                            <?php
                        } else {
                            ?>
                            <tr>
                                <td align ="right" style="width: 30%">
                                    Email:
                                </td>
                                <td align ="left">
                                    <input style="width: 60%;padding: 3px;border-radius: 8px;border: 3px solid rgba(0, 0, 0, 0.31);" type="text" name="Email" id="Email" value="" />
                                </td>
                            </tr>

                            <?php
                        }
                        if (strpos($AskOnRedirect, 'Zip') === false) {
                            ?>
                            <input type="hidden" name="Zip" id ="Zip"  value="NULL">
                            <?php
                        } else {
                            ?>
                            <tr>
                                <td align ="right"  style="width: 30%">
                                    Zip:
                                </td>
                                <td align ="left">
                                    <input style="width: 60%;padding: 3px;border-radius: 8px;border: 3px solid rgba(0, 0, 0, 0.31);" type="text" name="Zip" id="Zip" value="" />
                                </td>
                            </tr>

                            <?php
                        }
                        if (strpos($AskOnRedirect, 'Age') === false) {
                            ?>
                            <input type="hidden" name="Age" id ="Age"  value="NULL">
                            <?php
                        } else {
                            ?>
                            <tr>
                                <td align ="right"  style="width: 30%">
                                    Age:
                                </td>
                                <td align ="left">
                                    <input style="width: 60%;padding: 3px;border-radius: 8px;border: 3px solid rgba(0, 0, 0, 0.31);" type="text" name="Age" id="Age" value="" maxlength="3"/>
                                </td>
                            </tr>

                            <?php
                        }
                        if (strpos($AskOnRedirect, 'Gender') === false) {
                            ?>
                            <input type="hidden" name="Gender" id ="Gender"  value="NULL">
                            <?php
                        } else {
                            ?>
                            <tr>
                                <td align ="right"  style="width: 30%">
                                    Gender:
                                </td>
                                <td align ="left">
                                    <select name='Gender' id='Gender' style="width: 60%;padding: 3px;border-radius: 8px;border: 3px solid rgba(0, 0, 0, 0.31);">
                                        <option value="Male" SELECTED>Male</option>
                                        <option value="Female" >Female</option>
                                        <option value="Other" >Other</option>
                                    </select>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="2" align="center" >
                                <input type="hidden" name="ACT" value="post_extra"><input type="submit" name="submit" value="Submit">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center" >
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>
        <?php
        mysqli_close($dblink);
        exit();
    }
//End change by gaurang 2014-05-27
//Step 5 cehcking the max_redirect values
//$QFullURL = str_replace("{{FOREIGNID}}", $proj_id, $QFullURL);
   
    $QuotaFull_URL = str_replace("{{panellist_id}}", $project_id, $QuotaFull_URL);
//VENDOR's max redirect validation
//if (( $vnumRedirected >= $vMaxRedirects) && ($vMaxRedirects > 0)) {
    if (( $total_completed >= $max_redirects) && ($max_redirects > 0)) {
        $subject = "MaxHit P-" . $project_id . " V-" . $vp_id;
        $body = "Max Redirects for Project " . $project_id . " , Panel " . $vp_id . " has been met and new panellists will be redirected to Quota Full.";
        SendMessage($subject, $body, $project_id, $sales_user_id, $manager_user_id);
        echo '<h1 class="wrn">Thank you for your interest in the survey, But however the survey is no longer accepting any additional responses. <br/> We look forward to your input on the other opportunities.</h1>';
        /*
          $query = 'INSERT INTO bloackedredirects
          ( ClientRedirectID, VendorRedirectID, ForeignID, Status, Created, StartIP, panelistredirectsID)
          VALUES
          (' . $ClientRedirectID . ', ' . $vp_id . ', "' . $proj_id . '",  "Max-Re-direct" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '",0)';
         */
        $query = 'INSERT INTO ' . $tbl_blo_red . '
            ( project_id, vendor_project_id, panellist_id, status, created_datetime, StartIP, panellist_redirect_id)
            VALUES
            (' . $project_id . ', ' . $vp_id . ', "' . $panellist_id . '",  "Max-Re-direct" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '",0)';
        $result = mysqli_query($dblink, $query) or die(mysql_error($query));
        UpdateErrors($project_id, $vp_id);
        mysqli_close($dblink);

        header('Refresh:5; URL=' . $QuotaFull_URL);
        exit();
    }

    //checking PROJECT's total quota and buffer quota
    //exit("tot comp $total_completed , quota buffer  $quotabuffer_completes , proj_total_completed $proj_total_completed , proj_required_completes $proj_required_completes ");
    if (( $total_completed >= $quotabuffer_completes ) && ($quotabuffer_completes > 0)) {
        //$subject = "MaxComp P-" . $client_id . " V-" . $vp_id;
        $subject = "MaxComp P-" . $project_id . " V-" . $vp_id;
        $body = "Max Completed for Project " . $project_id . " , Panel " . $vp_id . " has been met and new panellists will be redirected to QF.";
        //SendMessage($subject,$body,$ClientRedirectID,$SalesPersonID,$ManagerID);
        SendMessage($subject, $body, $project_id, $sales_user_id, $manager_user_id);
        echo '<h1 class="wrn">Thank you for your interest in the survey, however the survey is no longer accepting additional responses. We look forward to your input on the next opportunity.</h1>';
//        $query = 'INSERT INTO bloackedredirects
//			( ClientRedirectID, VendorRedirectID, ForeignID, Status, Created, StartIP, panelistredirectsID)
//			VALUES
//			(' . $ClientRedirectID . ', ' . $vp_id . ', "' . $proj_id . '",  "Max-Completes-Vendor" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '",0)';
        $query = 'INSERT INTO ' . $tbl_blo_red . '
                    ( project_id, vendor_project_id, panellist_id, status, created_datetime, StartIP, panellist_redirect_id)
                    VALUES
                    (' . $project_id . ', ' . $vp_id . ', "' . $panellist_id . '",  "Max-Completes-Vendor" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '",0)';
        $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
        UpdateErrors($project_id, $vp_id);
        mysqli_close($dblink);

        header('Refresh:5; URL=' . $QuotaFull_URL);
        exit();
    }

    //checking PROJECT's total quota and buffer quota
    if (( $proj_total_completed >= $proj_quotabuffer_completes) && ($proj_quotabuffer_completes > 0)) {
        $subject = "MaxComp P-" . $project_id;
        //$body = "Max Completed for Project " . $ClientRedirectID . " has been met and new panellists will be redirected to QF.";
        $body = "Max Completed for Project " . $project_id . " has been met and new panellists will be redirected to QF.";
        SendMessage($subject, $body, $project_id, $sales_user_id, $manager_user_id);

        header('Refresh:5; URL=' . $QuotaFull_URL);
        echo '<h1 class="wrn">Thank you for your interest in the study however the study is no longer accepting additional responses. We look forward to your input on the other opportunities.</h1>';
//        $query = 'INSERT INTO bloackedredirects
//			( ClientRedirectID, VendorRedirectID, ForeignID, Status, Created, StartIP, panelistredirectsID)
//			VALUES
//			(' . $ClientRedirectID . ', ' . $vp_id . ', "' . $proj_id . '",  "Max-Completes-Survey" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '",0)';
        $query = 'INSERT INTO ' . $tbl_blo_red . '
			( project_id, vendor_project_id, panellist_id, status, created_datetime, StartIP, panellist_redirect_id)
			VALUES
			(' . $project_id . ', ' . $vp_id . ', "' . $panellist_id . '",  "Max-Completes-Survey" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '",0)';
        $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
        UpdateErrors($project_id, $vp_id);
        mysqli_close($dblink);
        exit();
    }

    //if ($cnumCompleted >= $cQuota) {
    //exit("tot comp $total_completed , quota buffer  $quotabuffer_completes , proj_total_completed $proj_total_completed , proj_required_completes $proj_required_completes ");
    if ($proj_total_completed >= $proj_required_completes) {
        $subject = "Quota P-" . $project_id; //$ClientRedirectID;
        $body = "Quota for Project " . $project_id; //$ClientRedirectID . " has been met";
        //SendMessage($subject, $body, $ClientRedirectID, $SalesPersonID, $ManagerID);
        SendMessage($subject, $body, $project_id, $sales_user_id, $manager_user_id);
    }

    //if ($vnumCompleted >= $vQuota) {
    if ($total_completed >= $required_completes) {
//      $subject = "Quota P-" . $ClientRedirectID . " V-" . $vp_id;
//      $body = "Quota for Project " . $ClientRedirectID . " , Panel " . $vp_id . " has been met";
//      SendMessage($subject, $body, $ClientRedirectID, $SalesPersonID, $ManagerID);
        $subject = "Quota P-" . $project_id . " V-" . $vp_id;
        $body = "Quota for Project " . $project_id . " , Panel " . $vp_id . " has been fulfilled";
        SendMessage($subject, $body, $project_id, $sales_user_id, $manager_user_id);
    }

//Step 6. Checking for Client Codes 
    if ((strpos($client_link, "{{CLIENTKEY}}", 0) > 0)) {
        //$query2 = 'SELECT Code FROM clientcode WHERE status IS NULL AND ClientRedirectID = ' . $ClientRedirectID . ' ORDER BY ID ASC LIMIT 1 ';
         $query2 = 'SELECT Code FROM ' . $tbl_cli_cd . ' WHERE status IS NULL AND project_id = ' . $project_id . ' ORDER BY id ASC LIMIT 1 ';
        $result2 = mysqli_query($dblink, $query2) or die(mysqli_error() . $query2);

        if (mysqli_num_rows($result2) <= 0) {
            echo '<h1 class="wrn">The study is temporarily on hold, Please try again later.</h1>';
            $subject = "Client code over flow Project-" . $project_id;
            $body = "Client code out of stock for Project " . $project_id . " and no new panellist will be allowed to these survey.";
            //SendMessage($subject, $body, $ClientRedirectID, $SalesPersonID, $ManagerID);
            //UpdateErrors($ClientRedirectID, $vp_id);
            SendMessage($subject, $body, $project_id, $sales_user_id, $manager_user_id);
            UpdateErrors($project_id, $vp_id);
            mysqli_close($dblink);
            exit();
        } else {
            extract(mysqli_fetch_assoc($result2));
            $client_link = str_replace("{{CLIENTKEY}}", $Code, $client_link);

            //ClientID	VendorID Referrer		
//            $query = 'INSERT INTO panelistredirects
//			( ClientRedirectID, VendorRedirectID, ForeignID,  ForeignMISC,Status, Created, StartIP, clientcode, ClientID, VendorID, referrer, DataOnRedirect)
//			VALUES
//			(' . $ClientRedirectID . ', ' . $vp_id . ', "' . $proj_id . '", "' . $ext . '",  "Redirected" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '","' . $Code . '", ' . $ClientID . ', ' . $VendorID . ', "' . $referrer . '" ,"' . $DataOnRedirect . '")';
            $query = 'INSERT INTO ' . $tbl_pnl_red . '
			( project_id, vendor_project_id, panellist_id, foreign_misc, redirect_status_id, created_datetime, StartIP, client_code, client_id, vendor_id, referrer, DataOnRedirect)
			VALUES
			(' . $project_id . ', ' . $vp_id . ', "' . $panellist_id . '", "' . $ext . '",  ' . STATUS_REDIRECT_REDIRECTED . '  , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '","' . $Code . '", ' . $client_id . ', ' . $vendor_id . ', "' . $referrer . '" ,"' . $DataOnRedirect . '")';
            $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
            $lastid = mysqli_insert_id($dblink);

            //$query = 'UPDATE clientcode SET Status="Used", panelistredirectsID = ' . $lastid . ' WHERE Code="' . $Code . '" AND ClientRedirectID = ' . $ClientRedirectID;
            $query = 'UPDATE ' . $tbl_cli_cd . ' SET status="Used", panellist_redirect_id = ' . $lastid . ' WHERE Code="' . $Code . '" AND project_id = ' . $project_id;
            $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
        }
    } else {
//        $query = 'INSERT INTO panelistredirects
//			( ClientRedirectID, VendorRedirectID, ForeignID,  ForeignMISC,Status, Created, StartIP, ClientID, VendorID, referrer, DataOnRedirect)
//			VALUES
//			(' . $ClientRedirectID . ', ' . $vp_id . ', "' . $proj_id . '", "' . $ext . '",  "Redirected" , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '", ' . $ClientID . ', ' . $VendorID . ', "' . $referrer . '","' . $DataOnRedirect . '")';
        $query = 'INSERT INTO ' . $tbl_pnl_red . '
			( project_id, vendor_project_id, panellist_id, foreign_misc, redirect_status_id, created_datetime, StartIP, client_id, vendor_id, referrer, DataOnRedirect)
			VALUES
			(' . $project_id . ', ' . $vp_id . ', "' . $panellist_id . '", "' . $ext . '",  ' . STATUS_REDIRECT_REDIRECTED . ' , NOW() ,"' . $_SERVER['REMOTE_ADDR'] . '", ' . $client_id . ', ' . $vendor_id . ', "' . $referrer . '","' . $DataOnRedirect . '")';
        $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
        $lastid = mysqli_insert_id($dblink);
    }

//updating counts
//    $query = 'UPDATE projects_master SET
//				numRedirected = numRedirected + 1,
//				LastRedirectOn = NOW()
//				WHERE projects_master.ID = ' . $ClientRedirectID;
    $query = 'UPDATE ' . $tbl_proj_mst . ' SET
                total_redirected = total_redirected + 1,
		LastRedirected_DateTime = NOW()
		WHERE project_id = ' . $project_id;
    $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);

//    $query = 'UPDATE vendor_redirects SET
//				numRedirected = numRedirected + 1,
//				LastRedirectOn = NOW()
//				WHERE vendor_redirects.ID = ' . $vp_id;
    $query = 'UPDATE ' . $tbl_ven_proj_mst . ' SET
		total_redirected = total_redirected + 1,
		LastRedirected_DateTime = NOW()
		WHERE vendor_project_id = ' . $vp_id;
    $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);

    $query = 'SELECT NOW() as cDate '; //by trk FROM '.$view_proj_mst.' WHERE project_id = ' . $project_id;
    $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
    extract(mysqli_fetch_assoc($result));

    echo "<script>setCookie('GWSID','" . $lastid . "', 30)</script>";
    echo "<script>setCookie('PROJECTID','" . $project_id . "', 30)</script>";

//	$lastid = 5500000;
//    setcookie('GWSID', $lastid);
//    setcookie('PROJECTID', $client_id);
//echo '<script>
//            function setsiteCookie(cname,cvalue,exdays)
//            {
//                var d = new Date();
//                d.setTime(d.getTime()+(exdays*24*60*60*1000));
//                var expires = "expires="+d.toGMTString();
//                document.cookie = cname + "=" + cvalue + "; " + expires;
//            }
//        </script>';
    //document.cookie = cname + "=" + cvalue + "; " + expires + "; " + ";path=/;domain=http://survey-office.com";
    //document.cookie = cname + "=" + cvalue + "; " + expires + "; " + ";path=/;domain=http://www.survey-office.com";
    //echo "setsiteCookie('GWSID','" . $lastid . "',1)";
    //echo "setsiteCookie('PROJECTID','" . $client_id . "',1)";
//Adding Screener information into the client link
    $client_link = str_replace("{{Email}}", $POSTEmail, $client_link);
    $client_link = str_replace("{{Zip}}", $POSTZip, $client_link);
    $client_link = str_replace("{{Age}}", $POSTAge, $client_link);
    $client_link = str_replace("{{Gender}}", $POSTGender, $client_link);
    $client_link = str_replace("{{ID}}", $lastid, $client_link);

    //header("location:" . $client_link);
    $cookie_name = "pl";
    $cookie_value = urlencode(base64_encode($panellist_id));
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
   
    echo "<script>window.location ='" . $client_link . "';</script>";

//} //(isset($_GET['redirect']))
//mysql_close($dblink);
    mysqli_close($dblink);

    function UpdateErrors($project_id, $vp_id) {
        global $db, $tbl_proj_mst, $tbl_ven_proj_mst,$dblink;
        $query = 'UPDATE ' . $tbl_proj_mst . ' SET
                total_errors = total_errors  + 1
                WHERE project_id = ' . $project_id; // $ClientRedirectID;
        //pending sit with nilesh and finalize
        $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
//    $query = 'UPDATE vendor_redirects SET
//                            numErrored = numErrored + 1
//                            WHERE vendor_redirects.ID = ' . $vp_id;
        $query = 'UPDATE ' . $tbl_ven_proj_mst . ' SET
                total_errors = total_errors + 1
                WHERE vendor_project_id = ' . $vp_id;
        $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
    }

//pending sit with nilesh and finalize
    function SendMessage($subject, $body, $cid, $sp, $pm) {
        return true;
        global $db;
        $query = 'SELECT ID FROM messages WHERE Header = "' . $subject . '" AND receipID = ' . $sp . ' AND DATE_FORMAT(Created,"%Y-%m-%d") = DATE_FORMAT(Now(),"%Y-%m-%d") ';
        $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);

        if (mysqli_num_rows($result) <= 0) {
            $query = 'INSERT INTO messages
                            (Type,Header,Body, receipID, senderID, Created, isRead, ChainID )
                            VALUES
                            (0,
                            "' . $subject . '",
                            "' . $body . '",
                            ' . $sp . ',
                            0, 
                            NOW(), 
                            0,
                            0),

                            (0,
                            "' . $subject . '",
                            "' . $body . '",
                            ' . $pm . ',
                            0, 
                            NOW(), 
                            0,
                            0)';
            $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);

            //$query = 'SELECT Email FROM contacts_master WHERE ID IN (' . $sp . ',' . $pm . ')';
            $query = 'SELECT primary_emailid FROM contact_master WHERE contact_id IN (' . $sp . ',' . $pm . ')';
            $result = mysqli_query($dblink, $query) or die(mysqli_error() . $query);
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['Email'] != "") {
                    $headers = "From: contact@survey-office.com";
                    mail($row['Email'], $subject, $body, $headers);
                }
            }
        }
    }
    ?>
</body>
</html>
<?php
ob_end_flush();
?>
