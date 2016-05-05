<?php
//Yii::app()->loadHelper('admin/htmleditor');
//PrepareEditorScript(false, $this);
?>
<script type="text/javascript">
    function chngcontent(value){
        $.ajax({
            type: 'POST',
            data: {body_language: value,
                body_id : $('#email_body_id').val()
            },
            url: '<?php echo CController::createUrl('admin/get/sa/bodycontent') ?>',
            success: function(data){
                //alert(data);
                $('#translated_content').html(data);
                CKEDITOR.instances.translated_content.setData(data);
            }
        })
    }
</script>
<div class='header ui-widget-header'><?php $clang->eT("Editing Email Body"); ?></div><br />
<?php echo CHtml::form(array("admin/get/sa/edit_body"), 'post', array('id' => 'editbodyform')); ?>
<?php
foreach ($mur as $mrw) {
    ?>
    <table style="width: 80%; margin: 0px auto;">
        <tr>
            <td align="right" style="text-align: right;">
                <label for='body_language'><?php $clang->eT("Select Language"); ?></label>
            </td>
            <td>
                <select style='min-width:220px;' id='body_language' name='body_language' onchange="chngcontent(this.value)">
                    <?php
                    foreach (getLanguageDataRestricted(false, Yii::app()->session['adminlang']) as $langkey => $langname) {
                        $chk = '';
                        if ($langkey == $email_language_code) {
                            $chk = 'selected=selected';
                        }
                        ?>
                        <option id='<?php echo $langkey; ?>' <?php echo $chk; ?> value='<?php echo $langkey; ?>'>
                            <?php echo $langname['description']; ?>
                        </option>
                    <?php } ?>
                </select>
                <input type='hidden' id='email_body_id' name='email_body_id' value="<?php echo $email_body_id ?>" />
                <input type='hidden' id='email_language_code' name='email_language_code' value="<?php echo $email_language_code ?>" />
            </td>
        </tr>

        <tr>
            <td align="right" style="text-align: right;">
                <label for='template_usein'><?php $clang->eT("Select Template Usein* : "); ?></label>
            </td>
            <td>
                <?php
                $templateusein = GetEmailUseInArray();
                echo CHtml::dropDownList('template_usein', '', $templateusein, array(
                    'ajax' => array(
                        'type' => 'POST',
                        'data' => array('emailUseIN' => 'js:this.value'),
                        'url' => CController::createUrl('admin/get/sa/fetchtemplatevariable'),
                        'update' => '#variabledetail',
                    ),
                ));
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='body_content'><?php $clang->eT("Body Content : "); ?></label>
            </td>
            <td>
                <textarea rows="3" cols="80" name="body_content" id="body_content"><?php echo $mrw['content_text'] ?></textarea>
            </td>
            <?php echo getEditor("body-content", "body_content", "[" . $clang->gT("Body Content:", "js") . "](en)", '127682', '', '', 'editsurveylocalesettings') ?>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='translated_content_en'><?php $clang->eT("Translated Content : "); ?></label>
            </td>
            <td>
                <div class='htmleditor'>
                    <textarea rows="3" cols="80" name="translated_content" id="translated_content"><?php echo $mrw['translated_body'] ?></textarea>
                </div>
            </td>
            <?php
            //echo getEditor("survey-welc", "welcome_" . $esrow['surveyls_language'], "[" . $clang->gT("Welcome:", "js") . "](" . $esrow['surveyls_language'] . ")", $surveyid, '', '', $action); 
            ?>
            <?php echo getEditor("trn-cont", "translated_content", "[" . $clang->gT("Translated Content:", "js") . "](en)", '127682', '', '', 'editsurveylocalesettings') ?>
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
    <div id="variabledetail"></div>
    <?php
}
?>
<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='modbody' />
</p>
</form>
