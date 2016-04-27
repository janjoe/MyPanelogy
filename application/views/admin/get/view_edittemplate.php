<div class='header ui-widget-header'><?php $clang->eT("Editing Email Template"); ?></div><br />
<?php echo CHtml::form(array("admin/get/sa/edit_tmplt"), 'post', array('id' => 'editbodyform')); ?>
<?php
foreach ($mur as $mrw) {
    ?>
    <table style="width: 80%; margin: 0px auto;">
        <tr>
            <td align="right" style="text-align: right;">
                <label for='email_title'><?php $clang->eT("Email Title : "); ?></label>
            </td>
            <td>
                <input type='text' id='email_title' name='email_title' value="<?php echo $mrw['title_text'] ?>" />
                <input type='hidden' id='template_emailid' name='template_emailid' value="<?php echo $mrw['template_emailid'] ?>" />
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='template_usein'><?php $clang->eT("Use Email Template Usein : "); ?></label>
            </td>
            <td>
                <?php
                $templateusein = GetEmailUseInArray();
                echo CHtml::dropDownList('template_usein', $mrw['use_in'], $templateusein, array(
                    'required' => true
                ));
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='email_subject'><?php $clang->eT("Select Email Subject : "); ?></label>
            </td>
            <td>
                <?php
                $subject = Get_subject::model()->findAll();
                $subject_list = CHtml::listData($subject, 'email_subjectid', 'subject_text');
                echo CHtml::dropDownList('email_subject', $mrw['email_subjectid'], $subject_list, array(
                    'prompt' => 'Select Email Subject',
                    'required' => true,
                ));
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" style="text-align: right;">
                <label for='body_content'><?php $clang->eT("Select Email Body Content : "); ?></label>
            </td>
            <td>
                <?php
                $body = Get_subject::model()->findAll();
                foreach ($body as $val) {
                    $test[$val['email_subjectid']] = 'CONTENT-ID-' . $val['email_subjectid'];
                }
                //$body_list = CHtml::listData($subject, 'email_bodyid', 'content_text');
                echo CHtml::dropDownList('body_content', $mrw['email_bodyid'], $test, array(
                    'prompt' => 'Select Email Body',
                    'required' => true,
                    'ajax' => array(
                        'type' => 'POST',
                        'data' => array('body_id' => 'js:this.value'),
                        'url' => CController::createUrl('admin/get/sa/templatecontent'),
                        'update' => '#bodycontent',
                    ),
                ));
                ?>
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
    <div id="bodycontent" style="text-align: center; margin-top: 1%; ">
        <?php echo $mrw['content_text'] ?>
    </div>
    <?php
}
?>
<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='modemailtemplate' />
</p>
</form>
