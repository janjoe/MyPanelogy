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

    class Resendplemail extends PL_Common_Action {

        public function run() {
           
            //18/06/2014 Add By Hari
            $quesql = "INSERT INTO {{CronLog}} (Start_DateTime) VALUES('" . Date('y-m-d h:i:s') . "')";
            $result = Yii::app()->db->createCommand($quesql)->query();
            $id = Yii::app()->db->lastInsertID;
            //18/06/2014 End

            $clang = Yii::app()->lang;
            $this->prnst();
            $dr = CronJobs::model()->getQue('resendemail');
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
            //echo $this->brain_datediff('d', date('Y-m-d H:i'), $r['LastExecutedOn']); exit();
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
                
                    if ($this->brain_datediff('d', date('Y-m-d H:i'), $r['LastExecutedOn']) > 1 || is_null($r['LastExecutedOn']))
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
            if ($exec){
                $this->executeCommand($r['cron_command'], $r['cron_id']);
            }
            
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

        public function resendemail() {

            $this->prnst();
            $sql = "SELECT act.*, plm.* FROM {{activation_temp}} as act JOIN {{panel_list_master}} plm ON plm.panel_list_id = act.panelllist_id WHERE  act.activation_type='reg' AND act.IsActive = '1' AND plm.status='R'";
            $result = Yii::app()->db->createCommand($sql)->query();
            $count = $result->rowCount;
            if ($count > 0) {
                $sresult = $result->readAll();
                
                foreach ($sresult as $pl)
                {  
                    $datediff = $this->brain_datediff('d', date('Y-m-d H:i'), $pl['create_date']);
                    if((($datediff >= 1 && $datediff < 2) || ($datediff >= 3 && $datediff < 4) || ($datediff >= 7 && $datediff < 8)) &&  $datediff <= 8)
                    {   
                        $email_address = $pl['email'];
                        $pwd = $pl['password'];
                        $pwd = base64_decode(urldecode($pwd));
                        $activation_id = $pl['code'];
                        $panellist_id = $pl['panelllist_id'];
                        
                        $activation_link = Yii::app()->createAbsoluteUrl('pl/registration/sa/activate/c/' . $panellist_id . '*' . $activation_id);
                        $whitelist = array(
                            '127.0.0.1',
                            '::1'
                        );
                        
                        if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                            $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_RegistrationReSend, $panellist_id, array('pwd' => "$pwd", 'activation_link' => "$activation_link"));
                        } else {
                             $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_RegistrationReSend, $panellist_id, array('pwd' => "$pwd", 'activation_link' => "$activation_link"));
                            
                        }
                        if (!$send) {
                            echo 'Error';
                            Yii::app()->setFlashMessage($clang->gT("Error in mail send"));
                        }
                        else
                        {
                            echo 'reminder confirmation sent mail';
                        }
                    }
                }    
            }

            $sqlagent = "SELECT agt.* FROM {{panel_list_agetnt_master}} as agt WHERE  agt.status= '0'";
            $agentresult = Yii::app()->db->createCommand($sqlagent)->query();
            $countagent = $agentresult->rowCount;
            
            if($countagent >  0)
            {
                $aresult = $agentresult->readAll();

                foreach ($aresult as $pl)
                {  

                   $datediff = $this->brain_datediff('d', date('Y-m-d H:i'), $pl['create_date']);
                    if((($datediff >= 1 && $datediff < 2) || ($datediff >= 3 && $datediff < 4) || ($datediff >= 7 && $datediff < 8)) &&  $datediff <= 8)
                    {   
                        $email_address = $pl['email'];
                       
                        $panellist_id = $pl['id'];
                        $cmp_id = $pl['cmp_id'];
                        $per_id = $pl['per_id'];
                        $fname = $pl['first_name'];
                        $lname = $pl['last_name'];
                        
                        $activation_link = Yii::app()->getBaseUrl(true).'/?pagename=JOIN%20NOW&fname=' . $fname . '&lname=' . $lname.'&email='.$email_address.'&cmp='.$cmp_id.'&per_id='.$per_id.'&type=agenthrough&rec='.base64_encode($panellist_id);

                        $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_agent_Registration, '', array('activation_link' => "$activation_link",'name'=> "$fname $lname", 'email'=>"$email_address" ));

                        if (!$send) {
                            echo 'Error'; 
                            Yii::app()->setFlashMessage($clang->gT("Error in mail send"));
                        }
                        else{
                            echo 'reminder confirmation sent mail';
                           // Yii::app()->setFlashMessage($clang->gT("Email send successfully check your mail"));
                        }
                    }
                } 
            }

            $sqlpanalist = "SELECT sp.* FROM {{panel_list_master}} as sp WHERE  sp.status= 'E' AND DATEDIFF(CURDATE(),last_login) > 90 ";
            $plresult = Yii::app()->db->createCommand($sqlpanalist)->query();
            $countpl = $plresult->rowCount;
            if($countpl >  0)
            {
                $presult = $plresult->readAll();
                
                foreach ($presult as $pl)
                {   
                    $email_address = $pl['email'];
                    $pwd = $pl['password'];
                    $pwd = base64_decode(urldecode($pwd));
                    $activation_id = '';
                    $panellist_id = $pl['panel_list_id'];
                    
                    $activation_link = Yii::app()->createAbsoluteUrl('pl/registration/sa/activate/c/' . $panellist_id . '*' . $activation_id);
                    $whitelist = array(
                        '127.0.0.1',
                        '::1'
                    );
                    
                    if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                        $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_remember_paused_account, $panellist_id, array('pwd' => "$pwd", 'activation_link' => "$activation_link"));
                    } else {
                         $send = get_SendEmail::model()->SendEmailByTemplate($email_address, EMAIL_POINT_PL_remember_paused_account, $panellist_id, array('pwd' => "$pwd", 'activation_link' => "$activation_link"));
                        
                    }
                    if (!$send) {
                        echo 'Error';
                        Yii::app()->setFlashMessage($clang->gT("Error in mail send"));
                    }
                    else{
                            echo 'paused account sent mail';
                           // Yii::app()->setFlashMessage($clang->gT("Email send successfully check your mail"));
                        }

                }
            }    
            

            $this->prned();
            return true;
            
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
