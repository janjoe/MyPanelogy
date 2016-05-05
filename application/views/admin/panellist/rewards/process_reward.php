<?php
if ($type == 'process') {
    $updsql = "Update {{reward_request}} set status = '1' where id in(" . $ids . ")";
    $result = Yii::app()->db->createCommand($updsql)->query();
    echo CHtml::form(array("admin/project/sa/filedownload"), 'post', array('id' => 'ids detail'));
    ?>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="reportHeader" align="right">
                <input type="submit" value="Download" class="text_btn">
                <input type="hidden" name="act" value="download">
                <input type="hidden" name="title" value="Request Reward"/>
            </td>
        </tr>
    </table>
    <div style="width: 100%; overflow:scroll; height: 500px;">
        <table style="width:100%;" class="InfoForm">
            <tr>
                <td align="center" >ID</td>
                <td align="center" >Panelist Name</td>
                <td align="center" >Panelist Email</td>
                <td align="center" width="150">Date of Request</td>
                <td align="center">Reward Title</td>
                <td align="center" >Amount</td>
            </tr>
            <?php
            $r = array();
            $q = 'select * from {{view_reward_request}} where  id IN(' . $ids . ')';
            $r = Yii::app()->db->createCommand($q)->query()->readAll();
            $odd = FALSE;
            $filebody = "Id,Panelist Name,Panelist Email,Date of Request,Reward Title,Amount";
            $filebody.= "\n";
            if (count($r) > 0) {
                foreach ($r as $key => $val) {
                    if ($odd) {
                        $cls = 'class="odd"';
                    } else {
                        $cls = 'class="even"';
                    }
                    ?>
                    <tr <?php echo $cls; ?>>
                        <td align="right"><?php echo $val['id']; ?></td>
                        <td align="left"><?php echo $val['full_name']; ?></td>
                        <td align="left"><?php echo $val['email']; ?></td>
                        <td align="left"><?php echo $val['date']; ?></td>
                        <td align="left"><?php echo $val['title']; ?></td>
                        <td align="right"><?php echo $val['amount']; ?></td>

                    </tr>
                    <?php
                    $odd = !$odd;
                    $filebody.=$val['id'] . "," . $val['full_name'] . "," . $val['email'] . "," . $val['date'] . "," . $val['title'] . "," . $val['amount'];
                    $filebody.= "\n";
                }
            }
            ?>
        </table>
    </div>
    <textarea name="filebody" style="display:none;"><?php echo $filebody; ?></textarea>
    </form>   
    <?php
}
if ($type == 'fulfilled') {
    $updsql = "Update {{reward_request}} set status = '2' where id in(" . $ids . ")";
    $result = Yii::app()->db->createCommand($updsql)->query();
    foreach ($id_ary as $key => $value) {
        $sq = "Update {{reward_request}} set paypal_trnaid = '$_POST[$value]' where id = $value";
        $result = Yii::app()->db->createCommand($sq)->query();
    }

    echo CHtml::form(array("admin/project/sa/filedownload"), 'post', array('id' => 'ids detail'));
    ?>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="reportHeader" align="right">
                <input type="submit" value="Download" class="text_btn">
                <input type="hidden" name="act" value="download">
                <input type="hidden" name="title" value="Request Reward"/>
            </td>
        </tr>
    </table>
    <div style="width: 100%; overflow:scroll; height: 500px;">
        <table style="width:100%;" class="InfoForm">
            <tr>
                <td align="center" >ID</td>
                <td align="center" >Panelist Name</td>
                <td align="center" >Panelist Email</td>
                <td align="center" width="150">Date of Request</td>
                <td align="center">Reward Title</td>
                <td align="center" >Amount</td>
            </tr>
            <?php
            $r = array();
            $q = 'select * from {{view_reward_request}} where  id IN(' . $ids . ')';
            $r = Yii::app()->db->createCommand($q)->query()->readAll();
            $odd = FALSE;
            $filebody = "Id,Panelist Name,Panelist Email,Date of Request,Reward Title,Amount";
            $filebody.= "\n";
            if (count($r) > 0) {
                foreach ($r as $key => $val) {
                    if ($odd) {
                        $cls = 'class="odd"';
                    } else {
                        $cls = 'class="even"';
                    }
                    ?>
                    <tr <?php echo $cls; ?>>
                        <td align="right"><?php echo $val['id']; ?></td>
                        <td align="left"><?php echo $val['full_name']; ?></td>
                        <td align="left"><?php echo $val['email']; ?></td>
                        <td align="left"><?php echo $val['date']; ?></td>
                        <td align="left"><?php echo $val['title']; ?></td>
                        <td align="right"><?php echo $val['amount']; ?></td>

                    </tr>
                    <?php
                    $odd = !$odd;
                    $filebody.=$val['id'] . "," . $val['full_name'] . "," . $val['email'] . "," . $val['date'] . "," . $val['title'] . "," . $val['amount'];
                    $filebody.= "\n";
                }
            }
            ?>
        </table>
    </div>
    <textarea name="filebody" style="display:none;"><?php echo $filebody; ?></textarea>
    </form>
    <?php
}
?>

