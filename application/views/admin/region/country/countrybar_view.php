<!--
Added by Gaurang 2014-04-10
common to region management 
-->
<div class='menubar'>
    <div class='menubar-title ui-widget-header'>
        <strong><?php $clang->eT("Region Management"); ?></strong>
        <?php
        if ($ugid && $grpresultcount > 0) {
            echo "{$grow['name']}";
        }
        ?>
    </div>
    <div class='menubar-main'>
        <div class='menubar-left'>
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='55' height='20' />
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/country/index"); ?>">
                <img src='<?php echo $imageurl; ?>country2.png' alt='<?php $clang->eT("Manage Country"); ?>' name='Manage Zone' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/zone"); ?>">
                <img src='<?php echo $imageurl; ?>zone.png' alt='<?php $clang->eT("Manage Zone"); ?>' name='Manage Zone' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/state"); ?>">
                <img src='<?php echo $imageurl; ?>state2.png' alt='<?php $clang->eT("Manage State"); ?>' name='Manage State' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/city"); ?>">
                <img src='<?php echo $imageurl; ?>city.png' alt='<?php $clang->eT("Manage City"); ?>' name='Manage City' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
        </div>
        <!--                <div class='menubar-right'>
                            <label for="ugid"><?php $clang->eT("User groups"); ?>:</label>  <select name='ugid' id='ugid' onchange="window.location=this.options[this.selectedIndex].value">
        <?php echo getUserGroupList($ugid, 'optionlist'); ?>
                            </select>
        <?php if (Permission::model()->hasGlobalPermission('superadmin', 'read')) { ?>
                                                                <a href='<?php echo $this->createUrl("admin/usergroups/sa/add"); ?>'>
                                                                    <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add new user group"); ?>' /></a>
        <?php } ?>
                            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
                            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='82' height='20' />
                        </div>-->
    </div>
</div>
<p style='margin:0;font-size:1px;line-height:1px;height:1px;'>&nbsp;</p>
