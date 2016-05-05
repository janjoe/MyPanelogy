<div class='header ui-widget-header'><?php $clang->eT("Editing Contact"); ?></div><br />

<script type="text/javascript">
    
    function getDateDiffInYears(date1, date2) {
        var dateParts1 = date1.split('-')
        , dateParts2 = date2.split('-')
        , d1 = new Date(dateParts1[0], dateParts1[1]-1, dateParts1[2])
        , d2 = new Date(dateParts2[0], dateParts2[1]-1, dateParts2[2])

        return new Date(d2 - d1).getYear() - new Date(0).getYear() + 1;
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
        
        if(diff < 18){
            ErrorMsg = ErrorMsg + "Your age must be 18+ \n";
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
        
        if(Error == 1){
            alert(ErrorMsg);
            return false;
        }
        else{
            return true;
        }
    }
</script>
<script type="text/javascript">
    $(function() {
        
        // onload checkbox remove and add required atribute
        var checkedCheckboxes = $('input[name="contact_type[]"]:checked'); 
        var checkboxes = $('input[name="contact_type[]"]'); 
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
                $("#Title_"+value).css({"display":"inline-block"});
                $("#titlelabel_"+value).css({"display":"inline-block"});
                $("#Title_"+value).attr('required', 'required');
            }else{
                $("#Title_"+value).css({"display":"none"});
                $("#titlelabel_"+value).css({"display":"none"});
                $("#Title_"+value).removeAttr('required');
            }
            
        })
        
    });
</script>
<style type="text/css">
    .header,.ui-widget-header{
        background: none;
        border: none; 
    }
    div.menubar-title{
        background: #EDEDFB;
    }
</style>
<?php echo CHtml::form(array("admin/contact/sa/modcontact/action/modcontact"), 'post', array('id' => 'editcontactform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<?php
foreach ($mus as $mrw) {
    ?>
    <table style="width: 80%; margin: 0px auto;">
        <tr>

            <td align="right" style="text-align: right;">
                <label for='saluation'><?php $clang->eT("Saluation : "); ?></label>
            </td>
            <td>
                <input type='text' maxlength="4" id='saluation' name='saluation' value="<?php echo $mrw['saluation']; ?>" />
            </td>
            <td align="right" style="text-align: right;">
                <label for='contact_fname'><?php $clang->eT("First Name* : "); ?></label>
            </td>
            <td>
                <input type='text' maxlength="25" id='contact_fname' name='contact_fname' required="required" value="<?php echo $mrw['first_name']; ?>" />
                <input type='hidden' id='contact_id' name='contact_id' value="<?php echo $mrw['contact_id']; ?>" />
                <input type='hidden' id='company_id' name='company_id' value="<?php echo $mrw['company_id']; ?>" />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='contact_mname'><?php $clang->eT("Middle Name : "); ?></label>
            </td>
            <td>
                <input type='text' maxlength="25" id='contact_mname' name='contact_mname' value="<?php echo $mrw['middle_name']; ?>" />
            </td>
            <td align="right" style="text-align: right;">
                <label for='contact_lname'><?php $clang->eT("Last Name* : "); ?></label>
            </td>
            <td>
                <input type='text' maxlength="25" id='contact_lname' name='contact_lname' required="required" value="<?php echo $mrw['last_name']; ?>" />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='contact_group'><?php $clang->eT("Contact Group* : "); ?></label>
            </td>
            <td>
                <?php
                $cgroup = Contact_group::model()->findAll();
                $cgrouplist = CHtml::listData($cgroup, 'contact_group_id', 'contact_group_name');
                $firstctype = array_keys($cgrouplist);
                echo CHtml::dropDownList('contact_group', $mrw['contact_group_id'], $cgrouplist, array('prompt' => 'Select Contact group', 'required' => true));
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" style="display: none;">
                <label for='contact_type'><?php $clang->eT("Contact Type* : "); ?></label>
            </td>
            <td style="display: none;">
                <?php
                $sql = "SELECT CONCAT(ctm.company_type_id,'__', ctm.company_type) AS company_type_id , company_type_name
                    FROM {{map_company_n_types}} mct 
                    LEFT JOIN {{company_type_master}} ctm ON ctm.company_type_id = mct.company_type_id
                    WHERE company_id =" . $mrw['company_id'] . "";
                $uresult = Yii::app()->db->createCommand($sql)->query()->readAll();
                $ctypelist = CHtml::listData($uresult, 'company_type_id', 'company_type_name');
                foreach ($ctypelist as $key => $value) {
                    $test[] = $key;
                }
                echo CHtml::checkBoxList('company_type', $test, $ctypelist, array(
                    'required' => true));
                ?>
            </td>
            <td align="right" style="text-align: right;">
                <?php
                $ctypes = Company_type::model()->findAll(
                        array(
                            'select' => 'concat(company_type_id,"__", company_type) as company_type_id , company_type_name',
                            'condition' => "company_type != 'O' AND IsTitle = '1'"));
                $ctypeslist = CHtml::listData($ctypes, 'company_type_id', 'company_type_name');
                foreach ($ctypelist as $key => $value) {
                    echo "<label id='titlelabel_" . $key . "'>Contact Title for " . $value . "* : </label>";
                    echo '<br/>';
                }
                ?>
            </td>
            <td>
                <?php
                $titleselected = explode(',', $mrw['contact_title_id']);
                $tsel = array_combine($test, $titleselected);
                foreach ($ctypelist as $ky => $val) {
                    $selectedtitle = array();
                    foreach ($tsel as $kye => $vle) {
                        if ($ky == $kye) {
                            $selectedtitle[] = $vle;
                        }
                    }
                    $title = Contact_title::model()->findAll(array('order' => 'contact_title_name'));
                    $titlelist = CHtml::listData($title, 'contact_title_id', 'contact_title_name');
                    echo CHtml::dropDownList($val, $selectedtitle, $titlelist, array(
                        'prompt' => 'Select ' . $val . ' title',
                        'name' => $val,
                        'id' => 'Title_' . $ky,
                        'required' => true,
                    ));
                    echo '<br/>';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;display: none">
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
                <label for='gender'><?php $clang->eT("Gender* : "); ?></label>
            </td>
            <td>
                <?php
                echo CHtml::radioButtonList('gender', $mrw['gender'], array('m' => 'Male', 'f' => 'Female', 'o' => 'Other'), array(
                    'separator' => ' ', //the default was a line break...
                ));
                ?>
            </td>
            <td align="right" style="text-align: right;">
                <label for='birthdate'><?php $clang->eT("Birth Date : "); ?></label>
            </td>
            <td>
                <?php
                $curYear = date('Y');
                $startYear = $curYear - 100;
                $endYear = $curYear - 18;
                $daterange = $startYear . ":" . $endYear;
                $birthdate = date('d-M-Y', strtotime($mrw['birth_date']));
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'birthdate',
                    'value' => $birthdate,
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'dateFormat' => 'dd-M-yy',
                        'showAnim' => 'blind',
                        'changeMonth' => true,
                        'changeYear' => true,
                        'yearRange' => $daterange
                    ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;',
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
                <label for='emails'><?php $clang->eT("Secondary Email ID : "); ?></label>
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
                <label for='phones'><?php $clang->eT("Secondary Phone No:"); ?></label>
            </td>
            <td>
                <input type='text' id='phones' name='phones' maxlength="20" value="<?php echo $mrw['other_contact_no']; ?>" />
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
    <input type='hidden' name='action' value='modcontact' />
    <input type="button" value="Cancel" onClick ="document.location.href='<?php echo $this->createUrl('admin/contact/sa/modifycontact/contact_id/' . $mrw['company_id'] . '/action/modifycompany') ?>'" />
</p>
</form>
