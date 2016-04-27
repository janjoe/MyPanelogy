<?php if ($display['header']) { ?>
    <div class='header ui-widget-header'>
        <?php $clang->eT("View Reports Dashboard"); ?>
        <a href="<?php echo $this->createUrl('admin/reports/index/print') ?>" target="_blank" style="float:right; padding-right: 50px;">
            <input type='button' value='<?php $clang->eT("Print"); ?>' />
        </a>
    </div>
<?php } ?>
<br />
<div style="text-align: center; padding-left: 25%; width: 100%;">
    <table id="dashboard" style="width:50%;border-top: 2px double green;border-bottom: 2px double green;border-left: 2px double green;border-right: 2px double green;">
        <thead>
            <tr>
                <th><?php $clang->eT("Status"); ?></th>
                <th>&nbsp;</th>
                <th><?php $clang->eT("Total Nos"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $type = '';
            for ($i = 0; $i < count($drows); $i++) {
                $r = $drows[$i];
                if ($r['type'] != $type && $r['type'] == 'pr')
                    echo '<tr><td colspan="3" style="background-color: lightgray;"><h4>PROJECT COUNTs STATUS WISE</h4>';
                if ($r['type'] != $type && $r['type'] == 'pl')
                    echo '<tr><td colspan="3" style="background-color: lightgray;"><h4>PANEL LIST COUNTs STATUS WISE</h4>';
                ?>
                <tr>
                    <td><?php echo $r['status_name']; ?></td>
                    <td><span style="display:block;height:20px; width:20px; background-color: <?php echo $r['status_color']; ?>;">&nbsp;</span></td>
                    <td><?php echo htmlspecialchars($r['tots']); ?></td>
                </tr>
                <?php
                $type = $r['type'];
            }
            ?>
        </tbody>
    </table>
</div>