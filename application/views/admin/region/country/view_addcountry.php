<div class='header ui-widget-header'><?php $clang->eT("Manage Country"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listCountry').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>

<table id="listCountry" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("Delete"); ?></th>
            <th><?php $clang->eT("Country ID"); ?></th>
            <th><?php $clang->eT("Country Name"); ?></th>
            <th><?php $clang->eT("Continent"); ?></th>
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
                    <?php echo CHtml::form(array('admin/country/sa/modifycountry'), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this country"); ?>' />
                    <input type='hidden' name='action' value='modifycountry' />
                    <input type='hidden' name='country_id' value='<?php echo $usr['country_id']; ?>' />
                    </form>
                </td>
                <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/country/sa/delcountry'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this country"); ?>' />
                    <input type='hidden' name='action' value='delcountry' />
                    <input type='hidden' name='country_name' value='<?php echo htmlspecialchars($usr['country_name']); ?>' />
                    <input type='hidden' name='country_id' value='<?php echo $usr['country_id']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['country_id']; ?></td>
                <td><?php echo htmlspecialchars($usr['country_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['continent']); ?></td>
                <td><?php if ($usr['IsActive'] == True) {echo 'True';} else {echo 'False';} ?></td>
            </tr>
            <?php $row++;
        } ?>
    </tbody>
</table>
<br /><br />
<?php echo CHtml::form(array('admin/country/sa/addcountry'), 'post'); ?>            
<table class='users'>
    <tr class='oddrow'>
        <th><?php $clang->eT("Add Country:"); ?></th>
        <td style='width:20%'><input type='text' maxlength="50" name='new_country' placeholder="Country Name" /></td>
        <td style='width:20%'><input type='text' maxlength="50" name='continent' placeholder="Continent Name" /></td>
        <td style='width:15%'><input type='submit' value='<?php $clang->eT("Save"); ?>' />
            <input type='hidden' name='action' value='addcountry' />
        </td>
    </tr>
</table>
</form>