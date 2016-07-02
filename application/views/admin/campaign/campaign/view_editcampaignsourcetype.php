<?php
Yii::app()->loadHelper('admin/htmleditor');
PrepareEditorScript(true, $this);
?>

<style type="text/css">
    #cke_page_content{
        width: 97% !important;
    }
</style>
<div class='header ui-widget-header'><?php $clang->eT("Editing Surce Type"); ?></div><br />
<?php echo CHtml::form(array("admin/campaign/sa/modcampaignsourcetype"), 'post', array('id' => 'editcontactform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<?php
foreach ($mur as $mrw) {
    ?>
    <table style="width: 80%; margin: 0px auto;">
        <tr>
            <td align="right" style="text-align: right;">
                <label for='page_name'><?php $clang->eT("Source Type Name* : "); ?></label>
            </td>
            <td>
                <input type='text' maxlength="100" id='name' name='name' autofocus="autofocus" required="required" value="<?php echo $mrw['name'] ?>" />
                <input type='hidden' id='cst_id' name='cst_id' value="<?php echo $mrw['cst_id'] ?>" />
                
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