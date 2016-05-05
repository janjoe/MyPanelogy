<script>
    function reloadpage(){
        return true;
    }
</script>

<div class='header ui-widget-header'><?php $clang->eT("Upload Unique links For Project: $project_id"); ?></div><br />

<?php
$r = array();
$total = 0;
$q = 'select count(*)as Total from {{client_code}} where  project_id =' . $project_id;
$r = Yii::app()->db->createCommand($q)->query()->readAll();
foreach ($r as $Key => $val) {
    $total = $val['Total'];
}

$used = 0;
$q = 'select count(*)as Total from {{client_code}} where  project_id =' . $project_id . ' and status ="Used"';
$r = Yii::app()->db->createCommand($q)->query()->readAll();
foreach ($r as $Key => $val) {
    $used = $val['Total'];
}

$unused = $total - $used;
?>

<table class="InfoForm" cellpadding="5px" cellspacing="5px" width="60%" border="0" style="margin-left:20%; background-color: #ECFBD6;" >
    <tbody>
        <tr>
            <td>
                Total Links
            </td>
            <td>
                Used Links
            </td>
            <td>
                Remaining Links
            </td>
        </tr>

        <tr>
            <td
            <?php
            echo "<div id='your-form-block-id'>";
            echo CHtml::beginForm();
            echo CHtml::link($total, array('admin/project/sa/showids/prjid/' . $project_id . '/type/ulinks/name/all'), array('class' => 'class-link'));
            echo CHtml::endForm();
            echo "</div>";
            ?>
        </td>
        <td>
            <?php
            echo "<div id='your-form-block-id'>";
            echo CHtml::beginForm();
            echo CHtml::link($used, array('admin/project/sa/showids/prjid/' . $project_id . '/type/ulinks/name/used'), array('class' => 'class-link'));
            echo CHtml::endForm();
            echo "</div>";
            ?>
        </td>
        <td>
            <?php
            echo "<div id='your-form-block-id'>";
            echo CHtml::beginForm();
            echo CHtml::link($unused, array('admin/project/sa/showids/prjid/' . $project_id . '/type/ulinks/name/unused'), array('class' => 'class-link'));
            echo CHtml::endForm();
            echo "</div>";
            ?>
        </td>
    </tr>
</tbody>
</table>

<br />

<?php echo CHtml::form(array('admin/project/sa/unique/project_id/' . $project_id), 'post', array('enctype' => 'multipart/form-data')); ?>
<script language="JavaScript">
    extArray = new Array(".csv");
    function LimitAttach(form, file) {
        allowSubmit = false;
        if (!file) return;
        while (file.indexOf("\\") != -1)
            file = file.slice(file.indexOf("\\") + 1);
        ext = file.slice(file.indexOf(".")).toLowerCase();
        for (var i = 0; i < extArray.length; i++) {
            if (extArray[i] == ext) { allowSubmit = true; break; }
        }
        if (allowSubmit) return true;
        else
            alert("Sorry,  Only .csv File is allowed..!");
        return false;
    }
    
</script>

<input type='hidden' name='action' value='uploadlinks' />
<input type='hidden' name='project_id' value='<?php echo $project_id; ?>' />

<table class="InfoForm" cellpadding="5px" cellspacing="5px" width="60%" border="0" style="margin-left:20%; background-color: #ECFBD6;" >
    <tbody>
        <tr>
            <td colspan="2">
                Upload Links:<input type="file" name="import_file" id="import_file" required="Please select file">(* Only .csv File is allowed..)
            </td>
            <td>
                <input type="submit" name="submit" value="Upload" onclick="return LimitAttach(this.form, this.form.import_file.value)" />
            </td>
        </tr>
    </tbody>
</table>
</form>

<br/>

<?php echo CHtml::form(array('admin/project/sa/unique/project_id/' . $project_id), 'post', array('enctype' => 'multipart/form-data')); ?>
<input type='hidden' name='action' value='dellinks' />
<input type='hidden' name='project_id' value='<?php echo $project_id; ?>' />

<table class="InfoForm" cellpadding="5px" cellspacing="5px" width="60%" border="0" style="margin-left:20%; background-color: #ECFBD6;" >
    <tbody>
        <tr>
            <td colspan="2">
                Delete Top links:   <input type="text" name="qty" id="qty" required="Please input no">
            </td>
            <td>
                <input type="submit" name="submit" value="Delete"/>&nbsp; &nbsp;
                <a href="<?php echo $this->createUrl('admin/project/sa/modifyproject/project_id/' . $project_id . '/action/modifyproject') ?>" class="limebutton">Cancel</a>
            </td>
        </tr>
    </tbody>
</table>
