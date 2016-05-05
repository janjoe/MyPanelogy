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
<div class='header ui-widget-header'><?php $clang->eT("Add Pages"); ?></div>
<br />
<?php echo CHtml::form(array("admin/cms/sa/add"), 'post', array('id' => 'contactform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<table style="width: 80%; margin: 0px auto;">
    <tr>
        <td align="right" style="text-align: right;">
            <label for='page_name'><?php $clang->eT("Page Name* : "); ?></label>
        </td>
        <td>
            <input type='text' maxlength="100" id='page_name' name='page_name' autofocus="autofocus" required="required" />
        </td>
        <td align="right" style="text-align: right;">
            <label for='page_title'><?php $clang->eT("Page Title* : "); ?></label>
        </td>
        <td>
            <input type='text' maxlength="200" id='page_title' name='page_title' required="required" />
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right;">
            <label for='page_language'><?php $clang->eT("Page Language* : "); ?></label>
        </td>
        <td>
            <select style='min-width:220px;' id='page_language' name='page_language'>
                <?php
                foreach (getLanguageDataRestricted(false, Yii::app()->session['adminlang']) as $langkey => $langname) {
                    ?>
                    <option id='<?php echo $langkey; ?>' value='<?php echo $langkey; ?>'>
                        <?php echo $langname['description']; ?>
                    </option>
                <?php } ?>
            </select>
        </td>
        <td align="right" style="text-align: right;">
            <label for='page_meta'><?php $clang->eT("Page Meta* : "); ?></label>
        </td>
        <td style="width: 30%;">
            <input type='text' maxlength="250" id='page_meta' name='page_meta' required="required" />
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right;">
            <label for='page_type'><?php $clang->eT("Page Type* : "); ?></label>
        </td>
        <td>
            <select name="page_type" id="page_type" onchange="hideshow(this.value);">
                <option value="1">Content</option>
                <option value="2">Redirect Page</option>
            </select>
        </td>
        <td align="right" style="text-align: right;">
            <label for='shwmenu'><?php $clang->eT("Show in Menu* : "); ?></label>
        </td>
        <td>
            <input type="checkbox" name="shwmenu" id="shwmenu" checked="checked"/>
        </td>
    </tr>
    <tr style="display: none" id="redirecturl">
        <td align="right" style="text-align: right;">
            <label for='page_content'><?php $clang->eT("URL* : "); ?></label>
        </td>
        <td>
            <input type='text' maxlength="250" id='redirectlink' name='page_content'/>
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right;">
            <label for='page_content'><?php $clang->eT("Page Content* : "); ?></label>
        </td>
        <td colspan="3">
            <textarea rows="3" cols="80" name="page_content" id="page_content"></textarea>
        </td>
        <?php echo getEditor("page-content", "page_content", "[" . $clang->gT("Page Content:", "js") . "]", '2541', '', '', '') ?>
    </tr>
</table>
<br/>
<!--<table style="width: 80%; margin: 0px auto;" id="contenteditor">
    <tr>
        <td align="right" style="text-align: right;">
            <label for='page_content'><?php $clang->eT("Page Content* : "); ?></label>
        </td>
        <td>
            <textarea rows="3" cols="80" name="page_content" id="page_content"></textarea>
        </td>
        <?php echo getEditor("page-content", "page_content", "[" . $clang->gT("Page Content:", "js") . "]", '', '', '', '') ?>
    </tr>
</table>-->

<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addcms' />
</p>
</form>