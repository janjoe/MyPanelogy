<div class='header ui-widget-header'><?php $clang->eT("Editing State"); ?></div><br />
<?php echo CHtml::form(array("admin/state/sa/modstate"), 'post', array('name' => 'modstateform', 'id' => 'modstateform')); ?>

<table class='edituser'>
    <thead>
        <tr>
            <th><?php $clang->eT("State"); ?></th>
            <th><?php $clang->eT("Country"); ?></th>
            <th><?php $clang->eT("Zone"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody><tr>
            <?php
            foreach ($mur as $mrw) {
                ?>
                <td>
                    <input type='text' maxlength="50" name='s_name' value="<?php echo $mrw['state_Name']; ?>" /></strong>
                    <input type='hidden' name='state_Name' value="<?php echo $mrw['state_Name']; ?>" />
                    <input type='hidden' name='state_id' value="<?php echo $mrw['state_id']; ?>" />
                    <input type='hidden' name='country_id' value="<?php echo $mrw['country_id']; ?>" />
                    <input type='hidden' name='zone_id' value="<?php echo $mrw['zone_id']; ?>" />
                </td>
                <td>
                    <?php
                    $region = Country::model()->findAll(array('order' => 'country_name'));
                    $reglist = CHtml::listData($region, 'country_id', 'country_name');
                    echo CHtml::dropDownList('country_name', "" . $mrw["country_id"] . "", $reglist, array(
                        'prompt' => 'Select Country...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                        'ajax' => array(
                            'type' => 'POST',
                            'data' => array('action' => 'selectzone', 'country_name' => 'js:this.value'),
                            'url' => CController::createUrl('admin/state/sa/selectzone'),
                            'update' => '#zonelistmod'
                        )
                    ));
                    ?>
                </td>
                <td>
                    <?php
                    $region = Zone::model()->findAll('country_id=:country_id', array(':country_id' => (int) $mrw["country_id"]));
                    $reglist = CHtml::listData($region, 'zone_id', 'zone_Name');
                    echo CHtml::dropDownList('zonelistmod', "" . $mrw["zone_id"] . "", $reglist, array());
                    ?>
                </td>
                <?php
                $chk = '';
                if ($mrw['IsActive'] == 1) {
                    $chk = 'checked=checked';
                }
                ?>
                <td>
                    <input type="checkbox" <?php echo $chk; ?> name="IsActive" />
                </td>
                <?php
            }
            ?>
        </tr>
    </tbody>
</table>
<p>
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='modstate' />
</p>
</form>