<?php
Yii::app()->loadHelper('admin/htmleditor');
PrepareEditorScript(true, $this);
?>
<div class='header ui-widget-header'><?php $clang->eT("Add Email Body"); ?></div>
<?php echo CHtml::form(array("admin/get/sa/ins_body"), 'post', array('id' => 'addemailbody')); ?>
<table style="width: 80%; margin: 0px auto;">
    <tr>
        <td align="right" style="text-align: right;">
            <label for='body_language'><?php $clang->eT("Select Language : "); ?></label>
        </td>
        <td>
            <?php
            $test = getLanguageDataRestricted(false, Yii::app()->session['adminlang']);
            $language = array();
            foreach ($test as $ky => $val) {
                $language[$ky] = $val['description'];
            }
            echo CHtml::dropDownList('body_language', 'en', $language);
            ?>
        </td>
    </tr>
    <tr>
        <td align="right" style="text-align: right;">
            <label for='template_usein'><?php $clang->eT("Select Template Usein : "); ?></label>
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
        <td align="right" style="text-align: right; width: 20%">
            <label for='email_body'><?php $clang->eT("Email Body : "); ?></label>
        </td>
        <td>
            <textarea rows="3" cols="10" name="email_body" id="email_body"></textarea>
        </td>
        <?php echo getEditor("email-body", "email_body", "[" . $clang->gT("Email Body : ", "js") . "]", '125345', '', '', '') ?>
    </tr>
</table>
<div id="variabledetail"></div>

<p style="padding-top: 1em;">
    <input type='submit' value='<?php $clang->eT("Save"); ?>' />
    <input type='hidden' name='action' value='addemailbody' />
</p>
</form>