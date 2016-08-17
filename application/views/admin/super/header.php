<!DOCTYPE html>
<html lang="<?php echo $adminlang; ?>"<?php echo $languageRTL; ?>>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <?php
        App()->getClientScript()->registerPackage('jqueryui');
        App()->getClientScript()->registerPackage('jquery-cookie');
        App()->getClientScript()->registerPackage('jquery-superfish');
        App()->getClientScript()->registerPackage('qTip2');
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('adminstyleurl') . "jquery-ui/jquery-ui.css");
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('adminstyleurl') . "superfish.css");
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('publicstyleurl') . 'jquery.multiselect.css');
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('publicstyleurl') . 'jquery.multiselect.filter.css');
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('adminstyleurl') . "displayParticipants.css");
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "adminstyle.css");
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('adminstyleurl') . "adminstyle.css");
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('adminstyleurl') . "printablestyle.css", 'print');
        ?>
        <?php echo $datepickerlang; ?>
        <title><?php echo $sitename; ?></title>
        <?php echo $firebug ?>
        <?php $this->widget('ext.LimeScript.LimeScript'); ?>
        <?php $this->widget('ext.LimeDebug.LimeDebug'); ?>
        <link rel="shortcut icon" href="<?php echo App()->baseUrl; ?>/styles/favicon.png" type="image/x-icon" /><!-- 16/06/2014 Add By Hari -->
        <link rel="icon" href="<?php echo App()->baseUrl; ?>/styles/favicon.png" type="image/x-icon" /><!-- 16/06/2014 Add By Hari -->  
        <style type="text/css">.ui-notify-message-style p{ color: #666!important; }</style>     
    </head>
    <body>
        <?php if (isset($formatdata)) { ?>
            <script type='text/javascript'>
                var userdateformat='<?php echo $formatdata['jsdate']; ?>';
                var userlanguage='<?php echo $adminlang; ?>';
            </script>
        <?php } ?>
        <div class='wrapper'>
            <?php $this->widget('ext.FlashMessage.FlashMessage'); ?>
            <div class='maintitle'>
				<a href='<?php echo $this->createUrl('/admin/index'); ?>'>
                <img src="<?php echo $baseurl; ?>styles/Logo.png" alt="Panelogy" title="Panelogy" />
                </a>
                <!-- <img src="<?php echo $baseurl; ?>styles/prod-logo.png" style="width: 20%; float: right" alt="Survey Office" title="Survey Office" /> -->
            </div>
