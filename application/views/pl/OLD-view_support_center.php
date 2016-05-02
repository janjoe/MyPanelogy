<script>
    function reloadpage(){
        return true;
    }
</script>
<style>
    table tr, table td{
        padding: 0px;
        margin: 0px;
    }
</style>
<div id="tab-1">
    <section class="container w90_per">
        <div class="box w98_per effect7">
            <h3>Support Center</h3>
            <p style="display: inline-block">
                <?php echo CHtml::form(array('pl/home/sa/support_center'), 'post', array('id' => 'ticket', 'name' => 'ticket')); ?>
            <table style="margin: 0px auto; padding: 0px;">
                <tr>
                    <td><label for="email_to">To</label></td>
                    <td>
                        <?php
                        //$user = User::model()->findAll();
                        $sql = "SELECT * FROM {{user_in_groups}} AS a INNER JOIN {{users}} AS b ON a.uid = b.uid WHERE ugid = " . getGlobalSetting('Project_Manager') . " ORDER BY b.users_name";
                        $user = Yii::app()->db->createCommand($sql)->query()->readAll();
                        $userlist = CHtml::listData($user, 'uid', 'users_name');
                        echo CHtml::dropDownList('email_to', '0', $userlist, array(
                            'prompt' => 'Please Select',
                            'required' => true
                        ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="subject">Subject</label></td>
                    <td><input name="subject" id="subject" type="text" required=""/></td>
                </tr>
                <tr>
                    <td><label for="message">Message</label></td>
                    <td><textarea name="message" id="message" rows="15" cols="50" required style="margin: 5px; height: 150px; width: 350px;"></textarea></td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        <input type="hidden" name="action" value="add"/>
                        <input type="hidden" name="parent" value="0"/>
                        <input type="submit" name="save" value="Save"/>
                    </td>
                    <td>
                        Please prove as much details as possible including any survey numbers.
                    </td>
                </tr>
            </table>
            <?php echo CHtml::endForm(); ?>
            </p>
        </div>

        <div class="box w98_per effect7">
            <h3>Read Message</h3>
            <p style="display: inline-block">
            <table class="InfoForm" style="width: 95%; margin: 0px auto;">
                <tr>
                    <th>Id</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Time</th>
                    <th>View Detail</th>
                    <th>Status</th>
                </tr>
                <?php
                //$msglist = messagePanellist($_SESSION['plid'], 'Unread');
                //$sql = "SELECT * FROM {{view_email_message}} WHERE (email_to = '" . $_SESSION['plid'] . "' OR email_from = '" . $_SESSION['plid'] . "') and status = 'Unread' ";
                $sql = "SELECT * FROM {{view_read_emails}} WHERE (email_to = '" . $_SESSION['plid'] . "' OR email_from = '" . $_SESSION['plid'] . "')";
                $msglist = Yii::app()->db->createCommand($sql)->query()->readAll();
                $odd = FALSE;
                $id = '';
                if (count($msglist) > 0) {
                    foreach ($msglist as $key => $value) {
                        if ($value['type'] == 'Parent') {
                            $cls = 'class="odd"';
                            echo '<tr ' . $cls . '>
                        <td>' . $value['id'] . '</td>
                        <td>' . $value['subject'] . '</td>
                        <td>' . $value['body'] . '</td>
                        <td>' . $value['created_datetime'] . '</td>
                        <td>';
                            echo "<div id='your-form-block-id'>";
                            echo CHtml::beginForm();
                            echo CHtml::link('Reply', array('/pl/home/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                            echo CHtml::endForm();
                            echo "</div>";
                            echo '</td>
                        <td>
                            <a title ="Click change to status Read" href="' . CController::createUrl('pl/home/sa/message_status/s/Unread/id/' . $value['id']) . '">' . $value['status'] . '</a>
                        </td>
                    <tr>';
                        } else {
                            $cls = 'class="even"';
                            echo '<tr ' . $cls . '>
                        <td>' . $value['id'] . '</td>
                        <td>' . $value['subject'] . '</td>
                        <td>&nbsp&nbsp&nbsp' . $value['body'] . '</td>
                        <td>' . $value['created_datetime'] . '</td>
                        <td>';
                            echo "<div id='your-form-block-id'>";
                            echo CHtml::beginForm();
                            echo CHtml::link('Reply', array('/pl/home/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                            echo CHtml::endForm();
                            echo "</div>";
                            echo '</td>
                        <td>
                            <a title ="Click change to status Read" href="' . CController::createUrl('pl/home/sa/message_status/s/Unread/id/' . $value['id']) . '">' . $value['status'] . '</a>
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
            </p>
        </div>
    </section>
</div>