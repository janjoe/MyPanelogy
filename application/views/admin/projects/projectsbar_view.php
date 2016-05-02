<!--<script type="text/javascript">
    window.clipboardData.setData("Text","ID,Status,Segment,Country code");
</script>-->
<div class='menubar'>
    <div class='menubar-title ui-widget-header'>
        <strong><?php $clang->eT("Manage Projects"); ?></strong>
    </div>
    <div class='menubar-main'>
        <div class='menubar-left'>
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='55' height='20' />
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/project/index"); ?>">
                <img src='<?php echo $imageurl; ?>projects.png' alt='<?php $clang->eT("Manage Project"); ?>' name='Manage Project' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <?php
            if (isset($_GET['project_id'])) $project_id = $_GET['project_id']; else $project_id =0;
            
            if (isset($_GET['sa'])) {
                if ($_GET['sa'] == 'modifyproject') {
                    ?>
                    <a href='<?php echo $this->createUrl("admin/project/sa/rectify/project_id/".$project_id); ?>'>
                        <img src='<?php echo $imageurl; ?>rectify.png' alt='<?php $clang->eT("Rectify Project Redirects (Trueup)"); ?>' /></a>
                    <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
                    <?php
                }
            }
            ?>
            <?php
            if (isset($_GET['project_id'])) $project_id = $_GET['project_id']; else $project_id =0;
            
            if (isset($_GET['sa'])) {
                if ($_GET['sa'] == 'modifyproject') {
                    ?>
                    <a href='<?php echo $this->createUrl("admin/project/sa/unique/project_id/".$project_id); ?>'>
                        <img src='<?php echo $imageurl; ?>client_link_icon.png' alt='<?php $clang->eT("Unique links"); ?>' /></a>
                    <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
                    <?php
                }
            }
            ?>
        </div>
        <div class='menubar-right'>
            <?php if (Permission::model()->hasGlobalPermission('superadmin', 'read')) { ?>
                <a href='<?php echo $this->createUrl("admin/project/sa/add"); ?>'>
                    <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add new Projects"); ?>' /></a>
            <?php } ?>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='82' height='20' />
        </div>
    </div>
</div>