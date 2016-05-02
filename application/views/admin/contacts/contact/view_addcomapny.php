<div class='header ui-widget-header'><?php $clang->eT("Add Company"); ?></div>
<br/>

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
        
        var zonelist = document.getElementById('zonelist').value;
        if(zonelist == 0 || zonelist == 'prompt'){
            ErrorMsg = ErrorMsg + "Please Selecte Zone \n";
            Error = 1;
        }
        var statelist = document.getElementById('statelist').value;
        if(statelist == 0 || statelist == 'prompt' ){
            ErrorMsg = ErrorMsg + "Please Selecte State \n";
            Error = 1;
        }
        var citylist = document.getElementById('citylist').value;
        if(citylist == 0 || citylist == 'prompt' ){
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
                ErrorMsg = ErrorMsg + "Please provide Valid Secondary Email address \n";
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
<?php echo CHtml::form(array("admin/contact/sa/add"), 'post', array('id' => 'companyform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<table style="width: 80%; margin: 0px auto;">
    <tr>
        <td align="right" style="text-align: right">
            <label for='company_name'><?php $clang->eT("Company Name* : "); ?></label>
        </td>
        <td>
            <input type='text' maxlength="25" id='company_name' name='company_name' autofocus="autofocus" required="required" />
        </td>
        <td align="right" style="text-align: right">
            <label for='parent_company' title="Non-child companies are only visible."><?php $clang->eT("Parent Company : "); ?></label>
        </td>
        <td>
            <?php
            $company = "SELECT * FROM {{view_company}} WHERE company_id = '-1' AND IsActive = 1 order by company_name";
            $companylist = Yii::app()->db->createCommand($company)->query()->readAll();
            $companylist = CHtml::listData($companylist, 'contact_id', 'company_name');
            $test = array('0' => 'None');
            $companylist = array_merge($test, $companylist);
            echo CHtml::dropDownList('parent_company', '0', $companylist, array(
                'prompt' => 'Select Parent Company',
                'title' => "Non-child companies are only visible."
            ));
            ?>
        </td>
    </tr>
    <tr style="display: none">
        <td align="right">
            <label for='contact_group'><?php $clang->eT("Contact Group* : "); ?></label>
        </td>
        <td>
            <?php
            $cgroup = Contact_group::model()->isactive()->findAll();
            $cgrouplist = CHtml::listData($cgroup, 'contact_group_id', 'contact_group_name');
            $firstctype = array_keys($cgrouplist);
            echo CHtml::dropDownList('contact_group', '', $cgrouplist, array('prompt' => 'Select Contact group'));
            ?>
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right">
            <label for='company_type'><?php $clang->eT("Company Type* : "); ?></label>
        </td>
        <td>
            <?php
            $ctype = Company_type::model()->isactive()->findAll(array(
                'select' => 'concat(company_type_id,"__", company_type) as company_type_id , company_type_name',
                'condition' => "Istitle = '1'"
                    ));
            $ctypelist = CHtml::listData($ctype, 'company_type_id', 'company_type_name');
            $firstctype = array_keys($ctypelist);
            if (count($ctypelist)) {
                $firstcontacttype = $firstctype[0];
            } else {
                $firstcontacttype = 0;
            }
            echo CHtml::checkBoxList('company_type', $firstcontacttype, $ctypelist, array(
                'onChange' => 'javascript:hideshow(this.value,this.id)',
                'required' => true));
            ?>
        </td>
        <td align="right" style="display: none">
            <?php
            $ctypes = Company_type::model()->findAll(
                    array(
                        'select' => 'concat(company_type_id,"__", company_type) as company_type_id , company_type_name',
                        'condition' => "company_type != 'O' AND IsTitle = '1'"));
            $ctypeslist = CHtml::listData($ctypes, 'company_type_id', 'company_type_name');
            foreach ($ctypeslist as $key => $value) {
                echo "<label id='titlelabel_" . $key . "'>Company Title for " . $value . "* : </label>";
                echo '<br/>';
            }
            ?>
        </td>
        <td style="display: none">
            <?php
            foreach ($ctypeslist as $ky => $val) {
                $title = Contact_title::model()->findAll();
                $titlelist = CHtml::listData($title, 'contact_title_id', 'contact_title_name');
                echo CHtml::dropDownList($val, '', $titlelist, array(
                    'prompt' => 'Select ' . $val . ' title',
                    'name' => $val,
                    'id' => 'Title_' . $ky,
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
            echo CHtml::radioButtonList('IsListProvider', '0', array('1' => 'Yes',
                '0' => 'No'), array(
                'separator' => ' ', //the default was a line break...
            ));
            ?>
        </td>
        <td align="right" style="text-align: right">
            <label for='notes' ><?php $clang->eT("Notes : "); ?></label>
        </td>
        <td>
            <textarea cols='30' rows='2' id='notes' name='notes'></textarea>
        </td>
    </tr>
    <tr style="display: none;">
        <td align="right">
            <label for='gender'><?php $clang->eT("Gender* : "); ?></label>
        </td>
        <td>
            <?php
            echo CHtml::radioButtonList('gender', 'm', array('m' => 'Male',
                'f' => 'Female'), array(
                'separator' => ' ', //the default was a line break...
            ));
            ?>
        </td>
        <td align="right" style="text-align: right">
            <label for='birthdate'><?php $clang->eT("Birth Date : "); ?></label>
        </td>
        <td>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'birthdate',
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
        <td align="right" style="text-align: right">
            <label for='add1'><?php $clang->eT("Address1 : "); ?></label>
        </td>
        <td>
            <input type='text' id='add1' maxlength="50" name='add1' />
        </td>
        <td align="right" style="text-align: right">
            <label for='add2'><?php $clang->eT("Address2 : "); ?></label>
        </td>
        <td>
            <input type='text' id='add2' maxlength="50" name='add2' />
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right">
            <label for='add3'><?php $clang->eT("Address3 : "); ?></label>
        </td>
        <td>
            <input type='text' id='add3' maxlength="50" name='add3' />
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right">
            <label for='country'>
                <a href="<?php echo CController::createUrl('admin/country/index') ?>" target="_blank"><?php $clang->eT("Country* : "); ?></a>
            </label>
        </td>
        <td>
            <?php
            $region = Country::model()->isactive()->findAll(array('order' => 'country_name'));
            $reglist = CHtml::listData($region, 'country_id', 'country_name');
            echo CHtml::dropDownList('country', 'country_id', $reglist, array(
                'prompt' => 'Select Country.....',
                'required' => true,
                'ajax' => array(
                    'type' => 'POST',
                    'data' => array('action' => 'selectzone', 'country_name' => 'js:this.value', 'isactive' => '1'),
                    'url' => CController::createUrl('admin/contact/sa/selectzone'),
                    'update' => '#zonelist',
                )
            ));
            ?>
        </td>
        <td align="right" style="text-align: right">
            <label for='zone'>
                <a href="<?php echo CController::createUrl('admin/zone/index') ?>" target="_blank"><?php $clang->eT("Zone* : "); ?></a>
            </label>
        </td>
        <td>
            <?php
            echo CHtml::dropDownList('zonelist', 'zone_id', array('prompt' => 'Select Zone.....'), array(
                'ajax' => array(
                    'type' => 'POST',
                    'data' => array('action' => 'selectstate', 'zone_name' => 'js:this.value', 'isactive' => '1'),
                    'url' => CController::createUrl('admin/contact/sa/selectstate'),
                    'update' => '#statelist',
                )
            ));
            ?>
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right">
            <label for='state'>
                <a href="<?php echo CController::createUrl('admin/state/index') ?>" target="_blank"><?php $clang->eT("State* : "); ?></a>
            </label>
        </td>
        <td>
            <?php
            echo CHtml::dropDownList('statelist', 'state_id', array('prompt' => 'Select State.....'), array(
                'ajax' => array(
                    'type' => 'POST',
                    'data' => array('action' => 'selectcity', 'state_name' => 'js:this.value', 'isactive' => '1'),
                    'url' => CController::createUrl('admin/contact/sa/selectcity'),
                    'update' => '#citylist',
                )
            ));
            ?>
        </td>
        <td align="right" style="text-align: right">
            <label for='city'>
                <a href="<?php echo CController::createUrl('admin/city/index') ?>" target="_blank"><?php $clang->eT("City* : "); ?></a>
            </label>
        </td>
        <td>
            <?php
            echo CHtml::dropDownList('citylist', 'city_id', array('prompt' => 'Select City.....'));
            ?>
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right">
            <label for='zip'><?php $clang->eT("Zip/Postal Code : "); ?></label>
        </td>
        <td>
            <input type='text' id='zip' name='zip' maxlength="15" />
        </td>
        <td align="right" style="text-align: right">
            <label for='fax'><?php $clang->eT("Fax : "); ?></label>
        </td>
        <td>
            <input type='text' id='fax' name='fax' maxlength="100" />
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right">
            <label for='emailp'><?php $clang->eT("Primary Email ID* : "); ?></label>
        </td>
        <td>
            <input type='text' id='emailp' maxlength="100" name='emailp' required="required" />
        </td>
        <td align="right" style="text-align: right">
            <label for='emails'><?php $clang->eT("Secondary Email ID : "); ?></label>
        </td>
        <td>
            <input type='text' id='emails' name='emails' maxlength="100"  />
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right">
            <label for='phonep'><?php $clang->eT("Primary Phone No* : "); ?></label>
        </td>
        <td>
            <input type='text' id='phonep' name='phonep' maxlength="20" required="required" />
        </td>
        <td align="right" style="text-align: right">
            <label for='phones'><?php $clang->eT("Secondary Phone No : "); ?></label>
        </td>
        <td>
            <input type='text' id='phones' name='phones' maxlength="20" />
        </td>
    </tr>
    <tr style="display: none;" id="vendor1">
        <td align="right" style="text-align: right">
            <label for='completionlink'><?php $clang->eT("Completion link* : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' id='completionlink' name='completionlink'>http://</textarea>
        </td>
        <td align="right" style="text-align: right">
            <label for='disqualifylink'><?php $clang->eT("Disqualify link* : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' id='disqualifylink' name='disqualifylink'>http://</textarea>
        </td>
    </tr>
    <tr style="display: none;" id="vendor2">
        <td align="right" style="text-align: right">
            <label for='quatafulllink'><?php $clang->eT("Quotafull link* : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' id='quatafulllink' name='quatafulllink'>http://</textarea>
        </td>
    </tr>
</table>
<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addcompany' />
</p>
</form>


