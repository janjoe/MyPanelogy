<?php
Yii::app()->loadHelper('admin/htmleditor');
PrepareEditorScript(true, $this);
?>

<script type="text/javascript">
    function hideshow(value){
        if(value == 1){
            // show content textarea
            $("#redirecturl").css({"display":"none"});
            $("#contenteditor").css({"display":"table"});
            $("#redirectlink").removeAttr('required');
        }else{
            //show redirect textbox
            $("#redirecturl").css({"display":"table-row"});
            $("#redirectlink").val('');
            $("#redirectlink").attr('required', 'required');
            $("#contenteditor").css({"display":"none"});
        }
    }
    $(function() {
        var value = $("#contenttype").val();
        if(value == 1){
            // show content textarea
            $("#redirecturl").css({"display":"none"});
            $("#contenteditor").css({"display":"table"});
            $("#redirectlink").removeAttr('required');
        }else{
            //show redirect textbox
            $("#redirecturl").css({"display":"table-row"});
            $("#redirectlink").attr('required', 'required');
            $("#contenteditor").css({"display":"none"});
        }
    });
</script>

<script type="text/javascript">
    function chngcontent(value){
        alert('test');
        $.ajax({
            type: 'POST',
            data: {page_language: value,
                page_id : $('#page_id').val()
            },
            url: '<?php echo CController::createUrl('admin/cms/sa/pagecontent') ?>',
            success: function(data){
                //alert(data);
                $('#source_notes').html(data);
                CKEDITOR.instances.page_content.setData(data);
            }
        })
    }
</script>
<style type="text/css">
    #cke_page_content{
        width: 97% !important;
    }
</style>
<div class='header ui-widget-header'><?php $clang->eT("Editing Campaign"); ?></div><br />
<?php echo CHtml::form(array("admin/campaign/sa/modcampaign"), 'post', array('id' => 'editcontactform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<?php
if (!empty($mur)) {
    ?>
   
<table style="width: 80%; margin: 0px auto;">
    <tr>
        <td align="right" style="text-align: right;">
            <label for='campaign_name'><?php $clang->eT("Campaign Name* : "); ?></label>
        </td>
        <td>
            <input type='text' maxlength="100" id='campaign_name' value="<?php echo $mur['campaign_name'] ?>" name='campaign_name' autofocus="autofocus" required="required" />
            <input type='hidden' id='cp_id' name='cp_id' value="<?php echo $mur['id']; ?>" />
        </td>

        <td align="right" style="text-align: right;">
            <label for='campaign_code'><?php $clang->eT("Campaign Code* : "); ?></label>
        </td>
            
        <td>
            <input type='text' maxlength="200" id='campaign_code' name='campaign_code' required="required" value="<?php echo $mur['campaign_code'] ?>" />
        </td>
        
    </tr>

    <tr>
        <td align="right" style="text-align: right;">
            <label for='campaign_src_id'><?php $clang->eT("Campaign Source* : "); ?></label>
        </td>
        <td>
            <select required style='min-width:220px;' id='campaign_src_id' name='campaign_src_id'>
                <option value="">Select Campaign Source</option>
                <?php
                if(isset($campaign_source) && !empty($campaign_source))
                {
                    foreach ($campaign_source as $srckey => $srcval) {
                        ?>
                        <option <?php if($srcval['cmp_id'] == $mur['campaign_src_id']) { echo 'selected'; } ?> id='<?php echo $srcval['cmp_id']; ?>' value='<?php echo $srcval['cmp_id']; ?>'>
                            <?php echo $srcval['source_name']; ?>
                        </option>
                    <?php } ?>
               <?php } ?>     
            </select>
        </td>

        <td align="right" style="text-align: right;">
            <label for='campaign_cst_id'><?php $clang->eT("Campaign Source Type* : "); ?></label>
        </td>
        <td>
            <select required style='min-width:220px;' id='campaign_cst_id' name='campaign_cst_id'>
                <option value="">Select Campaign Type</option>
                <?php
                if(isset($campaign_source_type) && !empty($campaign_source_type))
                {
                    foreach ($campaign_source_type as $srctkey => $srctval) {
                        ?>
                        <option <?php if($srctval['cst_id'] == $mur['campaign_cst_id']) { echo 'selected'; } ?> id='<?php echo $srctval['cst_id']; ?>' value='<?php echo $srctval['cst_id']; ?>'>
                            <?php echo $srctval['name']; ?>
                        </option>
                    <?php } ?>
               <?php } ?>     
            </select>
        </td>

    </tr>
  
    <tr>
        <td align="right" style="text-align: right;">
            <label for='notes'><?php $clang->eT("Campaign Notes : "); ?></label>
        </td>
        <td colspan="3">
            <textarea rows="3" cols="80" name="notes" id="notes"><?php echo $mur['notes'] ?></textarea>
            <?php echo getEditor("notes", "notes", "[" . $clang->gT("Campaign Notes:", "js") . "]", '2541', '', '', '') ?>
        </td>
    </tr>


     <tr>

        <td align="right" style="text-align: right;">
            <label for='cost'><?php $clang->eT("Campaign Cost* : "); ?></label>
        </td>
        <td>
            <input type='text' maxlength="100" value="<?php echo $mur['cost'] ?>" id='cost' name='cost' autofocus="autofocus" required="required" />
        </td>


       
        <td align="right" style="text-align: right;">
            <label for='campaign_status'><?php $clang->eT("Campaign Status* : "); ?></label>
        </td>
        <td>
            <select style='min-width:220px;' id='campaign_status' name='campaign_status'>
                <?php
                if(isset($campaign_status) && !empty($campaign_status))
                {
                    foreach ($campaign_status as $cskey => $csval) {
                        ?>
                        <option <?php if($csval['cs_id'] == $mur['campaign_status']) { echo 'selected'; } ?> id='<?php echo $csval['cs_id']; ?>' value='<?php echo $csval['cs_id']; ?>'>
                            <?php echo $csval['status_name']; ?>
                        </option>
                    <?php } ?>
               <?php } ?>     
            </select>
        </td>

    </tr>
    <tr>
        <td align="right" style="text-align: right;">
            <label for='page_id'><?php $clang->eT("Select page : "); ?></label>
        </td>
        <td>
            <select style='min-width:220px;' id='page_id' name='page_id'>
                 <option value="">Select Page</option>
                <?php
                if(isset($page_data) && !empty($page_data))
                {
                    foreach ($page_data as $cskey => $csval) {
                        ?>
                        <option <?php if($csval['page_id'] == $mur['page_id']) { echo 'selected'; } ?>  id='<?php echo $csval['page_id']; ?>' value='<?php echo $csval['page_id']; ?>'>
                            <?php echo $csval['page_name']; ?>
                        </option>
                    <?php } ?>
               <?php } ?>     
            </select>
        </td>
    </tr>
</table>




   
    <?php
}
?>
<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='modcampaign' />
</p>
</form>
