<div class='header ui-widget-header'><?php $clang->eT("Rewards"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listprofilecategory').dataTable({"sPaginationType": "full_numbers"});
    } );
    
</script>

<table id="listprofilecategory" style="width:100%">
    <thead>
        <tr>
            <th><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("ID"); ?></th>
            <th><?php $clang->eT("Title"); ?></th>
            <th><?php $clang->eT("IsActive"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            ?>
            <tr>

                <td style="padding:3px;">
                    <?php echo CHtml::form(array('admin/rewards/sa/mod'), 'post'); ?>            
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Reward"); ?>' />
                    <input type='hidden' name='action' value='modifyrewards' />
                    <input type='hidden' name='id' value='<?php echo $usr['id']; ?>' />
                    </form>
                </td>
                <td><?php echo $usr['id']; ?></td>              
                <td><?php echo htmlspecialchars($usr['title']); ?></td>
                <td><?php $cstatus="Yes"; if($usr['IsActive']=="0") $cstatus="No"; echo $cstatus;?></td>
            </tr>
            <?php
            $row++;
        }
        ?>
    </tbody>
</table>