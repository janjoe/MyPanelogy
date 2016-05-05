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
                    <td><input name="subject" id="subject" type="text"/></td>
                </tr>
                <tr>
                    <td><label for="message">Message</label></td>
                    <td><textarea name="message" id="message" rows="15" cols="50" required style="margin: 5px; height: 25px; width: 350px;"></textarea></td>
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
            <!--<h3>Read Message</h3> //20/06/2014 Remove By Hari -->
            <h3>All Messages</h3><!-- 20/06/2014 Add By Hari -->
            <p style="display: inline-block">
            <table class="InfoForm" style="width: 95%; margin: 0px auto; display:none;">
                <tr>
                    <th>Id</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Time</th>
                    <th>Sent/ Receive</th><!--Add by Parth 14-06-2014-->
                    <th>View Detail</th>
                    <th>Status</th>
                </tr>
                <?php
                //$msglist = messagePanellist($_SESSION['plid'], 'Unread');
                //$sql = "SELECT * FROM {{view_email_message}} WHERE (email_to = '" . $_SESSION['plid'] . "' OR email_from = '" . $_SESSION['plid'] . "') and status = 'Unread' ";
                $sql = "SELECT * FROM {{view_read_emails}} WHERE (email_to = '" . $_SESSION['plid'] . "' OR email_from = '" . $_SESSION['plid'] . "') order by created_datetime desc";  //Add by Parth 14-06-2014
                $msglist = Yii::app()->db->createCommand($sql)->query()->readAll();
                $odd = FALSE;
                $id = '';
                $rc = 0; // Add by Parth & Hari 14-06-2014
                if (count($msglist) > 0) {
                    foreach ($msglist as $key => $value) {
                        $rec = true; // Add by Parth & Hari 14-06-2014
                        if ($value['type'] == 'Parent') {
                            $cls = 'class="odd"';
                            echo '<tr ' . $cls . ' style="font-weight:bold;">
                        <td>' . $value['id'] . '</td>
                        <td>' . $value['subject'] . '</td>
                        <td>' . $value['body'] . '</td>
                        <td>' . $value['created_datetime'] . '</td>
                        <td>';   //Start by Parth 14-06-2014
                            if ($value['sender'] == 'B' && $value['email_from'] == $_SESSION['plid']) {
                                $rc = $rc + 1;
                                echo "Recevied";
                            } else {
                                $rec = false;
                                echo "Sent";
                            }

                            echo '</td>   
                        <td>';
                            echo "<div id='your-form-block-id'>";
                            echo CHtml::beginForm();
                            echo CHtml::link('Reply', array('/pl/home/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                            echo CHtml::endForm();
                            echo "</div>";
                            echo '</td>
                        <td>';
                            if ($rec) {
                                echo '<a title ="Click change to status Read" href="' . CController::createUrl('pl/home/sa/message_status/s/Unread/id/' . $value['id']) . '">' . $value['status'] . '</a>';
                            }
                            echo '</td>
                <tr>';  //End by Parth 14-06-2014
                        } else {
                            $cls = 'class="even"';
                            echo '<tr ' . $cls . '>
                    <td>' . $value['id'] . '</td>
                    <td>' . $value['subject'] . '</td>
                    <td>&nbsp&nbsp&nbsp' . $value['body'] . '</td>
                    <td>' . $value['created_datetime'] . '</td>
                    <td>';  //Start by Parth 14-06-2014
                            if ($value['sender'] == 'B' && $value['email_from'] == $_SESSION['plid']) {
                                $rc = $rc + 1;
                                echo "Recevied";
                            } else {
                                $rec = false;
                                echo "Sent";
                            }
                            echo '</td>
                    <td>';
                            echo "<div id='your-form-block-id'>";
                            echo CHtml::beginForm();
                            echo CHtml::link('Reply', array('/pl/home/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                            echo CHtml::endForm();
                            echo "</div>";
                            echo '</td>
                    <td>';
                            if ($rec) {
                                echo '<a title ="Click change to status Read" href="' . CController::createUrl('pl/home/sa/message_status/s/Unread/id/' . $value['id']) . '">' . $value['status'] . '</a>';
                            }
                            echo '</td>
                <tr>'; //End by Parth 14-06-2014
                        }
                        $odd = !$odd;
                    }
                } else {

                    echo '<tr class="odd">
                    <td colspan="7" style="text-align: center;">No Message Available</td>
                <tr>';
                }
                echo '<tr class="odd" style="display:none;">
                    <td colspan="7" style="text-align: center;">' . $rc . '</td><tr>';
                ?>
            </table>
            <?php
            //21/06/2014 Add BY Hari
            //$ReadStatus = "Read";
            //include 'application\controllers\EmailControl.php';
            //$ReadStatus = "UnRead";
            //include 'application\controllers\EmailControl.php';
            $User = "Panellist";
            $ReadStatus = "All";
            //include 'application/controllers/EmailControl.php';
            include 'application/controllers/EmailController.php';
            //21/06/2014 End
            ?>
            
            </p>
        </div>
    </section>
</div>