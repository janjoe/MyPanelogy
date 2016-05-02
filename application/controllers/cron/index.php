<HTML>
    <HEAD>
        <STYLE>
            body{font-family: Arial; font-size: 12pt;color:darkblue;padding:10px;}
            h1{font-size: 18pt;}
            p{color:green;}
            p span{color:blue;}
        </STYLE>
    </HEAD>
</HTML>
<BODY>

    <?php
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Index extends PL_Common_Action {

        public function run() {
            //18/06/2014 Add By Hari
            $quesql = "INSERT INTO {{CronLog}} (Start_DateTime) VALUES('" . Date('y-m-d h:i:s') . "')";
            $result = Yii::app()->db->createCommand($quesql)->query();
            $id = Yii::app()->db->lastInsertID;
            //18/06/2014 End

            $clang = Yii::app()->lang;
            $this->prnst();
            $dr = CronJobs::model()->getQue();
            while (($row = $dr->read()) !== false) {
                echo '<p>Checking <span>' . $row['frequency'] . '</span> commands<p/>';
                $this->executeJob($row);
                echo '<hr/>';
            }
            $this->prned();
            
            //18/06/2014 Add By Hari
            $quesql = "UPDATE {{CronLog}} SET End_DateTime='" . Date('y-m-d h:i:s') . "' WHERE CronLogID='" . $id . "'";
            $result = Yii::app()->db->createCommand($quesql)->query();
            //18/06/2014 End
        }

        public function executeJob($r) {
            //echo date('Y-m-d H:i').'   Current     ';
            //echo $r['LastExecutedOn'].'   Last Exec On <br/>';
            //echo $this->brain_datediff('i', date('Y-m-d H:i'), $r['LastExecutedOn']).'<br/>';
            $exec = false;

            switch (strtoupper($r['frequency'])) {
                case 'MINUTE':
                    if ($this->brain_datediff('i', date('Y-m-d H:i'), $r['LastExecutedOn']) > 1 || is_null($r['LastExecutedOn']))
                        $exec = true;
                    break;

                case 'HOURLY':
                    if ($this->brain_datediff('h', date('Y-m-d H:i'), $r['LastExecutedOn']) > 1 || is_null($r['LastExecutedOn']))
                        $exec = true;
                    break;

                case 'DAILY':
                    if (($this->brain_datediff('d', date('Y-m-d H:i'), $r['LastExecutedOn']) > 1 && date('H:i') == $r['occur_time'] ) || is_null($r['LastExecutedOn']))
                        $exec = true;
                    break;

                case 'WEEKLY':
                    if (($this->brain_datediff('d', date('Y-m-d H:i'), $r['LastExecutedOn']) >= 7 && strtoupper(date('D')) == strtoupper($r['occur_day']) && date('H:i') == $r['occur_time'] ) || is_null($r['LastExecutedOn']))
                        $exec = true;
                    break;

                case 'MONTHLY':
                    if (( $this->brain_datediff('m', date('Y-m-d H:i'), $r['LastExecutedOn']) > 1 && strtoupper(date('d')) == $r['occur_day'] && date('H:i') == $r['occur_time'] ) || is_null($r['LastExecutedOn']))
                        $exec = true;
                    break;

                case 'ONCE':
                    if (($this->brain_datediff('i', date('Y-m-d H:i'), $r['LastExecutedOn']) > 1 && date('Y-m-d') == $r['occur_day'] && date('H:i') == $r['occur_time'] ) || is_null($r['LastExecutedOn']))
                        $exec = true;
                    break;

                default:
            }
            echo "<p>Command <span>" . $r['cron_command'] . "</span> was last executed on <b>" . $r['LastExecutedOn'] . "</b></p>";
            if ($exec)
                $this->executeCommand($r['cron_command'], $r['cron_id']);
        }

        public function executeCommand($cmd, $id) {
            echo "<p>Executing Command <b>$cmd</b></p>";

            $anObject = $this;
            $methodVariable = array($anObject, $cmd);
            var_dump(is_callable($methodVariable, true, $callable_name));  //  bool(true)
            echo $callable_name, "\n";  //  someClass::someMethod
            $ret = call_user_func($callable_name);

            if ($ret)
                $test = CronJobs::model()->updateLastExecution($id, 'Success');
            else
                $test = CronJobs::model()->updateLastExecution($id, 'Error Generate');
        }

        public function sendqueryemails() {
            $this->prnst();
            $quesql = "select * from {{view_sendingqueque}} 
                WHERE 
                project_id IN (select project_id FROM {{project_master}} WHERE trueup IS NULL or trueup='' or trueup=0 )
                LIMIT 3000";

/*REmoved CONCAT(project_id,'/',panellist_id) NOT IN (SELECT CONCAT(project_id,'/',panellist_id) FROM {{panellist_project}}) And from above query for resend*/

            $qrydetail = Yii::app()->db->createCommand($quesql)->query()->readAll();
            $created_date = Date('y-m-d h:i:s');

            foreach ($qrydetail as $rs) {
                $internal = getGlobalSetting('Own_Panel');
                $internalevendor = 0;
                $vsql = "select vendor_project_id from {{project_master_vendors}} where project_id = " . $rs['project_id'] . " and vendor_id  = $internal order by vendor_project_id  LIMIT 1 ";
                $vdetail = Yii::app()->db->createCommand($vsql)->query()->readAll();
                foreach ($vdetail as $rsv) {
                    $internalevendor = $rsv['vendor_project_id'];
                }

                $pid = $rs['project_id'] * 7;
                $vpid = $internalevendor * 7;
                $paid = $rs['panellist_id'] * 7;
                $gid = $pid . "-" . $vpid . "-" . $paid;
                $gid = urlencode(base64_encode($gid));
                $projecturl = Yii::app()->getBaseUrl(true) . '/capture.php?int=' . $gid;

                if ($rs['type'] == "Send") {
                    $notassigned = 1;
                    $rsischeck = array();
                    $qischeck = "SELECT * FROM {{panellist_project}} Where  project_id = " . $rs['project_id'] . " and panellist_id = " . $rs['panellist_id'] . "";
                    $rsischeck = Yii::app()->db->createCommand($qischeck)->query()->readAll();
                    if(count($rsischeck)>0){
                            $notassigned = 0;
                    }
                    if($notassigned){
                        $sql_insert = "insert into {{panellist_project}} 
                            (panellist_id,project_id,project_url,points,status,created_date ) values
                            (" . $rs['panellist_id'] . "," . $rs['project_id'] . ",'$projecturl'," . $rs['points'] . ",'A','$created_date')";
                        $rString = Yii::app()->db->createCommand($sql_insert)->execute();
                        $sql_update = "Update  {{panel_list_master}} set no_invited = no_invited +1  Where  panel_list_id = '" . $rs['panellist_id'] . "'";
                        $rString = Yii::app()->db->createCommand($sql_update)->execute();
                        //$reslt = mysql_query($query) or die(mysql_error() . $query);
                    }  
                }

                //Send email
                $whitelist = array(
                    '127.0.0.1',
                    '::1'
                );
                $project_id = $rs['project_id'];
                $sql_pl = "select * from {{panel_list_master}} where panel_list_id = '" . $rs['panellist_id'] . "' and status ='E'";//Remove BY Hari
                //$sql_pl = "select * from {{panel_list_master}} where panel_list_id = '" . $rs['panellist_id'] . "' and status ='E' ORDER BY RAND()";//Add BY Hari
                $pl_email = Yii::app()->db->createCommand($sql_pl)->queryRow();
                     
                if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                    $send = get_SendEmail::model()->SendEmailByTemplate($pl_email['email'], EMAIL_POINT_QueryPullSend, $rs['panellist_id'], array('project_id' => "$project_id"));
                } else {
                   $send = get_SendEmail::model()->SendEmailByTemplate($pl_email['email'], EMAIL_POINT_QueryPullSend, $rs['panellist_id'], array('project_id' => "$project_id"));
                    echo '</br/>';
                }

                //update  it as sent
                $sql_upd = "update {{query_send_details}} set status = 1, send_date = '$created_date' where id = " . $rs['id'] . "";
                $rString = Yii::app()->db->createCommand($sql_upd)->execute();

                echo "send email" . $rs['type'] . "<br/>";
            }
            $this->prned();
            return true;
            //$this->getController()->redirect(array('/cron/execute/'));
        }

        function brain_datediff($str_interval, $dt_menor, $dt_maior, $relative = false) {
            if (is_null($dt_maior))
                return 0;

            if (is_string($dt_menor))
                $dt_menor = date_create($dt_menor);
            if (is_string($dt_maior))
                $dt_maior = date_create($dt_maior);

            $diff = date_diff($dt_menor, $dt_maior, !$relative);

            switch ($str_interval) {
                case "y":
                    $total = $diff->y + $diff->m / 12 + $diff->d / 365.25;
                    break;
                case "m":
                    $total = $diff->y * 12 + $diff->m + $diff->d / 30 + $diff->h / 24;
                    break;
                case "d":
                    $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h / 24 + $diff->i / 60;
                    break;
                case "h":
                    $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i / 60;
                    break;
                case "i":
                    $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s / 60;
                    break;
                case "s":
                    $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i) * 60 + $diff->s;
                    break;
            }
            if ($diff->invert)
                return -1 * $total;
            else
                return $total;
        }

        public static function call($className=__CLASS__) {
            return ($className);
        }

        function prnst() {
            echo '<h1>Cron job execution started at ' . date('d/M/Y h:i:s a') . '</h1>';
        }

        function prned() {
            echo '<h1>Cron job execution ended at ' . date('d/M/Y h:i:s a') . '</h1>';
        }

    }
    ?>

</BODY>
