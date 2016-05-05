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
        $.ajax({
            type: 'POST',
            data: {page_language: value,
                page_id : $('#page_id').val()
            },
            url: '<?php echo CController::createUrl('admin/cms/sa/pagecontent') ?>',
            success: function(data){
                //alert(data);
                $('#page_content').html(data);
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
<div class='header ui-widget-header'><?php $clang->eT("Editing Content"); ?></div><br />
<?php echo CHtml::form(array("admin/cms/sa/modcms"), 'post', array('id' => 'editcontactform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<?php
foreach ($mur as $mrw) {
    ?>
    <table style="width: 80%; margin: 0px auto;">
        <tr>
            <td align="right" style="text-align: right;">
                <label for='page_name'><?php $clang->eT("Page Name* : "); ?></label>
            </td>
            <td>
                <input type='text' maxlength="100" id='page_name' name='page_name' autofocus="autofocus" required="required" value="<?php echo $mrw['page_name'] ?>" />
                <input type='hidden' id='page_id' name='page_id' value="<?php echo $page_id ?>" />
                <input type='hidden' id='contenttype' name='contenttype' value="<?php echo $mrw['contenttype'] ?>" />
            </td>
            <td align="right" style="text-align: right;">
                <label for='page_title'><?php $clang->eT("Page Title* : "); ?></label>
            </td>
            <td>
                <input type='text' maxlength="200" id='page_title' name='page_title' required="required" value="<?php echo $mrw['page_title'] ?>" />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='page_language'><?php $clang->eT("Page Language* : "); ?></label>
            </td>
            <td>
                <select style='min-width:220px;' id='page_language' name='page_language' onchange="chngcontent(this.value)">
                    <?php
                    foreach (getLanguageDataRestricted(false, Yii::app()->session['adminlang']) as $langkey => $langname) {
                        $chk = '';
                        if ($langkey == $mrw['language_code']) {
                            $chk = 'selected=selected';
                        }
                        ?>
                        <option id='<?php echo $langkey; ?>' <?php echo $chk; ?> value='<?php echo $langkey; ?>'>
                            <?php echo $langname['description']; ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
            <td align="right" style="text-align: right;">
                <label for='page_meta'><?php $clang->eT("Page Meta* : "); ?></label>
            </td>
            <td style="width: 30%;">
                <input type='text' maxlength="250" id='page_meta' name='page_meta' required="required" value="<?php echo $mrw['meta_tags'] ?>" />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='page_type'><?php $clang->eT("Page Type* : "); ?></label>
            </td>
            <td>
                <?php
                $content = $redirect = '';
                if ($mrw['page_title'] == 1) {
                    $content = 'checked = "checked"';
                } elseif ($mrw['page_title'] == 2) {
                    $redirect = 'checked = "checked"';
                }
                ?>
                <select name="page_type" id="page_type" onchange="hideshow(this.value);">
                    <option value="1" <?php echo $content; ?> >Content</option>
                    <option value="2" <?php echo $redirect; ?>>Redirect Page</option>
                </select>
            </td>
            <td align="right" style="text-align: right;">
                <label for='shwmenu'><?php $clang->eT("Show in Menu* : "); ?></label>
            </td>
            <td>
                <?php
                $chk = '';
                if ($mrw['showinmenu'] == 1) {
                    $chk = 'checked=checked';
                }
                ?>
                <input type="checkbox" <?php echo $chk ?> name="shwmenu" id="shwmenu"/>
            </td>
        </tr>
        <tr style="display: none" id="redirecturl">
            <td align="right" style="text-align: right;">
                <label for='redirectlink'><?php $clang->eT("URL* : "); ?></label>
            </td>
            <td>
                <input type='text' maxlength="250" id='redirectlink' name='redirectlink' value='<?php echo $mrw['page_content'] ?>' />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='page_content'><?php $clang->eT("Page Content* : "); ?></label>
            </td>
            <td colspan="3">
                <textarea rows="3" cols="80" name="page_content" id="page_content"><?php echo $mrw['page_content'] ?></textarea>
                <?php echo getEditor("page-content", "page_content", "[" . $clang->gT("Page Content:", "js") . "]", '2541', '', '', '') ?>
            </td>
        </tr>

        <tr>
            <?php
            $chk = '';
            if ($mrw['IsActive'] == 1) {
                $chk = 'checked=checked';
            }
            ?>
            <td align="right" style="text-align: right;">
                <label for='IsActive'><?php $clang->eT("IsActive : "); ?></label>
            </td>
            <td>
                <input type="checkbox" id="IsActive" <?php echo $chk; ?> name="IsActive" />
            </td>
        </tr>
    </table>
    <br/>
<!--    <table style="width: 80%; margin: 0px auto;" id="contenteditor">
        <tr>
            <td align="right" style="text-align: right;">
                <label for='page_content'><?php $clang->eT("Page Content* : "); ?></label>
            </td>
            <td>
                <textarea rows="3" cols="80" name="page_content" id="page_content"><?php echo $mrw['page_content'] ?></textarea>
            </td>
            <?php echo getEditor("page-content", "page_content", "[" . $clang->gT("Page Content:", "js") . "]", '2541', '', '', '') ?>
        </tr>

        <tr>
            <?php
            $chk = '';
            if ($mrw['IsActive'] == 1) {
                $chk = 'checked=checked';
            }
            ?>
            <td align="right" style="text-align: right;">
                <label for='IsActive'><?php $clang->eT("IsActive : "); ?></label>
            </td>
            <td>
                <input type="checkbox" id="IsActive" <?php echo $chk; ?> name="IsActive" />
            </td>
        </tr>
    </table>-->
    <?php
}
?>
<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='modcms' />
</p>
</form>
