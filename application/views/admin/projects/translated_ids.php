<?php
$sql = "SHOW TABLES LIKE '{{temptranslate}}'";
$result = Yii::app()->db->createCommand($sql)->query();
$count = $result->rowCount;
if ($count > 0) {
    ?>
    <div class='header ui-widget-header'><?php $clang->eT('Translated IDs'); ?></div><br />
    <?php echo CHtml::form(array("admin/project/sa/filedownload"), 'post', array('id' => 'ids detail')); ?>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="reportHeader" align="right">
                <input type="submit" value="Download" class="text_btn"/>
                <input type="hidden" name="act" value="download"/>
                <input type="hidden" name="title" value="Translated_IDs"/>
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
                <td align="center" >Project</td>
            </tr>
            <?php
            $r = array();
            $q = "SELECT  b.ID,redirect_status_name,panellist_id,vendor_project_id,project_id
                    FROM {{temptranslate}} b 
                    LEFT JOIN {{view_panellist_redirects}} p ON b.ID = p.panellist_redirect_id";
            $r = Yii::app()->db->createCommand($q)->query()->readAll();
            $odd = FALSE;
            $filebody = "ID,Status,Reason,Resp ID,Panel,Project";
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
                        <td align="right"><?php echo $val['ID']; ?></td>
                        <td align="left"><?php echo $val['redirect_status_name']; ?></td>
                        <td></td>
                        <td align="left"><?php echo $val['panellist_id']; ?></td>
                        <td align="left"><?php echo getVendor($val['vendor_project_id']); ?></td>
                        <td align="right"><?php echo $val['project_id']; ?></td>
                    </tr>
                    <?php
                    $odd = !$odd;
                    $filebody.=$val['ID'] . "," . $val['redirect_status_name'] . ",," . $val['panellist_id'] . "," . getVendor($val['vendor_project_id']) . "," . $val['project_id'];
                    $filebody.= "\n";
                }
            }
            $query = 'DROP TABLE {{temptranslate}}';
            Yii::app()->db->createCommand($query)->query();
            ?>
        </table>
    </div>
    <textarea name="filebody" style="display:none;"><?php echo $filebody; ?></textarea>
    </form>
    <?php
} else {
    echo "Please upload file";
}
exit;
?>
