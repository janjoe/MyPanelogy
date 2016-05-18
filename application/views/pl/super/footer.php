</div> <!-- cl -->
</div> <!-- content -->
</div> <!-- wrapper -->

<div class='footer'>
    <div style='float:left;width:110px;text-align:left;'>
		<?php //echo $_SERVER['HTTP_HOST']; ?>
        <a href='<?php echo $this->createUrl('/pl/home'); ?>'><img alt='Panelogy - <?php eT("Online Manual"); ?>' title='Panelogy - <?php eT("Online manual"); ?>' src='<?php echo Yii::app()->getConfig('adminimageurl'); ?>docs.png' /></a>
    </div>
    <div style='float:right;'>
        <a href='<?php echo $this->createUrl('/pl/home'); ?>'>
            <img alt='Panelogy' width="100" title='Panelogy' src='<?php echo Yii::app()->getConfig('adminimageurl'); ?>prod-logo.png'/></a>
    </div>
</div>
</body>
</html>
