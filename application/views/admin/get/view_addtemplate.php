<div class='header ui-widget-header'><?php $clang->eT("Add Email Template"); ?></div>
<?php echo CHtml::form(array("admin/get/sa/ins_template"), 'post', array('id' => 'addemailtemplate')); ?>
<table style="width: 80%; margin: 0px auto;">
    <tr>

        <td align="right" style="text-align: right;">
            <label for='email_title'><?php $clang->eT("Email Title : "); ?></label>
        </td>
        <td id="new_subject_language">
            <input type='text' maxlength="50" id='email_title' name='email_title' required="required" />
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right;">
            <label for='template_usein'><?php $clang->eT("Use Email Template Usein : "); ?></label>
        </td>
        <td>
            <?php
            $templateusein = GetEmailUseInArray();
            echo CHtml::dropDownList('template_usein', '', $templateusein, array(
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
            $subject = Get_subject::model()->findAll(array('condition' => 'IsActive=1'));
            $subject_list = CHtml::listData($subject, 'email_subjectid', 'subject_text');
            echo CHtml::dropDownList('email_subject', '', $subject_list, array(
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
            $body = Get_body::model()->findAll(array('condition' => 'IsActive=1'));
            $test = array();
            foreach ($body as $val) {
                $test[$val['email_bodyid']] = 'CONTENT-ID-' . $val['email_bodyid'];
            }
            //$body_list = CHtml::listData($subject, 'email_bodyid', 'content_text');
            echo CHtml::dropDownList('body_content', '', $test, array(
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
</table>
<div id="bodycontent" style="text-align: center; margin-top: 1%; "></div>
<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addemailtemplate' />
</p>
</form>