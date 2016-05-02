<div class='header ui-widget-header'><?php $clang->eT("Manage Email Body Contents"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listEmailTemplates').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>
<br/>
<?php echo CHtml::form(array('admin/get/sa/list_body'), 'post'); ?>
<table style="width: 80%; margin: 0px auto;">
    <tr>
        <td align="right" style="text-align: right; width: 25%">
            <label for='body_language'><?php $clang->eT("Select Language : "); ?></label>
        </td>
        <td>
            <?php
            $lan_email_body = Yii::app()->request->cookies['Language-Email-Body']->value;
            $test = getLanguageDataRestricted(false, Yii::app()->session['adminlang']);
            $language = array();
            foreach ($test as $ky => $val) {
                $language[$ky] = $val['description'];
            }
            echo CHtml::dropDownList('body_language_list', $lan_email_body, $language, array(
                'submit' => '',
            ));
            ?>
        </td>
    </tr>
</table>
</form>
<br/>
<table id="listEmailTemplates" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("Delete"); ?></th>
            <th><?php $clang->eT("Email Body ID"); ?></th>
            <th><?php $clang->eT("Email Body Content"); ?></th>
            <th><?php $clang->eT("Email Body Translated Content"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            ?>
            <tr>

                <td style="padding:3px;">    
                    <?php echo CHtml::form(array('admin/get/sa/edit_body'), 'post'); ?>
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Page"); ?>' />
                    <input type='hidden' name='email_language_code' value='<?php echo $lan_email_body; ?>' />
                    <input type='hidden' name='email_body_id' value='<?php echo $usr['email_bodyid']; ?>' />
                    </form>
                </td>
                <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/get/sa/del_body'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this Page"); ?>' />
                    <input type='hidden' name='action' value='del_body' />
                    <input type='hidden' name='email_bodyid' value='<?php echo $usr['email_bodyid']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['email_bodyid']; ?></td>
                <td><?php echo ($usr['content_text']); ?></td>
                <td><?php echo ($usr['translated_body']); ?></td>
                <?php
                if ($usr['IsActive'] == '1') {
                    $Isactive = 'True';
                } elseif ($usr['IsActive'] == '0') {
                    $Isactive = 'False';
                } else {
                    $Isactive = '';
                }
                ?>
                <td><?php echo $Isactive; ?></td>
            </tr>
            <?php $row++;
        } ?>
    </tbody>
</table>
<br/>