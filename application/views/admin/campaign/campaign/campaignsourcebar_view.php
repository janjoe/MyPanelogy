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

            <a href="<?php echo $this->createUrl("admin/campaign/index"); ?>">
                <img src='<?php echo $imageurl; ?>icon-campaign.png' alt='<?php $clang->eT("Manage Campaign"); ?>' name='Manage Campaign' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            
            <a href="<?php echo $this->createUrl("admin/campaign/campaignsource"); ?>">
                <img src='<?php echo $imageurl; ?>network.png' alt='<?php $clang->eT("Manage Campaign Sources"); ?>' name='Manage Campaign Sources' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />


            <a href="<?php echo $this->createUrl("admin/campaign/campaignsourcetype"); ?>">
                <img src='<?php echo $imageurl; ?>icon-promoting.png' alt='<?php $clang->eT("Manage Sources Type"); ?>' name='Manage Sources Type' />
            </a>
            
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />

            <a href="<?php echo $this->createUrl("admin/campaign/campaignstatus"); ?>">
                <img src='<?php echo $imageurl; ?>icon-switch-off.png' alt='<?php $clang->eT("Manage Campaign Status"); ?>' name='Manage Campaign Status' />
            </a>
            
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />

            <a href="<?php echo $this->createUrl("admin/campaign/campaignreport"); ?>">
                <img src='<?php echo $imageurl; ?>presentation.png' alt='<?php $clang->eT("Manage Campaign Reports"); ?>' name='Manage Campaign Reports' />
            </a>
            
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />


        </div>
        <div class='menubar-right'>
            <?php if (Permission::model()->hasGlobalPermission('Campaign', 'create')) { ?>
                

                <?php 
                if (Yii::app()->controller->action->id == "campaign") {

                    $parts = explode("/", Yii::app()->request->url);
                    $currentaction =  end($parts);
                } 

                ?>
                
                 <?php if ($currentaction == "index") { ?>
                    <a href='<?php echo $this->createUrl("admin/campaign/sa/addcampaign"); ?>'>
                    <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add new Campaign"); ?>' /></a>
                <?php } ?>

                 <?php if ($currentaction == "campaignsourcetype") { ?>
                    <a href='<?php echo $this->createUrl("admin/campaign/sa/addcampaignsourcetype"); ?>'>
                    <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add new Source Type"); ?>' /></a>
                <?php } ?>

                <?php if ($currentaction == "campaignsource") { ?>
                    <a href='<?php echo $this->createUrl("admin/campaign/sa/addcampaignsource"); ?>'>
                        <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add new Source"); ?>' /></a>
                <?php } ?>

                <?php if ($currentaction == "campaignstatus") { ?>
                    <a href='<?php echo $this->createUrl("admin/campaign/sa/addcampaignstatus"); ?>'>
                        <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add new Status"); ?>' /></a>
                <?php } ?>     

            <?php } ?>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='82' height='20' />

        </div>
    </div>
</div>
