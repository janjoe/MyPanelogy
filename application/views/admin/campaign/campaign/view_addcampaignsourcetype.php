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
<?php echo CHtml::form(array("admin/campaign/sa/addcampaignsourcetype"), 'post', array('id' => 'contactform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<table style="width: 80%; margin: 0px auto;">
    <tr>
        <td align="right" style="text-align: right;">
            <label for='page_name'><?php $clang->eT("Source Type Name*  : "); ?></label>
        </td>
        <td>
            <input type='text' maxlength="100" id='name' name='name' autofocus="autofocus" required="required" />
        </td>

    </tr>
   
</table>
<br/>


<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addcampsourcetype' />
</p>
</form>