<div class='header ui-widget-header'><?php $clang->eT("Edit Project -[" . $_GET['project_id'] . "]"); ?></div>
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
    }
    function reloadpage(){
        return true;
    }
    /* Graph Call */
    function ChangeGraph(pjid){
        $("#graphdiv").html('<img src="images/loadingAnimation.gif">');
        var gHtml = "";
        var url = "";
        var VendorID, i,interval;
        VendoerID = $('#VendorID').val();
        //i = $('#interval').val();
        i = $("input[name='interval']:checked").val();

        console.log(i);
        if(i == 'day'){ interval = '0';}else{interval = '1';}
        url = "includes/get_project_graph.php?pjid="+pjid+"&vrid="+VendoerID+"&interval="+interval;
        gHtml = callAjaxContent(url);
        $("#graphdiv").html(gHtml).hide().show('slow');
        //document.location.href = "index.php?mod=EditProject&pjid="+pjid+"&vrid="+vrid+"&i="+i+"#graph";
    }
    
    function SysMsg(id)
    {
        TINY.box.show({url:'<?php echo CController::createUrl('admin/project/sa/showmsg') . '/project_id/' ?>'+ id });	 
        //TINY.box.show({url:'../displays/system_message.php?pjid='+id});	 
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
    
    function Validationeditproject(){
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
            alert('Please Select Internal Company First in Global Setting');
        }
        if(Error == 1){
            return false;
        }else{
            return true;
        }
    }
</script>

<?php echo CHtml::form(array("admin/project/sa/modproject/action/editproject"), 'post', array('id' => 'newprojectform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationeditproject()')); ?>
<?php
foreach ($mur as $mrw) {
    ?>
    <div>
        <div style="width: 75%; float: left">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: right;">
                        <label for='project_name'><?php $clang->eT("Project Name : "); ?></label>
                    </td>
                    <td>
                        <input type='text' id='project_name' maxlength="25" name='project_name' value="<?php echo $mrw['project_name']; ?>" required="required"/>
                        <input type='hidden' id='own_panel' name='own_panel' value="<?php echo getGlobalSetting('Own_Panel'); ?>"/>
                        <input type='hidden' id='project_id' name='project_id' value="<?php echo $mrw['project_id']; ?>" />
                    </td>
                    <td style="text-align: right;">
                        <label for='project_friendly_name'><?php $clang->eT("Project Friendly Name : "); ?></label>
                    </td>
                    <td>
                        <input type='text' id='project_friendly_name' maxlength="25" value="<?php echo $mrw['friendly_name']; ?>" name='project_friendly_name'/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;vertical-align: top;">
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
                        echo CHtml::dropDownList('parent_project', $mrw['parent_project_id'], $parent_project_list, array('prompt' => 'Select Parent Project'));
                        ?>
                    </td>
                    <?php
                    $Sql = ' SELECT project_id,parent_project_id ';
                    $Sql .= " ,IFNULL((SELECT GROUP_CONCAT(CONCAT('<a href=" . $_SERVER['HTTP_HOST'] . "/admin/project/sa/modifyproject/project_id/',project_id,'/action/modifyproject>',project_id,' -- ',project_name,'</a><br>')) FROM {{project_master}} WHERE parent_project_id=X.project_id),' ') AS child_projs ";
                    $Sql .= ' FROM {{project_master}} X ';
                    $Sql .= " WHERE project_id='" . $_GET['project_id'] . "' ";
                    $result = Yii::app()->db->createCommand($Sql)->query()->readAll();

                    //Start By Parth 24-06-2014
                    $Sql1 = ' SELECT project_id,parent_project_id ';
                    $Sql1 .= " ,IFNULL((SELECT GROUP_CONCAT(CONCAT('<a href=" . $_SERVER['HTTP_HOST'] . "/survey/index.php/admin/project/sa/modifyproject/project_id/',project_id,'/action/modifyproject>',project_id,' -- ',project_name,'</a>','<br>')) FROM {{project_master}} WHERE project_id=X.parent_project_id),' ') AS parent_projs ";
                    $Sql1 .= ' FROM {{project_master}} X ';
                    $Sql1 .= " WHERE project_id='" . $_GET['project_id'] . "' ";
                    $presult = Yii::app()->db->createCommand($Sql1)->query()->readAll();
                    //End By Parth 24-06-2014
                    ?>
                    <td style="text-align: right;font-weight:bold;vertical-align: top;">
                        <?php
                        if (count($result) > 0) {
                            foreach ($result as $key => $value) {
                                if ($value['child_projs'] <> " ") {
                                    echo "Child Project:";
                                }
                            }
                        }
                        //Start By Parth 24-06-2014
                        if (count($presult) > 0) {
                            foreach ($presult as $key => $value1) {
                                if ($value1['parent_projs'] <> " ") {
                                    echo "Parent Project:";
                                }
                            }
                        }
                        //End By Parth 24-06-2014
                        ?>
                    </td>
                    <td>
                        <?php
                        if (count($result) > 0) {
                            foreach ($result as $key => $value) {
                                if ($value['child_projs'] <> " ") {
                                    echo $value['child_projs'] . "<br>";
                                }
                            }
                        }
                        //Start By Parth 24-06-2014
                        if (count($presult) > 0) {
                            foreach ($presult as $key => $value1) {
                                if ($value1['parent_projs'] <> " ") {
                                    echo $value1['parent_projs'] . "<br>";
                                }
                            }
                        }
                        //End By Parth 24-06-2014
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">
                        <label for='client'><?php $clang->eT("Client : "); ?></label>
                    </td>
                    <td>
                        <?php
                        $company = Contact::model()->findAll(array('condition' => "contact_type_id = '1'", 'order' => 'company_name'));
                        $company_list = CHtml::listData($company, 'contact_id', 'company_name');
                        echo CHtml::dropDownList('client', $mrw['client_id'], $company_list, array(
                            'prompt' => 'Select Client',
                            'ajax' => array(
                                'type' => 'POST',
                                'data' => array('client_id' => 'js:this.value', 'selected_id' => '0'),
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
                        $client_contact = "SELECT * FROM {{view_contacts}} WHERE company_id= " . $mrw['client_id'] . " order by full_name ";
                        $result = Yii::app()->db->createCommand($client_contact)->query()->readAll();
                        $client_contact_list = CHtml::listData($result, 'contact_id', 'full_name');
                        echo CHtml::dropDownList('client_contact', $mrw['contact_id'], $client_contact_list);
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
                        //$pmquery = "SELECT * FROM {{view_contacts}} WHERE contact_title_id LIKE '%$Project_Manager%'";
                        $pmquery = "SELECT * FROM {{user_in_groups}} AS a INNER JOIN {{users}} AS b ON a.uid = b.uid WHERE ugid = " . $Project_Manager . " ORDER BY b.users_name";
                        $pmresult = Yii::app()->db->createCommand($pmquery)->query()->readAll(); //Checked
                        $projmanager_list = CHtml::listData($pmresult, 'uid', 'full_name');
                        echo CHtml::dropDownList('project_manager', $mrw['manager_user_id'], $projmanager_list, array('prompt' => 'Select Project Manager'));
                        ?>
                    </td>
                    <td style="text-align: right;
                        ">
                        <label for='sales_person'><?php $clang->eT("Sales Person : "); ?></label>
                    </td>
                    <td>
                        <?php
                        //$spquery = "SELECT * FROM {{view_contacts}} WHERE contact_title_id NOT LIKE '%$Project_Manager%'";
                        $spquery = "SELECT * FROM {{user_in_groups}} AS a INNER JOIN {{users}} AS b ON a.uid = b.uid 
                                    WHERE ugid != " . $Project_Manager . "  AND users_name != 'admin'
                                    ORDER BY b.users_name";
                        $spresult = Yii::app()->db->createCommand($spquery)->query()->readAll(); //Checked
                        $salesperson_list = CHtml::listData($spresult, 'uid', 'full_name');
                        echo CHtml::dropDownList('sales_person', $mrw['sales_user_id'], $salesperson_list, array('prompt' => 'Select Sales Person'));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;
                        ">
                        <label for='country'><?php $clang->eT("Country : "); ?></label>
                    </td>
                    <td>
                        <?php
                        $country = Country::model()->findAll(array('order' => 'country_name'));
                        $countrylist = CHtml::listData($country, 'country_id', 'country_name');
                        echo CHtml::dropDownList('country', $mrw['country_id'], $countrylist, array('prompt' => 'Select Country'));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;
                        ">
                        <label for='quota'><?php $clang->eT("Req. Completes : "); ?></label>
                    </td>
                    <td>
                        <input type='text' id='quota' name='quota' value="<?php echo $mrw['required_completes']; ?>" maxlength="6" onchange="fill_completes();" required="required"/><br/>(Must be between 1 to 99,999)
                    </td>
                    <td style="text-align: right;">
                        <label for='maxcompletes'><?php $clang->eT("Max. Completes : "); ?></label>
                    </td>
                    <td>
                        <select name="maxcompletes" id="maxcompletes">
                            <?php
                            echo fill_quota_buffer($mrw['QuotaBuffer_Completes'], $mrw['required_completes'])
                            ?>
                        </select>
                        <?php
                        //echo CHtml::dropDownList('maxcompletes', '', array(), array('prompt' => 'Select Max. Complets', 'required' => true));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">
                        <label for='cpc'><?php $clang->eT("CPC $ : "); ?></label>
                    </td>
                    <td>
                        <input type='text' id='cpc' maxlength="5" value="<?php echo $mrw['CPC']; ?>" name='cpc' value="-1" required="required"/><br/>(Must be between $0.1 to $1,000)
                    </td>
                    <td style="text-align: right;">
                        <label for='los'><?php $clang->eT("LOI : "); ?></label>
                    </td>
                    <td>
                        <input type='text' id='los' name='los' value="<?php echo $mrw['expected_los']; ?>" maxlength="5" value="0"/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">
                        <label for='ir'><?php $clang->eT("IR : "); ?></label>
                    </td>
                    <td>
                        <input type='text' id='ir' name='ir' value="<?php echo $mrw['IR']; ?>" maxlength="3"  value="1" required="required"/><br/> % (Must be between 1 to 100)
                    </td>
                    <td style="text-align: right;">
                        <label for='points'><?php $clang->eT("# of points to award :"); ?></label>
                    </td>
                    <td>
                        <input type='text' id='points' value="<?php echo $mrw['reward_points']; ?>" maxlength="11" name='points' required="required"/><br/> (The $ equivalent  100= $1)
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">
                        <label for='surveylink'><?php $clang->eT("Survey Link : "); ?></label>
                    </td>
                    <td>
                        <textarea cols='50' rows='2' id='surveylink' name='surveylink'><?php echo $mrw['client_link']; ?></textarea>
                    </td>
                    <td style="text-align: right;">
                        <label for='notes'><?php $clang->eT("Notes : "); ?></label>
                    </td>
                    <td>
                        <textarea cols='50' rows='2' id='notes' name='notes'><?php echo $mrw['notes']; ?></textarea>
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
                        echo CHtml::dropDownList('status', $mrw['project_status_id'], $project_status_list);
                        ?>
                        <input type="hidden" name="old_status" value="<?php echo $mrw['project_status_id']; ?>"/>
                    </td>
                </tr>
            </table>

            <p  style="padding-top: 1em;">
                <input type='submit' name ='submit' value='<?php $clang->eT("Save"); ?>' />
                <input type='submit' name ='submit' value='<?php $clang->eT("Clone"); ?>' />
                <input type='hidden' name='action' value='editproject' />
            </p>
        </div>
    <!--        <style>
            table.InfoForm {
                border: 0px solid #999;
                background: none repeat scroll 0% 0% #F5F5F5;
                border-radius: 4px;
                padding: 10px;
                border-collapse: inherit;
                border-spacing: 1px;
            }
            table.InfoForm tr.odd td {
                background: none repeat scroll 0% 0% #F5F5F5;
                padding: 5px;
            }
            table.InfoForm tr.even td {
                background: none repeat scroll 0% 0% #FFF;
                padding: 5px;
            }
        </style>-->
        <div style="width: 25%; float: left">
            <div style="border:0; padding: 10px;">
                <table class='InfoForm' width=100%>
                    <tbody>
                        <tr>
                            <td colspan=2 style="text-align: center">Panelist Statistics</td>
                        </tr>
                        <tr class=odd>
                            <td style="text-align: left">Redirects</td>
                            <td style="text-align: center"><?php echo $mrw['total_redirected']; ?></td>
                        </tr>
                        <tr class=even>
                            <td style="text-align: left">Completed</td>
                            <td style="text-align: center"><?php echo $mrw['total_completed']; ?></td>
                        </tr>
                        <tr class=even>
                            <td style="text-align: left">Disqualified</td>
                            <td style="text-align: center"><?php echo $mrw['total_disqualify']; ?></td>
                        </tr>
                        <tr class=even>
                            <td style="text-align: left">Quota Full</td>
                            <td style="text-align: center"><?php echo $mrw['total_quota_full']; ?></td>
                        </tr>
                        <?php
                        $cIR = (($mrw['total_completed'] + $mrw['total_disqualify'] + $mrw['total_quota_full']) > 0) ? number_format(($mrw['total_completed'] / ($mrw['total_completed'] + $mrw['total_disqualify'] + $mrw['total_quota_full'])) * 100, 2) : 0.00;
                        if ($cIR < ($mrw['IR'] * 0.9))
                            $trcolor = "style=color:red;";
                        else
                            $trcolor = "";
                        ?>
                        <tr  class=odd <?php echo $trcolor; ?>>
                            <td style="text-align: left">IR</td>
                            <td style="text-align: center"><?php echo $cIR; ?> %</td>
                        </tr>
                        <?php
                        $AvgLOI = ($mrw['total_completed'] > 0) ? number_format(($mrw['total_los'] / $mrw['total_completed']), 0) : 0;
                        if ($AvgLOI > $mrw['expected_los'] * 1.2)
                            $trcolor = "style=color:red;";
                        else
                            $trcolor = "";
                        ?>
                        <tr  class=odd <?php echo $trcolor; ?>>
                            <td style="text-align: left">Average LOI</td>
                            <td style="text-align: center"><?php echo $AvgLOI ?> mins</td>
                        </tr>
                        <?php
                        $redirect_completed = getGlobalSetting('redirect_status_completed');
                        $med = 0;
                        $sql = "SELECT COUNT(*) AS cnt FROM {{panellist_redirects}} WHERE redirect_status_id='$redirect_completed' 
                                AND project_id ='" . $mrw['project_id'] . "'
                                ORDER BY IFNULL(LOS,0)";
                        $result = Yii::app()->db->createCommand($sql)->queryRow();
                        $cnt = $result['cnt'];
                        if ($cnt > 0) {
                            $cntt = ceil(($cnt) / 2);
                            if ($cnt % 2 == 0) {
                                $test = $cntt;
                            } else {
                                $test = $cntt - 1;
                            }
                        } else {
                            $test = 1;
                        }

                        $q = "SELECT IFNULL(LOS,0) AS val FROM {{panellist_redirects}}
                                WHERE redirect_status_id='$redirect_completed' AND project_id ='" . $mrw['project_id'] . "'
                                ORDER BY IFNULL(LOS,0)";
                        $q .= " LIMIT " . ($test) . ",1";
                        //$q = "(select LOS as val from {{panellist_redirects}} where redirect_status_id='$redirect_completed' and project_id ='" . $mrw['project_id'] . "')";
                        //$mq = "SELECT x.val as med from " . $q . " as x, " . $q . " as y GROUP BY x.val HAVING SUM(SIGN(1-SIGN(y.val-x.val))) = (COUNT(*)+1)/2";
                        $result = Yii::app()->db->createCommand($q)->queryRow();
                        $med = number_format($result['val'], 0);
                        if ($med > $mrw['expected_los'] * 1.2)
                            $trcolor = "style=color:red;";
                        else
                            $trcolor = "";
                        ?>
                        <tr  class=odd <?php echo $trcolor; ?>>
                            <td style="text-align: left">Median LOI</td>
                            <td style="text-align: center"><?php echo $med; ?> mins</td>
                        </tr>
                        <?Php
                        $Abandons = ($mrw['total_redirected'] > 0) ? number_format((($mrw['total_redirected'] - $mrw['total_completed'] - $mrw['total_disqualify'] - $mrw['total_quota_full']) / $mrw['total_redirected']) * 100, 2) : 0.00;
                        if ($Abandons > 25)
                            $trcolor = "style=color:red;";
                        else
                            $trcolor = "";
                        ?>
                        <tr  class=odd <?php echo $trcolor; ?>>
                            <td style="text-align: left">Abandons</td>
                            <td style="text-align: center"><?php echo $Abandons; ?> %</td>
                        </tr>
                        <tr  class=even>                     

                            <td style="text-align: left;">Blocked</td>
                            <td style="text-align: center">
                                <?php
                                echo "<div id='your-form-block-id'>";
                                echo CHtml::beginForm();
                                echo CHtml::link($mrw['total_errors'], array('admin/project/sa/showids/prjid/' . $mrw['project_id'] . '/type/blocked/name/' . $mrw['project_name']), array('class' => 'class-link'));
                                echo CHtml::endForm();
                                echo "</div>";
                                ?> 
                            </td>
                        </tr>
                        <?php
                        if ($mrw['cleanedup'] == '') {
                            $cleanedup = '<input type="checkbox" name="cleanedup" value="" checked />';
                        } else {
                            $cleanedup = $mrw['cleanedup'];
                        }

                        if ($mrw['closed'] == '') {
                            $closed = '-';
                        } else {
                            $closed = $mrw['closed'];
                        }

                        if ($mrw['trueup'] == '') {
                            $trueup = '-';
                        } else {
                            $trueup = $mrw['trueup'];
                        }
                        ?>
                        <tr  class=odd style="display:none;">
                            <td style="text-align: left">Clean Up</td>
                            <td style="text-align: center"><?php echo $cleanedup; ?></td>
                        </tr>
                        <tr  class=odd>
                            <td style="text-align: left">Trued UP</td>
                            <td style="text-align: center"><?php echo $trueup; ?></td>
                        </tr>
                        <tr  class=odd>
                            <td style="text-align: left">Closed Date</td>
                            <td style="text-align: center"><?php echo $closed; ?></td>
                        </tr>
                        <tr class=even>
                            <td style="text-align: center">
                                <?php
                                echo "<div id='your-form-block-id'>";
                                echo CHtml::beginForm();
                                echo CHtml::link('Redirects', array('admin/project/sa/showids/prjid/' . $mrw['project_id'] . '/type/redirects/name/' . $mrw['project_name']), array('class' => 'class-link'));
                                echo CHtml::endForm();
                                echo "</div>";
                                ?> </td>
                            <td style="text-align: center">
                                <?php
                                echo "<div id='your-form-block-id'>";
                                echo CHtml::beginForm();
                                echo CHtml::link('Link Variables', array('admin/project/sa/showids/prjid/' . $mrw['project_id'] . '/type/variables/name/' . $mrw['project_name']), array('class' => 'class-link'));
                                echo CHtml::endForm();
                                echo "</div>";
                                ?> </td>
                        </tr>
                    </tbody>
                </table>
            </div>            
        </div>
    </div>
    <?php
}
?>
</form>
<br/>
<br/>

<span style="float: left;height:40px;width:100%">
    <?php echo CHtml::form(array('admin/project/index', 'action' => 'addvendor'), 'post'); ?>
    <input type='submit' value='<?php $clang->eT("Add Vendor"); ?>'/>
    <input type='hidden' name="project_id" value='<?php echo $project_id; ?>' />
</form>
</span>

<br />
<script>
    $(document).ready(function() {
        $('#listprojectvendor').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>

<table id="listprojectvendor" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
<!--            <th><?php $clang->eT("Delete"); ?></th>-->
            <th><?php $clang->eT("ID"); ?></th>
            <th><?php $clang->eT("Panel"); ?></th>
            <th><?php $clang->eT("Status"); ?></th>
            <th><?php $clang->eT("Redirects"); ?></th>
            <th><?php $clang->eT("Completed"); ?></th>
            <th><?php $clang->eT("Disqualified"); ?></th>
            <th><?php $clang->eT("QF"); ?></th>
            <th><?php $clang->eT("IR"); ?></th>
            <th><?php $clang->eT("CPC"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($vendor_arr); $i++) {
            $usr = $vendor_arr[$i];
            ?>
            <tr>

                <td style="padding:3px;">
                    <?php echo CHtml::form(array('admin/project/sa/modifyproject/project_id/' . $usr['project_id'] . '/action/modifyvendor/vid/' . $usr['vendor_project_id']), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this vendor"); ?>' />
                    <input type='hidden' name='action' value='modifyvendor' />
                    <input type='hidden' name='vendor_project_id' value='<?php echo $usr['vendor_project_id']; ?>' />
                    <input type='hidden' name='project_id' value='<?php echo $usr['project_id']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['vendor_project_id']; ?></td>
                <td><?php echo $usr['company_name']; ?></td>
                <td><?php echo $usr['status_name']; ?></td>
                <td>       
                    <?php
                    echo "<div id='your-form-block-id'>";
                    echo CHtml::beginForm();
                    echo CHtml::link($usr['total_redirected'] . '/' . $usr['max_redirects'], array('admin/project/sa/showids/vid/' . $usr['vendor_project_id'] . '/type/0/name/' . $usr['company_name']), array('class' => 'class-link'));
                    echo CHtml::endForm();
                    echo "</div>";
                    ?>                
                </td>
                <td>
                    <?php
                    echo "<div id='your-form-block-id'>";
                    echo CHtml::beginForm();
                    echo CHtml::link($usr['total_completed'] . '/' . $usr['required_completes'], array('admin/project/sa/showids/vid/' . $usr['vendor_project_id'] . '/type/' . getGlobalSetting('redirect_status_completed') . '/name/' . $usr['company_name']), array('class' => 'class-link'));
                    echo CHtml::endForm();
                    echo "</div>";
                    ?> 
                </td>

                <td>
                    <?php
                    echo "<div id='your-form-block-id'>";
                    echo CHtml::beginForm();
                    echo CHtml::link($usr['total_disqualified'], array('admin/project/sa/showids/vid/' . $usr['vendor_project_id'] . '/type/' . getGlobalSetting('redirect_status_disqual') . '/name/' . $usr['company_name']), array('class' => 'class-link'));
                    echo CHtml::endForm();
                    echo "</div>";
                    ?> 
                </td>
                <td>
                    <?php
                    echo "<div id='your-form-block-id'>";
                    echo CHtml::beginForm();
                    echo CHtml::link($usr['total_quota_full'], array('admin/project/sa/showids/vid/' . $usr['vendor_project_id'] . '/type/' . getGlobalSetting('redirect_status_qf') . '/name/' . $usr['company_name']), array('class' => 'class-link'));
                    echo CHtml::endForm();
                    echo "</div>";
                    ?> 
                </td>
                <?php
                $vIR = (($usr['total_completed'] + $usr['total_disqualified'] + $usr['total_quota_full']) > 0) ?
                        number_format(($usr['total_completed'] /
                                ($usr['total_completed'] + $usr['total_disqualified'] + $usr['total_quota_full'])) * 100, 2) : 0.0;
                $ir = projectview($usr['project_id']);

                if ($vIR < ($ir[0]['IR'] * 0.9)) {
                    $tdcolor = "style=color:red;";
                } else {
                    $tdcolor = "";
                }
                ?>
                <td <?php echo $tdcolor; ?>><?php echo $vIR; ?>%</td>
                <td>
                    <?php if ($usr['vendor_id'] != getGlobalSetting('Own_Panel'))
                        echo $usr['CPC']; ?></td>

            </tr>
            <?php
            $row++;
        }
        ?>
    </tbody>
</table>
<br />
<table width="100%" border="0" class="InfoForm">
    <tr>
        <td colspan="2" class="ListHeader"><b>Download Project IDs</b></td>
    </tr>
    <tr class="odd">
        <?php
        echo "<div id='your-form-block-id'>";
        echo CHtml::beginForm();
        ?>
        <td>
            <input style="vertical-align: sub;" type="checkbox" name="askExt" id ="askExt" value="1"/>Extension&nbsp;
            <input type="checkbox" style="vertical-align: sub;" name="askPrescreener" id ="askPrescreener"  value="1"/>Prescreener Information&nbsp;
            <input type="checkbox" style="vertical-align: sub;" name="askLOI" id ="askLOI"  value="1"/>LOI
            <input type="checkbox" style="vertical-align: sub;" name="askReferrer" id ="askReferrer"  value="1"/>Referrer&nbsp;
        </td>
        <td align="right" width="50">

            <a href="<?php echo CController::createUrl('admin/project/sa/allids/prjid/' . $project_id) ?>" class="class-link" >
                <input type="button" value="download"/>
            </a>
            <?php
//            echo CHtml::link(Download, array('admin/project/sa/allids/prjid/' . $project_id), array('class' => 'class-link'));
            echo CHtml::endForm();
            echo "</div>";
            ?>

        </td>
        </form>

    </tr>
</table>
<br />
<script language="JavaScript">
        !function(a){
        "use strict";
        "function"==typeof define&&define.amd?define(["jquery"],a):a("undefined"!=typeof jQuery?jQuery:window.Zepto)
    }(function(a){
        "use strict";
        function b(b){
            var c=b.data;
            b.isDefaultPrevented()||(b.preventDefault(),a(b.target).ajaxSubmit(c))
        }
        function c(b){
            var c=b.target,d=a(c);
            if(!d.is("[type=submit],[type=image]")){
                var e=d.closest("[type=submit]");
                if(0===e.length)return;
                c=e[0]
            }
            var f=this;
            if(f.clk=c,"image"==c.type)if(void 0!==b.offsetX)f.clk_x=b.offsetX,f.clk_y=b.offsetY;
            else if("function"==typeof a.fn.offset){
                var g=d.offset();
                f.clk_x=b.pageX-g.left,f.clk_y=b.pageY-g.top
            }else f.clk_x=b.pageX-c.offsetLeft,f.clk_y=b.pageY-c.offsetTop;
            setTimeout(function(){
                f.clk=f.clk_x=f.clk_y=null
            },100)
        }
        function d(){
            if(a.fn.ajaxSubmit.debug){
                var b="[jquery.form] "+Array.prototype.join.call(arguments,"");
                window.console&&window.console.log?window.console.log(b):window.opera&&window.opera.postError&&window.opera.postError(b)
            }
        }
        var e={};

        e.fileapi=void 0!==a("<input type='file'/>").get(0).files,e.formdata=void 0!==window.FormData;
        var f=!!a.fn.prop;
        a.fn.attr2=function(){
            if(!f)return this.attr.apply(this,arguments);
            var a=this.prop.apply(this,arguments);
            return a&&a.jquery||"string"==typeof a?a:this.attr.apply(this,arguments)
        },a.fn.ajaxSubmit=function(b){
            function c(c){
                var d,e,f=a.param(c,b.traditional).split("&"),g=f.length,h=[];
                for(d=0;g>d;d++)f[d]=f[d].replace(/\+/g," "),e=f[d].split("="),h.push([decodeURIComponent(e[0]),decodeURIComponent(e[1])]);
                return h
            }
            function g(d){
                for(var e=new FormData,f=0;f<d.length;f++)e.append(d[f].name,d[f].value);
                if(b.extraData){
                    var g=c(b.extraData);
                    for(f=0;f<g.length;f++)g[f]&&e.append(g[f][0],g[f][1])
                }
                b.data=null;
                var h=a.extend(!0,{},a.ajaxSettings,b,{
                    contentType:!1,
                    processData:!1,
                    cache:!1,
                    type:i||"POST"
                });
                b.uploadProgress&&(h.xhr=function(){
                    var c=a.ajaxSettings.xhr();
                    return c.upload&&c.upload.addEventListener("progress",function(a){
                        var c=0,d=a.loaded||a.position,e=a.total;
                        a.lengthComputable&&(c=Math.ceil(d/e*100)),b.uploadProgress(a,d,e,c)
                    },!1),c
                }),h.data=null;
                var j=h.beforeSend;
                return h.beforeSend=function(a,c){
                    c.data=b.formData?b.formData:e,j&&j.call(this,a,c)
                },a.ajax(h)
            }
            function h(c){
                function e(a){
                    var b=null;
                    try{
                        a.contentWindow&&(b=a.contentWindow.document)
                    }catch(c){
                        d("cannot get iframe.contentWindow document: "+c)
                    }
                    if(b)return b;
                    try{
                        b=a.contentDocument?a.contentDocument:a.document
                    }catch(c){
                        d("cannot get iframe.contentDocument: "+c),b=a.document
                    }
                    return b
                }
                function g(){
                    function b(){
                        try{
                            var a=e(r).readyState;
                            d("state = "+a),a&&"uninitialized"==a.toLowerCase()&&setTimeout(b,50)
                        }catch(c){
                            d("Server abort: ",c," (",c.name,")"),h(A),w&&clearTimeout(w),w=void 0
                        }
                    }
                    var c=l.attr2("target"),f=l.attr2("action"),g="multipart/form-data",j=l.attr("enctype")||l.attr("encoding")||g;
                    x.setAttribute("target",o),(!i||/post/i.test(i))&&x.setAttribute("method","POST"),f!=m.url&&x.setAttribute("action",m.url),m.skipEncodingOverride||i&&!/post/i.test(i)||l.attr({
                        encoding:"multipart/form-data",
                        enctype:"multipart/form-data"
                    }),m.timeout&&(w=setTimeout(function(){
                        v=!0,h(z)
                    },m.timeout));
                    var k=[];
                    try{
                        if(m.extraData)for(var n in m.extraData)m.extraData.hasOwnProperty(n)&&(a.isPlainObject(m.extraData[n])&&m.extraData[n].hasOwnProperty("name")&&m.extraData[n].hasOwnProperty("value")?k.push(a('<input type="hidden" name="'+m.extraData[n].name+'">').val(m.extraData[n].value).appendTo(x)[0]):k.push(a('<input type="hidden" name="'+n+'">').val(m.extraData[n]).appendTo(x)[0]));m.iframeTarget||q.appendTo("body"),r.attachEvent?r.attachEvent("onload",h):r.addEventListener("load",h,!1),setTimeout(b,15);
                        try{
                            x.submit()
                        }catch(p){
                            var s=document.createElement("form").submit;
                            s.apply(x)
                        }
                    }finally{
                        x.setAttribute("action",f),x.setAttribute("enctype",j),c?x.setAttribute("target",c):l.removeAttr("target"),a(k).remove()
                    }
                }
                function h(b){
                    if(!s.aborted&&!F){
                        if(E=e(r),E||(d("cannot access response document"),b=A),b===z&&s)return s.abort("timeout"),y.reject(s,"timeout"),void 0;
                        if(b==A&&s)return s.abort("server abort"),y.reject(s,"error","server abort"),void 0;
                        if(E&&E.location.href!=m.iframeSrc||v){
                            r.detachEvent?r.detachEvent("onload",h):r.removeEventListener("load",h,!1);
                            var c,f="success";
                            try{
                                if(v)throw"timeout";
                                var g="xml"==m.dataType||E.XMLDocument||a.isXMLDoc(E);
                                if(d("isXml="+g),!g&&window.opera&&(null===E.body||!E.body.innerHTML)&&--G)return d("requeing onLoad callback, DOM not available"),setTimeout(h,250),void 0;
                                var i=E.body?E.body:E.documentElement;
                                s.responseText=i?i.innerHTML:null,s.responseXML=E.XMLDocument?E.XMLDocument:E,g&&(m.dataType="xml"),s.getResponseHeader=function(a){
                                    var b={
                                        "content-type":m.dataType
                                    };
                            
                                    return b[a.toLowerCase()]
                                },i&&(s.status=Number(i.getAttribute("status"))||s.status,s.statusText=i.getAttribute("statusText")||s.statusText);
                                var j=(m.dataType||"").toLowerCase(),k=/(json|script|text)/.test(j);
                                if(k||m.textarea){
                                    var l=E.getElementsByTagName("textarea")[0];
                                    if(l)s.responseText=l.value,s.status=Number(l.getAttribute("status"))||s.status,s.statusText=l.getAttribute("statusText")||s.statusText;
                                    else if(k){
                                        var o=E.getElementsByTagName("pre")[0],p=E.getElementsByTagName("body")[0];
                                        o?s.responseText=o.textContent?o.textContent:o.innerText:p&&(s.responseText=p.textContent?p.textContent:p.innerText)
                                    }
                                }else"xml"==j&&!s.responseXML&&s.responseText&&(s.responseXML=H(s.responseText));
                                try{
                                    D=J(s,j,m)
                                }catch(t){
                                    f="parsererror",s.error=c=t||f
                                }
                            }catch(t){
                                d("error caught: ",t),f="error",s.error=c=t||f
                            }
                            s.aborted&&(d("upload aborted"),f=null),s.status&&(f=s.status>=200&&s.status<300||304===s.status?"success":"error"),"success"===f?(m.success&&m.success.call(m.context,D,"success",s),y.resolve(s.responseText,"success",s),n&&a.event.trigger("ajaxSuccess",[s,m])):f&&(void 0===c&&(c=s.statusText),m.error&&m.error.call(m.context,s,f,c),y.reject(s,"error",c),n&&a.event.trigger("ajaxError",[s,m,c])),n&&a.event.trigger("ajaxComplete",[s,m]),n&&!--a.active&&a.event.trigger("ajaxStop"),m.complete&&m.complete.call(m.context,s,f),F=!0,m.timeout&&clearTimeout(w),setTimeout(function(){
                                m.iframeTarget?q.attr("src",m.iframeSrc):q.remove(),s.responseXML=null
                            },100)
                        }
                    }
                }
                var j,k,m,n,o,q,r,s,t,u,v,w,x=l[0],y=a.Deferred();
                if(y.abort=function(a){
                    s.abort(a)
                },c)for(k=0;k<p.length;k++)j=a(p[k]),f?j.prop("disabled",!1):j.removeAttr("disabled");
                if(m=a.extend(!0,{},a.ajaxSettings,b),m.context=m.context||m,o="jqFormIO"+(new Date).getTime(),m.iframeTarget?(q=a(m.iframeTarget),u=q.attr2("name"),u?o=u:q.attr2("name",o)):(q=a('<iframe name="'+o+'" src="'+m.iframeSrc+'" />'),q.css({
                    position:"absolute",
                    top:"-1000px",
                    left:"-1000px"
                })),r=q[0],s={
                    aborted:0,
                    responseText:null,
                    responseXML:null,
                    status:0,
                    statusText:"n/a",
                    getAllResponseHeaders:function(){},
                    getResponseHeader:function(){},
                    setRequestHeader:function(){},
                    abort:function(b){
                        var c="timeout"===b?"timeout":"aborted";
                        d("aborting upload... "+c),this.aborted=1;
                        try{
                            r.contentWindow.document.execCommand&&r.contentWindow.document.execCommand("Stop")
                        }catch(e){}
                        q.attr("src",m.iframeSrc),s.error=c,m.error&&m.error.call(m.context,s,c,b),n&&a.event.trigger("ajaxError",[s,m,c]),m.complete&&m.complete.call(m.context,s,c)
                    }
                },n=m.global,n&&0===a.active++&&a.event.trigger("ajaxStart"),n&&a.event.trigger("ajaxSend",[s,m]),m.beforeSend&&m.beforeSend.call(m.context,s,m)===!1)return m.global&&a.active--,y.reject(),y;
                if(s.aborted)return y.reject(),y;
                t=x.clk,t&&(u=t.name,u&&!t.disabled&&(m.extraData=m.extraData||{},m.extraData[u]=t.value,"image"==t.type&&(m.extraData[u+".x"]=x.clk_x,m.extraData[u+".y"]=x.clk_y)));
                var z=1,A=2,B=a("meta[name=csrf-token]").attr("content"),C=a("meta[name=csrf-param]").attr("content");
                C&&B&&(m.extraData=m.extraData||{},m.extraData[C]=B),m.forceSync?g():setTimeout(g,10);
                var D,E,F,G=50,H=a.parseXML||function(a,b){
                    return window.ActiveXObject?(b=new ActiveXObject("Microsoft.XMLDOM"),b.async="false",b.loadXML(a)):b=(new DOMParser).parseFromString(a,"text/xml"),b&&b.documentElement&&"parsererror"!=b.documentElement.nodeName?b:null
                },I=a.parseJSON||function(a){
                    return window.eval("("+a+")")
                },J=function(b,c,d){
                    var e=b.getResponseHeader("content-type")||"",f="xml"===c||!c&&e.indexOf("xml")>=0,g=f?b.responseXML:b.responseText;
                    return f&&"parsererror"===g.documentElement.nodeName&&a.error&&a.error("parsererror"),d&&d.dataFilter&&(g=d.dataFilter(g,c)),"string"==typeof g&&("json"===c||!c&&e.indexOf("json")>=0?g=I(g):("script"===c||!c&&e.indexOf("javascript")>=0)&&a.globalEval(g)),g
                };
        
                return y
            }
            if(!this.length)return d("ajaxSubmit: skipping submit process - no element selected"),this;
            var i,j,k,l=this;
            "function"==typeof b?b={
                success:b
            }:void 0===b&&(b={}),i=b.type||this.attr2("method"),j=b.url||this.attr2("action"),k="string"==typeof j?a.trim(j):"",k=k||window.location.href||"",k&&(k=(k.match(/^([^#]+)/)||[])[1]),b=a.extend(!0,{
                url:k,
                success:a.ajaxSettings.success,
                type:i||a.ajaxSettings.type,
                iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"
            },b);
            var m={};

            if(this.trigger("form-pre-serialize",[this,b,m]),m.veto)return d("ajaxSubmit: submit vetoed via form-pre-serialize trigger"),this;
            if(b.beforeSerialize&&b.beforeSerialize(this,b)===!1)return d("ajaxSubmit: submit aborted via beforeSerialize callback"),this;
            var n=b.traditional;
            void 0===n&&(n=a.ajaxSettings.traditional);
            var o,p=[],q=this.formToArray(b.semantic,p);
            if(b.data&&(b.extraData=b.data,o=a.param(b.data,n)),b.beforeSubmit&&b.beforeSubmit(q,this,b)===!1)return d("ajaxSubmit: submit aborted via beforeSubmit callback"),this;
            if(this.trigger("form-submit-validate",[q,this,b,m]),m.veto)return d("ajaxSubmit: submit vetoed via form-submit-validate trigger"),this;
            var r=a.param(q,n);
            o&&(r=r?r+"&"+o:o),"GET"==b.type.toUpperCase()?(b.url+=(b.url.indexOf("?")>=0?"&":"?")+r,b.data=null):b.data=r;
            var s=[];
            if(b.resetForm&&s.push(function(){
                l.resetForm()
            }),b.clearForm&&s.push(function(){
                l.clearForm(b.includeHidden)
            }),!b.dataType&&b.target){
                var t=b.success||function(){};
        
                s.push(function(c){
                    var d=b.replaceTarget?"replaceWith":"html";
                    a(b.target)[d](c).each(t,arguments)
                })
            }else b.success&&s.push(b.success);
            if(b.success=function(a,c,d){
                for(var e=b.context||this,f=0,g=s.length;g>f;f++)s[f].apply(e,[a,c,d||l,l])
            },b.error){
                var u=b.error;
                b.error=function(a,c,d){
                    var e=b.context||this;
                    u.apply(e,[a,c,d,l])
                }
            }
            if(b.complete){
                var v=b.complete;
                b.complete=function(a,c){
                    var d=b.context||this;
                    v.apply(d,[a,c,l])
                }
            }
            var w=a("input[type=file]:enabled",this).filter(function(){
                return""!==a(this).val()
            }),x=w.length>0,y="multipart/form-data",z=l.attr("enctype")==y||l.attr("encoding")==y,A=e.fileapi&&e.formdata;
            d("fileAPI :"+A);
            var B,C=(x||z)&&!A;
            b.iframe!==!1&&(b.iframe||C)?b.closeKeepAlive?a.get(b.closeKeepAlive,function(){
                B=h(q)
            }):B=h(q):B=(x||z)&&A?g(q):a.ajax(b),l.removeData("jqxhr").data("jqxhr",B);
            for(var D=0;D<p.length;D++)p[D]=null;
            return this.trigger("form-submit-notify",[this,b]),this
        },a.fn.ajaxForm=function(e){
            if(e=e||{},e.delegation=e.delegation&&a.isFunction(a.fn.on),!e.delegation&&0===this.length){
                var f={
                    s:this.selector,
                    c:this.context
                };
                
                return!a.isReady&&f.s?(d("DOM not ready, queuing ajaxForm"),a(function(){
                    a(f.s,f.c).ajaxForm(e)
                }),this):(d("terminating; zero elements found by selector"+(a.isReady?"":" (DOM not ready)")),this)
            }
            return e.delegation?(a(document).off("submit.form-plugin",this.selector,b).off("click.form-plugin",this.selector,c).on("submit.form-plugin",this.selector,e,b).on("click.form-plugin",this.selector,e,c),this):this.ajaxFormUnbind().bind("submit.form-plugin",e,b).bind("click.form-plugin",e,c)
        },a.fn.ajaxFormUnbind=function(){
            return this.unbind("submit.form-plugin click.form-plugin")
        },a.fn.formToArray=function(b,c){
            var d=[];
            if(0===this.length)return d;
            var f,g=this[0],h=this.attr("id"),i=b?g.getElementsByTagName("*"):g.elements;
            if(i&&(i=a(i).get()),h&&(f=a(":input[form="+h+"]").get(),f.length&&(i=(i||[]).concat(f))),!i||!i.length)return d;
            var j,k,l,m,n,o,p;
            for(j=0,o=i.length;o>j;j++)if(n=i[j],l=n.name,l&&!n.disabled)if(b&&g.clk&&"image"==n.type)g.clk==n&&(d.push({
                name:l,
                value:a(n).val(),
                type:n.type
            }),d.push({
                name:l+".x",
                value:g.clk_x
            },{
                name:l+".y",
                value:g.clk_y
            }));
            else if(m=a.fieldValue(n,!0),m&&m.constructor==Array)for(c&&c.push(n),k=0,p=m.length;p>k;k++)d.push({
                name:l,
                value:m[k]
            });
            else if(e.fileapi&&"file"==n.type){
                c&&c.push(n);
                var q=n.files;
                if(q.length)for(k=0;k<q.length;k++)d.push({
                    name:l,
                    value:q[k],
                    type:n.type
                });else d.push({
                    name:l,
                    value:"",
                    type:n.type
                })
            }else null!==m&&"undefined"!=typeof m&&(c&&c.push(n),d.push({
                name:l,
                value:m,
                type:n.type,
                required:n.required
            }));if(!b&&g.clk){
                var r=a(g.clk),s=r[0];
                l=s.name,l&&!s.disabled&&"image"==s.type&&(d.push({
                    name:l,
                    value:r.val()
                }),d.push({
                    name:l+".x",
                    value:g.clk_x
                },{
                    name:l+".y",
                    value:g.clk_y
                }))
            }
            return d
        },a.fn.formSerialize=function(b){
            return a.param(this.formToArray(b))
        },a.fn.fieldSerialize=function(b){
            var c=[];
            return this.each(function(){
                var d=this.name;
                if(d){
                    var e=a.fieldValue(this,b);
                    if(e&&e.constructor==Array)for(var f=0,g=e.length;g>f;f++)c.push({
                        name:d,
                        value:e[f]
                    });else null!==e&&"undefined"!=typeof e&&c.push({
                        name:this.name,
                        value:e
                    })
                }
            }),a.param(c)
        },a.fn.fieldValue=function(b){
            for(var c=[],d=0,e=this.length;e>d;d++){
                var f=this[d],g=a.fieldValue(f,b);
                null===g||"undefined"==typeof g||g.constructor==Array&&!g.length||(g.constructor==Array?a.merge(c,g):c.push(g))
            }
            return c
        },a.fieldValue=function(b,c){
            var d=b.name,e=b.type,f=b.tagName.toLowerCase();
            if(void 0===c&&(c=!0),c&&(!d||b.disabled||"reset"==e||"button"==e||("checkbox"==e||"radio"==e)&&!b.checked||("submit"==e||"image"==e)&&b.form&&b.form.clk!=b||"select"==f&&-1==b.selectedIndex))return null;
            if("select"==f){
                var g=b.selectedIndex;
                if(0>g)return null;
                for(var h=[],i=b.options,j="select-one"==e,k=j?g+1:i.length,l=j?g:0;k>l;l++){
                    var m=i[l];
                    if(m.selected){
                        var n=m.value;
                        if(n||(n=m.attributes&&m.attributes.value&&!m.attributes.value.specified?m.text:m.value),j)return n;
                        h.push(n)
                    }
                }
                return h
            }
            return a(b).val()
        },a.fn.clearForm=function(b){
            return this.each(function(){
                a("input,select,textarea",this).clearFields(b)
            })
        },a.fn.clearFields=a.fn.clearInputs=function(b){
            var c=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;
            return this.each(function(){
                var d=this.type,e=this.tagName.toLowerCase();
                c.test(d)||"textarea"==e?this.value="":"checkbox"==d||"radio"==d?this.checked=!1:"select"==e?this.selectedIndex=-1:"file"==d?/MSIE/.test(navigator.userAgent)?a(this).replaceWith(a(this).clone(!0)):a(this).val(""):b&&(b===!0&&/hidden/.test(d)||"string"==typeof b&&a(this).is(b))&&(this.value="")
            })
        },a.fn.resetForm=function(){
            return this.each(function(){
                ("function"==typeof this.reset||"object"==typeof this.reset&&!this.reset.nodeType)&&this.reset()
            })
        },a.fn.enable=function(a){
            return void 0===a&&(a=!0),this.each(function(){
                this.disabled=!a
            })
        },a.fn.selected=function(b){
            return void 0===b&&(b=!0),this.each(function(){
                var c=this.type;
                if("checkbox"==c||"radio"==c)this.checked=b;
                else if("option"==this.tagName.toLowerCase()){
                    var d=a(this).parent("select");
                    b&&d[0]&&"select-one"==d[0].type&&d.find("option").selected(!1),this.selected=b
                }
            })
        },a.fn.ajaxSubmit.debug=!1
    });
        
    //  End -->
</script>

<script type="text/javascript">
    $(document).ready(function() { 
        var options = { 
            target:   '#output',   // target element(s) to be updated with server response 
            beforeSubmit:  beforeSubmit,  // pre-submit callback 
            success:       afterSuccess,  // post-submit callback 
            uploadProgress: OnProgress, //upload progress callback 
            resetForm: true        // reset the form after successful submit 
        }; 
    		
        $( "#codefile" ).change(function() {
            //alert( "Handler for .change() called." );
            $('#translateform').ajaxSubmit(options);  			
            // always return false to prevent standard browser submit and page navigation 
            return false;
        });
        //                $('#MyUploadForm').submit(function() { 
        //                    $(this).ajaxSubmit(options);  			
        //                    // always return false to prevent standard browser submit and page navigation 
        //                    return false;
        //                
        //                }); 
        //		

        //function after succesful file upload (when server response)
        function afterSuccess()
        {
            $('#submit-btn').show(); //hide submit button
            $('#loading-img').hide(); //hide submit button
            $('#progressbox').delay( 1000 ).fadeOut(); //hide progress bar

        }

        //function to check file size before uploading.
        function beforeSubmit(){
            //check whether browser fully supports all File API
            if (window.File && window.FileReader && window.FileList && window.Blob)
            {
    		
                if( !$('#codefile').val()) //check empty input filed
                {
                    $("#output").html("Please select file");
                    return false
                }
    		
                var fsize = $('#codefile')[0].files[0].size; //get file size
                var ftype = $('#codefile')[0].files[0].type; // get file type
    		
                //alert(ftype)
                //allow file types 
                switch(ftype){
                    /* case 'image/png': 
                            case 'image/gif': 
                            case 'image/jpeg': 
                            case 'image/pjpeg':
                            case 'text/plain':
                            case 'text/html':
                            case 'application/x-zip-compressed':
                            case 'application/pdf':
                            case 'application/msword': */
                    case 'application/vnd.ms-excel':
                        //case 'video/mp4':
                        break;
                    default:
                        //$("#output").html("<b>"+ftype+"</b> Unsupported file type!");
                        $("#output").html("<b>Only CSV file allowed</b>");
                        return false
                }
    		
                //Allowed file size is less than 5 MB (1048576)
                if(fsize>5242880) 
                {
                    $("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big file! <br />File is too big, it should be less than 5 MB.");
                    return false
                }
    				
                $('#submit-btn').hide(); //hide submit button
                $('#loading-img').show(); //hide submit button
                $("#output").html("");  
            }
            else
            {
                //Output error to older unsupported browsers that doesn't support HTML5 File API
                $("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
                return false;
            }
        }

        //progress bar function
        function OnProgress(event, position, total, percentComplete)
        {
            //Progress bar
            $('#progressbox').show();
            $('#progressbar').width(percentComplete + '%') //update progressbar percent complete
            $('#statustxt').html(percentComplete + '%'); //update status text
            if(percentComplete > 0)
            {
                $('#statustxt').css('color','#000'); //change status text to white after 50%
            }
        }

        //function to format bites bit.ly/19yoIPO
        function bytesToSize(bytes) {
            var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes == 0) return '0 Bytes';
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        }

    }); 

</script>
<style>
    /* prograss bar */
    #progressbox {
        border: 1px solid #CAF2FF;
        padding: 1px; 
        position:relative;
        width:400px;
        border-radius: 3px;
        margin: 10px;
        display:none;
        text-align:left;
    }
    #progressbar {
        height:20px;
        border-radius: 3px;
        background-color: #CAF2FF;
        width:1%;
    }
    #statustxt {
        top:3px;
        left:50%;
        position:absolute;
        display:inline-block;
        color: #FFFFFF;
    }
</style>
<table width="100%" border="0" class="InfoForm">
    <tr>
        <td colspan="2" class="ListHeader"><b>Redirect IDs => Foreign IDs</b></td>
    </tr>
    <tr class="odd">
        <?php
        echo "<div id='your-form-block-id'>";
        echo CHtml::form(array("admin/project/sa/uploadfile"), 'post', array('id' => 'translateform', 'enctype' => 'multipart/form-data', 'enableClientValidation' => true));
//echo CHtml::beginForm('', 'post', array('id' => 'translateids', 'enctype' => 'multipart/form-data', 'enableClientValidation' => true));
        ?>
        <td> 
            File: <input name="codefile" id = "codefile" type="file" style="font:10px Verdana,sans-serif;" required=""/>
            <div id="progressbox" >
                <div id="progressbar"></div ><div id="statustxt">0%</div>
            </div>
            <div id="output"></div>
        </td>
        <td align="right" width="50">
            <?php
//echo Yii::app()->basePath;
            ?>
            <a href="<?php echo CController::createUrl('admin/project/sa/translateids/prjid/' . $project_id) ?>" class="class-link" id="csvupload">
                <input type="submit" name="submit" value="Translate"/>    
            </a>

        </td>
        <?php
        echo CHtml::endForm();
        echo "</div>";
        ?>
    </tr>
</table>


<!--
<br/>
<a name="graph"></a>
<table width="100%" border="0" class="InfoForm">
    <tr><td class="ListHeader">Statistics :
            <select name="VendorID" id="VendorID" onchange="ChangeGraph();"><option value="">Select Vendor</option></select>
            <input type="radio" name="interval" id = "interval" value="day" checked onchange="ChangeGraph();">Daily
            <input type="radio" name="interval" id = "interval" value="hour" onchange="ChangeGraph();">Hourly
        </td>
    </tr>
    <tr class="even">
        <td>
            <div id="graphdiv" style="width:800px; height:200px;"></div>
        </td>
    </tr>
</table>
<br />
<table width="100%" class="InfoForm" cellpadding="1" cellspacing="2" border="0">
    <tr>
        <td class="admin_inner" colspan="2"><b>History</b></td>
    </tr>
<?php
$query = 'SELECT * FROM {{messages}} WHERE chainid = ' . $project_id . ' AND type_id = 10 ORDER BY messages_id DESC';
$result = Yii::app()->db->createCommand($query)->query()->readAll();
//print_r($result);
$odd = false;
foreach ($result as $value) {
    ?>
                                                                                                                                                                                                                                                                                                                                            <tr class="<?php
    if ($odd)
        echo "odd"; else
        echo "even";
    ?>">
                                                                                                                                                                                                                                                                                                                                            <td width="170" style="padding:5px;"><?php echo $value['created']; ?></td>
                                                                                                                                                                                                                                                                                                                                            <td style="padding:5px;"><?php echo $value['body']; ?></td>
                                                                                                                                                                                                                                                                                                                                            </tr>
    <?php
    $odd = !$odd;
}
?>
</table>
<br /><br />-->