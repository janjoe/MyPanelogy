<div class='header ui-widget-header'>Viewing details of <?php  echo $cmpname; ?></div><br />
<!-- <h4 id="popup_title" class="popup_title" style="text-align: center;">Viewing details of <?php  echo $cmpname; ?></h4> -->
<script>
     function reloadpage(){
        return true;
    }
    $(document).ready(function() {
        $('#listContactGroup').dataTable({"sPaginationType": "full_numbers"});

        $('html').on('click', function(e) {
          if (typeof $(e.target).data('original-title') == 'undefined' &&
             !$(e.target).parents().is('.popover.in')) {
            $('[data-original-title]').popover('hide');
          }
        });

    });

</script>
<table id="listContactGroup" style="width:100%">
    <thead>
        <tr>
            <th>View</th>
            <th>ID</th>
            <th>First name</th>
            
            <th>Last name</th>
            <th>Email</th>
            
            <th>Status</th>
            <th>Created date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        //print_r($usr_arr); exit();
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];

            $pl_query = "SELECT panel_list_id FROM {{panel_list_master}} WHERE email = '" . $usr['email'] . "'";
            $dr = Yii::app()->db->createCommand($pl_query)->query()->readAll();


            if ($odd) {
                    $cls = 'class="odd"';
                } else {
                    $cls = 'class="even"';
                }
            ?>
            <tr <?php echo $cls; ?>>
                <td  style="padding:3px; width:25px">

                    <?php
                    if(!empty($dr) && $dr[0]['panel_list_id'] != ''){
                    //17/06/2014 Add By Parth-Hari
                    echo "<div id='your-form-block-id'>";
                    echo CHtml::beginForm();
                    //echo CHtml::link($row['panel_list_id'], array('admin/panellist/PanellistInfo/panel_list_id/' . $row['panel_list_id']), array('class' => 'class-link'));
                    echo CHtml::link("<img src='" . $imageurl . "icon-view.png' width='24px;' alt='View Panel List Profile Details'/>", array('admin/panellist/PanellistInfo/panel_list_id/'.$dr[0]['panel_list_id']), array('class' => 'class-link'));
                    echo CHtml::endForm();
                    echo "</div>";
                    //17/06/2014 End
                    }
                    ?> 
                </td>
                <td><?php //echo $usr['id']; 
                 if(!empty($dr) && $dr[0]['panel_list_id'] != ''){ echo $dr[0]['panel_list_id']; } ?></td>
                <td><?php echo $usr['first_name']; ?></td>
                <td><?php echo htmlspecialchars($usr['last_name']); ?></td>
                <td><?php echo htmlspecialchars($usr['email']); ?></td>
                <td><?php if($usr['status'] == 0) echo 'Email sent'; else echo 'Email sent and successfully responded'; ?></td>
                <td><?php echo $usr['create_date']; ?></td>
                
                
            </tr>
            <?php $odd = !$odd; $row++;
        } ?>
    </tbody>
</table>
