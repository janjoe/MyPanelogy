<div class='header ui-widget-header'><?php $clang->eT("Edit Vendor - For Project # [" . $_GET['project_id'] . "]"); ?></div>
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
    
    function validateURL(textval) {
        if(textval == ""){
            return false;
        }else{
            var urlregex = new RegExp("^(https:\/\/|http:\/\/|https:\/\/www.|https:\/\/www.){1}([0-9A-Za-z]+\.)");
            return urlregex.test(textval);
        }
    }
    
    function Validationeditvendor(){
        var Error = 0;
        var regdecimal = /^\s*((\d+(\.\d+)?)|(\.\d))\s*$/;
        var cpc = $("#cpc").val();
        var quota = $("#quota").val();
        var maxredirects = $("#maxredirects").val();
        var completionlink = $("#completionlink").val();
        var disqualifylink = $("#disqualifylink").val();
        var quatafulllink = $("#quatafulllink").val();
        
        if(regdecimal.test(cpc) == true){
            if(cpc < 1 || cpc > 99999){
                $("#cpc").addClass("error2");
                Error = 1;
            }else{
                $("#cpc").removeClass("error2");
            }
        }else{
            $("#cpc").addClass("error2");
            Error = 1;
        }
        if(regdecimal.test(quota) == true){
            if(quota < 1 || quota > 99999){
                $("#quota").addClass("error2");
                Error = 1;
            }else{
                $("#quota").removeClass("error2");
            }
        }else{
            $("#quota").addClass("error2");
            Error = 1;
        }
        if(regdecimal.test(maxredirects) == true){
            if(maxredirects < 0 ){
                $("#maxredirects").addClass("error2");
                Error = 1;
            }else{
                $("#maxredirects").removeClass("error2");
            }
        }else{
            $("#maxredirects").addClass("error2");
            Error = 1;
        }
        
        if(validateURL(completionlink) == false){
            $("#completionlink").addClass("error2");
            Error = 1;
        }else{
            $("#completionlink").removeClass("error2");
        }
        
        if(validateURL(disqualifylink) == false){
            $("#disqualifylink").addClass("error2");
            Error = 1;
        }else{
            $("#disqualifylink").removeClass("error2");
        }
        
        if(validateURL(quatafulllink) == false){
            $("#quatafulllink").addClass("error2");
            Error = 1;
        }else{
            $("#quatafulllink").removeClass("error2");
        }
        
        if(Error == 1){
            return false;
        }else{
            return true;
        }
        
    }
    
</script>
<script>
    $(document).ready(function() {
        //$('#vendorlist').dataTable();
        var CPC = $('#cpc');
        var prv_price = $('#prv_price');
        CPC.blur(getprice);
        CPC.keyup(getprice);
        function getprice(){
            var price_val = prv_price.val();
            var cpc_val = CPC.val();
            var project_price = prv_price.val() - CPC.val() ;
            $("#disprice").text("");
            cpc_val = parseInt(cpc_val);
            price_val = parseInt(price_val);
            if(cpc_val > price_val){
                $("#disprice").removeClass("trues");
                $("#disprice").addClass("error");
                $("#disprice").text("$"+project_price);
            } else {
                $("#disprice").removeClass("error");
                $("#disprice").addClass("trues");
                $("#disprice").text("$"+project_price);	
            }
        }
    } );
</script>
<style>
    .trues{
        color: #008000;
    }
</style>
<?php echo CHtml::form(array("admin/project/sa/modproject/action/editvendor"), 'post', array('id' => 'newprojectform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationeditvendor()')); ?>
<?php
//print_r($vendor_arr_single);
//foreach ($vendor_arr_single as $val) {
?>
<table style="width: 80%; margin: 0px auto;">
    <tr>
        <td style="text-align: right;">
            <label for='panel'><?php $clang->eT("Panel : "); ?></label>
        </td>
        <td>
            <?php
            $sql = "SELECT * FROM {{view_company}} WHERE company_type LIKE '%V%'";
            $result = Yii::app()->db->createCommand($sql)->query();
            $vendor_list = CHtml::listData($result, 'contact_id', 'company_name');
            echo CHtml::dropDownList('panel', $vendor_arr_single[0]['vendor_id'], $vendor_list, array(
                'prompt' => 'Select Vendor',
                'ajax' => array(
                    'type' => 'POST',
                    'data' => array('client_id' => 'js:this.value', 'vendor' => 'vendor'),
                    'url' => CController::createUrl('admin/project/sa/selectclientcontact'),
                    'update' => '#vendor_contact',
                ),
                'required' => true
            ));
            ?>
            <input type='hidden' id='project_id' name='project_id' value="<?php echo $project_id; ?>"/>
            <input type='hidden' id='vendor_project_id' name='vendor_project_id' value="<?php echo $vendor_project_id; ?>"/>
        </td>
        <td style="text-align: right;">
            <label for='vendor_contact'><?php $clang->eT("Vendor Contact : "); ?></label>
        </td>
        <td>
            <?php
            $sql = "SELECT * FROM {{view_contacts}} WHERE company_id =" . $vendor_arr_single[0]['vendor_id'] . "";
            $result = Yii::app()->db->createCommand($sql)->query();
            $vendor_contact_list = CHtml::listData($result, 'contact_id', 'full_name');
            echo CHtml::dropDownList('vendor_contact', $vendor_arr_single[0]['vendor_contact_id'], $vendor_contact_list, array('prompt' => 'Select Contact', 'required' => true));
            ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='cpc'><?php $clang->eT("Cost Per Complete : "); ?></label>
        </td>
        <td>
            <?php
            $sql = "SELECT * FROM {{view_project_master}} WHERE project_id = $project_id";
            $result = Yii::app()->db->createCommand($sql)->queryRow();
            extract($result);
            $price = $CPC - $vendor_arr_single[0]['CPC'];
            $price_dis = '';
            if ($price > 0) {
                $price_dis = '<span id="disprice" class="trues">$' . $price . '</span>';
            } else {
                $price_dis = '<span id="disprice" class="error2">$' . $price . '</span>';
            }
            ?>
            <input type="text" name="cpc" id="cpc" value="<?php echo $vendor_arr_single[0]['CPC']; ?>" required/>
            <input type="hidden" value="<?php echo $CPC; ?>" name="prv_price" id="prv_price"/> 
            <br/>
            Balance: <?php echo $price_dis; ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='quota'><?php $clang->eT("Req. Completes : "); ?></label>
        </td>
        <td>
            <input type='text' id='quota' name='quota' onchange="fill_completes();" value="<?php echo $required_completes; ?>"/>
        </td>
        <td style="text-align: right;">
            <label for='maxcompletes'><?php $clang->eT("Max. Completes : "); ?></label>
        </td>
        <td>
            <select name="maxcompletes" id="maxcompletes">
                <?php
                echo fill_quota_buffer($vendor_arr_single[0]['QuotaBuffer_Completes'], $required_completes)
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='maxredirects'><?php $clang->eT("Max. Redirects : "); ?></label>
        </td>
        <td>
            <input type='text' id='maxredirects' value="<?php echo $vendor_arr_single[0]['max_redirects'] ?>" name='maxredirects' value="0"/>
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right">
            <label for='completionlink'><?php $clang->eT("Completion link : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' id='completionlink' name='completionlink'><?php echo $vendor_arr_single[0]['completed_link'] ?></textarea>
        </td>
        <td align="right" style="text-align: right">
            <label for='disqualifylink'><?php $clang->eT("Disqualify link : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' id='disqualifylink' name='disqualifylink'><?php echo $vendor_arr_single[0]['disQualified_link'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right">
            <label for='quatafulllink'><?php $clang->eT("Quotafull link : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' id='quatafulllink' name='quatafulllink'><?php echo $vendor_arr_single[0]['QuotaFull_URL'] ?></textarea>
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
            echo CHtml::dropDownList('status', $vendor_arr_single[0]['vendor_status_id'], $project_status_list);
            ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='notes'><?php $clang->eT("Notes : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' id='notes' name='notes'><?php echo $vendor_arr_single[0]['notes'] ?></textarea>
        </td>
    </tr>
</table>
<table style="width: 80%; margin: 0 auto;">
    <tr>
        <td style="text-align: right; width: 19.6%">
            <label><?php $clang->eT("Panelist Link: "); ?></label>
        </td>

        <?php
        $pid = $project_id * 7;
        $vpid = $vendor_project_id * 7;
        $gid = $pid . "-" . $vpid;
        $gid = urlencode(base64_encode($gid));
        ?>

        <td>
            <?php echo Yii::app()->getBaseUrl(true) . '/capture.php?gid=' . $gid . '&pid={{PANELIST IDENTIFIER}}&ext={{PANEL MISC DATA}}'; ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label><?php $clang->eT("Data to ask user for on redirect : "); ?></label>
        </td>
        <td>
            <?php
            $askEmailvalue = (strpos($vendor_arr_single[0]['AskOnRedirect'], 'Email') === false) ? '' : 'CHECKED';
            $askZipvalue = (strpos($vendor_arr_single[0]['AskOnRedirect'], 'Zip') === false) ? '' : 'CHECKED';
            $askAgevalue = (strpos($vendor_arr_single[0]['AskOnRedirect'], 'Age') === false) ? '' : 'CHECKED';
            $askGendervalue = (strpos($vendor_arr_single[0]['AskOnRedirect'], 'Gender') === false) ? '' : 'CHECKED';
            ?>
            <span id="company_type">
                <input value="Yes" type="checkbox" id="askEmail" name="askEmail" <?php echo $askEmailvalue; ?>/> <label for="askEmail">Email Address</label><br>
                <input value="Yes" type="checkbox" id="askZip" name="askZip" <?php echo $askZipvalue; ?>/> <label for="askZip">Zip Code</label><br>
                <input value="Yes" type="checkbox" id="askAge" name="askAge" <?php echo $askAgevalue; ?>/> <label for="askAge">Age</label><br>
                <input value="Yes" type="checkbox" id="askGender" name="askGender" <?php echo $askGendervalue; ?>/> <label for="askGender">Gender</label>
            </span>
        </td>
    </tr>
</table>
<p  style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='editvendor' />
    &nbsp;&nbsp;
    <a href="<?php echo $this->createUrl('admin/project/sa/modifyproject/project_id/' . $project_id . '/action/modifyproject') ?>" class="limebutton">Cancel</a>
</p>
</form>
<?php
if (getGlobalSetting('Own_Panel') == $vendor_arr_single[0]['vendor_id']) {
    ?>
    <div class='header ui-widget-header'>
        <?php $clang->eT("Query and Count"); ?>
        <span style="float: right">
            <?php
//                $imghtml = CHtml::image($imageurl . 'add.png');
//                echo "<div id='your-form-block-id'>";
//                echo CHtml::beginForm();
//                echo CHtml::link($imghtml, array('admin/project/sa/add_query/prjid/' . $project_id), array('class' => 'class-link'));
//                echo CHtml::endForm();
//                echo "</div>";
//                
            ?>
            <a href='<?php echo $this->createUrl("admin/pquery/sa/add/prjid/$project_id/vid/$vendor_project_id"); ?>'>
                <img src='<?php echo $imageurl; ?>add.png' alt='<?php $clang->eT("Add New Query"); ?>' />
            </a>
        </span>
    </div>
    <br />
    <script>
        $(document).ready(function() {
            $('#editVendor').dataTable({"sPaginationType": "full_numbers"});
        } );                                                    
    </script>

    <table id="editVendor" style="width:100%">
        <thead>
            <tr>
                <th><?php $clang->eT("Edit"); ?></th>
                <th><?php $clang->eT("ID"); ?></th>
                <th><?php $clang->eT("Name"); ?></th>
                <th><?php $clang->eT("Project"); ?></th>
                <th><?php $clang->eT("Sending"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < count($usr_arr); $i++) {
                $usr = $usr_arr[$i];
                if ($usr['project_id'] == $project_id) {
                    $project = Project::model()->findAllByPk($usr['project_id']);
                    ?>
                    <tr>

                        <td style="padding:3px;">
                            <?php echo CHtml::form(array("admin/pquery/sa/mod/prjid/$project_id/vid/$vendor_project_id"), 'post'); ?>            
                            <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Query"); ?>' />
                            <input type='hidden' name='action' value='modifyquery' />
                            <input type='hidden' name='query_id' value='<?php echo $usr['id']; ?>' />
                            </form>
                        </td>
                        <td><?php echo $usr['id']; ?></td>              
                        <td><?php echo htmlspecialchars($usr['name']); ?></td>
                        <td><?php echo $project[0]['project_name'] . ' [' . $usr['project_id'] . ']'; ?></td>

                        <td>
                            <?php
                            echo "<div id='your-form-block-id'>";
                            echo CHtml::beginForm();
                            echo CHtml::link('Send Invitations', array('admin/pquery/sa/send/id/' . $usr['id'] . '/prjid/' . $usr['project_id'] . '/qname/' . $usr['name'] . '/vid/' . $vendor_project_id), array('class' => 'class-link'));
                            echo " | ";
                            echo CHtml::link('Reminder', array('admin/pquery/sa/send/id/' . $usr['id'] . '/prjid/' . $usr['project_id'] . '/resend/1/qname/' . $usr['name'] . '/vid/' . $vendor_project_id), array('class' => 'class-link'));
                            echo " | ";
                            echo CHtml::link('History', array('admin/pquery/sa/history/prjid/' . $usr['project_id'] . '/qname/' . $usr['name']), array('class' => 'class-link'));
                            echo CHtml::endForm();
                            echo "</div>";
                            ?> 

                        </td>
                    </tr>
                    <?php
                    $row++;
                }
            }
            ?>
        </tbody>
    </table>

    <?php
}
//}
?>