<div class='header ui-widget-header'><?php $clang->eT("Editing Email Subject"); ?></div><br />
<?php echo CHtml::form(array("admin/get/sa/edit_subs"), 'post', array('id' => 'editcontactform', 'enableClientValidation' => true, 'onsubmit' => 'javascript:return Validationnew()')); ?>
<?php
foreach ($mur as $mrw) {
    ?>
    <table style="width: 80%; margin: 0px auto;">
        <tr>
            <td align="right" style="text-align: right;">
                <label for='subject_language'><?php $clang->eT("Select Language"); ?></label>
            </td>
            <td>
                <?php
                $test = getLanguageDataRestricted(false, Yii::app()->session['adminlang']);
                $language = array();
                foreach ($test as $ky => $val) {
                    $language[$ky] = $val['description'];
                }
                echo CHtml::dropDownList('subject_language', $language_code_dest, $language, array(
                    'ajax' => array(
                        'type' => 'POST',
                        'data' => array('language_code' => 'js:this.value', 'subject_id' => $email_subjectid),
                        'url' => CController::createUrl('admin/get/sa/selectsubjectcontent'),
                        'update' => '#new_subject_language',
                    ),
                ));
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='subject_text'><?php $clang->eT("Selected Subject Text : "); ?></label>
            </td>
            <td>
                <input type='text' maxlength="500" id='subject_text' name='subject_text' required="required" value="<?php echo $mrw['subject_text'] ?>" />
                <input type='hidden' name='email_subjectid' value='<?php echo $email_subjectid; ?>' />
                <input type='hidden' name='language_code_dest' value='<?php echo $language_code_dest; ?>' />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='translate_subject_text'><?php $clang->eT("Translated Subject Text : "); ?></label>
            </td>
            <td id="new_subject_language">
                <input type='text' maxlength="500" id='translate_subject_text' name='translate_subject_text' required="required" value="<?php echo $mrw['translated_subject'] ?>" />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='IsActive'><?php $clang->eT("IsActive : "); ?></label>
            </td>
            <?php
            $chk = '';
            if ($mrw['IsActive'] == 1) {
                $chk = 'checked=checked';
            }
            ?>
            <td>
                <input style="vertical-align: sub;" type="checkbox" <?php echo $chk; ?> name="IsActive" />
            </td>
        </tr>
    </table>
    <?php
}
?>
<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='modsubject' />
</p>
</form>
