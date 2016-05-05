<div class='header ui-widget-header'><?php $clang->eT("Editing Company Type"); ?></div><br />
<?php echo CHtml::form(array("admin/company_type/sa/modcompany_type"), 'post', array('name' => 'modcompanyform', 'id' => 'modcompanyform')); ?>

<table style="width: 80%; margin: 0px auto;">
    <thead>
        <tr>
            <th><?php $clang->eT("Company Type Name"); ?></th>
            <th><?php $clang->eT("Select Company Type"); ?></th>
            <th><?php $clang->eT("Ask Title in Contact"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody><tr>
            <?php
            foreach ($mur as $mrw) {
                ?>
                <td>
                    <input type='text' maxlength="50" name='c_type_name' value="<?php echo $mrw['company_type_name']; ?>" /></strong>
                    <input type='hidden' name='company_type_name' value="<?php echo $mrw['company_type_name']; ?>" />
                    <input type='hidden' name='company_type_id' value="<?php echo $mrw['company_type_id']; ?>" />
                </td>
                <?php
                $company_type = array(
                    '0' => 'Please select Company type',
                    'C' => 'Client',
                    'V' => 'Vendor',
                    'O' => 'Other',
                );
                echo '<td>' . CHtml::dropDownList('company_type', $mrw['company_type'], $company_type) . '</td>';
                ?>
                <?php
                $chk = '';
                if ($mrw['Istitle'] == 1) {
                    $chk = 'checked=checked';
                }
                ?>
                <td>
                    <input type="checkbox" <?php echo $chk; ?> name="IsTitle"  title="If ask title is checked, then in contact master it will ask for title, against that particular company type."/>
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
    <input type='hidden' name='action' value='modcompany_type' />
</p>
</form>