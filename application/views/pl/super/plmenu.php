<?php
$ok = 0;
$plans_list = array();
if (isset($_SESSION['plid'])) {
    $plid = $_SESSION['plid'];
} else {
    $plid = 0;
}
$sql = "SELECT * FROM {{panellist_answer}} WHERE panellist_id = '" . $plid . "'";
$pl_answer = Yii::app()->db->createCommand($sql)->query()->readAll();
foreach ($pl_answer as $key => $value) {
    foreach ($value as $ky => $val) {
        $plans_list[$ky] = $val;
    }
}

$quelist = Question(get_question_categoryid('Registration'), '', false, true);
foreach ($quelist as $key => $value) {
    if (isset($plans_list['question_id_' . $key]) == '') {
        $ok = 1;
        break;
    }
}
$quelist = Question(get_question_categoryid('Profile'), '', false, true);
foreach ($quelist as $key => $value) {
    if (isset($plans_list['question_id_' . $key]) == '') {
        $ok = 1;
        break;
    }
}
?>


<?php
if ($ok == 0) {
    if (Yii::app()->session['plid']) {
        ?>
        <div class="push"></div>
        </div> <!-- c1 -->
        </div> <!-- content -->
        <script>
            $(document).ready(function(){
                // Gaurang 2013-12-10
                var link = window.location.href.split('/');
                //alert(link);
                page = link[link.length - 1];
                //alert(page);
                // now select the link based on the address
                $('#'+page).closest('li').addClass('active');
            })
        </script>
        <aside id="sidebar">
            <strong class="logo"><a href="<?php echo CController::createUrl('pl/home/') ?>" title="Dashboard">lg</a></strong>
            <ul class="tabset buttons">
                <li class="">
                    <a href="<?php echo CController::createUrl('pl/home/') ?>" id="home" title="My Dashbord" class="ico1">
                        <span>My Dashboard</span><em></em>
                    </a>
                    <span class="tooltip"><span>My Dashboard</span></span>
                </li>
                <li>
                    <a href="<?php echo CController::createUrl('pl/home/sa/account') ?>" id="account" title="My Account" class="ico2"><span>My Account</span><em></em></a>
                    <span class="tooltip"><span>My Account</span></span>
                </li>
                <li>
                    <a href="<?php echo CController::createUrl('pl/home/sa/surveys') ?>" id="surveys" title="My Surveys" class="ico3"><span>My Surveys</span><em></em></a>
                    <span class="tooltip"><span>My Surveys</span></span>
                </li>
                <li>
                    <a href="<?php echo CController::createUrl('pl/home/sa/rewards') ?>" title="My Rewards" id="rewards" class="ico4"><span>My Rewards</span><em></em></a>
                    <span class="tooltip"><span>Widgets</span></span>
                </li>
                <li>
                    <a href="<?php echo CController::createUrl('pl/home/sa/help') ?>" title="FAQ/Help" id="help" class="ico5"><span>FAQ/Help</span><em></em></a>
                    <span class="tooltip"><span>FAQ/Help</span></span>
                </li>
                <li>
                    <a href="<?php echo CController::createUrl('pl/home/sa/support_center') ?>" id="support_center" title="Messages" class="ico6"><span style="width: 33%">Messages</span><em></em></a>
                    <span class="tooltip"><span>Messages</span></span>
                </li>
                <li>
                    <a href="<?php echo CController::createUrl('pl/home/sa/cancel_account') ?>" id="cancel_account" title="Cancel Account" class="ico7"><span>Cancel Account</span><em></em></a>
                    <span class="tooltip"><span>Cancel Account</span></span>
                </li>
                <!--            <br/><br/>
                            <li>
                <?php
                echo "<div id='your-form-block-id'>";
                echo CHtml::beginForm();
                echo CHtml::link('Points Details', array('/pl/home/sa/point_detail'), array('class' => 'class-link'));
                echo CHtml::endForm();
                echo "</div>";
                ?>
                            </li>-->

            </ul>
            <span class="shadow"></span>
        </aside>
        <?php
    }
}
?>