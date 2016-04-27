<script>
    function reloadpage(){
        return true;
    }
</script>
<section class="container w90_per">
    <div class="box w98_per effect7">
        <h3>Requested Rewards</h3>
        <p style="display: inline-block">
        <table class="InfoForm" style="width: 95%; margin: 0px auto;">
            <tr>
                <th>Reward</th>
                <th>Date Submitted</th>
                <th>Status</th>
            </tr>
            <?php
            $rows = 0;
            $sql = "SELECT * from {{reward_request}} WHERE  panellist_id = " . $_SESSION['plid'];
            $query_details = Yii::app()->db->createCommand($sql)->query()->readAll();
            if (count($query_details) > 0) {
                foreach ($query_details as $key => $value) {
                    $str = '';
                    $status = '';
                    $rows++;
                    if (($rows / 2) == floor($rows / 2)) {
                        $cssclass = 'even';
                    } else {
                        $cssclass = 'odd';
                    }
                    $query_reward = "SELECT title from {{reward_master}} where id = '" . $value['reward_id'] . "'";
                    $resreward = Yii::app()->db->createCommand($query_reward)->queryRow();

                    if ($value['status'] == 0) {
                        $status = 'Pending';
                    } else {
                        $status = 'Completed';
                    }
                    echo '<tr class="' . $cssclass . '">
                        <td>' . $resreward['title'] . '</td>
                        <td>' . $value['date'] . '</td>
                        <td>' . $status . '</td></tr>';
                }
            } else {
                echo '<tr class="odd"><td colspan="3">No Reward Requested</td></tr>';
            }
            ?>
        </table>
        </p>
    </div>

    <div class="box w98_per effect7">
        <?php
        $points_qry = "select balance_points,earn_points from {{panel_list_master}} where panel_list_id  = '" . $_SESSION['plid'] . "'";
        $points_details = Yii::app()->db->createCommand($points_qry)->queryRow();
        ?>
        <h3>
            Available Rewards 
            <span style="float: right;font-size: 18px;margin-right: 10px;">
                <?php
                $sql = 'SELECT SUM(points) AS points FROM {{panellist_project}} WHERE status != \'A\' AND status != \'C\' AND panellist_id = ' . $_SESSION['plid'] . ' GROUP BY panellist_id';
                $result = Yii::app()->db->createCommand($sql)->queryRow();
                if ($result['points'] == '') {
                    $point = 0;
                } else {
                    $point = $result['points'];
                }
                echo '<a class="bittooltip">Total Earned Points: ' . $points_details['earn_points'] . '<span class="classic" style="font-size:15px;">This is the total amount of point you have earned (does not including pending points)</span></a> &nbsp&nbsp
                     <a class="bittooltip">Available Points: ' . $points_details['balance_points'] . '<span class="classic" style="font-size:15px;">The total amount of points you have currently available to redeem rewards</span></a> &nbsp&nbsp
                     <a class="bittooltip" href="' . CController::createUrl('pl/home/sa/surveys') . '">Pending Points: ' . $point . '<span class="classic" style="font-size:15px; width:auto">Points associated with studies where we are awaiting validation of your completed survey before we can release those points to Available Points</span></a>'
                ?>
            </span>
        </h3>
        <p style="display: inline-block">
        <table class="InfoForm" style="width: 95%; margin: 0px auto;">
            <tr>
                <th>Name</th>
                <th>Reward points required for redemption</th>
                <th>Redeem</th>
            </tr>
            <?php
            $row = 0;
            $date = date('Y-m-d');
            $rewarlist = rewardsview('', $date);
            if (count($rewarlist) > 0) {
                foreach ($rewarlist as $val) {
                    if ($val['IsActive'] == 1) {
                        $row++;
                        if (($row / 2) == floor($row / 2)) {
                            $cssclass = 'odd';
                        } else {
                            $cssclass = 'even';
                        }
                        echo '<tr class="' . $cssclass . '">
                            <td>' . $val['title'] . '</td>
                            <td>' . $val['points'] . '</td>
                            <td>';
                        if ($points_details['balance_points'] < $val['points']) {
                            echo '<input type="submit" value="Redeem" disabled="disabled">**Insufficient points';
                        } else {
                            echo "<div id='your-form-block-id'>";
                            echo CHtml::beginForm();
                            echo '<a class = "class-link" href="' . CController::createUrl('pl/home/sa/redeem_box/r_id/' . $val['id'] . '/bal/' . $points_details['balance_points'] . '/red/' . $val['points']) . '"><input type="button" value="Redeem"></a>';
                            echo CHtml::endForm();
                            echo "</div>";
                        }
                        echo '</td></tr>';
                    }
                }
            } else {
                echo '<tr class="odd"><td colspan="2">No Reward Available</td></tr>';
            }
            ?>
        </table>
        </p>
    </div>
</section>