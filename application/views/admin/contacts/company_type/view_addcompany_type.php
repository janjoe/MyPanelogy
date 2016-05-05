<div class='header ui-widget-header'><?php $clang->eT("Manage Company Type"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listCompanyType').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>

<table id="listCompanyType" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("Delete"); ?></th>
            <th><?php $clang->eT("Company Type ID"); ?></th>
            <th><?php $clang->eT("Company Type Name"); ?></th>
            <th><?php $clang->eT("Company Type"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            ?>
            <tr>

                <td style="padding:3px;">          
                    <?php echo CHtml::form(array('admin/company_type/sa/modifycompany_type'), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit these Company type"); ?>' />
                    <input type='hidden' name='action' value='modifycompany_type' />
                    <input type='hidden' name='company_type_id' value='<?php echo $usr['company_type_id']; ?>' />
                    </form>
                </td>
                <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/company_type/sa/delcompany_type'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete these Company type"); ?>' />
                    <input type='hidden' name='action' value='delcompany_type' />
                    <input type='hidden' name='company_type_name' value='<?php echo htmlspecialchars($usr['company_type_name']); ?>' />
                    <input type='hidden' name='company_type_id' value='<?php echo $usr['company_type_id']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['company_type_id']; ?></td>
                <td><?php echo htmlspecialchars($usr['company_type_name']); ?></td>
                <?php
                $chk = '';
                if ($usr['company_type'] == 'V') {
                    $chk = 'Vendor';
                } elseif ($usr['company_type'] == 'C') {
                    $chk = 'Client';
                } elseif ($usr['company_type'] == 'O') {
                    $chk = 'Other';
                }
                ?>
                <td><?php echo $chk; ?></td>
                <td><?php
            if ($usr['IsActive'] == True) {
                echo 'True';
            } else {
                echo 'False';
            }
            ?></td>
            </tr>
    <?php $row++;
} ?>
    </tbody>
</table>
<br />
<?php echo CHtml::form(array('admin/company_type/sa/addcompany_type'), 'post'); ?>            
<table width="100%">
    <tr class='oddrow'>
        <th><?php $clang->eT("Add Company Type Name:"); ?></th>
        <td style='width:20%'><input type='text' maxlength="50" name='new_contact_type' placeholder="Company Type Name" /></td>
        <?php
        $company_type = array(
            '' => 'Please select Company type',
            'C' => 'Client',
            'V' => 'Vendor',
            'O' => 'Other'
        );
        echo '<td>' . CHtml::dropDownList('company_type', '', $company_type, array('required' => true)) . '</td>';
        ?>
        <td>
            <input type='checkbox' name='istitle' style="vertical-align: top;"/>
            <label title="If ask title is checked, then in contact master it will ask for title, against that particular company type.">Ask Title in Contact</label>
        </td>
        <td style='width:15%'><input type='submit' value='<?php $clang->eT("Save"); ?>' />
            <input type='hidden' name='action' value='addcompany_type' />
        </td>
    </tr>
</table>
</form>