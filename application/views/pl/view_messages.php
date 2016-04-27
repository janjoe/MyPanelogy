<script>
    function reloadpage(){
        return true;
    }
</script>
<section class="container w90_per">
    <div class="box w98_per effect7">
        <h3>Messages</h3>
        <p style="display: inline-block">
            <?php
            //23/06/2014 Add BY Hari
            $ReadStatus = "Unread";
            $User = "Panellist";
            //include 'application/controllers/EmailControl.php';
            include 'application/controllers/EmailController.php';
            //23/06/2014 End
            ?>
        <table class="InfoForm" style="width: 95%; margin: 0px auto;display: none;">
            <tr>
                <th>Id</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Time</th>
                <th>Sent/ Receive</th><!-- Add by Parth 14-06-2014-->
                <th>View Detail</th>
                <th>Status</th>
            </tr>
            <?php
            //$msglist = messagePanellist($_SESSION['plid'], 'Unread');
            //$sql = "SELECT * FROM {{view_email_message}} WHERE (email_to = '" . $_SESSION['plid'] . "' OR email_from = '" . $_SESSION['plid'] . "') and status = 'Unread' ";
            $sql = "SELECT * FROM {{view_unread_emails}} WHERE (email_to = '" . $_SESSION['plid'] . "' OR email_from = '" . $_SESSION['plid'] . "') order by created_datetime desc "; //Add by Parth 14-06-2014
            $msglist = Yii::app()->db->createCommand($sql)->query()->readAll();
            $odd = FALSE;
            $id = '';
            $rc = 0;
            if (count($msglist) > 0) {
                foreach ($msglist as $key => $value) {
                    $rec = true; //Add by Parth & Hari 14-06-2014
                    //19/06/2014 Add BY Parth for Only Recevied Messges
                    if ($value['sender'] == 'B' && $value['email_from'] == $_SESSION['plid']) {
                        //Start If Condition
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
                                echo '<a title ="Click change to status Read" href="' . CController::createUrl('pl/home/sa/message_status/s/Read/id/' . $value['id']) . '">' . $value['status'] . '</a>';
                            }
                            echo '</td>
                    <tr>';   //End by Parth 14-06-2014
                        } else {
                            $cls = 'class="even"';
                            echo '<tr ' . $cls . '>
                        <td>' . $value['id'] . '</td>
                        <td>' . $value['subject'] . '</td>
                        <td>&nbsp&nbsp&nbsp' . $value['body'] . '</td>
                        <td>' . $value['created_datetime'] . '</td>
                        <td>';   //Start Add by Parth & Hari 14-06-2014
                            if ($value['sender'] == 'B' && $value['email_from'] == $_SESSION['plid']) {
                                $rc = $rc + 1;
                                echo "Recevied";
                            } else {
                                $rec = false;
                                echo "Sent";
                            }
                            //Start Add by Parth & Hari 14-06-2014
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
                                echo '<a title ="Click change to status Read" href="' . CController::createUrl('pl/home/sa/message_status/s/Read/id/' . $value['id']) . '">' . $value['status'] . '</a>';
                            }
                            echo '</td>
                    <tr>';
                        }
                        //End If Condition
                    }
                    //19/06/2014 End
                    $odd = !$odd;
                }
            } else {

                echo '<tr class="odd">
                        <td colspan="7" style="text-align: center;">No Message Available</td>
                      <tr>';
            }
            echo '<tr class="odd" style="display:none">
                        <td colspan="7" style="text-align: center;">' . $rc . '</td><tr>';
            ?>
        </table>
        </p>
    </div>
</section>