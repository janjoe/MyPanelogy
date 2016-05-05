<section class="container w90_per">
    <div class="box w98_per effect7">
        <h3>Available Rewards</h3>
        <p style="display: inline-block">
        <table class="InfoForm" style="width: 95%; margin: 0px auto;">
            <tr>
                <th>Reward</th>
                <th>Status</th>
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
                            <td>available</td>
                        </tr>';
                    }
                }
            } else {
                echo '<tr class="odd"><td colspan="2">No Reward Available</td></tr>';
            }
            ?>
        </table>
    </div>
</section>