<div class='header ui-widget-header'><?php $clang->eT("Editing Contact Group"); ?></div><br />
<?php echo CHtml::form(array("admin/contact_group/sa/modcontact_group"), 'post', array('name' => 'modcountryform', 'id' => 'modcountryform')); ?>

<table class='edituser'>
    <thead>
        <tr>
            <th><?php $clang->eT("Contact Group"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody><tr>
            <?php
            foreach ($mur as $mrw) {
                ?>
                <td>
                    <input type='text' maxlength="50" name='c_group_name' value="<?php echo $mrw['contact_group_name']; ?>" /></strong>
                    <input type='hidden' name='contact_group_name' value="<?php echo $mrw['contact_group_name']; ?>" />
                    <input type='hidden' name='contact_group_id' value="<?php echo $mrw['contact_group_id']; ?>" />
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
    <input type='hidden' name='action' value='modcontact_group' />
</p>
</form>