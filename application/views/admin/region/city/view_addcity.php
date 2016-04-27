<div class='header ui-widget-header'><?php $clang->eT("Manage City"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listCity').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>

<?php echo CHtml::form(array('admin/city/index'), 'post'); ?>
<table class="users">
    <tr>
        <td><?php $clang->eT("Select Country"); ?></td>
        <td>
            <?php
            $country_id = Yii::app()->request->cookies['Country']->value;
            if (isset($mur)) {
                $region = Country::model()->findAll(array('order' => 'country_name'));
            } else {
                $region = Country::model()->isactive()->findAll(array('order' => 'country_name'));
            }
            $reglist = CHtml::listData($region, 'country_id', 'country_name');
            echo CHtml::dropDownList('country_name', $country_id, $reglist, array(
                'submit' => '',
            ));
            ?>
        </td>
    </tr>
</table>
</form>

<table id="listCity" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("Delete"); ?></th>
            <th><?php $clang->eT("City ID"); ?></th>
            <th><?php $clang->eT("City Name"); ?></th>
            <th><?php $clang->eT("State Name"); ?></th>
            <th><?php $clang->eT("Zone Name"); ?></th>
            <th><?php $clang->eT("Country Name"); ?></th>
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
                    <?php echo CHtml::form(array('admin/city/sa/modifycity'), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this city"); ?>' />
                    <input type='hidden' name='action' value='modifycity' />
                    <input type='hidden' name='city_id' value='<?php echo $usr['city_id']; ?>' />
                    </form>
                </td>
                <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/city/sa/delcity'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this city"); ?>' />
                    <input type='hidden' name='action' value='delcity' />
                    <input type='hidden' name='city_name' value='<?php echo htmlspecialchars($usr['city_Name']); ?>' />
                    <input type='hidden' name='city_id' value='<?php echo $usr['city_id']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['city_id']; ?></td>
                <td><?php echo htmlspecialchars($usr['city_Name']); ?></td>
                <td><?php echo htmlspecialchars($usr['state_Name']); ?></td>
                <td><?php echo htmlspecialchars($usr['zone_Name']); ?></td>
                <td><?php echo htmlspecialchars($usr['country_name']); ?></td>
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
<?php
if (isset($mur)) {
    ?>

    <?php echo CHtml::form(array('admin/city/sa/modcity'), 'post'); ?>            
    <table>
        <tr class='oddrow'>
            <th><?php $clang->eT("Edit city:"); ?></th>
            <?php
            foreach ($mur as $mrw) {
                ?>
                <td style='width:20%'>
                    <input type='text' maxlength="50" name='c_name'  value="<?php echo $mrw['city_Name'] ?>"/>
                    <input type='hidden' name='city_Name' value="<?php echo $mrw['city_Name']; ?>" />
                    <input type='hidden' name='city_id' value="<?php echo $mrw['city_id']; ?>" />
                    <input type='hidden' name='country_id' value="<?php echo $mrw['country_id']; ?>" />
                    <input type='hidden' name='zone_id' value="<?php echo $mrw['zone_id']; ?>" />
                    <input type='hidden' name='state_id' value="<?php echo $mrw['state_id']; ?>" />
                    <input type='hidden' name='country_name' value="<?php echo (int) $country_id; ?>" />
                </td>
                <td>
                    <?php
                    $zone = Zone::model()->findAll('country_id=:country_id', array(':country_id' => (int) $mrw['country_id']));
                    $zonelist = CHtml::listData($zone, 'zone_id', 'zone_Name');
                    echo CHtml::dropDownList('zonelist', "" . $mrw['zone_id'] . "", $zonelist, array(
                        'ajax' => array(
                            'type' => 'POST',
                            'data' => array('action' => 'selectstate', 'zonelist' => 'js:this.value'),
                            'url' => CController::createUrl('admin/city/sa/selectstate'),
                            'update' => '#statelist'
                        )
                    ));
                    ?>
                </td>
                <td>
                    <?php
                    $state = State::model()->findAll('zone_id=:zone_id', array(':zone_id' => (int) $mrw['zone_id']));
                    $stalist = CHtml::listData($state, 'state_id', 'state_Name');
                    echo CHtml::dropDownList('statelist', '', $stalist, array());
                    ?>
                </td>
                <?php
                $chk = '';
                if ($mrw['IsActive'] == 1) {
                    $chk = 'checked=checked';
                }
                ?>
                <td>
                    IsActive : <input type="checkbox" style="vertical-align: sub;" <?php echo $chk; ?> name="IsActive" />
                </td>
                <?php
            }
            ?>
            <td style='width:15%'><input type='submit' value='<?php $clang->eT("Save"); ?>' />
                <input type='hidden' name='action' value='modcity' /></td>
        </tr>
    </table>
    </form>
    <?php
} else {
    ?>
    <?php echo CHtml::form(array('admin/city/sa/addcity'), 'post'); ?>            
    <table class='users'>
        <tr class='oddrow'>
            <th><?php $clang->eT("Add city:"); ?></th>
            <td style='width:20%'>
                <input type='text' maxlength="50" name='new_city' />
                <input type='Hidden' name='country_name' value="<?php echo (int) $country_id; ?>" />
            </td>
            <td>
                <?php
                $region = Zone::model()->isactive()->findAll('country_id=:country_id', array(':country_id' => (int) $country_id), array('condition' => "IsActive = 1"));
                $reglist = CHtml::listData($region, 'zone_id', 'zone_Name');
                echo CHtml::dropDownList('zonelist', 'zone_id', $reglist, array(
                    'prompt' => 'Select zone',
                    'ajax' => array(
                        'type' => 'POST',
                        'data' => array('action' => 'selectstate', 'zonelist' => 'js:this.value', 'isactive' => '1'),
                        'url' => CController::createUrl('admin/city/sa/selectstate'),
                        'update' => '#statelist')
                ));
                ?>
            </td>
            <td>
                <?php
                echo CHtml::dropDownList('statelist', '', array('stateselect' => 'Select state'));
                ?>
            </td>
            <td style='width:15%'><input type='submit' value='<?php $clang->eT("Save"); ?>' />
                <input type='hidden' name='action' value='addcity' /></td>
        </tr>
    </table>
    </form>
    <?php
}
?>
