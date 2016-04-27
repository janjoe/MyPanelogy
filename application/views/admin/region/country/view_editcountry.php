<div class='header ui-widget-header'><?php $clang->eT("Editing Country"); ?></div><br />
<?php echo CHtml::form(array("admin/country/sa/modcountry"), 'post', array('name' => 'modcountryform', 'id' => 'modcountryform')); ?>

<table class='edituser'>
    <thead>
        <tr>
            <th><?php $clang->eT("Country"); ?></th>
            <th><?php $clang->eT("Continent"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody><tr>
            <?php
            foreach ($mur as $mrw) {
                ?>
                <td>
                    <input type='text' maxlength="50" name='c_name' value="<?php echo $mrw['country_name']; ?>" /></strong>
                    <input type='hidden' name='country_name' value="<?php echo $mrw['country_name']; ?>" />
                    <input type='hidden' name='country_id' value="<?php echo $mrw['country_id']; ?>" />
                </td>
                <td>
                    <input type="text" maxlength="50" name="continent_name" value="<?php echo $mrw['continent']; ?>"/>
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
    <input type='hidden' name='action' value='modcountry' />
</p>
</form>