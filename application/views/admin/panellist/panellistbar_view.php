<div class='menubar'>
    <div class='menubar-title ui-widget-header'>
        <strong><?php $clang->eT("Manage Panelists and Profile Questions"); ?></strong>
    </div>
    <div class='menubar-main'>
        <div class='menubar-left'>
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='55' height='20' />
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/panellist/index"); ?>">
                <img src='<?php echo $imageurl; ?>panellist.png' alt='<?php $clang->eT("View Panelists"); ?>' name='View Panelists' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/rewards/index"); ?>">
                <img src='<?php echo $imageurl; ?>reward.png' alt='<?php $clang->eT("View Rewards"); ?>' name='View Rewards' />
            </a>
            <!-- Start Add By Hari --->
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/rewards/sa/rewardrequest"); ?>">
                <img src='<?php echo $imageurl; ?>pay_reward.png' alt='<?php $clang->eT("Request Reward"); ?>' name='Request Reward' />
            </a>
            <!-- End Hari -->
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/pquery/index"); ?>">
                <img src='<?php echo $imageurl; ?>query-count.png' alt='<?php $clang->eT("View Queries"); ?>' name='View Queries' />
            </a>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/profilequestion/index"); ?>">
                <img src='<?php echo $imageurl; ?>q_n_a.png' alt='<?php $clang->eT("View Profile Questions"); ?>' name='View Profile Questions' />
            </a>
            <a href="<?php echo $this->createUrl("admin/profilecategory/index"); ?>">
                <img src='<?php echo $imageurl; ?>ques-cat.png' alt='<?php $clang->eT("View Categories"); ?>' name='View Categories' />
            </a>
            <!-- Start Add By Parth 18-06-2014 --->
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/cron/index"); ?>">
                <img src='<?php echo $imageurl; ?>cron-log.png' alt='<?php $clang->eT("Cron Status"); ?>' name='Cron Status' />
            </a>
            <!-- End Parth 18-06-2014 -->
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
        </div>
        <div class='menubar-right'>
            <?php if (Permission::model()->hasGlobalPermission('superadmin', 'read')) { ?>
                <?php if (Yii::app()->controller->action->id == "rewards") { ?>
                    <a href='<?php echo $this->createUrl("admin/rewards/sa/add"); ?>'>
                        <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add New Reward"); ?>' /></a>
                <?php } ?>
                <?php if (Yii::app()->controller->action->id == "pquery") { ?>
                    <a href='<?php echo $this->createUrl("admin/pquery/sa/add"); ?>'>
                        <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add New Query"); ?>' /></a>
                <?php } ?>
                <?php if (Yii::app()->controller->action->id == "profilecategory") { ?>
                    <a href='<?php echo $this->createUrl("admin/profilecategory/sa/add"); ?>'>
                        <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add New Category"); ?>' /></a>
                <?php } ?>
                <?php if (Yii::app()->controller->action->id == "profilequestion") { ?>
                    <a href='<?php echo $this->createUrl("admin/profilequestion/sa/add"); ?>'>
                        <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add New Question"); ?>' /></a>
                <?php } ?>
            <?php } ?>
            <img src='<?php echo $imageurl; ?>separator.gif' class='separator' alt='' />
            <img src='<?php echo $imageurl; ?>blank.gif' alt='' width='82' height='20' />
        </div></div>
</div>
<p style='margin:0;font-size:1px;line-height:1px;height:1px;'>&nbsp;</p>
