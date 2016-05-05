<section class="container w45_per" style="margin: 0px auto; min-height: 50px;">
    <div class="box w98_per effect7">
        <h3>Points Detail</h3>
        <p style="display: inline-block">
        <table class="InfoForm" style="width: 95%; margin: 0px auto;">

            <?php
            $sql = PL::model()->findAll(array('condition' => 'panel_list_id = ' . $_SESSION['plid'] . ''));
            //print_r($sql);
            echo '<tr class = "even"><td><h5>Earn Points = ' . $sql[0]['earn_points'] . '</h5></td></tr>';
            echo '<tr class = "even"><td><h5>Balance Points = ' . $sql[0]['balance_points'] . '</h5></td></tr>';
            $sql = 'SELECT SUM(points) AS points FROM {{panellist_project}} WHERE status != \'A\' AND status != \'C\' AND panellist_id = ' . $_SESSION['plid'] . ' GROUP BY panellist_id';
            $result = Yii::app()->db->createCommand($sql)->queryRow();
            if($result['points'] == ''){
                $point = 0;
            }else{
                $point = $result['points'];
            }
            echo '<tr class = "even"><td><h5>Pending Points = ' . $point . '</h5></td></tr>';
            ?>
        </table>
        </p>
    </div>
</section>