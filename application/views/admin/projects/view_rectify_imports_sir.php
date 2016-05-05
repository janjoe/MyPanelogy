<?php
if (isset($_GET['project_id']))
    $project_id = $_GET['project_id'];
else
    $project_id = 0;
if ($project_id == 0)
    exit('Can not find the project id, Please contact your web master.');
$dr = Project::model()->findAllByPk($project_id);
$row = $dr[0];

if ($file['size'] > 0) {
    $sql = 'CREATE TEMPORARY TABLE IF NOT EXISTS {{tmp_import}} ( SELECT panellist_redirect_id,redirect_status_id,created_datetime FROM {{panellist_redirects}} )';
    Yii::app()->db->createCommand($sql)->query();

    $sql = 'delete from {{tmp_import}} ';
    Yii::app()->db->createCommand($sql)->query();

    $trueup_type;
    
    $csvfile = fopen($file['tmp_name'], 'r');
    $theData = fgets($csvfile);
    $i = 0;
    while (!feof($csvfile)) {
        $csv_data[] = fgets($csvfile, 1024);
        $csv_array = explode(",", $csv_data[$i]);
        $sql = "INSERT INTO {{tmp_import}} (panellist_redirect_id,redirect_status_id,created_datetime)
        VALUES(" . $csv_array[0] . "," . $csv_array[1] . ",now())";
        Yii::app()->db->createCommand($sql)->query();
        $i++;
    }


    fclose($csvfile);

    Yii::app()->setFlashMessage($clang->gT("File data successfully imported to database."));
    //if (CheckValidations()) ImportData();
} else {
    Yii::app()->setFlashMessage($clang->gT("No data found the selected file !!!"));
}
//
?>
<div class='header ui-widget-header'><?php $clang->eT("Rectify Project Redirects"); ?></div><br />
<table class="InfoForm" cellpadding="5px" cellspacing="5px" width="60%" border="0" style="margin-left:20%; background-color: #ECFBD6;" >
    <caption>CSV Import Details</caption>
    <tbody>
        <tr>
            <td colspan="2">
                &nbsp;
            </td>
        </tr>
    </tbody>
</table>
