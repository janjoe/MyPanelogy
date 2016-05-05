<div class='header ui-widget-header'><?php $clang->eT("Editing Contact Title"); ?></div><br />
<?php echo CHtml::form(array("admin/contact_title/sa/modcontact_title"), 'post', array('name' => 'modcountryform', 'id' => 'modcountryform')); ?>

<table class='edituser'>
    <thead>
        <tr>
            <th><?php $clang->eT("Contact Title"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody><tr>
            <?php
            foreach ($mur as $mrw) {
                ?>
                <td>
                    <input type='text' maxlength="50" name='c_title_name' value="<?php echo $mrw['contact_title_name']; ?>" /></strong>
                    <input type='hidden' name='contact_title_name' value="<?php echo $mrw['contact_title_name']; ?>" />
                    <input type='hidden' name='contact_title_id' value="<?php echo $mrw['contact_title_id']; ?>" />
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
    <input type='hidden' name='action' value='modcontact_title' />
</p>
</form>