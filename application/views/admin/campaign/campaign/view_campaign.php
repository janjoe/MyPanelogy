<div class='header ui-widget-header'><?php $clang->eT("Manage Campaigns"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listContactGroup').dataTable({"sPaginationType": "full_numbers"});
    } );
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
});
</script>
<table id="listContactGroup" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
<!--            <th><?php $clang->eT("Delete"); ?></th>-->
            <th><?php $clang->eT("ID"); ?></th>
            <th><?php $clang->eT("Campaign Name"); ?></th>
            <th><?php $clang->eT("Campaign Code"); ?></th>
            <th><?php $clang->eT("Cost"); ?></th>
            <th><?php $clang->eT("Campaign source"); ?></th>
            <th><?php $clang->eT("Source Type"); ?></th>
            <th><?php $clang->eT("Status"); ?></th>
            <th><?php $clang->eT("Created By"); ?></th>
            <th><?php $clang->eT("Create Date"); ?></th>
            <th><?php $clang->eT("Unique Hit"); ?></th>
            <th><?php $clang->eT("Share Link"); ?></th>
            <th><?php $clang->eT("Invited to 1st survey"); ?></th>

            
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            ?>
            <tr>

                <td style="padding:3px;">    
                    <?php echo CHtml::form(array('admin/campaign/sa/modifycampaign'), 'post'); ?>
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Page"); ?>' />
                    <input type='hidden' name='action' value='modifycampaign' />
                    <input type='hidden' name='cp_id' value='<?php echo $usr['id']; ?>' />
                    </form>
                </td>
<?php /*               <td  style="padding:3px;">
                    <?php echo CHtml::form(array('admin/cms/sa/delcms'), 'post', array('onsubmit' => 'return confirm("' . $clang->gT("Are you sure you want to delete this entry?", "js") . '")')); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>token_delete.png' alt='<?php $clang->eT("Delete this Page"); ?>' />
                    <input type='hidden' name='action' value='delcms' />
                    <input type='hidden' name='page_id' value='<?php echo $usr['page_id']; ?>' />
                    </form>
                </td> */?>
                <td><?php echo $usr['id']; ?></td>
                <td><a href="#"  data-html="true" data-toggle="popover" data-trigger="hover" data-content="<?php echo htmlspecialchars($usr['notes']); ?>"><?php echo htmlspecialchars($usr['campaign_name']); ?></a></td>
                <td><?php echo htmlspecialchars($usr['campaign_code']); ?></td>
                <td><?php echo $usr['cost']; ?></td>
                <td><?php echo htmlspecialchars($usr['source_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['name']); ?></td>
                <td><?php echo htmlspecialchars($usr['status_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['full_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['created_date']); ?></td>
                <td><?php echo htmlspecialchars($usr['unique_hit']); ?></td>
                <?php 
                     $pagejoin = 'JOIN NOW';
                     if (isset ($usr['page_name']) && !empty($usr['page_name']) ) 
                        {  
                            $pagejoin = $usr['page_name'];
                        }    
                 ?>
                <td><a href='<?php echo Yii::app()->getBaseUrl()."/index.php/?pagename=".$pagejoin."&cmp=".base64_encode($usr['id']); ?>' target='_blank'>Link</a></td>
                <td>
                <?php 
                    if(isset($usr['total_first_survey_sent_users'])) 
                        { 
                            echo $usr['total_first_survey_sent_users'].'%'; 
                        }
                     ?>
                            
                </td>
                
            </tr>
            <?php $row++;
        } ?>
    </tbody>
</table>