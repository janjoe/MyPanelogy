<?php if ($display['header']) { ?>
    <div class='header'>
        <?php $clang->eT("Analyze Projects"); ?>
        <a href="<?php echo $this->createUrl('admin/reports/sa/project/print') ?>" target="_blank" style="float:right; padding-right: 50px;">
               <input type='button' value='<?php $clang->eT("Print"); ?>' />
        </a>
    </div>
<?php } ?>
<br />
<div style="text-align: center; padding-left: 10%; width: 100%;">
    <!-- Company wise analysis -->
    <table id="dashboard" style="width:80%;border-top: 2px double green;border-bottom: 2px double green;border-left: 2px double green;border-right: 2px double green;">
        <caption><?php $clang->eT("Company wise Cost, Revenues and Profit Analysis"); ?></caption>
        <thead>
            <tr>
                <th><?php $clang->eT("Company"); ?></th>
                <th><?php $clang->eT("Company's Avg CPC"); ?></th>
                <th><?php $clang->eT("Vendor's Avg. CPC"); ?></th>
                <th><?php $clang->eT("Total Cost"); ?></th>
                <th><?php $clang->eT("Total Revenues"); ?></th>
                <th><?php $clang->eT("Gross Profit"); ?></th>
                <th><?php $clang->eT("Total Completes"); ?></th>
                <th><?php $clang->eT("Profit %"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < count($dr_det1); $i++) {
                $r = $dr_det1[$i];
                if ($dr_sum1[0]['total_profit'] != 0 && $r['tot_profit'] != 0)
                    $per = ($r['tot_profit'] * 100) / $dr_sum1[0]['total_profit'];
                else
                    $per = 0;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($r['company_name']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($r['avg_comp_cpc'],2)); ?></td>
                    <td><?php echo htmlspecialchars(number_format($r['avg_ven_cpc'],2)); ?></td>
                    <td><?php echo htmlspecialchars($r['tot_cost']); ?></td>
                    <td><?php echo htmlspecialchars($r['tot_revenues']); ?></td>
                    <td><?php echo htmlspecialchars($r['tot_profit']); ?></td>
                    <td><?php echo htmlspecialchars($r['tot_completed']); ?></td>
                    <td><span style="display:block;height:20px; width:<?php echo $per; ?>px; background-color:darkblue; color: orange;"><?php echo number_format($per,2); ?>%</span></td>
                </tr>
                <?php
            }
            $rs = $dr_sum1[0];
            ?>
        <tfoot>
            <tr>
                <th><b>Grand Totals</b></th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th><?php echo $rs['total_cost']; ?></th>
                <th><?php echo $rs['total_revenues']; ?></th>
                <th><?php echo $rs['total_profit']; ?></th>
                <th><?php echo $rs['total_completed']; ?></th>
                <th>&nbsp;</th>
            </tr>
        </tfoot>
        </tbody>
    </table>

<br/><br/>

    <!-- Sales Executive wise analysis -->
    <table id="dashboard" style="width:80%;border-top: 2px double green;border-bottom: 2px double green;border-left: 2px double green;border-right: 2px double green;">
        <caption><?php $clang->eT("Sales Executive wise Cost, Revenues and Profit Analysis"); ?></caption>
        <thead>
            <tr>
                <th><?php $clang->eT("Sales Executive"); ?></th>
                <th><?php $clang->eT("Company's Avg CPC"); ?></th>
                <th><?php $clang->eT("Vendor's Avg. CPC"); ?></th>
                <th><?php $clang->eT("Total Cost"); ?></th>
                <th><?php $clang->eT("Total Revenues"); ?></th>
                <th><?php $clang->eT("Gross Profit"); ?></th>
                <th><?php $clang->eT("Total Completes"); ?></th>
                <th><?php $clang->eT("Profit %"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i=0;
            for ($i = 0; $i < count($dr_det2); $i++) {
                $r = $dr_det2[$i];
                if ($dr_sum1[0]['total_profit'] != 0 && $r['tot_profit'] != 0)
                    $per = ($r['tot_profit'] * 100) / $dr_sum1[0]['total_profit'];
                else
                    $per = 0;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($r['sales_name']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($r['avg_comp_cpc'],2)); ?></td>
                    <td><?php echo htmlspecialchars(number_format($r['avg_ven_cpc'],2)); ?></td>
                    <td><?php echo htmlspecialchars($r['tot_cost']); ?></td>
                    <td><?php echo htmlspecialchars($r['tot_revenues']); ?></td>
                    <td><?php echo htmlspecialchars($r['tot_profit']); ?></td>
                    <td><?php echo htmlspecialchars($r['tot_completed']); ?></td>
                    <td><span style="display:block;height:20px; width:<?php echo $per; ?>px; background-color:darkblue; color: orange;"><?php echo number_format($per,2); ?>%</span></td>
                </tr>
                <?php
            }
            $rs = $dr_sum1[0];
            ?>
        <tfoot>
            <tr>
                <th><b>Grand Totals</b></th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th><?php echo $rs['total_cost']; ?></th>
                <th><?php echo $rs['total_revenues']; ?></th>
                <th><?php echo $rs['total_profit']; ?></th>
                <th><?php echo $rs['total_completed']; ?></th>
                <th>&nbsp;</th>
            </tr>
        </tfoot>
        </tbody>
    </table>

</div>