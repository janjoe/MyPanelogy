<?php
$dr = Project::model()->findAllByPk($project_id);
$row = $dr[0];

$sql = "select * from {{project_status_master}} where status_for='r' and IsActive=1 ";
$dtStatus = Yii::app()->db->createCommand($sql)->query()->readAll(); //createCommand($query)->query($sql)->resultAll();
?>

<div class='header ui-widget-header'><?php $clang->eT("Rectify Project Redirects"); ?></div><br />

<div id='box_view'>
    <table class="display">
        <tbody>
            <tr>
                <td class="c1">RedirectID</td>
                <td class="c1">StatusID</td>
            </tr>
            <tr>
                <td>6589755</td>
                <td>5</td>
            </tr>
        </tbody>
    </table>
</div>

<?php
//30/06/2014 Add By Hari
if (isset($_GET['NotRectify'])) {
    if ($_GET['NotRectify'] == 'true') {
        if (isset($_SESSION['Data'])) {
            echo "<table class='InfoForm' style='margin: 0px auto;'>
                <tr><td>Completed & Not Rectified ID's List</td></tr>
                <tr class='odd'><td>Panellist Redirect Id</td></tr>";
            $element = explode(",", $_SESSION['Data']);
            foreach ($element as $key => $value) {
                echo "<tr class='even'><td>" . $value . "</td></tr>";
            }
            echo "</table>";
        }
    }
}
//30/06/2014 End
?>

<?php echo CHtml::form(array('admin/project/sa/importcsv/project_id/' . $row['project_id']), 'post', array('enctype' => 'multipart/form-data')); ?>

<input type='hidden' name='action' value='importcsv' />
<input type='hidden' name='project_id' value='<?php echo $row['project_id']; ?>' />

<table class="InfoForm" cellpadding="5px" cellspacing="5px" width="70%" border="0" style="margin: 0px auto; background-color: #ECFBD6;" >
    <caption>Project : <?php echo $row['project_id'] . ' - ' . $row['project_name']; ?></caption>
    <tbody>
        <tr>
            <td colspan="2">
                Rectification (Trueup) File :<input type="file" name="import_file" id="import_file" required="Please select file">(* Only .csv File is allowed..)
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Rectification Options : 
                <label><input type="radio" name="trueup_option" value="1" >Final Rectify</label>
                <label><input type="radio" name="trueup_option" value="0" checked="">Temp Rectify</label>
            </td>
        </tr>            
        <tr>
            <td align="left" colspan="2" style="padding-top: 10px;">
                <!--<input type="submit" class="limebutton" name="save" value="Submit"> &nbsp; 24/06/2014 Remove By Hari-->
                <?php
                //24/06/2014 Add By Hari
                $chkfinaltrupexist = Rectify::model()->findAll(array('condition' => 'project_id = ' . $project_id . ' AND rectify_type = 1'));
                if (count($chkfinaltrupexist) > 0) {
                    ?>
                    <input type="submit" class="limebutton" name="save" value="Submit" disabled> &nbsp;
                <?php } else { ?>
                    <input type="submit" class="limebutton" name="save" value="Submit"> &nbsp;
                    <?php
                }
                //24/06/2014 End
                ?>
                <a href="<?php echo $this->createUrl('admin/project/sa/modifyproject/project_id/' . $project_id . '/action/modifyproject') ?>" class="limebutton">Cancel</a>&nbsp; &nbsp;
                <?php
                $this->widget("application.extensions.Brain.BrainPopupContentWidget", array(
                    "popup_box_id" => "box_view",
                    "popup_link_id" => "link_view",
                    "container_id" => "",
                    "popup_on_load" => "false",
                    "popup_title" => "Sample Of File Format",
                    "uid" => $row['project_id'],
                    "height" => "300px;",
                    "width" => "300px;",
                ));
                ?>
                <a id="link_view" title="Rectify Project Redirects Sample File format" class="limebutton"><?php $clang->eT("Download Sample File Format"); ?></a>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" width="50%">
                <hr style="color:white;border:1px solid white"/>
                <fieldset>
                    <legend>List of Trueup</legend>
                    <?php
                    $trueup = Rectify::model()->findAll(array('condition' => "project_id ='" . $row['project_id'] . "'"));

                    if (count($trueup) > 0) {
                        foreach ($trueup as $key => $value) {
                            $trueuplist[$value['rectify_id']] = $value['rectify_no'] . ' - ' . $value['rectify_date']; //24/06/2014 Remove By Hari
                            //24/06/2014 Add By Hari
                            if ($value['rectify_type'] == '1') {
                                $trueuplist[$value['rectify_id']] = $value['rectify_no'] . ' - ' . $value['rectify_date'] . ' - ' . 'Final Rectify';
                            } else {
                                $trueuplist[$value['rectify_id']] = $value['rectify_no'] . ' - ' . $value['rectify_date'] . ' - ' . 'Temp Rectify';
                            }
                            //24/06/2014 End
                        }
                        echo CHtml::listBox('c_id', "" . 0 . "", $trueuplist, array("style" => "height:200px;width:100%;"));
                    } else {
                        echo 'No TrueUp Exist';
                    }
                    ?>
                </fieldset>
            </td>
            <td align="left"  valign="top" width="50%">
                <hr style="color:white;border:1px solid white"/>
                <fieldset>
                    <legend>Status <b>ID</b> to be used in csv files</legend>
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <?php
                                    $sql_project = "SELECT status_id,CONCAT(status_id,' - ',status_name) AS status_name FROM {{project_status_master}} WHERE status_for = 'r'  ORDER BY status_order ";
                                    $result_project = Yii::app()->db->createCommand($sql_project)->query();
                                    $project_status_list = CHtml::listData($result_project, 'status_id', 'status_name');
                                    echo CHtml::listBox('project_status_test', 'status_id', $project_status_list, array("style" => "height:200px;width:100%;"));
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </td>
<!--                <td align="left" valign="top" width="50%">
                <hr style="color:white;border:1px solid white"/>
                <fieldset>
                    <legend>Status <b>CHAR</b> to be used in csv files</legend>
            <?php
            $region = Country::model()->findAll(array('order' => 'country_name'));
            $reglist = CHtml::listData($region, 'country_id', 'country_name');
            echo CHtml::listBox('c_id', "" . 0 . "", $reglist, array("style" => "height:200px;width:100%;"));
            ?>
                </fieldset>
            </td>-->
        </tr>
    </tbody>
</table>
</form>
<!-- 04/06/2014 Add BY Hari -->
<script>
    $(document).ready(function() {
        $('#tbl').dataTable({"sPaginationType": "full_numbers"});
    } );   
</script>
<style>
    #tbl_wrapper{
        width: 70% !important;
        margin: 0px auto;
    }
</style>
<br/><br/>
<div style="text-align: center;">
    <span>List of ids that are completed but yet not rectified.</span>
</div>
<?php
//$sql = "SELECT pr.panellist_redirect_id,pr.vendor_project_id,pr.panellist_id,pr.project_id,pm.parent_project_id FROM {{panellist_redirects}} pr
//        left outer join {{project_master}} pm on pr.project_id=pm.project_id
//        WHERE (pm.project_id=" . $row['project_id'] . " OR pm.parent_project_id = " . $row['project_id'] . ") AND ifnull(pr.rectify_id,0)=0 AND pr.redirect_status_id=" . getGlobalSetting('redirect_status_completed');
$result = Project::model()->GetProjectCompletedNotRectify($row['project_id']); //Yii::app()->db->createCommand($sql)->query()->readAll();
echo "<table id='tbl' width='100%'>";
echo "<thead>
    <tr>
    <th>Panellist Redirect Id</th>
    <th>Vendor Project Id</th>
    <th>Panellist Id</th>
    <th>Project Id</th>
    <th>Parent Project Id</th>
    </tr>
    </thead>
    <tbody>";
foreach ($result as $key => $value) {
    echo "<tr>";
    echo "<td>" . $value['panellist_redirect_id'] . "</td>";
    echo "<td>" . $value['vendor_project_id'] . "</td>";
    echo "<td>" . $value['panellist_id'] . "</td>";
    echo "<td>" . $value['project_id'] . "</td>";
    echo "<td>" . $value['parent_project_id'] . "</td>";
    echo "</tr>";
}
echo "</tbody></table>";
?>
<!-- 04/06/2014 End -->