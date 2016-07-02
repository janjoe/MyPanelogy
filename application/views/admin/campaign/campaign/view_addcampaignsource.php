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
            $("#redirectlink").attr('required', 'required');
            $("#contenteditor").css({"display":"none"});
        }
    }
</script>

<style type="text/css">
    #cke_page_content{
        width: 97% !important;
    }
</style>
<div class='header ui-widget-header'><?php $clang->eT("Add Campaign Source"); ?></div>
<br />
<?php echo CHtml::form(array("admin/campaign/sa/addcampaignsource"), 'post', array('id' => 'contactform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<table style="width: 80%; margin: 0px auto;">
    <tr>
        <td align="right" style="text-align: right;">
            <label for='page_name'><?php $clang->eT("Source Name* : "); ?></label>
        </td>
        <td>
            <input type='text' maxlength="100" id='source_name' name='source_name' autofocus="autofocus" required="required" />
        </td>

        <!-- <td align="right">
            <label for='source_code'><?php $clang->eT("Source Code : "); ?></label>
        </td>
            
        <td>
            <input type='text' maxlength="200" id='source_code' name='source_code' required="required" value="" />
        </td> -->
        
    </tr>
   
  
    <tr>
        <td align="right" style="text-align: right;">
            <label for='page_content'><?php $clang->eT("Source Notes : "); ?></label>
        </td>
        <td colspan="3">
            <textarea rows="3" cols="80" name="source_notes" id="source_notes"></textarea>
            <?php echo getEditor("source_notes", "source_notes", "[" . $clang->gT("Source Notes:", "js") . "]", '2541', '', '', '') ?>
        </td>
        </tr>
</table>
<br/>


<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addcamp' />
</p>
</form>