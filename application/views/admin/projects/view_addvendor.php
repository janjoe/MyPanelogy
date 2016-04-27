<div class='header ui-widget-header'><?php $clang->eT("Add Vendor"); ?></div>
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
    
    function validateURL(textval) {
        if(textval == ""){
            return false;
        }else{
            var urlregex = new RegExp("^(https:\/\/|http:\/\/|https:\/\/www.|https:\/\/www.){1}([0-9A-Za-z]+\.)");
            return urlregex.test(textval);
        }
    }
    
    function Validationnewvendor(){
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
<?php echo CHtml::form(array("admin/project/sa/add"), 'post', array('id' => 'newprojectform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnewvendor()')); ?>
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
            echo CHtml::dropDownList('panel', '', $vendor_list, array(
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
        </td>
        <td style="text-align: right;">
            <label for='vendor_contact'><?php $clang->eT("Vendor Contact : "); ?></label>
        </td>
        <td>
            <?php
            echo CHtml::dropDownList('vendor_contact', '', array(), array('prompt' => 'Select Contact', 'required' => true));
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
            ?>
            <input type="text" name="cpc" id="cpc" required/>
            <input type="hidden" value="<?php echo $CPC; ?>" name="prv_price" id="prv_price"/> 
            <br/>
            Balance: <span id="disprice" class="trues">$<?php echo $CPC; ?></span>
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
                echo fill_quota_buffer(0, $required_completes)
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='maxredirects'><?php $clang->eT("Max. Redirects : "); ?></label>
        </td>
        <td>
            <input type='text' id='maxredirects' name='maxredirects' value="0"/>
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right">
            <label for='completionlink'><?php $clang->eT("Completion link* : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' id='completionlink' name='completionlink'></textarea>
        </td>
        <td align="right" style="text-align: right">
            <label for='disqualifylink'><?php $clang->eT("Disqualify link* : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' id='disqualifylink' name='disqualifylink'></textarea>
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right">
            <label for='quatafulllink'><?php $clang->eT("Quotafull link* : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' id='quatafulllink' name='quatafulllink'></textarea>
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
            echo CHtml::dropDownList('status', $project_status_id, $project_status_list);
            ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;">
            <label for='notes'><?php $clang->eT("Notes : "); ?></label>
        </td>
        <td>
            <textarea cols='50' rows='2' id='notes' name='notes'></textarea>
        </td>
    </tr>

</table>
<p  style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addvendor' />
    &nbsp; &nbsp;
    <a href="<?php echo $this->createUrl('admin/project/sa/modifyproject/project_id/' . $project_id . '/action/modifyproject') ?>" class="limebutton">Cancel</a>
</p>
</form>
