<div class='header ui-widget-header'><?php $clang->eT("Editing Company -[" . $_GET['contact_id'] . "]"); ?></div><br />
<script type="text/javascript">
    
    // company type and title validation
    
    function hideshow(value,id){
        // onchange checkbox remove and add required atribute
        var checkedCheckboxes = $('input[name="company_type[]"]:checked'); 
        var checkboxes = $('input[name="company_type[]"]'); 
        if(checkedCheckboxes.length) {
            checkboxes.removeAttr('required');
        } else {
            checkboxes.attr('required', 'required');
        }
        var test = value.split('__');
        checkboxes.each(function(){
            if(document.getElementById(id).checked){
                if(test[1] == 'V'){
                    $("#vendor1,#vendor2").css({"display":"table-row"});
                    $("#completionlink,#disqualifylink,#quatafulllink").attr('required', 'required');
                }
            }else{
                if(test[1] == 'V'){
                    $("#vendor1,#vendor2").css({"display":"none"});
                    $("#completionlink,#disqualifylink,#quatafulllink").removeAttr('required');
                }
            }
        })
    }
    $(function() {
        
        // onload checkbox remove and add required atribute
        var checkedCheckboxes = $('input[name="company_type[]"]:checked'); 
        var checkboxes = $('input[name="company_type[]"]'); 
        if(checkedCheckboxes.length) {
            checkboxes.removeAttr('required');
        } else {
            checkboxes.attr('required', 'required');
        }
        checkboxes.each(function(){
            var value = this.value;
            var id = this.id;
            var test = value.split('__');
            if(document.getElementById(id).checked){
                if(test[1] == 'V'){
                    $("#vendor1,#vendor2").css({"display":"table-row"});
                    $("#completionlink,#disqualifylink,#quatafulllink").attr('required', 'required');
                }
            }else{
                if(test[1] == 'V'){
                    $("#vendor1,#vendor2").css({"display":"none"});
                    $("#completionlink,#disqualifylink,#quatafulllink").removeAttr('required');
                }
            }
        })
        
    });
</script>

<script type="text/javascript">
    
    function getDateDiffInYears(date1, date2) {
        var dateParts1 = date1.split('-')
        , dateParts2 = date2.split('-')
        , d1 = new Date(dateParts1[0], dateParts1[1]-1, dateParts1[2])
        , d2 = new Date(dateParts2[0], dateParts2[1]-1, dateParts2[2])

        return new Date(d2 - d1).getYear() - new Date(0).getYear() + 1;
    }
    
    function validateURL(textval) {
        if(textval == ""){
            return false;
        }else{
            //var urlregex = new RegExp("^(https:\/\/|http:\/\/|https:\/\/www.|https:\/\/www.){1}([0-9A-Za-z]+\.)");
            var urlregex = new RegExp("^(http|https)://", "i");
            return urlregex.test(textval);
        }
    }
    
    function Validationnew(){
        //Get today date
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        if(dd<10) {
            dd='0'+dd
        } 

        if(mm<10) {
            mm='0'+mm
        } 

        today = yyyy+'-'+mm+'-'+mm;
        
        // get userdate
        var userdate = $("#birthdate").val();
        var d = new Date(userdate),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        var clientdate = [year, month, day].join('-');
        
        // date differnce
        var diff = getDateDiffInYears(clientdate, today);
        
        var ErrorMsg = "Following fields needs to be filled.. \n\n";
        var Error = 0;
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        //var regdecimal = /^\s*((\d+(\.\d+)?)|(\.\d))\s*$/;
        var regdecimal = /^(\+|[0-9])([0-9|\s{0,1}]){7,20}$/;
        var emailp = document.getElementById('emailp').value;
        var emails = document.getElementById('emails').value;
        var phonep = document.getElementById('phonep').value;
        var phones = document.getElementById('phones').value;
        var checkedCheckboxes = $('input[name="company_type[]"]:checked'); 
        var checkboxes = $('input[name="company_type[]"]'); 
        if(document.getElementById('zonelist').value == 0 ){
            ErrorMsg = ErrorMsg + "Please Selecte Zone \n";
            Error = 1;
        }
        if(document.getElementById('statelist').value == 0 ){
            ErrorMsg = ErrorMsg + "Please Selecte State \n";
            Error = 1;
        }
        if(document.getElementById('citylist').value == 0 ){
            ErrorMsg = ErrorMsg + "Please Selecte City \n";
            Error = 1;
        }
        
       
        if(reg.test(emailp) == false) {
            ErrorMsg = ErrorMsg + "Please provide Valid Primary Email Address \n";
            Error = 1;
        } 
        if(regdecimal.test(phonep) == false) {
            ErrorMsg = ErrorMsg + "Please provide Valid Primary Phone Number \n";
            Error = 1;
        } 
        if(phones != ""){
            if(regdecimal.test(phones) == false) {
                ErrorMsg = ErrorMsg + "Please provide Valid Secondary Phone Number \n";
                Error = 1;
            } 
        }
        if(emails != ""){
            if(reg.test(emails) == false) {
                ErrorMsg = ErrorMsg + "Please provide Valid Secondary Email Address \n";
                Error = 1;
            } 
        }
        
        checkboxes.each(function(){
            var value = this.value;
            var id = this.id;
            var test = value.split('__');
            if(document.getElementById(id).checked){
                if(test[1] == 'V'){
                    var completionlink = $("#completionlink").val();
                    var disqualifylink = $("#disqualifylink").val();
                    var quatafulllink = $("#quatafulllink").val();
                    //                    if(validateURL(completionlink) == false){
                    //                        ErrorMsg = ErrorMsg + "Please provide Valid Completion Link \n";
                    //                        Error = 1;
                    //                    }
                    //                    if(validateURL(disqualifylink) == false){
                    //                        ErrorMsg = ErrorMsg + "Please provide Valid Disqulify Link \n";
                    //                        Error = 1;
                    //                    }
                    //                    if(validateURL(quatafulllink) == false){
                    //                        ErrorMsg = ErrorMsg + "Please provide Valid Quotafull Link \n";
                    //                        Error = 1;
                    //                    }
                }
            }
        })
        
        if(Error == 1){
            alert(ErrorMsg);
            return false;
        }
        else{
            return true;
        }
    }
</script>
<style type="text/css">
    .header,.ui-widget-header{
        background: none;
        border: none; 
    }
    div.menubar-title{
        background: #ecfbd6;
    }
</style>
<?php echo CHtml::form(array("admin/contact/sa/modcontact/action/modcompany"), 'post', array('id' => 'editcontactform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<?php
foreach ($mur as $mrw) {
    ?>
    <table style="width: 80%; margin: 0px auto;">
        <tr>
            <td align="right" style="text-align: right;">
                <label for='company_name'><?php $clang->eT("Company Name* : "); ?></label>
            </td>
            <td>
                <input type='text' maxlength="25" id='company_name' name='company_name' required="required" value="<?php echo $mrw['company_name']; ?>" />
                <input type='hidden' id='contact_id' name='contact_id' value="<?php echo $mrw['contact_id']; ?>" />
            </td>
            <td align="right" style="text-align: right;">
                <label for='parent_company' title="Non-child companies are only visible."><?php $clang->eT("Parent Company : "); ?></label>
            </td>
            <td>
                <?php
                $contact = "SELECT * FROM {{view_company}} WHERE company_id = '-1' order by company_name";
                $contactlist = Yii::app()->db->createCommand($contact)->query()->readAll();
                $contactlist = CHtml::listData($contactlist, 'contact_id', 'company_name');
                $test = array('0' => 'None');
                $contactlist = array_merge($test, $contactlist);
                echo CHtml::dropDownList('parent_company', $mrw['parent_contact_id'], $contactlist, array(
                    'prompt' => 'Select Parent Company',
                    'title' => "Non-child companies are only visible."
                ));
                ?>
            </td>
        </tr>
        <tr style="display: none;">
            <td align="right">
                <label for='contact_group'><?php $clang->eT("Contact Group* : "); ?></label>
            </td>
            <td>
                <?php
                $cgroup = Contact_group::model()->findAll();
                $cgrouplist = CHtml::listData($cgroup, 'contact_group_id', 'contact_group_name');
                $firstctype = array_keys($cgrouplist);
                echo CHtml::dropDownList('contact_group', $mrw['contact_group_id'], $cgrouplist, array('prompt' => 'Select Contact group'));
                ?>
            </td>
        </tr>
    <!--        <tr>
            <td align="right">
                <label for='company_type'><?php $clang->eT("Contact Type* : "); ?></label>
            </td>
            <td>
        <?php
        $sql = "SELECT * FROM {{company_type_master}} WHERE company_type = 'O' 
                    ORDER BY company_type_id ASC LIMIT 1";
        $contactlist = Yii::app()->db->createCommand($sql)->queryRow();
        echo CHtml::checkBoxList('company_type', $contactlist['company_type_id'] . '__O', array($contactlist['company_type_id'] . '__O' => 'Company'), array('required' => true));
        ?>
            </td>
        </tr>-->
        <tr>
            <td align="right" style="text-align: right;">
                <label for='company_type'><?php $clang->eT("Company Type* : "); ?></label>
            </td>
            <td>
                <?php
                $test = explode(',', $mrw['company_type_id']);
                $test1 = explode(',', $mrw['company_type']);
                $t = array_combine($test, $test1);
                foreach ($t as $key => $val) {
                    $testkey[] = $key . '__' . $val;
                }
                $ctype = Company_type::model()->findAll(
                        array(
                            'select' => 'concat(company_type_id,"__", company_type) as company_type_id , company_type_name',
                            'condition' => "Istitle = '1'"
                        ));
                $ctypelist = CHtml::listData($ctype, 'company_type_id', 'company_type_name');
                echo CHtml::checkBoxList('company_type', $testkey, $ctypelist, array(
                    'onChange' => 'javascript:hideshow(this.value,this.id)',
                    'required' => true));
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right; display: none">
                <label for='IsListProvider'><?php $clang->eT("IsListProvider : "); ?></label>
            </td>
            <td style="display: none">
                <?php
                echo CHtml::radioButtonList('IsListProvider', $mrw['is_list_provider'], array('1' => 'Yes',
                    '0' => 'No'), array(
                    'separator' => ' ', //the default was a line break...
                ));
                ?>
            </td>
            <td align="right" style="text-align: right;">
                <label for='notes'><?php $clang->eT("Notes : "); ?></label>
            </td>
            <td>
                <textarea cols='30' rows='2' id='notes' name='notes'><?php echo $mrw['notes']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='IsListProvider'><?php $clang->eT("Use RelevantIDs? : "); ?></label>
            </td>
            <?php
            $RIDCheckYes = "";
            $RIDCheckNo = "";
            if ($mrw['RIDCheck'] == "Yes")
                $RIDCheckYes = "selected";
            else
                $RIDCheckNo = "selected";
            ?>
            <td>
                <select name="RIDCheck" id="RIDCheck" >
                    <option value="Yes" <?php echo $RIDCheckYes ?> >Yes</option>
                    <option value="No" <?php echo $RIDCheckNo ?> >No</option>
                </select>
            </td>
        </tr>
        <tr style="display: none">
            <td align="right" style="text-align: right;">
                <label for='gender'><?php $clang->eT("Gender* : "); ?></label>
            </td>
            <td>
                <?php
                echo CHtml::radioButtonList('gender', $mrw['gender'], array('m' => 'Male',
                    'f' => 'Female'), array(
                    'separator' => ' ', //the default was a line break...
                ));
                ?>
            </td>
            <td align="right" style="text-align: right;">
                <label for='birthdate'><?php $clang->eT("Birth Date : "); ?></label>
            </td>
            <td>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'birthdate',
                    'value' => $mrw['birth_date'],
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'dateFormat' => 'dd-M-yy',
                        'showAnim' => 'blind',
                        'changeMonth' => true,
                        'changeYear' => true,
                        'yearRange' => '1930:2020'
                    ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;'
                    ),
                ));
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='add1'><?php $clang->eT("Address1 : "); ?></label>
            </td>
            <td>
                <input type='text' id='add1' name='add1' maxlength="50" value="<?php echo $mrw['address1']; ?>" />
            </td>
            <td align="right" style="text-align: right;">
                <label for='add2'><?php $clang->eT("Address2 : "); ?></label>
            </td>
            <td>
                <input type='text' id='add2' name='add2' maxlength="50" value="<?php echo $mrw['address2']; ?>" />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='add3'><?php $clang->eT("Address3 : "); ?></label>
            </td>
            <td>
                <input type='text' id='add3' name='add3' maxlength="50" value="<?php echo $mrw['address3']; ?>" />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='country'>
                    <a href="<?php echo CController::createUrl('admin/country/index') ?>" target="_blank"><?php $clang->eT("Country* : "); ?></a>
                </label>
            </td>
            <td>
                <?php
                $region = Country::model()->findAll(array('order' => 'country_name'));
                $reglist = CHtml::listData($region, 'country_id', 'country_name');
                echo CHtml::dropDownList('country', $mrw['country_id'], $reglist, array(
                    'prompt' => 'Select Country.....',
                    'required' => true,
                    'ajax' => array(
                        'type' => 'POST',
                        'data' => array('action' => 'selectzone', 'country_name' => 'js:this.value'),
                        'url' => CController::createUrl('admin/contact/sa/selectzone'),
                        'update' => '#zonelist',
                    )
                ));
                ?>
            </td>
            <td align="right" style="text-align: right;">
                <label for='zone'>
                    <a href="<?php echo CController::createUrl('admin/zone/index') ?>" target="_blank"><?php $clang->eT("Zone* : "); ?></a>
                </label>
            </td>
            <td>
                <?php
                $zone = Zone::model()->findAll('country_id=:country_id', array(':country_id' => (int) $mrw["country_id"]));
                $zonelist = CHtml::listData($zone, 'zone_id', 'zone_Name');
                echo CHtml::dropDownList('zonelist', $mrw['zone_id'], $zonelist, array(
                    'ajax' => array(
                        'type' => 'POST',
                        'data' => array('action' => 'selectstate', 'zone_name' => 'js:this.value'),
                        'url' => CController::createUrl('admin/contact/sa/selectstate'),
                        'update' => '#statelist',
                    )
                ));
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='state'>
                    <a href="<?php echo CController::createUrl('admin/state/index') ?>" target="_blank"><?php $clang->eT("State* : "); ?></a>
                </label>
            </td>
            <td>
                <?php
                $state = State::model()->findAll('zone_id=:zone_id', array(':zone_id' => (int) $mrw["zone_id"]));
                $statelist = CHtml::listData($state, 'state_id', 'state_Name');
                echo CHtml::dropDownList('statelist', $mrw['state_id'], $statelist, array(
                    'ajax' => array(
                        'type' => 'POST',
                        'data' => array('action' => 'selectcity', 'state_name' => 'js:this.value'),
                        'url' => CController::createUrl('admin/contact/sa/selectcity'),
                        'update' => '#citylist',
                    )
                ));
                ?>
            </td>
            <td align="right" style="text-align: right;">
                <label for='city'>
                    <a href="<?php echo CController::createUrl('admin/city/index') ?>" target="_blank"><?php $clang->eT("City* : "); ?></a>
                </label>
            </td>
            <td>
                <?php
                $city = City::model()->findAll('state_id=:state_id', array(':state_id' => (int) $mrw["state_id"]));
                $citylist = CHtml::listData($city, 'city_id', 'city_Name');
                echo CHtml::dropDownList('citylist', $mrw['city_id'], $citylist);
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='zip'><?php $clang->eT("Zip : "); ?></label>
            </td>
            <td>
                <input type='text' id='zip' name='zip' maxlength="15" value="<?php echo $mrw['zip']; ?>" />
            </td>
            <td align="right" style="text-align: right;">
                <label for='fax'><?php $clang->eT("Fax : "); ?></label>
            </td>
            <td>
                <input type='text' id='fax' name='fax' maxlength="100" value="<?php echo $mrw['fax']; ?>" />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='emailp'><?php $clang->eT("Primary Email ID* : "); ?></label>
            </td>
            <td>
                <input type='text' id='emailp' name='emailp' maxlength="100" required="required" value="<?php echo $mrw['primary_emailid']; ?>" />
            </td>
            <td align="right" style="text-align: right;">
                <label for='emails'><?php $clang->eT("Secondary Email ID :"); ?></label>
            </td>
            <td>
                <input type='text' id='emails' name='emails' maxlength="100" value="<?php echo $mrw['other_emailid']; ?>" />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='phonep'><?php $clang->eT("Primary Phone No* : "); ?></label>
            </td>
            <td>
                <input type='text' id='phonep' name='phonep' maxlength="20" required="required" value="<?php echo $mrw['primary_contact_no']; ?>" />
            </td>
            <td align="right" style="text-align: right;">
                <label for='phones'><?php $clang->eT("Secondary Phone No :"); ?></label>
            </td>
            <td>
                <input type='text' id='phones' name='phones' maxlength="20" value="<?php echo $mrw['other_contact_no']; ?>" />
            </td>
        </tr>
        <tr style="display: none;" id="vendor1">
            <td align="right" style="text-align: right;">
                <label for='completionlink'><?php $clang->eT("Completion link* : "); ?></label>
            </td>
            <td>
                <textarea cols='50' rows='2' id='completionlink' name='completionlink'><?php echo $mrw['completionlink']; ?></textarea>
            </td>
            <td align="right" style="text-align: right;">
                <label for='disqualifylink'><?php $clang->eT("Disqualify link* : "); ?></label>
            </td>
            <td>
                <textarea cols='50' rows='2' id='disqualifylink' name='disqualifylink'><?php echo $mrw['disqualifylink']; ?></textarea>
            </td>
        </tr>
        <tr style="display: none;" id="vendor2">
            <td align="right" style="text-align: right;">
                <label for='quatafulllink'><?php $clang->eT("Quatafull link* : "); ?></label>
            </td>
            <td>
                <textarea cols='50' rows='2' id='quatafulllink' name='quatafulllink'><?php echo $mrw['quatafulllink']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='IsActive'><?php $clang->eT("IsActive : "); ?></label>
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
        </tr>
        <?php
    }
    ?>
</table>
<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='modcompany' />
</p>
</form>
<br/>
<?php echo CHtml::form(array('admin/contact/index', 'action' => 'addcontact'), 'post'); ?>            
<input type='submit' value='<?php $clang->eT("Add Contact"); ?>' style="float: right;" />
<input type='hidden' name="company_id" value='<?php echo $contact_id; ?>' />
</form>

<div class='header ui-widget-header'><?php $clang->eT("Manage Contact"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listCompanyContact').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>

<table id="listCompanyContact" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
<!--            <th><?php $clang->eT("Delete"); ?></th>-->
            <th><?php $clang->eT("ID"); ?></th>
            <th><?php $clang->eT("Contact Name"); ?></th>
            <th><?php $clang->eT("Contact Email ID"); ?></th>
            <th><?php $clang->eT("Contact Type Name"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            if ($usr['company_id'] == $contact_id) {
                ?>
                <tr>

                    <td style="padding:3px;">
                        <?php echo CHtml::form(array('admin/contact/sa/modifycontact/contact_id/' . $usr['contact_id'] . '/action/modifycontact'), 'post'); ?>            
                        <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Contact"); ?>' />
                        <input type='hidden' name='action' value='modifycontact' />
                        <input type='hidden' name='contact_id' value='<?php echo $usr['contact_id']; ?>' />
                        <input type='hidden' name='company_id' value='<?php echo $contact_id; ?>' />
                        </form>
                    </td>
        <!--                    <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/contact/sa/delcontact'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                        <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this user"); ?>' />
                        <input type='hidden' name='action' value='delcontact' />
                        <input type='hidden' name='contact_id' value='<?php echo $usr['contact_id']; ?>' />
                        </form>
                    </td>-->
                    <td><?php echo $usr['contact_id']; ?></td>
                    <td><?php echo htmlspecialchars($usr['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($usr['primary_emailid']); ?></td>
                    <td><?php echo htmlspecialchars($usr['contact_title_name']); ?></td>
                    <td><?php
            IF ($usr['IsActive'] == TRUE) {
                echo 'True';
            } ELSE {
                echo 'False';
            }
                    ?></td>
                </tr>
                <?php
                $row++;
            }
        }
        ?>
    </tbody>
</table>