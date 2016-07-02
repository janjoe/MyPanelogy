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
<div class='header ui-widget-header'><?php $clang->eT("Editing Source"); ?></div><br />
<?php echo CHtml::form(array("admin/campaign/sa/modcampaignsource"), 'post', array('id' => 'editcontactform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<?php
foreach ($mur as $mrw) {
    ?>
    <table style="width: 80%; margin: 0px auto;">
        <tr>
            <td align="right" style="text-align: right;">
                <label for='page_name'><?php $clang->eT("Source Name* : "); ?></label>
            </td>
            <td>
                <input type='text' maxlength="100" id='source_name' name='source_name' autofocus="autofocus" required="required" value="<?php echo $mrw['source_name'] ?>" />
                <input type='hidden' id='cmp_id' name='cmp_id' value="<?php echo $mrw['cmp_id'] ?>" />
                
            </td>
          <?php /*  <td align="right">
                <label for='source_code'><?php $clang->eT("Source Code : "); ?></label>
            </td>
            
            <td>
                <input type='text' maxlength="200" id='source_code' name='source_code' required="required" value="<?php echo $mrw['source_code'] ?>" />
            </td> */?>
        </tr>
        <tr></tr>
       
        <tr>
            <td align="right" style="text-align: right;">
                <label for='page_content'><?php $clang->eT("Source Notes : "); ?></label>
            </td>
            <td colspan="3">
                <textarea rows="3" cols="80" name="source_notes" id="source_notes"><?php echo $mrw['source_notes'] ?></textarea>
                <?php echo getEditor("source_notes", "source_notes", "[" . $clang->gT("Source Notes:", "js") . "]", '2541', '', '', '') ?>
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
