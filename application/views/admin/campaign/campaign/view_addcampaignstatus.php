<?php
Yii::app()->loadHelper('admin/htmleditor');
PrepareEditorScript(true, $this);
?>

<style type="text/css">
    #cke_page_content{
        width: 97% !important;
    }
</style>
<div class='header ui-widget-header'><?php $clang->eT("Add Campaign Source Type"); ?></div>
<br />
<?php echo CHtml::form(array("admin/campaign/sa/addcampaignstatus"), 'post', array('id' => 'contactform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<table style="width: 80%; margin: 0px auto;">
    <tr>
        <td align="right" style="text-align: right;">
            <label for='status_name'><?php $clang->eT("Status Name*  : "); ?></label>
        </td>
        <td>
            <input type='text' maxlength="100" id='status_name' name='status_name' autofocus="autofocus" required="required" />
        </td>

    </tr>

    <tr>
        <td align="right" style="text-align: right;">
            <label for='status_code'><?php $clang->eT("Status  Code*  : "); ?></label>
        </td>
        <td>
            <input type='text' maxlength="100" id='status_code' name='status_code' autofocus="autofocus" required="required" />
        </td>

    </tr>
   
</table>
<br/>


<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addcampaignstatus' />
</p>
</form>