<div class='header ui-widget-header'><?php $clang->eT("Rectify Project Redirects"); ?></div><br />
<table class="InfoForm" cellpadding="5px" cellspacing="5px" width="60%" border="0" style="margin-left:20%;" >
    <caption>CSV Import Details</caption>
    <tbody>
        <tr>
            <th>RedirectID</th>
            <th>Status</th>
            <th>Error</th>
        </tr>
        <?php
        echo $trerror;
        ?>
        <tr>
            <td colspan="3"><hr></hr></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center">
                <a href="<?php echo $this->createUrl('admin/project/sa/modifyproject/project_id/' . $_GET['project_id'] . '/action/modifyproject') ?>" class="limebutton">Back</a>&nbsp; &nbsp;
            </td> 
        </tr>
    </tbody>
</table>
