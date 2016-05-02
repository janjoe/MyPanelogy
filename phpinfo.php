<?php
//echo '  GWSID '.$_COOKIE['GWSID'];
//echo '  PROJECTID '.$_COOKIE['PROJECTID'];
//echo phpinfo();

//mail("tarakgandhi@gmail.com",'test subject','test message',"From: tarakgandhi@gmail \n");
//if (isset($_GET('action'))) {
	//if ($_GET('action') == 'email') {
	//	mail("tarakgandhi@gmail.com",'test subject','test message',"From: tarakgandhi@gmail \n");
	//	echo "email sent ? ";
	//}
//}
ob_start();
header("Location: http://www.survey-office.com/endcapture.php?st=111&xyz=2451");
exit;
echo '<script type="text/javascript">
    window.location = "http://www.survey-office.com/endcapture.php?st=111&GWSID=' + $_COOKIE['GWSID'] + '&PROJECTID='+$_COOKIE['PROJECTID'].'"
</script>';
?>