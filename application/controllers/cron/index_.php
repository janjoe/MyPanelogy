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
            $clang = Yii::app()->lang;
            $this->prnst();
            $dr = CronJobs::model()->getQue();
            while (($row = $dr->read()) !== false) {
                echo '<p>Checking <span>' . $row['frequency'] . '</span> commands<p/>';
                $this->executeJob($row);
                echo '<hr/>';
            }
            $this->prned();
        }

        public function executeJob($r) {
            //echo date('Y-m-d H:i').'   Current     ';
            //echo $r['LastExecutedOn'].'   Last Exec On <br/>';
            //echo $this->brain_datediff('i', date('Y-m-d H:i'), $r['LastExecutedOn']).'<br/>';
            $exec = false;

            switch (strtoupper($r['frequency'])) {
                case 'HOURLY':
                    if ($this->brain_datediff('h', date('Y-m-d H:i'), $r['LastExecutedOn']) > 1)
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

        function sendqueryemails() {
//            $this->prnst();
//            echo "send email";
//            $this->prned();
            return true;
            //$this->getController()->redirect(array('/cron/execute/'));
        }

        public function executeCommand($cmd, $id) {
            echo "<p>Executing Command <b>$cmd</b></p>";
            //$this->getController()->redirect(array('/cron/execute/sa/'.$cmd.'/id/'.$id));
            $ret = false;
            if(method_exists($this,$cmd)){
                call_user_func($cmd, $a);
            } 
            if ($ret) {
                $test = CronJobs::model()->updateLastExecution($id, 'Success');
                exit;
            } else {
                $test = CronJobs::model()->updateLastExecution($id, 'Error Generate');
            }
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
