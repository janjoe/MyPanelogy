<?php echo CHtml::form(array('pl/home/sa/edit_profile'), 'post', array('id' => 'loginform', 'name' => 'loginform')); ?>
<div id="tab-2">
    <!--    <div style="float:right;">
            <a href="edit_profile" class="nav-cta login">
                <input type="button" value="Edit Profile">
            </a>
                <a href="security_question" class="nav-cta login">Security Questions</span></a>
        </div>-->

    <?php
    $pl_details = $plans_list = array();
    $sql = "SELECT * FROM {{view_panel_list_master}} WHERE panel_list_id = '" . $_SESSION['plid'] . "'";
    $pl_details = Yii::app()->db->createCommand($sql)->query()->readAll();

    $sql = "SELECT * FROM {{panellist_answer}} WHERE panellist_id = '" . $_SESSION['plid'] . "'";
    $pl_answer = Yii::app()->db->createCommand($sql)->query()->readAll();
    foreach ($pl_answer as $key => $value) {
        foreach ($value as $ky => $val) {
            $plans_list[$ky] = $val;
        }
    }
    ?>
    <section class="container w90_per">
        <div class="box w98_per effect7">
            <h3>Registration Information</h3>
            <p>
            <table class="InfoForm" style="width: 95%; margin: 0px auto;">
                <tr class="odd">
                    <td>Email Address</td>
                    <td><?php echo $pl_details[0]['email'] ?></td>
                </tr>
                <tr class="even">
                    <td>First Name</td>
                    <td>
                        <input type="text" name="fname" value="<?php echo $pl_details[0]['first_name'] ?>" />
                    </td>
                </tr>
                <tr class="odd">
                    <td>Last Name</td>
                    <td>
                        <input type="text" name="lname" value="<?php echo $pl_details[0]['last_name'] ?>" />
                    </td>
                </tr>
                <?php
                $odd = FALSE;
                $quelist = Question(get_question_categoryid('Registration'), '', false, true);
                $quetype = Question(get_question_categoryid('Registration'), '', true, false);
                foreach ($quelist as $key => $value) {
                    if ($odd) {
                        $cls = 'class="odd"';
                    } else {
                        $cls = 'class="even"';
                    }
                    echo '<tr ' . $cls . '>
                        <td>' . $value . '</td>';
                    if ($quetype[$key] == 'Text') {
                        echo '<td><input type="text" name="' . $key . '" value="' . $plans_list['question_id_' . $key] . '" /></td>';
                    } elseif ($quetype[$key] == 'DOB') {
                        echo '<td><input type="date" name="' . $key . '" value="' . $plans_list['question_id_' . $key] . '" /></td>';
                    } elseif ($quetype[$key] == 'TextArea') {
                        echo '<td><input type="text" name="' . $key . '" value="' . $plans_list['question_id_' . $key] . '" /></td>';
                    } else {
                        echo '<td>' . get_edit_answer($key, $quetype[$key], $plans_list['question_id_' . $key]) . '</td>';
                    }
                    echo '<tr>';
                    $odd = !$odd;
                }
                ?>
            </table> 
            </p>
        </div>
        <div class="box w98_per effect7">
            <h3>Profile Details</h3>
            <p>
            <table class="InfoForm" style="width: 95%; margin: 0px auto;">
                <?php
                $odd = FALSE;
                $quelist = Question(get_question_categoryid('Profile'), '', false, true);
                $quetype = Question(get_question_categoryid('Profile'), '', true, false);
                foreach ($quelist as $key => $value) {
                    if ($odd) {
                        $cls = 'class="odd"';
                    } else {
                        $cls = 'class="even"';
                    }
                    echo '<tr ' . $cls . '>
                        <td>' . $value .'=>'.$key. '</td>';
                    if ($quetype[$key] == 'Text') {
                        echo '<td><input type="text" name="' . $key . '" value="' . $plans_list['question_id_' . $key] . '" /></td>';
                    } elseif ($quetype[$key] == 'DOB') {
                        echo '<td><input type="date" name="' . $key . '" value="' . $plans_list['question_id_' . $key] . '" /></td>';
                    } elseif ($quetype[$key] == 'TextArea') {
                        echo '<td><input type="text" name="' . $key . '" value="' . $plans_list['question_id_' . $key] . '" /></td>';
                    } else {
                        echo '<td>' . get_edit_answer($key, $quetype[$key], $plans_list['question_id_' . $key]) . '</td>';
                    }
                    echo '<tr>';
                    $odd = !$odd;
                }
                ?>
            </table> 
            </p>
        </div>
        <div>
            <input type="hidden" name="action" value="edit_profile"/>
            <input type="hidden" name="panellist_id" value="<?php echo $_SESSION['plid']; ?>"/>
            <br>
            <br>
            <input type="submit" value="Save">
        </div>

    </section>
</div>

<?php echo CHtml::endForm(); ?>