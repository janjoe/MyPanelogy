<?php

global $tblPrefix;
global $dblink;
//$tblPrefix = 'lime_';
$tblPrefix = 'brn_';

$iscapture = true;
if (!defined('ISCAPTURE'))
    $iscapture = false;

if ($iscapture)
    connectdb();

status_to_define();

function connectdb() {
    $server = "localhost";
    $database = "surveynew";
    $user_name = "root";
    $password = "RJH";

//    $server = "173.192.169.228";
//    $database = "bit_srv";
//    $user_name = "usr_srv";
//    $password = "usrv478";

    $dblink = mysql_connect($server, $user_name, $password);
    mysql_select_db($database, $dblink);
}

function status_to_define() {
    $ret = true;
    global $tblPrefix, $iscapture;
    // setting project status
    $sql = "select stg_value from " . $tblPrefix . "settings_global where stg_name like 'project_status_%' order by stg_name ";
    if ($iscapture) {
        $result = mysql_query($sql) or die(mysql_error() . $sql);
    } else {
        //to be changed by gaurang
        $result = Yii::app()->db->createCommand($uquery)->query()->readAll();
    }
    if (mysql_num_rows($result) > 0) {
        define('STATUS_PROJECT_CLOSED', mysql_result($result, 0));
        define('STATUS_PROJECT_COMPLETED', mysql_result($result, 1));
        define('STATUS_PROJECT_ONHOLD', mysql_result($result, 2));
        define('STATUS_PROJECT_RUNNING', mysql_result($result, 3));
        define('STATUS_PROJECT_TESTING', mysql_result($result, 4));
    } else {
        echo 'Project status are not been configured, Can not move ahead !!!';
        $ret = false;
        exit;
    }


    // setting redirect status
    $sql = "select stg_value from " . $tblPrefix . "settings_global where stg_name like 'redirect_status_%' order by stg_name ";
    $result = mysql_query($sql) or die(mysql_error() . $sql);
    if (mysql_num_rows($result) > 0) {
        define('STATUS_REDIRECT_COMPLETED', mysql_result($result, 0));
        define('STATUS_REDIRECT_DISQUALIFIED', mysql_result($result, 1));
        define('STATUS_REDIRECT_QUOTAFULL', mysql_result($result, 2));
        define('STATUS_REDIRECT_REDIRECTED', mysql_result($result, 3));
        define('STATUS_REDIRECT_REJECTED_FAILED', mysql_result($result, 4));
        define('STATUS_REDIRECT_REJECTED_INCONSITENCY', mysql_result($result, 5));
        define('STATUS_REDIRECT_REJECTED_POOR', mysql_result($result, 6));
        define('STATUS_REDIRECT_REJECTED_QUALITY', mysql_result($result, 7));
        define('STATUS_REDIRECT_REJECTED_SPEED', mysql_result($result, 8));
    } else {
        echo 'Re-Direct status are not been configured, Can not move ahead !!!';
        $ret = false;
        exit;
    }
    return $ret;
}

?>
