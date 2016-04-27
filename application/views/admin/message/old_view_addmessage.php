<div class='header ui-widget-header'><?php $clang->eT("Manage Message"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listunread').dataTable({"sPaginationType": "full_numbers","iDisplayLength": 25});
        $('#listread').dataTable({"sPaginationType": "full_numbers","iDisplayLength": 25});
    } );
    
    function reloadpage(){
        return true;
    }
</script>
<div id='tabs'>
    <ul>
        <li><a href='#unread' id="tab-01"><?php $clang->eT("Unread Message"); ?></a></li>
        <li><a href='#read' id="tab-02"><?php $clang->eT("Read Message"); ?></a></li>
    </ul>
    <div id="unread">      
        <table class="InfoForm" style="width: 95%; margin: 0px auto;">
            <tr>
                <th>Id</th>
                <th>Panellist</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Time</th>
                <th>Send/ Receive</th><!--Add by Parth 14-06-2014-->
                <th>View Detail</th>
                <th>Status</th>
            </tr>
            <?php
            $sql = "SELECT * FROM {{view_unread_emails}} ";
            if (!Permission::model()->hasGlobalPermission('superadmin', 'read')) {
                $sql .= "WHERE (email_to = '" . $_SESSION['loginID'] . "' OR email_from = '" . $_SESSION['loginID'] . "')";
            }
            $msglist = Yii::app()->db->createCommand($sql)->query()->readAll();
            $odd = FALSE;
            $id = '';
            if (count($msglist) > 0) {
                foreach ($msglist as $key => $value) {
                    if ($value['type'] == 'Parent') {
                        $cls = 'class="odd"';
                        echo '<tr ' . $cls . '>
                        <td>' . $value['id'] . '</td>
                        <td>';
                        if ($value['sender'] == 'P') {
                            $user = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $value['email_from'] . ' '));
                        } else {
                            $user = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $value['email_to'] . ' '));
                        }
                        echo htmlspecialchars($user[0]['first_name'] . ' ' . $user[0]['last_name']);
                        echo '</td>
                        <td>' . $value['subject'] . '</td>
                        <td>' . $value['body'] . '</td>
                        <td>' . $value['created_datetime'] . '</td>
                        <td>';
                        echo "<div id='your-form-block-id'>";
                        echo CHtml::beginForm();
                        echo CHtml::link('Reply', array('admin/message/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                        echo CHtml::endForm();
                        echo "</div>";
                        echo '</td>
                        <td>
                            <a title ="Click change to status Read" href="' . CController::createUrl('admin/message/sa/message_status/s/Read/id/' . $value['id']) . '">' . $value['status'] . '</a>
                        </td>
                    <tr>';
                    } else {
                        $cls = 'class="even"';
                        echo '<tr ' . $cls . '>
                        <td>' . $value['id'] . '</td>
                        <td>';
                        if ($value['sender'] == 'P') {
                            $user = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $value['email_from'] . ' '));
                        } else {
                            $user = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $value['email_to'] . ' '));
                        }
                        echo htmlspecialchars($user[0]['first_name'] . ' ' . $user[0]['last_name']);
                        echo '</td>
                        <td>' . $value['subject'] . '</td>
                        <td>&nbsp&nbsp&nbsp' . $value['body'] . '</td>
                        <td>' . $value['created_datetime'] . '</td>
                        <td>';
                        echo "<div id='your-form-block-id'>";
                        echo CHtml::beginForm();
                        echo CHtml::link('Reply', array('admin/message/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                        echo CHtml::endForm();
                        echo "</div>";
                        echo '</td>
                        <td>
                            <a title ="Click change to status Read" href="' . CController::createUrl('admin/message/sa/message_status/s/Read/id/' . $value['id']) . '">' . $value['status'] . '</a>
                        </td>
                    <tr>';
                    }
                    $odd = !$odd;
                }
            } else {

                echo '<tr class="odd">
                        <td colspan="6" style="text-align: center;">No Message Available</td>
                      <tr>';
            }
            ?>
        </table>
    </div>        
    <div id="read">        
        <table class="InfoForm" style="width: 95%; margin: 0px auto;">
            <tr>
                <th>Id</th>
                <th>Panellist</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Time</th>
                <th>View Detail</th>
                <th>Status</th>
            </tr>
            <?php
            $sql = "SELECT * FROM {{view_read_emails}} ";
            if (!Permission::model()->hasGlobalPermission('superadmin', 'read')) {
                $sql .= "WHERE (email_to = '" . $_SESSION['loginID'] . "' OR email_from = '" . $_SESSION['loginID'] . "')";
            }
            $msglist = Yii::app()->db->createCommand($sql)->query()->readAll();
            $odd = FALSE;
            $id = '';
            if (count($msglist) > 0) {
                foreach ($msglist as $key => $value) {
                    if ($value['type'] == 'Parent') {
                        $cls = 'class="odd"';
                        echo '<tr ' . $cls . '>
                        <td>' . $value['id'] . '</td>
                        <td>';
                        if ($value['sender'] == 'P') {
                            $user = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $value['email_from'] . ' '));
                        } else {
                            $user = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $value['email_to'] . ' '));
                        }
                        echo htmlspecialchars($user[0]['first_name'] . ' ' . $user[0]['last_name']);
                        echo '</td>
                        <td>' . $value['subject'] . '</td>
                        <td>' . $value['body'] . '</td>
                        <td>' . $value['created_datetime'] . '</td>
                        <td>';
                        echo "<div id='your-form-block-id'>";
                        echo CHtml::beginForm();
                        echo CHtml::link('Reply', array('admin/message/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                        echo CHtml::endForm();
                        echo "</div>";
                        echo '</td>
                        <td>
                            <a title ="Click change to status Read" href="' . CController::createUrl('admin/message/sa/message_status/s/Unread/id/' . $value['id']) . '">' . $value['status'] . '</a>
                        </td>
                    <tr>';
                    } else {
                        $cls = 'class="even"';
                        echo '<tr ' . $cls . '>
                        <td>' . $value['id'] . '</td>
                        <td>';
                        if ($value['sender'] == 'P') {
                            $user = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $value['email_from'] . ' '));
                        } else {
                            $user = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $value['email_to'] . ' '));
                        }
                        echo htmlspecialchars($user[0]['first_name'] . ' ' . $user[0]['last_name']);
                        echo '</td>
                        <td>' . $value['subject'] . '</td>
                        <td>&nbsp&nbsp&nbsp' . $value['body'] . '</td>
                        <td>' . $value['created_datetime'] . '</td>
                        <td>';
                        echo "<div id='your-form-block-id'>";
                        echo CHtml::beginForm();
                        echo CHtml::link('Reply', array('admin/message/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                        echo CHtml::endForm();
                        echo "</div>";
                        echo '</td>
                        <td>
                            <a title ="Click change to status Read" href="' . CController::createUrl('admin/message/sa/message_status/s/Unread/id/' . $value['id']) . '">' . $value['status'] . '</a>
                        </td>
                    <tr>';
                    }
                    $odd = !$odd;
                }
            } else {

                echo '<tr class="odd">
                        <td colspan="6" style="text-align: center;">No Message Available</td>
                      <tr>';
            }
            ?>
        </table>
    </div>  
</div>

<br />
<?php echo CHtml::form(array('admin/message/sa/addmessage'), 'post'); ?>            
<table class='users'>
    <tr class='oddrow'>
        <th><?php $clang->eT("Add New Message:"); ?></th>
        <td style='width:20%'>
            <?php
            $user = "Select * from {{view_panel_list_master}} where status = 'E'";
            $uresult = Yii::app()->db->createCommand($user)->query()->readAll();
            $userlist = CHtml::listData($uresult, 'panel_list_id', 'full_name');
            echo CHtml::dropDownList('email_to', '0', $userlist, array(
                'prompt' => 'Please Select Panellist',
                'required' => true
            ));
            ?>
        </td>
        <td style='width:20%'>
            <input type='text' maxlength="50" name='subject' placeholder="Subject" required=""/>
        </td>
        <td style='width:20%'>
            <textarea name="message" id="message" required=""></textarea>
        </td>
        <td style='width:15%'>
            <input type="hidden" name="chat" value="1"/>
            <input type='hidden' name='action' value='addmessage' />
            <input type='submit' value='<?php $clang->eT("Save"); ?>' />
        </td>
    </tr>
</table>
</form>