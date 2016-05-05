<div class='header ui-widget-header'><?php $clang->eT("Manage Email Subjects"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listEmailTemplates').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>
<script>
    function chnglanguage(){
        location.reload();
        alert('<?Php echo Yii::app()->createAbsoluteUrl('admin/get/list_subs', array(), 'https') ?>');
    }
</script>
<?php echo CHtml::form(array('admin/get/sa/list_subs'), 'post'); ?>
<table class="users">
    <tr>
        <td><?php $clang->eT("Select Language"); ?></td>
        <td>
            <?php
            $lan_email_sub = Yii::app()->request->cookies['Language-Email-Subject']->value;
            $test = getLanguageDataRestricted(false, Yii::app()->session['adminlang']);
            $language = array();
            foreach ($test as $ky => $val) {
                $language[$ky] = $val['description'];
            }
            echo CHtml::dropDownList('subject_language', $lan_email_sub, $language, array(
                'submit' => '',
            ));
            ?>
        </td>
    </tr>
</table>
</form>
<table id="listEmailTemplates" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("Delete"); ?></th>
            <th><?php $clang->eT("Subject ID"); ?></th>
            <th><?php $clang->eT("Subject Text"); ?></th>
            <th><?php $clang->eT("Translated Subject"); ?></th>
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
                    <?php echo CHtml::form(array('admin/get/sa/edit_subs'), 'post'); ?>
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Page"); ?>' />
                    <input type='hidden' name='email_subjectid' value='<?php echo $usr['email_subjectid']; ?>' />
                    <input type='hidden' name='language_code_dest' value='<?php echo $lanemail; ?>' />
                    </form>
                </td>
                <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/get/sa/del_subs'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this Page"); ?>' />
                    <input type='hidden' name='action' value='del_subs' />
                    <input type='hidden' name='email_subjectid' value='<?php echo $usr['email_subjectid']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['email_subjectid']; ?></td>
                <td><?php echo ($usr['subject_text']); ?></td>
                <td><?php echo ($usr['translated_subject']); ?></td>
                <?php
                if ($usr['IsActive'] === '1') {
                    $Isactive = 'True';
                } elseif ($usr['IsActive'] === '0') {
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
<br />
<?php echo CHtml::form(array('admin/get/sa/ins_subs'), 'post'); ?>            
<table class='users'>
    <tr class='oddrow'>
        <th><?php $clang->eT("Email Subject Text : "); ?></th>
        <td style='width:20%'>
            <input type='text' maxlength="500" name='subject_text' placeholder="Email Subject"/>
            <input type='hidden' name='email_sub_language' value="<?php echo $lanemail; ?>"/>
        </td>
        <td style='width:15%'><input type='submit' value='<?php $clang->eT("save"); ?>' />
            <input type='hidden' name='action' value='addsubject' />
        </td>
    </tr>
</table>
</form>