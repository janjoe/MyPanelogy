<?php

global $tblPrefix;
global $dblink;
$tblPrefix = 'lime_';
//$tblPrefix = 'brn_';

$iscapture = true;
if (!defined('ISCAPTURE'))
    $iscapture = false;

if ($iscapture)
    connectdb();

status_to_define();




function connectdb() {
    $server = "localhost";
    $database = "panelogy";
    $user_name = "panelogy";
    $password = "lsmcnQ6W7LVLhuYSkJp6";

//    $server = "173.192.169.228";
//    $database = "bit_srv";
//    $user_name = "usr_srv";
//    $password = "usrv478";

    //$dblink = mysql_connect($server, $user_name, $password);
    //mysql_select_db($database, $dblink);
    
    $dblink = mysqli_connect($server, $user_name, $password, $database);
    
    return $dblink;
    
    //echo '<pre>';print_r($dblink);
}
function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
}
function status_to_define() {
	
	$dblink = connectdb();
	
    $ret = true;
    global $tblPrefix, $iscapture;
    // setting project status
   $sql = "select stg_value from " . $tblPrefix . "settings_global where stg_name like 'project_status_%' order by stg_name ";
   
    if ($iscapture) {
		
        $result = mysqli_query($dblink, $sql) or die(mysqli_error() . $sql);
    } else {
        //to be changed by gaurang
        $result = mysqli_query($dblink, $sql) or die(mysqli_error() . $sql);
        //$result = Yii::app()->db->createCommand($sql)->query()->readAll();
    }
    
   // $row = mysqli_fetch_array($result);
   // print_r($row);


    if (mysqli_num_rows($result) > 0) {
         define('STATUS_PROJECT_CLOSED', mysqli_result($result, 0));
        define('STATUS_PROJECT_COMPLETED', mysqli_result($result, 1));
        define('STATUS_PROJECT_ONHOLD', mysqli_result($result, 2));
        define('STATUS_PROJECT_RUNNING', mysqli_result($result, 3));
        define('STATUS_PROJECT_TESTING', mysqli_result($result, 4));

    } else {
        echo 'Project status are not been configured, Can not move ahead !!!';
        $ret = false;
        exit;
    }


    // setting redirect status
    $sql = "select stg_value from " . $tblPrefix . "settings_global where stg_name like 'redirect_status_%' order by stg_name ";
    
    $result = mysqli_query($dblink, $sql) or die(mysqli_error() . $sql);
	$row = mysqli_fetch_row($result);

    if (mysqli_num_rows($result) > 0) {
        define('STATUS_REDIRECT_COMPLETED', mysqli_result($result, 0));
        define('STATUS_REDIRECT_DISQUALIFIED', mysqli_result($result, 1));
        define('STATUS_REDIRECT_QUOTAFULL', mysqli_result($result, 2));
        define('STATUS_REDIRECT_REDIRECTED', mysqli_result($result, 3));
        define('STATUS_REDIRECT_REJECTED_FAILED', mysqli_result($result, 4));
        define('STATUS_REDIRECT_REJECTED_INCONSITENCY', mysqli_result($result, 5));
        define('STATUS_REDIRECT_REJECTED_POOR', mysqli_result($result, 6));
        define('STATUS_REDIRECT_REJECTED_QUALITY', mysqli_result($result, 7));
        define('STATUS_REDIRECT_REJECTED_SPEED', mysqli_result($result, 8));

    } else {
        echo 'Re-Direct status are not been configured, Can not move ahead !!!';
        $ret = false;
        exit;
    }
    return $ret;
}

?>
