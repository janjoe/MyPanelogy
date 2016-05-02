<?php if (isset($prjid)) { ?>
    <div class='header ui-widget-header'><?php $clang->eT('Project IDS: ' . $prjid); ?></div><br />
    <?php echo CHtml::form(array("admin/project/sa/filedownload"), 'post', array('id' => 'ids detail')); ?>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="reportHeader" align="right">
                <input type="submit" value="Download" class="text_btn">
                <input type="hidden" name="act" value="download">
                <input type="hidden" name="title" value=Project_IDS_<?php echo $prjid; ?>>
            </td>
        </tr>
    </table>
    <div style="width: 100%; overflow:scroll; height: 500px;">
        <table style="width:100%;" class="InfoForm">
            <tr>
                <td align="center" >ID</td>
                <td align="center" >Status</td>
                <td align="center" >Reason</td>
                <td align="center" width="150">Resp ID</td>
                <td align="center">Panel</td>
                <td align="center" >Time</td>
                <td align="center" >IP</td>
                <?php
                if ($askExt == "1")
                    echo '<td align="center" >Misc Data</td>';
                if ($askLOI == "1")
                    echo '<td align="center" >LOI</td>';
                if ($askReferrer == "1")
                    echo '<td align="center" >Referrer</td>';
                if ($askPrescreener == "1") {
                    echo '<td align="center" >Email</td>';
                    echo '<td align="center" >Zip</td>';
                    echo '<td align="center" >Age</td>';
                    echo '<td align="center" >Gender</td>';
                }
                ?>
            </tr>
            <?php
            $r = array();
            $q = 'select * from {{view_panellist_redirects}} where  project_id =' . $prjid;
            $q .= ' order by panellist_redirect_id desc';
            $r = Yii::app()->db->createCommand($q)->query()->readAll();
            $odd = FALSE;
            $filebody = "ID,Status,Reason,Resp ID,Panel,Time,IP";
            if ($askExt == "1")
                $filebody.= ",Misc Data";
            if ($askLOI == "1")
                $filebody.= ",LOI";
            if ($askReferrer == "1")
                $filebody.= ",Referrer";
            if ($askPrescreener == "1")
                $filebody.= ",Email,Zip,Age,Gender";
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
                        <td></td>
                        <td align="left"><?php echo $val['panellist_id']; ?></td>
                        <td align="left"><?php echo getVendor($val['vendor_project_id']); ?></td>
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
                        <?php
                        if ($askExt == "1")
                            echo '<td align="center" >' . $val['foreign_misc'] . '</td>';
                        if ($askLOI == "1")
                            echo '<td align="center" >' . $val['LOS'] . '</td>';
                        if ($askReferrer == "1")
                            echo '<td align="center" >' . $val['Referrer'] . '</td>';
                        if ($askPrescreener == "1") {
                            $ex = explode(";", $val['DataOnRedirect']);
                            $Email = (isset($ex[0])) ? $ex[0] : "";
                            $Zip = (isset($ex[1])) ? $ex[1] : "";
                            $Age = (isset($ex[2])) ? $ex[2] : "";
                            $Gender = (isset($ex[3])) ? $ex[3] : "";
                            echo '<td align="left" >' . $Email . '</td>';
                            echo '<td align="left" >' . $Zip . '</td>';
                            echo '<td align="right" >' . $Age . '</td>';
                            echo '<td align="left" >' . $Gender . '</td>';
                        }
                        ?>
                    </tr>
                    <?php
                    $odd = !$odd;
                    $filebody.=$val['panellist_redirect_id'] . "," . $val['redirect_status_name'] . ",," . $val['panellist_id'] . "," . getVendor($val['vendor_project_id']) . "," . $TimeStamp . "," . $val['StartIP'];
                    if ($askExt == "1")
                        $filebody.= "," . $val['foreign_misc'];
                    if ($askLOI == "1")
                        $filebody.= "," . $val['LOS'];
                    if ($askReferrer == "1")
                        $filebody.= "," . $val['Referrer'];
                    if ($askPrescreener == "1")
                        $filebody.= "," . $Email . "," . $Zip . "," . $Age . "," . $Gender;
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
