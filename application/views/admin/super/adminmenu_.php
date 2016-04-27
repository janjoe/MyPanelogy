<div class='menubar'>
    <div class='menubar-title ui-widget-header'>
        <div class='menubar-title-left'>
            <strong><?php $clang->eT("Administration"); ?></strong>
            <?php
            if (Yii::app()->session['loginID']) {
                ?>
                --  <?php $clang->eT("Logged in as:"); ?><strong>
                    <a href="<?php echo $this->createUrl("/admin/user/sa/personalsettings"); ?>">
                        <?php echo Yii::app()->session['user']; ?> <img src='<?php echo $sImageURL; ?>profile_edit.png' alt='<?php $clang->eT("Edit your personal preferences"); ?>' /></a>
                </strong>
            <?php } ?>
        </div>
        <?php
        if ($showupdate) {
            ?>
            <div class='menubar-title-right'><a href='<?php echo $this->createUrl("admin/globalsettings"); ?>'><?php echo sprintf($clang->ngT('Update available: %s', 'Updates available: %s', count($aUpdateVersions)), $sUpdateText); ?></a></div>
        <?php } ?>
    </div>
    <div class='menubar-main'>
        <div class='menubar-left' id="menu-top">
            <ul>
                <li>
                    <a href="<?php echo $this->createUrl("/admin/survey/sa/index"); ?>">
                        <img src='<?php echo $sImageURL; ?>home.png' alt='<?php $clang->eT("Default administration page"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>

                    <img src='<?php echo $sImageURL; ?>blank.gif' alt='' width='11' />
                    <img src='<?php echo $sImageURL; ?>separator.gif' id='separator1' class='separator' alt='' />
                </li>
                <li>
                    <a href="<?php echo $this->createUrl("admin/user/sa/index"); ?>">
                        <img src='<?php echo $sImageURL; ?>security.png' alt='<?php $clang->eT("Manage survey administrators"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                </li>
                <?php
                if (Permission::model()->hasGlobalPermission('usergroups', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl("admin/usergroups/sa/index"); ?>">
                            <img src='<?php echo $sImageURL; ?>usergroup.png' alt='<?php $clang->eT("Create/edit user groups"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                    </li>
                    <?php
                }
                if (Permission::model()->hasGlobalPermission('settings', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl("admin/globalsettings"); ?>">
                            <img src='<?php echo $sImageURL; ?>global.png' alt='<?php $clang->eT("Global settings"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                        <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                    </li>
                    <?php
                }
                if (Permission::model()->hasGlobalPermission('settings', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl("admin/checkintegrity"); ?>">
                            <img src='<?php echo $sImageURL; ?>checkdb.png' alt='<?php $clang->eT("Check Data Integrity"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                    </li>
                    <?php
                }
                if (Permission::model()->hasGlobalPermission('superadmin', 'read')) {

                    if (in_array(Yii::app()->db->getDriverName(), array('mysql', 'mysqli')) || Yii::app()->getConfig('demoMode') == true) {
                        ?>
                        <li>
                            <a href="<?php echo $this->createUrl("admin/dumpdb"); ?>" >
                                <img src='<?php echo $sImageURL; ?>backup.png' alt='<?php $clang->eT("Backup Entire Database"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/>
                            </a>
                        </li>
                        <li>
                        <?php } else { ?>
                            <img src='<?php echo $sImageURL; ?>backup_disabled.png' alt='<?php $clang->eT("The database export is only available for MySQL databases. For other database types please use the according backup mechanism to create a database dump."); ?>' />
                        </li>
                    <?php } ?>

                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />

                    <?php
                }
                if (Permission::model()->hasGlobalPermission('labelsets', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl("admin/labels/sa/view"); ?>" >
                            <img src='<?php echo $sImageURL; ?>labels.png'  alt='<?php $clang->eT("Edit label sets"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                        <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                    </li>
                    <?php
                }
                if (Permission::model()->hasGlobalPermission('templates', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl("admin/templates/sa/view"); ?>">
                            <img src='<?php echo $sImageURL; ?>templates.png' alt='<?php $clang->eT("Template Editor"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                        <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                    </li>
                <?php } ?>

                <!-- start by brain -->

                <?php
                if (Permission::model()->hasGlobalPermission('Country', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl(""); ?>">
                            <img src='<?php echo $sImageURL; ?>region.png' alt='<?php $clang->eT("Region Management"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                        <ul>
                            <li>
                                <a href="<?php echo $this->createUrl("admin/country/index"); ?>">
                                    <img src='<?php echo $sImageURL; ?>country2.png' alt='<?php $clang->eT("Manage Country"); ?>' name='Manage Country' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->createUrl("admin/zone/index"); ?>">
                                    <img src='<?php echo $sImageURL; ?>zone.png' alt='<?php $clang->eT("Manage Zone"); ?>' name='Manage Zone' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->createUrl("admin/state/index"); ?>">
                                    <img src='<?php echo $sImageURL; ?>state2.png' alt='<?php $clang->eT("Manage State"); ?>' name='Manage State' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->createUrl("admin/city/index"); ?>">
                                    <img src='<?php echo $sImageURL; ?>city.png' alt='<?php $clang->eT("Manage City"); ?>' name='Manage City' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php
                }
                if (Permission::model()->hasGlobalPermission('contacts', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl("admin/contact/index"); ?>" >
                            <img src='<?php echo $sImageURL; ?>company.png' alt='<?php $clang->eT("Contact Management"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                        <ul>
                            <!--                            <li>
                                                            <a href="<?php echo $this->createUrl("admin/contact/index"); ?>">
                                                                <img src='<?php echo $sImageURL; ?>contact.png' alt='<?php $clang->eT("Manage Contacts"); ?>' name='Manage Contacts' />
                                                                <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                                            </a>
                                                        </li>-->
                            <li>
                                <a href="<?php echo $this->createUrl("admin/contact/index"); ?>">
                                    <img src='<?php echo $sImageURL; ?>contact.png' alt='<?php $clang->eT("Manage Company"); ?>' name='Manage Company' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->createUrl("admin/contact_group/index"); ?>">
                                    <img src='<?php echo $sImageURL; ?>contact_group.png' alt='<?php $clang->eT("Contact Groups"); ?>' name='Contact Groups' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->createUrl("admin/company_type/index"); ?>">
                                    <img src='<?php echo $sImageURL; ?>contact_type.png' alt='<?php $clang->eT("Company types"); ?>' name='Company types' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->createUrl("admin/contact_title/index"); ?>">
                                    <img src='<?php echo $sImageURL; ?>contact_title.png' alt='<?php $clang->eT("Contact Titles"); ?>' name='Contact Titles' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                            <!--                            <li>
                                                            <a href="<?php echo $this->createUrl("admin/contact/index", array('action' => 'addcompany')); ?>">
                                                                <img src='<?php echo $sImageURL; ?>company_1.png' alt='<?php $clang->eT("Add Company"); ?>' name='Add Company' />
                                                                <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                                            </a>
                                                        </li>-->
                        </ul>
                    </li>
                    <?php
                }
                ?>
                <?php
                if (Permission::model()->hasGlobalPermission('CMS', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl("admin/cms/index"); ?>" >
                            <img src='<?php echo $sImageURL; ?>icon-cms.png' alt='<?php $clang->eT("CMS Management"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                        <ul>
                            <li>
                                <a href="<?php echo $this->createUrl("admin/cms/index"); ?>">
                                    <img src='<?php echo $sImageURL; ?>cms-page.png' alt='<?php $clang->eT("Manage pages"); ?>' name='Manage pages' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->createUrl("admin/template/index"); ?>">
                                    <img src='<?php echo $sImageURL; ?>cms-template.png' alt='<?php $clang->eT("Manage Content Template"); ?>' name='Manage Content Template' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                        </ul>
                        <img src='<?php echo $sImageURL; ?>separator.gif' id='separator1' class='separator' alt='' />
                    </li>
                <?php } ?>
                <?php
                if (Permission::model()->hasGlobalPermission('project', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl("admin/project/index"); ?>" >
                            <img src='<?php echo $sImageURL; ?>projects.png' alt='<?php $clang->eT("Project Management"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                    </li>
                <?php } ?>

                <?php
                if (Permission::model()->hasGlobalPermission('panelist', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl(""); ?>" >
                            <img src='<?php echo $sImageURL; ?>panellist.png' alt='<?php $clang->eT("Panelist Management"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                    </li>
                <?php } ?>
                <?php
                if (Permission::model()->hasGlobalPermission('rewards', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl(""); ?>" >
                            <img src='<?php echo $sImageURL; ?>reward.png' alt='<?php $clang->eT("Reward Management"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                    </li>
                <?php } ?>

                <?php
                if (Permission::model()->hasGlobalPermission('emailtemp', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl(""); ?>" >
                            <img src='<?php echo $sImageURL; ?>email-template.png' alt='<?php $clang->eT("Global Email Template Management"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                        <ul>
                            <li>
                                <a href="<?php echo $this->createUrl(""); ?>">
                                    <img src='<?php echo $sImageURL; ?>email-subject.png' alt='<?php $clang->eT("Manage Email subject"); ?>' name='Manage Email subject' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->createUrl(""); ?>">
                                    <img src='<?php echo $sImageURL; ?>email-body.png' alt='<?php $clang->eT("Manage Email Body"); ?>' name='Manage Email Body' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->createUrl(""); ?>">
                                    <img src='<?php echo $sImageURL; ?>email-manage.png' alt='<?php $clang->eT("Manage Email"); ?>' name='Manage Email' />
                                    <img src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
                                </a>
                            </li>
                        </ul>
                        <img src='<?php echo $sImageURL; ?>separator.gif' id='separator1' class='separator' alt='' />
                    </li>
                <?php } ?>

                <!-- end brain -->

                <?php
                if (Permission::model()->hasGlobalPermission('participantpanel', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl("admin/participants/sa/index"); ?>" >
                            <img src='<?php echo $sImageURL; ?>cpdb.png' alt='<?php $clang->eT("Central participant database/panel"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                    </li>
                    <?php
                }
                if (Permission::model()->hasGlobalPermission('superadmin', 'read')) {
                    ?>
                    <li>
                        <a href="<?php echo $this->createUrl("plugins/"); ?>" >
                            <img src='<?php echo $sImageURL; ?>plugin.png' alt='<?php $clang->eT("Plugin manager"); ?>' width='<?php echo $iconsize; ?>' height='<?php echo $iconsize; ?>'/></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class='menubar-right'>
            <label for='surveylist'><?php $clang->eT("Surveys:"); ?></label>
            <select id='surveylist' name='surveylist' onchange="if (this.options[this.selectedIndex].value!='') {window.open('<?php echo $this->createUrl("/admin/survey/sa/view/surveyid/"); ?>/'+this.options[this.selectedIndex].value,'_top')} else {window.open('<?php echo $this->createUrl("/admin/survey/sa/index/"); ?>','_top')}">
                <?php echo getSurveyList(false, $surveyid); ?>
            </select>
            <a href="<?php echo $this->createUrl("admin/survey/sa/index"); ?>">
                <img src='<?php echo $sImageURL; ?>surveylist.png' alt='<?php $clang->eT("Detailed list of surveys"); ?>' />
            </a>

            <?php
            if (Permission::model()->hasGlobalPermission('surveys', 'create')) {
                ?>

                <a href="<?php echo $this->createUrl("admin/survey/sa/newsurvey"); ?>">
                    <img src='<?php echo $sImageURL; ?>add.png' alt='<?php $clang->eT("Create, import, or copy a survey"); ?>' /></a>
            <?php } ?>


            <img id='separator2' src='<?php echo $sImageURL; ?>separator.gif' class='separator' alt='' />
            <a href="<?php echo $this->createUrl("admin/authentication/sa/logout"); ?>" >
                <img src='<?php echo $sImageURL; ?>logout.png' alt='<?php $clang->eT("Logout"); ?>' /></a>

            <a href="http://manual.limesurvey.org" target="_blank">
                <img src='<?php echo $sImageURL; ?>showhelp.png' alt='<?php $clang->eT("LimeSurvey online manual"); ?>' /></a>
        </div>
    </div>
</div>
<p style='margin:0;font-size:1px;line-height:1px;height:1px;'>&nbsp;</p>