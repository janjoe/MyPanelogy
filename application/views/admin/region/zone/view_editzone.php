<div class='header ui-widget-header'><?php $clang->eT("Editing Zone"); ?></div><br />
<?php echo CHtml::form(array("admin/zone/sa/modzone"), 'post', array('name' => 'modzoneform', 'id' => 'modzoneform')); ?>

<table class='edituser'>
    <thead>
        <tr>
            <th><?php $clang->eT("Zone Name"); ?></th>
            <th><?php $clang->eT("Country"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody><tr>
            <?php
            foreach ($mur as $mrw) {
                ?>
                <td>
                    <input type='text' maxlength="50" name='z_name' value="<?php echo $mrw['zone_Name']; ?>" /></strong>
                    <input type='hidden' name='zone_name' value="<?php echo $mrw['zone_Name']; ?>" />
                    <input type='hidden' name='zone_id' value="<?php echo $mrw['zone_id']; ?>" />
                    <input type='hidden' name='country_id' value="<?php echo $mrw['country_id']; ?>" />
                </td>
                <td>
                    <?php
                    $region = Country::model()->findAll(array('order' => 'country_name'));
                    $reglist = CHtml::listData($region, 'country_id', 'country_name');
                    echo CHtml::dropDownList('c_id', "" . $mrw["country_id"] . "", $reglist, array('prompt' => 'Select Country...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'));
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
    <input type='hidden' name='action' value='modzone' />
</p>
</form>