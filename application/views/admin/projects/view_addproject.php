<div class='header ui-widget-header'><?php $clang->eT("Add Projects"); ?></div>
<br />
<script>
    function fill_completes(){
        $.ajax({
            type: 'POST',
            data: {total_completes: $('#quota').val()},
            url: '<?php echo CController::createUrl('admin/project/sa/fillcompletes') ?>',
            success: function(data){
                $('#maxcompletes').html(data)
            }
        })
    };
    
    function validateURL(textval) {
        if(textval == ""){
            return false;
        }else{
            //var urlregex = new RegExp("^(https:\/\/|http:\/\/|https:\/\/www.|https:\/\/www.){1}([0-9A-Za-z]+\.)");
            var urlregex = new RegExp("^(http|https)://", "i");
            return urlregex.test(textval);
        }
    }
    
    function Validationnewproject(){
        var Error = 0;
        var regdecimal = /^\s*((\d+(\.\d+)?)|(\.\d))\s*$/;
        var Quotaval = $("#quota").val();
        var project_manager = $("#project_manager").val();
        var sales_person = $("#sales_person").val();
        var cpc = $("#cpc").val();
        var ir = $("#ir").val();
        var points = $("#points").val();
        var los = $("#los").val();
        var SurveyLnk = $("#surveylink").val();
                
        if(regdecimal.test(Quotaval) == true){
            if(Quotaval < 1 || Quotaval > 99999){
                $("#quota").addClass("error2");
                Error = 1;
            }else{
                $("#quota").removeClass("error2");
            }
        }else{
            $("#quota").addClass("error2");
            Error = 1;
        }
        
                
        if(regdecimal.test(cpc) == true){
            if(cpc <= 0){
                $("#cpc").addClass("error2");
                Error = 1;
            }else{
                $("#cpc").removeClass("error2");
            }
        }else{
            $("#cpc").addClass("error2");
            Error = 1;
        }
        
                
        if(regdecimal.test(ir) == true){
            if(ir < 1 || ir > 100){
                $("#ir").addClass("error2");
                Error = 1;
            }else{
                $("#ir").removeClass("error2");
            }
        }else{
            $("#ir").addClass("error2");
            Error = 1;
        }
        
        if(regdecimal.test(points) == false){
            $("#points").addClass("error2");
            Error = 1;
        }else{
            $("#points").removeClass("error2");
        }
        
        if(regdecimal.test(los) == true){
            if(los < 1 ){
                $("#los").addClass("error2");
                Error = 1;
            }else{
                $("#los").removeClass("error2");
            }
        }else{
            $("#los").addClass("error2");
            Error = 1;
        }
        if(validateURL(SurveyLnk) == false){
            $("#surveylink").addClass("error2");
            Error = 1;
        }else{
            $("#surveylink").removeClass("error2");
        }
        
        
        if(project_manager == ''){
            $("#project_manager").addClass("error2");
            Error = 1;
        }else{
            $("#project_manager").removeClass("error2");
        }
        
        if(sales_person == ''){
            $("#sales_person").addClass("error2");
            Error = 1;
        }else{
            $("#sales_person").removeClass("error2");
        }
        
        if($("#own_panel").val() == ''){
            alert('Please Select Own Panel First in Global Setting');
        }
        if(Error == 1){
            return false;
        }else{
            return true;
        }
        
    }
</script>
<?php echo CHtml::form(array("admin/project/sa/add"), 'post', array('id' => 'newprojectform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnewproject()')); ?>
<table style="width: 80%; margin: 0px auto;">
    <tr>
        <td style="text-align: right;">
            <label for='project_name'><?php $clang->eT("Project Name : "); ?></label>
        </td>
        <td>
            <input type='text' id='project_name' maxlength="25" name='project_name' required="required"/>
            <input type='hidden' id='own_panel' name='own_panel' value="<?php echo getGlobalSetting('Own_Panel'); ?>"/>
        </td>
        <td style="text-align: right;">
            <label for='project_friendly_name'><?php $clang->eT("Project Friendly Name : "); ?></label>
        </td>
        <td>
            <input type='text' id='project_friendly_name' maxlength="25" name='project_friendly_name'/>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='parent_project'><?php $clang->eT("Parent Project : "); ?></label>
        </td>
        <td>
            <?php
            $parent_project = Project::model()->findAll(array('condition' => "parent_project_id = '0'", 'order' => 'project_id desc'));
            $parent_project_list = array();
            foreach ($parent_project as $val) {
                $parent_project_list[$val['project_id']] = $val['project_id'] . ' - ' . $val['project_name'];
            }
            //$parent_project_list = CHtml::listData($parent_project, 'project_id', 'project_name');
            echo CHtml::dropDownList('parent_project', '', $parent_project_list, array('prompt' => 'Select Parent Project'));
            ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='client'><?php $clang->eT("Client : "); ?></label>
        </td>
        <td>
            <?php
            $company = Contact::model()->findAll(array('condition' => "contact_type_id = '1'"));
            $company_list = CHtml::listData($company, 'contact_id', 'company_name');
            echo CHtml::dropDownList('client', '', $company_list, array(
                'prompt' => 'Select Client',
                'ajax' => array(
                    'type' => 'POST',
                    'data' => array('client_id' => 'js:this.value', 'isactive' => '1'),
                    'url' => CController::createUrl('admin/project/sa/selectclientcontact'),
                    'update' => '#client_contact',
                ),
                'required' => true
            ));
            ?>
        </td>
        <td style="text-align: right;">
            <label for='client_contact'><?php $clang->eT("Client Contact : "); ?></label>
        </td>
        <td>
            <?php
            echo CHtml::dropDownList('client_contact', '', array(), array('prompt' => 'Select Contact', 'required' => true));
            ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='project_manager'><?php $clang->eT("Project Manager : "); ?></label>
        </td>
        <td>
            <?php
            $Project_Manager = getGlobalSetting('Project_Manager');
            //$pmquery = "SELECT * FROM {{view_contacts}} WHERE contact_title_id LIKE '%$Project_Manager%' AND IsActive = '1'";
            $pmquery = "SELECT * FROM {{user_in_groups}} AS a INNER JOIN {{users}} AS b ON a.uid = b.uid WHERE ugid = " . $Project_Manager . " ORDER BY b.users_name";
            $pmresult = Yii::app()->db->createCommand($pmquery)->query()->readAll(); //Checked
            $projmanager_list = CHtml::listData($pmresult, 'uid', 'full_name');
            echo CHtml::dropDownList('project_manager', '', $projmanager_list, array('prompt' => 'Select Project Manager'));
            ?>
        </td>
        <td style="text-align: right;">
            <label for='sales_person'><?php $clang->eT("Sales Person : "); ?></label>
        </td>
        <td>
            <?php
            $Sales_Person = getGlobalSetting('Sales_Person');
            //$spquery = "SELECT * FROM {{view_contacts}} WHERE contact_title_id NOT LIKE '%$Project_Manager%' AND IsActive = '1'";
            $spquery = "SELECT * FROM {{user_in_groups}} AS a INNER JOIN {{users}} AS b ON a.uid = b.uid 
                        WHERE ugid != " . $Project_Manager . "  AND users_name != 'admin'
                        ORDER BY b.users_name";
            $spresult = Yii::app()->db->createCommand($spquery)->query()->readAll(); //Checked
            $salesperson_list = CHtml::listData($spresult, 'uid', 'full_name');
            echo CHtml::dropDownList('sales_person', '', $salesperson_list, array('prompt' => 'Select Sales Person'));
            ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='country'><?php $clang->eT("Country : "); ?></label>
        </td>
        <td>
            <?php
            $country = Country::model()->isactive()->findAll(array('order' => 'country_name'));
            $countrylist = CHtml::listData($country, 'country_id', 'country_name');
            $countrylists = array();
            $selected = array_keys($countrylist);
            if (count($selected) <= 0) {
                $selected[0] = 0;
            }
            echo CHtml::dropDownList('country', $selected[0], $countrylist, array('prompt' => 'Select Country'));
            ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='quota'><?php $clang->eT("Req. Completes : "); ?></label>
        </td>
        <td>
            <input type='text' id='quota' name='quota' maxlength="6" onchange="fill_completes();" required="required"/><br/>(Must be between 1 to 99,999)
        </td>
        <td style="text-align: right;">
            <label for='maxcompletes'><?php $clang->eT("Max. Completes : "); ?></label>
        </td>
        <td>
            <?php
            echo CHtml::dropDownList('maxcompletes', '', array(), array('prompt' => 'Select Max. Complets', 'required' => true));
            ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='cpc'><?php $clang->eT("CPC $ : "); ?></label>
        </td>
        <td>
            <input type='text' id='cpc' maxlength="5" name='cpc' value="-1" required="required"/><br/>(Must be between $0.1 to $1,000)
        </td>
        <td style="text-align: right;">
            <label for='los'><?php $clang->eT("LOI : "); ?></label>
        </td>
        <td>
            <input type='text' id='los' name='los' maxlength="5" value="0"/>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='ir'><?php $clang->eT("IR : "); ?></label>
        </td>
        <td>
            <input type='text' id='ir' name='ir' maxlength="3"  value="1" required="required"/><br/> % (Must be between 1 to 100)
        </td>
        <td style="text-align: right;">
            <label for='points'><?php $clang->eT("# of points to award :"); ?></label>
        </td>
        <td>
            <input type='text' id='points' maxlength="11" name='points' required="required"/><br/> (The $ equivalent  100= $1)
        </td>
    </tr>

    <tr>
        <td style="text-align: right;">
            <label for='surveylink'><?php $clang->eT("Survey Link : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' required="required" id='surveylink' name='surveylink'>http://</textarea>
        </td>
        <td style="text-align: right;">
            <label for='notes'><?php $clang->eT("Notes : "); ?></label>
        </td>
        <td>
            <?php
//            $this->widget('application.extensions.extckeditor.ExtCKEditor', array(
//                'model' => 'model',
//                'attribute' => 'surveylink', // model atribute
//                'name' => 'surveylink',
//                'language' => 'en', /* default lang, If not declared the language of the project will be used in case of using multiple languages */
//                'editorTemplate' => 'full', // Toolbar settings (full, basic, advanced)
//            ));
            ?>
            <textarea cols='50' rows='2' id='notes' name='notes'></textarea>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='status'><?php $clang->eT("Status : "); ?></label>
        </td>
        <td>
            <?php
            $sql_project_status = "SELECT status_id,status_name FROM {{project_status_master}} WHERE status_for = 'p'";
            $project_status = Yii::app()->db->createCommand($sql_project_status)->query();
            $project_status_list = CHtml::listData($project_status, 'status_id', 'status_name');
            echo CHtml::dropDownList('status', '', $project_status_list);
            ?>
        </td>
    </tr>
</table>
<p  style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addproject' />
</p>
</form>