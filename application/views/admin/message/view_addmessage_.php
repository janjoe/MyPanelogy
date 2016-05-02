<div class='header ui-widget-header'><?php $clang->eT("Manage Message"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listunread').dataTable({"sPaginationType": "full_numbers"});
        $('#listread').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>
<div id='tabs'>
    <ul>
        <li><a href='#unread' id="tab-01"><?php $clang->eT("Unread Message"); ?></a></li>
        <li><a href='#read' id="tab-02"><?php $clang->eT("Read Message"); ?></a></li>
    </ul>
    <div id="unread">      
        <table id="listunread" style="width:100%">
            <thead>
                <tr>
                    <th><?php $clang->eT("ID"); ?></th>
                    <th><?php $clang->eT("Subject"); ?></th>
                    <th><?php $clang->eT("Panellist"); ?></th>
                    <th><?php $clang->eT("Message"); ?></th>
                    <th><?php $clang->eT("Time"); ?></th>
                    <th><?php $clang->eT("View Detail"); ?></th>
                    <th><?php $clang->eT("Status"); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < count($msglist); $i++) {
                    $usr = $msglist[$i];
                    if ($usr['status'] == 'Unread') {
                        ?>
                        <tr>
                            <td><?php echo $usr['id']; ?></td>
                            <td><?php echo htmlspecialchars($usr['subject']); ?></td>
                            <td>
                                <?php
                                $user = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $usr['email_from'] . ' '));
                                echo htmlspecialchars($user[0]['first_name'] . ' ' . $user[0]['last_name'])
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($usr['body']); ?></td>
                            <td><?php echo htmlspecialchars($usr['created_datetime']); ?></td>
                            <td>
                                <?php
                                echo "<div id='your-form-block-id'>";
                                echo CHtml::beginForm();
                                echo CHtml::link('Read more', array('admin/message/sa/message_history/id/' . $usr['id'] . '/email_to/' . $usr['email_from'] . '/subject/' . $usr['subject']), array('class' => 'class-link'));
                                echo CHtml::endForm();
                                echo "</div>";
                                ?>
                            </td>
                            <td>
                                <?php
                                echo '<a title ="Click change to status Read" href="' . CController::createUrl('admin/message/sa/message_status/s/Read/id/' . $usr['id']) . '">' . $usr['status'] . '</a>';
                                ?>
                            </td>
                        </tr>
                        <?php
                        $row++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>        
    <div id="read">        
        <table id="listread" style="width:100%">
            <thead>
                <tr>
                    <th><?php $clang->eT("ID"); ?></th>
                    <th><?php $clang->eT("Subject"); ?></th>
                    <th><?php $clang->eT("Panellist"); ?></th>
                    <th><?php $clang->eT("Message"); ?></th>
                    <th><?php $clang->eT("Time"); ?></th>
                    <th><?php $clang->eT("View Detail"); ?></th>
                    <th><?php $clang->eT("Status"); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < count($msglist); $i++) {
                    $usr = $msglist[$i];
                    if ($usr['status'] == 'Read') {
                        ?>
                        <tr>
                            <td><?php echo $usr['id']; ?></td>
                            <td><?php echo htmlspecialchars($usr['subject']); ?></td>
                            <td>
                                <?php
                                $user = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $usr['email_from'] . ' '));
                                echo htmlspecialchars($user[0]['first_name'] . ' ' . $user[0]['last_name'])
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($usr['body']); ?></td>
                            <td><?php echo htmlspecialchars($usr['created_datetime']); ?></td>
                            <td>
                                <?php
                                echo "<div id='your-form-block-id'>";
                                echo CHtml::beginForm();
                                echo CHtml::link('Read more', array('admin/message/sa/message_history/id/' . $usr['id'] . '/email_to/' . $usr['email_from'] . '/subject/' . $usr['subject']), array('class' => 'class-link'));
                                echo CHtml::endForm();
                                echo "</div>";
                                ?>
                            </td>
                            <td>
                                <?php
                                echo '<a title ="Click change to status Unread" href="' . CController::createUrl('admin/message/sa/message_status/s/Unread/id/' . $usr['id']) . '">' . $usr['status'] . '</a>';
                                ?>
                            </td>
                        </tr>
                        <?php
                        $row++;
                    }
                }
                ?>
            </tbody>
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