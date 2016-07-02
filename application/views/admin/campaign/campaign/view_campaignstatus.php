<div class='header ui-widget-header'><?php $clang->eT("Manage Sources type"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listContactGroup').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>
<table id="listContactGroup" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
           <th><?php $clang->eT("Delete"); ?></th>
            <th><?php $clang->eT("Campaign Status Id"); ?></th>
            <th><?php $clang->eT("Campaign Status Name"); ?></th>
             <th><?php $clang->eT("Campaign Status Code"); ?></th>
            
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            ?>
            <tr>

                <td style="padding:3px;">    
                    <?php echo CHtml::form(array('admin/campaign/sa/modifycampaignstatus'), 'post'); ?>
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Status"); ?>' />
                    <input type='hidden' name='action' value='modifycampaignstatus' />
                    <input type='hidden' name='cs_id' value='<?php echo $usr['cs_id']; ?>' />
                    </form>
                </td>
              <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/campaign/sa/delcampaignstatus'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this Status"); ?>' />
                    <input type='hidden' name='action' value='delcampaignstatus' />
                    <input type='hidden' name='cs_id' value='<?php echo $usr['cs_id']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['cs_id']; ?></td>
                <td><?php echo htmlspecialchars($usr['status_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['status_code']); ?></td>
                
            </tr>
            <?php $row++;
        } ?>
    </tbody>
</table>