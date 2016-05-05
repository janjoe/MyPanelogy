<?php if (isset($vid)) { ?>
    <div class='header ui-widget-header'><?php $clang->eT('Vendor: ' . $vid . '-' . $name); ?></div><br />
    <?php echo CHtml::form(array("admin/project/sa/filedownload"), 'post', array('id' => 'ids detail')); ?>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="reportHeader" align="right">
                <input type="submit" value="Download" class="text_btn">
                <input type="hidden" name="act" value="download">
                <input type="hidden" name="title" value=Project_vendor_IDS_<?php echo $vid; ?>>
            </td>
        </tr>
    </table>
    <div style="width: 100%; overflow:scroll; height: 500px;">
        <table style="width:100%;" class="InfoForm">
            <tr>
                <td align="center" >ID</td>
                <td align="center" >Status</td>
                <td align="center" >Previous Status</td>
                <td align="center" >Reason</td>
                <td align="center" width="150">Resp ID</td>
                <td align="center" >Time</td>
                <td align="center" >IP</td>
            </tr>
            <?php
            $r = array();
            $q = 'select * from {{view_panellist_redirects}} where  vendor_project_id =' . $vid;
            if ($type != "0") {
                $q.=' and redirect_status_id = ' . $type;
            }
            $q .= ' order by panellist_redirect_id desc';
            $r = Yii::app()->db->createCommand($q)->query()->readAll();
            $odd = FALSE;
            $filebody = "ID,Status,Previous Status,Reason,Resp ID,Time,IP";
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
                        <td align="right"><?php echo $val['panellist_redirect_id']; ?></td>
                        <td align="left"><?php echo $val['redirect_status_name']; ?></td>
                        <td align="left"><?php echo $val['prev_redirect_name']; ?></td>
                        <td></td>
                        <td align="left"><?php echo $val['panellist_id']; ?></td>
                        <td align="right">
                            <?php
                            if (is_null($val['CompletedOn'])) {
                                $TimeStamp = $val['created_datetime'];
                            } else {
                                $TimeStamp = $val['CompletedOn'];
                            }
                            echo $TimeStamp;
                            ?></td>
                        <td align="right"><?php echo $val['StartIP']; ?></td>
                    </tr>
                    <?php
                    $odd = !$odd;
                    $filebody.=$val['panellist_redirect_id'] . "," . $val['redirect_status_name'] . "," . $val['prev_redirect_name'] . ",," . $val['panellist_id'] . "," . $TimeStamp . "," . $val['StartIP'];
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
if (isset($prjid)) {
    if ($type == 'redirects') {
        ?>
        <div class='header ui-widget-header'><?php $clang->eT('Redirects: For Project: ' . $prjid . '-' . $name); ?></div><br />
        <?php
        echo 'Completed: &nbsp <a target="_blank" href="' . Yii::app()->getBaseUrl(true) . '/endcapture.php?st=111">' . Yii::app()->getBaseUrl(true) . '/endcapture.php?st=111</a>';
        echo '<br />';
        echo 'Disqualified: &nbsp <a target="_blank" href="' . Yii::app()->getBaseUrl(true) . '/endcapture.php?st=222">' . Yii::app()->getBaseUrl(true) . '/endcapture.php?st=222</a>';
        echo '<br />';
        echo 'Quota Full: &nbsp <a target="_blank" href="' . Yii::app()->getBaseUrl(true) . '/endcapture.php?st=333">' . Yii::app()->getBaseUrl(true) . '/endcapture.php?st=333</a>';
        echo '<br />';
    }

    if ($type == 'variables') {
        ?>
        <div class='header ui-widget-header'><?php $clang->eT('Link Variable: For Project: ' . $prjid . '-' . $name); ?></div><br />
        <?php
        echo 'ClientCode: {{CLIENTKEY}}';
        echo '<br />';
        echo 'ID: {{ID}}';
        echo '<br />';
        echo 'Ext: {{PASSTHRU}}';
        echo '<br />';
        echo 'Email: {{Email}}';
        echo '<br />';
        echo 'Zip: {{Zip}}';
        echo '<br />';
        echo 'Age: {{Age}}';
        echo '<br />';
        echo 'Gender: {{Gender}}';
        echo '<br />';
        echo 'Panellist ID: {{panellist_id}}';
        echo '<br />';
    }
    ?>
    <?php
    if ($type == 'blocked') {
        ?>
        <div class='header ui-widget-header'><?php $clang->eT('SysMsg Summary' . $prjid); ?></div><br />
        <?php echo CHtml::form(array("admin/project/sa/filedownload"), 'post', array('id' => 'sysmessge')); ?>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td class="" align="right">
                    <input type="submit" value="Download In Details">
                    <input type="hidden" name="act" value="download">
                    <input type="hidden" name="title" value=SysMsg_IDS_<?php echo $prjid; ?>>
                </td>
            </tr>
        </table>
        <div style="width: 100%; overflow:scroll; height: 500px;">
            <table style="width:100%;" class="InfoForm">
                <tr>
                    <td>Panel</td>
                    <td>Status</td>
                    <td>Count</td>
                </tr>
                <?php
                $filebody = "ID,Panellistredirect ID, Status,Reason,Resp ID,Panel,Time,IP\n";
                $query = 'SELECT created_datetime, blocked_redirect_id, status,panellist_id, vendor_project_id, panellist_redirect_id, project_id, StartIP FROM {{blocked_redirects}} WHERE project_id =' . $prjid . ' ORDER BY created_datetime';
                $result = Yii::app()->db->createCommand($query)->query()->readAll();
                foreach ($result as $row) {
                    $TimeStamp = $row['created_datetime'];
                    $filebody.=$row['blocked_redirect_id'] . "," . $row['panellist_redirect_id'] . "," . $row['status'] . ",," . $row['panellist_id'] . "," . getVendor($row['vendor_project_id']) . "," . $TimeStamp . "," . $row['StartIP'] . "\n";
                }
                $query = 'SELECT vendor_project_id, status, Count(blocked_redirect_id) AS Total  FROM {{blocked_redirects}} WHERE project_id =' . $prjid . ' GROUP BY vendor_project_id, status  ORDER BY vendor_project_id,status';
                $result = Yii::app()->db->createCommand($query)->query()->readAll();
                $odd = FALSE;
                foreach ($result as $row) {
                    if ($odd) {
                        $cls = 'class="odd"';
                    } else {
                        $cls = 'class="even"';
                    }
                    echo '<tr ' . $cls . '>
                    <td align="left">' . getVendor($row['vendor_project_id']) . '</td>
                    <td align="left">' . $row['status'] . '</td>
                    <td align="right">' . $row['Total'] . '</td>
                </tr>';
                    $odd = !$odd;
                }
                ?>

            </table>
        </div>
        <textarea name="filebody" style="display:none;"><?php echo $filebody; ?></textarea>
        </form>
        <?php
    }
    if ($type == 'ulinks') {
        ?>

        <div class='header ui-widget-header'><?php $clang->eT('Links Project: ' . $prjid); ?></div><br />
        <?php echo CHtml::form(array("admin/project/sa/filedownload"), 'post', array('id' => 'ids detail')); ?>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td class="reportHeader" align="right">
                    <input type="submit" value="Download" class="text_btn">
                    <input type="hidden" name="act" value="download">
                    <input type="hidden" name="title" value=Unique_Links_IDS_<?php echo $prjid; ?>>
                </td>
            </tr>
        </table>
        <div style="width: 100%; overflow:scroll; height: 500px;">
            <table style="width:100%;" class="InfoForm">
                <tr>
                    <td align="center" >Code</td>
                    <td align="center" >Status</td>
                    <td align="center" width="150">Panellist_id</td>               
                </tr>
                <?php
                $r = array();
                $q = 'select * from {{client_code}} where  project_id =' . $prjid;
                if ($name == "used") {
                    $q.=' and status ="Used"';
                }
                if ($name == "unused") {
                    $q.=' and status IS NULL';
                }
                $r = Yii::app()->db->createCommand($q)->query()->readAll();
                $odd = FALSE;
                $filebody = "Code,Status,Panellist_id,,";
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
                            <td align="right"><?php echo $val['code']; ?></td>
                            <td align="left"><?php echo $val['status']; ?></td>
                            <td align="left"><?php echo $val['panellist_redirect_id']; ?></td>                        
                            <td></td>
                        </tr>
                        <?php
                        $odd = !$odd;
                        $filebody.=$val['code'] . "," . $val['status'] . ",," . $val['panellist_redirect_id'];
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
}
?>
