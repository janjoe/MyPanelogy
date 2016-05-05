<?php
Yii::app()->loadHelper('admin/htmleditor');
PrepareEditorScript(true, $this);
?>
<script>
    function Validationnew(){
        var a = CKEDITOR.instances.template_editor.getData();
        Error = 0;
        ErrorMsg = '';
        if(a == ''){
            ErrorMsg = "Template must contain Menu tag\n";
            ErrorMsg = ErrorMsg + "Template must contain Content tag\n";
            Error = 1;
        } 
        if(a.search("{menu}") == -1){
            //alert('header');
            ErrorMsg = "Template must contain menu tag\n";
            Error = 1;
        }
        if(a.search("{content}") == -1){
            //alert('content');
            ErrorMsg = ErrorMsg + "Template must contain Content tag\n";
            Error = 1;
        }
        if(Error == 1){
            alert(ErrorMsg);
            return false;
        }
        else{
            return true;
        }
    }
</script>
<div class='header ui-widget-header'><?php $clang->eT("Template Editor"); ?></div>
<br />
<?php echo CHtml::form(array("admin/template/sa/add"), 'post', array('id' => 'templateform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<table style="width: 80%; margin: 0px auto;" id="contenteditor">
    <tr>
        <td align="right" style="text-align: right;">
            <label for='template_editor'><?php $clang->eT("Template Editor* : "); ?></label>
        </td>
        <?php
        $newPath = "application.views.admin.cms";
        $newPath = YiiBase::getPathOfAlias($newPath);
        $filepath = $newPath . '/template/default.tpl.php';
        $page = file_get_contents($filepath);
        ?>
        <td>
            <textarea rows="3" cols="80" name="template_editor" id="template_editor"><?php echo $page; ?></textarea>
        </td>

        <?php echo getEditor("template_editor", "template_editor", "[" . $clang->gT("Template Editor:", "js") . "]", '2541', '', '', '') ?>
    </tr>
</table>

<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addcms' />
</p>
</form>
