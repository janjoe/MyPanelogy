<?php
//Page Created By Hari
if ($User == "Admin") {
    if (isset($ReadStatus)) {
        if ($ReadStatus == "All") {
            ?>
            <table class="InfoForm" style="width: 95%; margin: 0px auto;">
                <tr>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>View Detail</th>
                    <th>Time</th>
                    <th>Send/Received</th>
                </tr>
                <?php
                $ParentSql = "SELECT x.*, IFNULL(pm.subject,' ') AS parent_subject, IFNULL(pm.body,' ') AS parent_body 
                            FROM (
                            SELECT em.*
                            , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
                            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
                            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
                            FROM `{{email_message}}` em
                            ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id
                            WHERE (x.email_to = '" . $_SESSION['loginID'] . "' OR x.email_from = '" . $_SESSION['loginID'] . "')
                            AND x.parent='0'
                            ORDER BY created_datetime DESC";
                $ParentList = Yii::app()->db->createCommand($ParentSql)->query()->readAll();
                //$ParentList = EmailMessage("True", $_SESSION['loginID'], "All");
                if (count($ParentList) > 0) {
                    foreach ($ParentList as $key => $value) {
                        $pItalic = "";
                        $pSendRecv = "";
                        $pIsRecv = FALSE;
                        if ($value['sender'] == 'P' && $value['email_from'] == $_SESSION['loginID']) {
                            $pIsRecv = TRUE;
                            $pItalic = "font-style: italic;";
                            $pSendRecv = "Recevied";
                        } else {
                            $pSendRecv = "Sent";
                        }
                        if ($value['status'] == "Unread") {
                            $pUnReadClr = "style='color:orange; text-decoration:none;'";
                            $Title = "Click change to status Read";
                            $Href = "admin/message/sa/message_status/s/Read/id/" . $value['id'];
                        } else {
                            $pUnReadClr = "style='color:green;text-decoration:none;'";
                            $Title = "Click change to status Unread";
                            $Href = "admin/message/sa/message_status/s/Unread/id/" . $value['id'];
                        }
                        echo "<tr class='even' style='font-weight:bold;" . $pItalic . "'>";
                        echo "<td>" . $value['subject'] . "</td>";
                        echo "<td>" . $value['body'] . "</td>";
                        echo "<td " . $pUnReadClr . ">";
                        if ($pIsRecv == TRUE) {
                            //echo "<a $cUnReadClr title ='" . $Title . "' href='" . CController::createUrl($Href) . "'>" . $value['chain_status'] . "</a>";
                            echo $value['status'];
                        }
                        echo "</td>";
                        echo "<td>";
                        echo "<div id='your-form-block-id'>";
                        echo CHtml::beginForm();
                        echo CHtml::link('Reply', array('admin/message/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                        echo CHtml::endForm();
                        echo "</div>";
                        echo "</td>";
                        echo "<td>" . $value['created_datetime'] . "</td>";
                        echo "<td>" . $pSendRecv . "</td>";
                        echo "</tr>";

                        $ChildSql = "SELECT x.*, IFNULL(pm.subject,' ') AS parent_subject, IFNULL(pm.body,' ') AS parent_body 
                                FROM (
                                SELECT em.*
                                , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
                                , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
                                , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
                                FROM `{{email_message}}` em
                                ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id
                                WHERE (x.email_to = '" . $_SESSION['loginID'] . "' OR x.email_from = '" . $_SESSION['loginID'] . "') 
                                AND x.parent='" . $value['id'] . "'
                                ORDER BY created_datetime DESC";
                        $ChildList = Yii::app()->db->createCommand($ChildSql)->query()->readAll();
                        //$ChildList = EmailMessage("False", $_SESSION['loginID'], "All", $value['id']);
                        if (count($ChildList) > 0) {
                            foreach ($ChildList as $key => $value) {
                                $cItalic = "";
                                $cSendRecv = "";
                                $cIsRecv = FALSE;
                                $UnReadClr = "";
                                if ($value['sender'] == 'P' && $value['email_to'] == $_SESSION['loginID']) {
                                    $cIsRecv = TRUE;
                                    $cItalic = "font-style:italic;";
                                    $cSendRecv = "Recevied";
                                } else {
                                    $cSendRecv = "Sent";
                                }
                                if ($value['status'] == "Unread") {
                                    $cUnReadClr = "style='color:orange;text-decoration:none;'";
                                    $Title = "Click change to status Read";
                                    $Href = "admin/message/sa/message_status/s/Read/id/" . $value['id'];
                                } else {
                                    $cUnReadClr = "style='color:green;text-decoration:none;'";
                                    $Title = "Click change to status Unread";
                                    $Href = "admin/message/sa/message_status/s/Unread/id/" . $value['id'];
                                }
                                echo "<tr class='even' style='" . $cItalic . "'>";
                                echo "<td>" . $value['subject'] . "</td>";
                                echo "<td>" . $value['body'] . "</td>";
                                echo "<td " . $cUnReadClr . ">";
                                if ($cIsRecv == TRUE) {
                                    //echo "<a $cUnReadClr title ='" . $Title . "' href='" . CController::createUrl($Href) . "'>" . $value['chain_status'] . "</a>";
                                    echo $value['status'];
                                }
                                echo "</td>";
                                echo "<td>" . $value['created_datetime'] . "</td>";
                                echo "<td>" . $cSendRecv . "</td>";
                                echo "</tr>";
                            }
                        }
                    }
                } else {
                    echo '<tr>
                        <td colspan="5" style="text-align: center;">No Message Available</td>
                      <tr>';
                }
                ?>
            </table>
        <?php } else { ?>
            <table class="InfoForm" style="width: 95%; margin: 0px auto;">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Time</th>
                    <th>View Detail</th>
                    <th>Status</th>
                    <th>Send/Received</th>
                </tr>
                <?php
                $ParentSql = "SELECT x.*, IFNULL(pm.subject,' ') AS parent_subject, IFNULL(pm.body,' ') AS parent_body 
                            FROM (
                            SELECT em.*
                            , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
                            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
                            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
                            FROM `{{email_message}}` em
                            ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id
                            WHERE (x.email_from = " . $_SESSION['loginID'] . " OR x.email_to = " . $_SESSION['loginID'] . ") 
                            AND x.parent='0' AND tot_childs<>0 AND x.chain_status='" . $ReadStatus . "'
                            UNION ALL
                            SELECT x.*, IFNULL(pm.subject,' ') AS parent_subject, IFNULL(pm.body,' ') AS parent_body 
                            FROM (
                            SELECT em.*
                            , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
                            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
                            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
                            FROM `{{email_message}}` em
                            ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id
                            WHERE (x.email_from = " . $_SESSION['loginID'] . " OR x.email_to = " . $_SESSION['loginID'] . ") 
                            AND x.parent='0' AND tot_childs=0 AND x.chain_status='" . $ReadStatus . "'
                            ORDER BY created_datetime DESC";
                $ParentList = Yii::app()->db->createCommand($ParentSql)->query()->readAll();
                if (count($ParentList) > 0) {
                    foreach ($ParentList as $key => $value) {
                        $pItalic = "";
                        $pSendRecv = "";
                        $pIsRecv = FALSE;
                        if ($value['sender'] == 'P' && $value['email_to'] == $_SESSION['loginID']) {
                            $pIsRecv = TRUE;
                            $pItalic = "font-style: italic;";
                            $pSendRecv = "Recevied";
                        } else {
                            $pSendRecv = "Sent";
                        }
                        if ($value['status'] == "Unread") {
                            $pUnReadClr = "style='color:orange;text-decoration:none;'";
                            $pTitle = "Click change to status Read";
                            $pHref = "admin/message/sa/message_status/s/Read/id/" . $value['id'];
                        } else {
                            $pUnReadClr = "style='color:green;text-decoration:none;'";
                            $pTitle = "Click change to status Unread";
                            $pHref = "admin/message/sa/message_status/s/Unread/id/" . $value['id'];
                        }
                        $user = PL::model()->findAll(array("condition" => "email = '" . $value['email_id'] . "' "));
                        $ChildSql = "SELECT x.*, IFNULL(pm.subject,' ') AS parent_subject, IFNULL(pm.body,' ') AS parent_body 
                                FROM (
                                SELECT em.*
                                , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
                                , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
                                , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
                                FROM `{{email_message}}` em
                                ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id
                                WHERE (x.email_to = '" . $_SESSION['loginID'] . "' OR x.email_from = '" . $_SESSION['loginID'] . "') 
                                AND x.parent='" . $value['id'] . "' AND x.status='" . $ReadStatus . "'
                                ORDER BY created_datetime DESC";
                        $ChildList = Yii::app()->db->createCommand($ChildSql)->query()->readAll();
                        echo "<tr class='even' style='font-weight:bold;" . $pItalic . "'>";
                        echo "<td>" . $value['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($user[0]['first_name'] . ' ' . $user[0]['last_name']) . "</td>";
                        echo "<td>" . $value['email_id'] . "</td>";
                        echo "<td>" . $value['subject'] . "</td>";
                        echo "<td>" . $value['body'] . "</td>";
                        echo "<td>" . $value['created_datetime'] . "</td>";
                        echo "<td>";
                        echo "<div id='your-form-block-id'>";
                        echo CHtml::beginForm();
                        echo CHtml::link('Reply', array('admin/message/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                        echo CHtml::endForm();
                        echo "</div>";
                        echo "</td>";
                        echo "<td " . $pUnReadClr . ">";
                        if ($pIsRecv) {
                            if (count($ChildList) > 0) {
                                //echo $value['status'];
                            } else {
                                echo "<a $pUnReadClr title ='" . $pTitle . "' href='" . CController::createUrl($pHref) . "'>" . $value['status'] . "</a>";
                            }
                        }
                        echo "</td>";
                        echo "<td>" . $pSendRecv . "</td>";
                        echo "</tr>";
                        if (count($ChildList) > 0) {
                            foreach ($ChildList as $key => $value) {
                                $cItalic = "";
                                $cSendRecv = "";
                                $cIsRecv = FALSE;
                                $UnReadClr = "";
                                if ($value['sender'] == 'P' && $value['email_to'] == $_SESSION['loginID']) {
                                    $cIsRecv = TRUE;
                                    $cItalic = "font-style:italic;";
                                    $cSendRecv = "Recevied";
                                } else {
                                    $cSendRecv = "Sent";
                                }
                                if ($value['status'] == "Unread") {
                                    $cUnReadClr = "style='color:orange;text-decoration:none;'";
                                    $Title = "Click change to status Read";
                                    $Href = "admin/message/sa/message_status/s/Read/id/" . $value['id'];
                                } else {
                                    $cUnReadClr = "style='color:green;text-decoration:none;'";
                                    $Title = "Click change to status Unread";
                                    $Href = "admin/message/sa/message_status/s/Unread/id/" . $value['id'];
                                }
                                $user = PL::model()->findAll(array("condition" => "email = '" . $value['email_id'] . "' "));
                                echo "<tr class='even' style='" . $cItalic . "'>";
                                echo "<td>" . $value['id'] . "</td>";
                                echo "<td>" . htmlspecialchars($user[0]['first_name'] . ' ' . $user[0]['last_name']) . "</td>";
                                echo "<td>" . $value['email_id'] . "</td>";
                                echo "<td>" . $value['subject'] . "</td>";
                                echo "<td>" . $value['body'] . "</td>";
                                echo "<td>" . $value['created_datetime'] . "</td>";
                                echo "<td>";
                                echo "<div id='your-form-block-id'>";
                                echo CHtml::beginForm();
                                echo CHtml::link('Reply', array('/pl/home/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                                echo CHtml::endForm();
                                echo "</div>";
                                echo "</td>";
                                echo "<td " . $cUnReadClr . ">";
                                if ($cIsRecv == TRUE) {
                                    echo "<a $cUnReadClr title ='" . $Title . "' href='" . CController::createUrl($Href) . "'>" . $value['status'] . "</a>";
                                }
                                echo "</td>";
                                echo "<td>" . $cSendRecv . "</td>";
                                echo "</tr>";
                            }
                        }
                    }
                } else {
                    echo '<tr>
                        <td colspan="5" style="text-align: center;">No Message Available</td>
                      <tr>';
                }
                ?>
            </table>
            <?php
        }
    }
} elseif ($User == "Panellist") {
    if (isset($ReadStatus)) {
        if ($ReadStatus == "All") {
            ?>
            <table class="InfoForm" style="width: 95%; margin: 0px auto;">
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Time</th>
                    <th>View Detail</th>
                    <th>Status</th>
                    <th>Send/Received</th>
                </tr>
                <?php
                //WHERE (x.sender='P' and x.email_from = " . $_SESSION['plid'] . ") OR (x.sender='B' and x.email_to = " . $_SESSION['plid'] . ")
                //WHERE (x.email_from = " . $_SESSION['plid'] . " OR x.email_to = " . $_SESSION['plid'] . ")
                $ParentSql = "SELECT x.*, IFNULL(pm.subject,' ') AS parent_subject, IFNULL(pm.body,' ') AS parent_body 
                            FROM (
                            SELECT em.*
                            , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
                            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
                            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
                            FROM `{{email_message}}` em
                            ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id
                            WHERE (x.email_from = " . $_SESSION['plid'] . " OR x.email_to = " . $_SESSION['plid'] . ")
                            AND x.parent='0' 
                            ORDER BY created_datetime DESC";
                $ParentList = Yii::app()->db->createCommand($ParentSql)->query()->readAll();
                if (count($ParentList) > 0) {
                    foreach ($ParentList as $key => $value) {
                        $pItalic = "";
                        $pSendRecv = "";
                        $pIsRecv = FALSE;
                        if ($value['sender'] == 'B' && $value['email_to'] == $_SESSION['plid']) {
                            $pIsRecv = TRUE;
                            $pItalic = "font-style: italic;";
                            $pSendRecv = "Recevied";
                        } else {
                            $pSendRecv = "Sent";
                        }
                        if ($value['status'] == "Unread") {
                            $pUnReadClr = "style='color:orange; text-decoration:none;'";
                            $Title = "Click change to status Read";
                            $Href = "pl/home/sa/message_status/s/Read/id/" . $value['id'];
                        } else {
                            $pUnReadClr = "style='color:green;text-decoration:none;'";
                            $Title = "Click change to status Unread";
                            $Href = "pl/home/sa/message_status/s/Unread/id/" . $value['id'];
                        }
                        echo "<tr class='even' style='font-weight:bold;" . $pItalic . "'>";
                        echo "<td>" . $value['id'] . "</td>";
                        echo "<td>" . $value['subject'] . "</td>";
                        echo "<td>" . $value['body'] . "</td>";
                        echo "<td>" . $value['created_datetime'] . "</td>";
                        echo "<td>";
                        echo "<div id='your-form-block-id'>";
                        echo CHtml::beginForm();
                        echo CHtml::link('Reply', array('/pl/home/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                        echo CHtml::endForm();
                        echo "</div>";
                        echo "</td>";
                        echo "<td " . $pUnReadClr . ">";
                        if ($pIsRecv == TRUE) {
                            //echo "<a $cUnReadClr title ='" . $Title . "' href='" . CController::createUrl($Href) . "'>" . $value['chain_status'] . "</a>";
                            echo $value['status'];
                        }
                        echo "</td>";
                        echo "<td>" . $pSendRecv . "</td>";
                        echo "</tr>";

                        $ChildSql = "SELECT x.*, IFNULL(pm.subject,' ') AS parent_subject, IFNULL(pm.body,' ') AS parent_body 
                                FROM (
                                SELECT em.*
                                , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
                                , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
                                , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
                                FROM `{{email_message}}` em
                                ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id
                                WHERE (x.email_to = '" . $_SESSION['plid'] . "' OR x.email_from = '" . $_SESSION['plid'] . "') 
                                AND x.parent='" . $value['id'] . "'
                                ORDER BY created_datetime DESC";
                        $ChildList = Yii::app()->db->createCommand($ChildSql)->query()->readAll();
                        if (count($ChildList) > 0) {
                            foreach ($ChildList as $key => $value) {
                                $cItalic = "";
                                $cSendRecv = "";
                                $cIsRecv = FALSE;
                                $UnReadClr = "";
                                if ($value['sender'] == 'B' && $value['email_to'] == $_SESSION['plid']) {
                                    $cIsRecv = TRUE;
                                    $cItalic = "font-style:italic;";
                                    $cSendRecv = "Recevied";
                                } else {
                                    $cSendRecv = "Sent";
                                }
                                if ($value['status'] == "Unread") {
                                    $cUnReadClr = "style='color:orange;text-decoration:none;'";
                                    $Title = "Click change to status Read";
                                    $Href = "pl/home/sa/message_status/s/Read/id/" . $value['id'];
                                } else {
                                    $cUnReadClr = "style='color:green;text-decoration:none;'";
                                    $Title = "Click change to status Unread";
                                    $Href = "pl/home/sa/message_status/s/Unread/id/" . $value['id'];
                                }
                                echo "<tr class='even' style='" . $cItalic . "'>";
                                echo "<td>" . $value['id'] . "</td>";
                                echo "<td>" . $value['subject'] . "</td>";
                                echo "<td>" . $value['body'] . "</td>";
                                echo "<td>" . $value['created_datetime'] . "</td>";
                                echo "<td>";
                                echo "<div id='your-form-block-id'>";
                                echo CHtml::beginForm();
                                echo CHtml::link('Reply', array('/pl/home/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                                echo CHtml::endForm();
                                echo "</div>";
                                echo "</td>";
                                echo "<td " . $cUnReadClr . ">";
                                if ($cIsRecv == TRUE) {
                                    //echo "<a $cUnReadClr title ='" . $Title . "' href='" . CController::createUrl($Href) . "'>" . $value['chain_status'] . "</a>";
                                    echo $value['status'];
                                }
                                echo "</td>";
                                echo "<td>" . $cSendRecv . "</td>";
                                echo "</tr>";
                            }
                        }
                    }
                } else {
                    echo '<tr>
                        <td colspan="5" style="text-align: center;">No Message Available</td>
                      <tr>';
                }
                ?>
            </table>
        <?php } else { ?>
            <table class="InfoForm" style="width: 95%; margin: 0px auto;">
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Time</th>
                    <th>View Detail</th>
                    <th>Status</th>
                    <th>Send/Received</th>
                </tr>
                <?php
                $ParentSql = "SELECT x.*, IFNULL(pm.subject,' ') AS parent_subject, IFNULL(pm.body,' ') AS parent_body 
                            FROM (
                            SELECT em.*
                            , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
                            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
                            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
                            FROM `{{email_message}}` em
                            ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id
                            WHERE (x.email_to = '" . $_SESSION['plid'] . "' OR x.email_from = '" . $_SESSION['plid'] . "') 
                            AND x.status='" . $ReadStatus . "'
                            ORDER BY created_datetime DESC";
                $ParentList = Yii::app()->db->createCommand($ParentSql)->query()->readAll();
                if (count($ParentList) > 0) {
                    foreach ($ParentList as $key => $value) {
                        $Italic = "";
                        $SendRecv = "";
                        $IsRecv = FALSE;
                        if ($value['sender'] == 'B' && $value['email_to'] == $_SESSION['plid']) {
                            $IsRecv = TRUE;
                            $Italic = "font-style: italic;";
                            $SendRecv = "Recevied";
                        } else {
                            $SendRecv = "Sent";
                        }
                        if ($value['status'] == "Unread") {
                            $UnReadClr = "style='color:orange;text-decoration:none;'";
                            $Title = "Click change to status Read";
                            $Href = "pl/home/sa/message_status/s/Read/id/" . $value['id'];
                        } else {
                            $UnReadClr = "style='color:green;text-decoration:none;'";
                            $Title = "Click change to status Unread";
                            $Href = "pl/home/sa/message_status/s/Unread/id/" . $value['id'];
                        }
                        if ($IsRecv) {
                            echo "<tr class='even' style='font-weight:bold;" . $Italic . "'>";
                            echo "<td>" . $value['id'] . "</td>";
                            echo "<td>" . $value['subject'] . "</td>";
                            echo "<td>" . $value['body'] . "</td>";
                            echo "<td>" . $value['created_datetime'] . "</td>";
                            echo "<td>";
                            echo "<div id='your-form-block-id'>";
                            echo CHtml::beginForm();
                            echo CHtml::link('Reply', array('/pl/home/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                            echo CHtml::endForm();
                            echo "</div>";
                            echo "</td>";
                            echo "<td " . $UnReadClr . ">";
                            if ($IsRecv == TRUE) {
                                echo "<a $UnReadClr title ='" . $Title . "' href='" . CController::createUrl($Href) . "'>" . $value['status'] . "</a>";
                                //echo $value['chain_status'];
                            }
                            echo "</td>";
                            echo "<td>" . $SendRecv . "</td>";
                            echo "</tr>";
                        }
                    }
                } else {
                    echo '<tr>
                        <td colspan="5" style="text-align: center;">No Message Available</td>
                      <tr>';
                }
                ?>
            </table>
            <?php
        }
    }
}
?>