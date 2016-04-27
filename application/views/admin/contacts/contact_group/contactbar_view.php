<!--
Added by Gaurang 2014-04-10
common to contact management 
-->
<div class='menubar'>
    <div class='menubar-title ui-widget-header'>
        <strong><?php $clang->eT("Contact Management"); ?></strong>
    </div>
    <div class='menubar-main'>
        <div class='menubar-left'>
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='55' height='20' />
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/contact/index"); ?>">
                <img src='<?php echo $imageurl; ?>company.png' alt='<?php $clang->eT("Manage Company"); ?>' name='Manage Company' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/company_type/index"); ?>">
                <img src='<?php echo $imageurl; ?>company-type.png' alt='<?php $clang->eT("Company Type"); ?>' name='Company Type' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/contact_group/index"); ?>">
                <img src='<?php echo $imageurl; ?>contact_group.png' alt='<?php $clang->eT("Contact Group"); ?>' name='Contact Group' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/contact_title/index"); ?>">
                <img src='<?php echo $imageurl; ?>contact_title.png' alt='<?php $clang->eT("Contact Title"); ?>' name='Contact Title' />
            </a>
<!--            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/contact/index", array('action' => 'addcompany')); ?>">
                <img src='<?php echo $imageurl; ?>company_1.png' alt='<?php $clang->eT("Add Company"); ?>' name='Add Company' />
            </a>-->
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
        </div>
        <div class='menubar-right'>
            <?php if (Permission::model()->hasGlobalPermission('contacts', 'create')) { ?>
                <a href='<?php echo $this->createUrl("admin/contact/index", array('action' => 'addcompany')); ?>'>
                    <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add new Company"); ?>' /></a>
            <?php } ?>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='82' height='20' />
        </div>
    </div>
</div>
