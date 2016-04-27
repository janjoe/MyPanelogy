<!--
Added by Gaurang 2014-04-10
common to region management 
-->
<div class='menubar'>
    <div class='menubar-title ui-widget-header'>
        <strong><?php $clang->eT("Email Template Management"); ?></strong>
    </div>
    <div class='menubar-main'>
        <div class='menubar-left'>
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='55' height='20' />

            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/get/sa/list_subs"); ?>">
                <img src='<?php echo $imageurl; ?>email-subject.png' alt='<?php $clang->eT("Manage Email subject"); ?>' name='Manage Email subject' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/get/sa/list_body"); ?>">
                <img src='<?php echo $imageurl; ?>email-body.png' alt='<?php $clang->eT("Manage Email Body"); ?>' name='Manage Email Body' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/get/sa/list_tmplt"); ?>">
                <img src='<?php echo $imageurl; ?>email-manage.png' alt='<?php $clang->eT("Manage Email"); ?>' name='Manage Email' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
        </div>

        <div class='menubar-right'>
            <?php
            if (Permission::model()->hasGlobalPermission('email_template', 'create')) {
                if ($_GET['sa'] == "list_body") {
                    ?> <a href='<?php echo $this->createUrl("admin/get/sa/form_add_body"); ?>'>
                        <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add Email Body"); ?>' /></a> 
                <?php } elseif ($_GET['sa'] == "list_tmplt") {
                    ?> <a href='<?php echo $this->createUrl("admin/get/sa/add_template"); ?>'>
                        <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add Email Templates"); ?>' /></a> 
                <?php }
            } ?>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='82' height='20' />
        </div>
    </div>

    <div id="popup_subs" style="visibility: hidden;">
        <p>Hi subject form goes here...</p>
    </div>


</div>
<p style='margin:0;font-size:1px;line-height:1px;height:1px;'>&nbsp;</p>
