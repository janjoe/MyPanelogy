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
    if ($User == "Admin") {
        $UserID = $_SESSION['loginID'];
    } elseif ($User == "Panellist") {
        $UserID = $_SESSION['plid'];
    }
    $sql = "SELECT x.*, IFNULL(pm.subject, ' ') AS parent_subject, IFNULL(pm.body, ' ') AS parent_body, IFNULL(pm.email_id, ' ') AS parent_email_id , pm.created_datetime parent_datetime
            FROM (
            SELECT em.*
            , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
            , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
            FROM `{{email_message}}` em
            ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id ";
    if ($User == "Admin") {
        $sql .= " WHERE ( (x.sender='B' and x.email_from = " . $UserID . ") OR (x.sender='P' and x.email_to = " . $UserID . ") ) ";
    } elseif ($User == "Panellist") {
        $sql .= " WHERE ( (x.sender='P' and x.email_from = " . $UserID . ") OR (x.sender='B' and x.email_to = " . $UserID . ") ) ";
    }
    if ($ReadStatus <> "All") {
        $sql .= " AND x.status='" . $ReadStatus . "'";
    }

    //$sql .= " AND x.id NOT IN (SELECT parent FROM {{email_message}} WHERE IFNULL(parent,0)<>0 GROUP BY parent) ";
    //$sql .= " ORDER BY pm.created_datetime,created_datetime DESC";

    $sql .= " ORDER BY created_datetime DESC";
    //echo $sql;

    $ParentList = Yii::app()->db->createCommand($sql)->query()->readAll();
    if (count($ParentList) > 0) {
        $preParBody = '';
        foreach ($ParentList as $key => $value) {
            //if parent found then add an extra parent rown
            /*
              if ($preParBody != $value['parent_body'] && $value['parent_body'] != ' ') {
              echo "<tr style='font-weight:bold;'>";
              echo "<td></td>";
              echo "<td></td>";
              echo "<td>" . $value['parent_email_id'] . "</td>";
              echo "<td>" . $value['parent_subject'] . "</td>";
              echo "<td>" . $value['parent_body'] . "</td>";
              echo "<td></td>";
              echo "<td></td>";
              echo "<td></td>";
              echo "<td></td>";
              //echo "<td colspan='6'>Parent Email: " . $value['parent_body'] . "</td>";
              echo "</tr>";
              } */
            //end parent print

            /* if ($value['parent'] > 0)
              $space = "&nbsp;&nbsp;&nbsp;";
              else
              $space=""; */

            $IsRecv = "FALSE";
            $Italic = "";
            $Sender = "";
            $Email_to = $UserID;
            $IsRecvStyle = '';

            if ($User == "Admin") {
                $Sender = 'P';
            } elseif ($User == "Panellist") {
                $Sender = 'B';
            }

            if ($value['sender'] == $Sender && $value['email_to'] == $Email_to) {
                $IsRecv = "TRUE";
                $Italic = "font-style: italic;";
                $SendRecv = "Recevied";
            } else {
                $SendRecv = "Sent";
            }

            if ($value['status'] == "Unread") {
                $UnReadClr = "style='color:orange;text-decoration:none;'";
                $Title = "Click change to status Read";
                if ($User == "Admin") {
                    $Href = "admin/message/sa/message_status/s/Read/id/" . $value['id'];
                } elseif ($User == "Panellist") {
                    $Href = "pl/home/sa/message_status/s/Read/id/" . $value['id'];
                }
            } else {
                $UnReadClr = "style='color:green;text-decoration:none;'";
                $Title = "Click change to status Unread";
                if ($User == "Admin") {
                    $Href = "admin/message/sa/message_status/s/Unread/id/" . $value['id'];
                } elseif ($User == "Panellist") {
                    $Href = "pl/home/sa/message_status/s/Unread/id/" . $value['id'];
                }
            }

            //$plUser = PL::model()->findAll(array("condition" => "email = '" . $value['email_id'] . "' "));
            //$first_name = $plUser[0]['first_name'];
            //$last_name = $plUser[0]['last_name'];
            //printing child rows

            if ($IsRecv == "FALSE" && $User == "Panellist" && $ReadStatus == "Unread") {
                continue;
            }

            if ($IsRecv == "TRUE") {
                echo "<tr class='odd' style='" . $Italic . "'>";
            } else {
                echo "<tr class='even'>";
            }
            echo "<td>" . $value['id'] . "</td>";
            echo "<td>" . $value['email_id'] . "</td>";
            echo "<td>" . $value['subject'] . "</td>";
            echo "<td>" . $value['body'] . "</td>";
            echo "<td>" . $value['created_datetime'] . "</td>";
            echo "<td>";
            if ($IsRecv == "TRUE") {
                echo "<div id='your-form-block-id'>";
                echo CHtml::beginForm();
                if ($User == "Admin") {
                    echo CHtml::link('Reply', array('admin/message/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                } elseif ($User == "Panellist") {
                    echo CHtml::link('Reply', array('/pl/home/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
                }
                echo CHtml::endForm();
                echo "</div>";
            }
            echo "</td>";
            echo "<td " . $UnReadClr . ">";
            if ($IsRecv == "TRUE") {
                echo "<a $UnReadClr title ='" . $Title . "' href='" . CController::createUrl($Href) . "'>" . $value['status'] . "</a>";
            }
            echo "</td>";
            echo "<td>" . $SendRecv . "</td>";
            echo "</tr>";
            //end child over

            $preParBody = $value['parent_body'];
        }// for
    } else {
        echo "<tr class='even'>
                  <td colspan='8' style='text-align: center;'>No Message Available</td>
              <tr>";
    }
    //if count

    /*
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
      if ($User == "Admin") {
      $UserID = $_SESSION['loginID'];
      } elseif ($User == "Panellist") {
      $UserID = $_SESSION['plid'];
      }
      $sql = "SELECT x.*, IFNULL(pm.subject, ' ') AS parent_subject, IFNULL(pm.body, ' ') AS parent_body
      FROM (
      SELECT em.*
      , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
      , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
      , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
      FROM `{{email_message}}` em
      ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id ";
      if ($User == "Admin") {
      $sql .= " WHERE (x.sender='B' and x.email_from = " . $UserID . ") OR (x.sender='P' and x.email_to = " . $UserID . ") ";
      } elseif ($User == "Panellist") {
      $sql .= " WHERE (x.sender='P' and x.email_from = " . $UserID . ") OR (x.sender='B' and x.email_to = " . $UserID . ") ";
      }

      if (isset($ReadStatus)) {
      $ParentSql = " SELECT x.*, IFNULL(pm.subject,' ') AS parent_subject, IFNULL(pm.body,' ') AS parent_body
      FROM (
      SELECT em.*
      , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
      , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
      , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
      FROM `{{email_message}}` em
      ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id";
      if ($User == "Admin") {
      //$ParentSql .= " WHERE (x.sender='B' and x.email_to = " . $UserID . ") OR (x.sender='P' and x.email_from = " . $UserID. ") ";
      $ParentSql .= " WHERE (x.email_to = " . $UserID . " OR x.email_from = " . $UserID. ") ";
      } elseif ($User == "Panellist") {
      //$ParentSql .= " WHERE (x.sender='P' and x.email_from = " . $UserID . ") OR (x.sender='B' and x.email_to = " . $UserID . ") ";
      $ParentSql .= " WHERE (x.email_to = " . $UserID . " OR x.email_from = " . $UserID. ") ";
      }
      $ParentSql .= " AND x.parent='0' AND tot_childs<>0";
      if ($ReadStatus <> "All") {
      $ParentSql .= " AND x.chain_status='" . $ReadStatus . "'";
      }
      $ParentSql .= " UNION ALL
      SELECT x.*, IFNULL(pm.subject, ' ') AS parent_subject, IFNULL(pm.body, ' ') AS parent_body
      FROM (
      SELECT em.*
      , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
      , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
      , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
      FROM `{{email_message}}` em
      ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id";
      if ($User == "Admin") {
      //$ParentSql .= " WHERE (x.sender='B' and x.email_to = " . $UserID . ") OR (x.sender='P' and x.email_from = " . $UserID . ") ";
      $ParentSql .= " WHERE (x.email_to = " . $UserID . " OR x.email_from = " . $UserID. ") ";
      } elseif ($User == "Panellist") {
      //$ParentSql .= " WHERE (x.sender='P' and x.email_from = " . $UserID . ") OR (x.sender='B' and x.email_to = " . $UserID . ") ";
      $ParentSql .= " WHERE (x.email_to = " . $UserID . " OR x.email_from = " . $UserID. ") ";
      }
      $ParentSql .= " AND x.parent='0' AND tot_childs=0 ";
      if ($ReadStatus <> "All") {
      $ParentSql .= " AND x.chain_status='" . $ReadStatus . "'";
      }
      $ParentSql .= " ORDER BY created_datetime DESC";
      //$ParentList = Yii::app()->db->createCommand($ParentSql)->query()->readAll();
      $sql .= " ORDER BY created_datetime DESC";
      //echo $sql;
      $ParentList = Yii::app()->db->createCommand($sql)->query()->readAll();
      if (count($ParentList) > 0) {
      $preParBody='';
      foreach ($ParentList as $key => $value) {
      //if parent found then add an extra parent rown
      if ($preParBody!=$value['parent_body'] && $value['parent_body']!=''){
      echo "<tr class='odd' style='font-weight:bold;'>";
      echo "<td>".$value['parent']."</td>";
      echo "<td>".$value['email_id']."</td>";
      echo "<td colspan='6'>Parent Email: ".$value['parent_body']."</td>";
      echo "</tr>";
      }
      //end parent print
      $preParBody=$value['parent_body'];
      $pItalic = "";
      $pSendRecv = "";
      $pIsRecv = FALSE;
      $pSender = "";
      $pEmail_to = $UserID;
      if ($User == "Admin") {
      $pSender = 'P';
      } elseif ($User == "Panellist") {
      $pSender = 'B';
      }
      if ($value['sender'] == $pSender && $value['email_to'] == $pEmail_to) {
      $pIsRecv = TRUE;
      $pItalic = "font-style: italic;";
      $pSendRecv = "Recevied";
      } else {
      $pSendRecv = "Sent";
      }
      if ($value['status'] == "Unread") {
      $pUnReadClr = "style='color:orange;text-decoration:none;'";
      $pTitle = "Click change to status Read";
      if ($User == "Admin") {
      $pHref = "admin/message/sa/message_status/s/Read/id/" . $value['id'];
      } elseif ($User == "Panellist") {
      $pHref = "pl/home/sa/message_status/s/Read/id/" . $value['id'];
      }
      } else {
      $pUnReadClr = "style='color:green;text-decoration:none;'";
      $pTitle = "Click change to status Unread";
      if ($User == "Admin") {
      $pHref = "admin/message/sa/message_status/s/Unread/id/" . $value['id'];
      } elseif ($User == "Panellist") {
      $pHref = "pl/home/sa/message_status/s/Unread/id/" . $value['id'];
      }
      }
      $user = PL::model()->findAll(array("condition" => "email = '" . $value['email_id'] . "' "));
      $ChildSql = "SELECT x.*, IFNULL(pm.subject, ' ') AS parent_subject, IFNULL(pm.body, ' ') AS parent_body
      FROM (
      SELECT em.*
      , (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id) AS tot_childs
      , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN 1 ELSE 0 END AS isparent
      , CASE WHEN (SELECT COUNT(*) FROM {{email_message}} WHERE parent=em.id)>0 THEN (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=em.id) ELSE em.STATUS END AS chain_status
      FROM `{{email_message}}` em
      ) AS x LEFT OUTER JOIN {{email_message}} AS pm ON x.parent=pm.id ";
      if ($User == "Admin") {
      $ChildSql .= " WHERE (x.sender='B' and x.email_to = " . $UserID . ") OR (x.sender='P' and x.email_from = " . $UserID . ") ";
      //$ChildSql .= " WHERE (x.email_to = " . $UserID . " OR x.email_from = " . $UserID. ") ";
      } elseif ($User == "Panellist") {
      $ChildSql .= " WHERE (x.sender='P' and x.email_from = " . $UserID . ") OR (x.sender='B' and x.email_to = " . $UserID . ") ";
      //$ChildSql .= " WHERE (x.email_to = " . $UserID . " OR x.email_from = " . $UserID. ") ";
      }
      //$ChildSql .= " AND x.parent='" . $value['id'] . "' ";
      if ($ReadStatus <> "All") {
      $ChildSql .= " AND x.status='" . $ReadStatus . "'";
      }
      $ChildSql .= " ORDER BY created_datetime DESC limit 0,0";
      $ChildList = Yii::app()->db->createCommand($ChildSql)->query()->readAll();
      echo "<tr class='odd' style='font-weight:bold;" . $pItalic . "'>";
      echo "<td>" . $value['id'] . "</td>";
      echo "<td>" . $value['email_id'] . "</td>";
      //echo "<td>" . htmlspecialchars($user[0]['first_name'] . ' ' . $user[0]['last_name']) . " :: <br>" . $value['subject'] . "</td>";
      echo "<td>" . $value['subject'] . "</td>";
      echo "<td>" . $value['body'] . "</td>";
      echo "<td>" . $value['created_datetime'] . "</td>";
      echo "<td>";
      if ($pIsRecv) {
      echo "<div id='your-form-block-id'>";
      echo CHtml::beginForm();
      if ($User == "Admin") {
      echo CHtml::link('Reply', array('admin/message/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
      } elseif ($User == "Panellist") {
      echo CHtml::link('Reply', array('/pl/home/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
      }
      echo CHtml::endForm();
      echo "</div>";
      }
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
      $cSender = "";
      $cEmail_to = $UserID;
      if ($User == "Admin") {
      $cSender = 'P';
      } elseif ($User == "Panellist") {
      $cSender = 'B';
      }
      if ($value['sender'] == $cSender && $value['email_to'] == $cEmail_to) {
      $cIsRecv = TRUE;
      $cItalic = "font-style:italic;
      ";
      $cSendRecv = "Recevied";
      } else {
      $cSendRecv = "Sent";
      }
      if ($value['status'] == "Unread") {
      $cUnReadClr = "style='color:orange;text-decoration:none;'";
      $cTitle = "Click change to status Read";
      if ($User == "Admin") {
      $cHref = "admin/message/sa/message_status/s/Read/id/" . $value['id'];
      } elseif ($User == "Panellist") {
      $cHref = "pl/home/sa/message_status/s/Read/id/" . $value['id'];
      }
      } else {
      $cUnReadClr = "style='color:green;text-decoration:none;'";
      $cTitle = "Click change to status Unread";
      if ($User == "Admin") {
      $cHref = "admin/message/sa/message_status/s/Unread/id/" . $value['id'];
      } elseif ($User == "Panellist") {
      $cHref = "pl/home/sa/message_status/s/Unread/id/" . $value['id'];
      }
      }
      $user = PL::model()->findAll(array("condition" => "email = '" . $value['email_id'] . "' "));
      echo "<tr class='even' style='" . $cItalic . "'>";
      echo "<td>" . $value['id'] . "</td>";
      echo "<td>" . $value['email_id'] . "</td>";
      //echo "<td>" . htmlspecialchars($user[0]['first_name'] . ' ' . $user[0]['last_name']) . " :: <br>" . $value['subject'] . "</td>";
      echo "<td>" . $value['subject'] . "</td>";
      echo "<td>" . $value['body'] . "</td>";
      echo "<td>" . $value['created_datetime'] . "</td>";
      echo "<td>";
      if ($cIsRecv) {
      echo "<div id='your-form-block-id'>";
      echo CHtml::beginForm();
      if ($User == "Admin") {
      echo CHtml::link('Reply', array('admin/message/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
      } elseif ($User == "Panellist") {
      echo CHtml::link('Reply', array('/pl/home/sa/message_history/id/' . $value['id'] . '/email_to/' . $value['email_from'] . '/subject/' . $value['subject']), array('class' => 'class-link'));
      }
      echo CHtml::endForm();
      echo "</div>";
      }
      echo "</td>";
      echo "<td " . $cUnReadClr . ">";
      if ($cIsRecv) {
      echo "<a $cUnReadClr title ='" . $cTitle . "' href='" . CController::createUrl($cHref) . "'>" . $value['status'] . "</a>";
      }
      echo "</td>";
      echo "<td>" . $cSendRecv . "</td>";
      echo "</tr>";
      }
      }
      }
      } else {
      echo "<tr class='even'>
      <td colspan='8' style='text-align: center;'>No Message Available</td>
      <tr>        ";
      }
      }
     */
    ?>
</table>
