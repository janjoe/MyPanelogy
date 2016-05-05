<script>
    function reloadpage(){
        return true;
    }
</script>
<section class="container w90_per">
    <div class="box w98_per effect7">
        <h3>Messages</h3>
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
            $sql = "SELECT * FROM {{view_unread_emails}} WHERE (email_to = '" . $_SESSION['plid'] . "' OR email_from = '" . $_SESSION['plid'] . "')";
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
                            <a title ="Click change to status Read" href="' . CController::createUrl('pl/home/sa/message_status/s/Read/id/' . $value['id']) . '">' . $value['status'] . '</a>
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
                            <a title ="Click change to status Read" href="' . CController::createUrl('pl/home/sa/message_status/s/Read/id/' . $value['id']) . '">' . $value['status'] . '</a>
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