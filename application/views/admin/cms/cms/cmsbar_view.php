<!--
Added by Gaurang 2014-04-10
common to content management 
-->
<div class='menubar'>
    <div class='menubar-title ui-widget-header'>
        <strong><?php $clang->eT("Content Management"); ?></strong>
    </div>
    <div class='menubar-main'>
        <div class='menubar-left'>
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='55' height='20' />
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/cms/index"); ?>">
                <img src='<?php echo $imageurl; ?>cms-page.png' alt='<?php $clang->eT("Manage Pages"); ?>' name='Manage Pages' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/template/index"); ?>">
                <img src='<?php echo $imageurl; ?>cms-template.png' alt='<?php $clang->eT("Manage Template"); ?>' name='Manage Template' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
        </div>
        <div class='menubar-right'>
            <?php if (Permission::model()->hasGlobalPermission('CMS', 'create')) { ?>
                <a href='<?php echo $this->createUrl("admin/cms/sa/add"); ?>'>
                    <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add new Page"); ?>' /></a>
            <?php } ?>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='82' height='20' />
        </div>
    </div>
</div>
