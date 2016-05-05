<div class='header ui-widget-header'><?php $clang->eT("Profile Questions"); ?></div><br />
<script>
    
    $(document).ready(function() {
        $('#listreg').dataTable({"sPaginationType": "full_numbers"});
        $('#listgeo').dataTable({"sPaginationType": "full_numbers"});
        $('#listprof').dataTable({"sPaginationType": "full_numbers"});
        $('#listproj').dataTable({"sPaginationType": "full_numbers"});
    } );
    
</script>

<div id='tabs'>
    <ul>
        <li><a href='#registration' id="tab-01"><?php $clang->eT("Registration"); ?></a></li>
        <li><a href='#geographical' id="tab-02"><?php $clang->eT("Geographical"); ?></a></li>
        <li><a href='#profile' id="tab-03"><?php $clang->eT("Profile"); ?></a></li>
        <li><a href='#project' id="tab-04"><?php $clang->eT("Project"); ?></a></li>
    </ul>

    <?php
    $listreg = '<table id="listreg" style="width:100%">
    <thead>
        <tr>
            <th>Edit</th>
            <th>ID</th>
            <th>Title</th>
            <th>IsActive</th>
        </tr>
    </thead>
    <tbody>';

    $listgeo = '<table id="listgeo" style="width:100%">
    <thead>
        <tr>
            <th>Edit</th>
            <th>ID</th>
            <th>Title</th>
            <th>IsActive</th>
        </tr>
    </thead>
    <tbody>';

    $listprof = '<table id="listprof" style="width:100%">
    <thead>
        <tr>
            <th>Edit</th>
            <th>ID</th>
            <th>Title</th>
            <th>IsActive</th>
        </tr>
    </thead>
    <tbody>';

    $listproj = '<table id="listproj" style="width:100%">
    <thead>
        <tr>
            <th>Edit</th>
            <th>ID</th>
            <th>Title</th>
            <th>IsActive</th>
        </tr>
    </thead>
    <tbody>';
    ?>

    <?php
    for ($i = 0; $i < count($usr_arr); $i++) {
        $usr = $usr_arr[$i];
        $listtype = "x";
        if ($usr['category_id'] == '1')
            $listtype = "listreg";
        if ($usr['category_id'] == '2')
            $listtype = "listgeo";
        if ($usr['category_id'] == '3')
            $listtype = "listprof";
        if ($usr['category_id'] == '4')
            $listtype = "listproj";

        $cstatus = "Yes";
        if ($usr['IsActive'] == "0")
            $cstatus = "No";
        $$listtype .= '<tr>
                <td style="padding:3px;">';
        $$listtype .= CHtml::form(array('admin/profilequestion/sa/mod/action/modifyquestion/question_id/' . $usr['id'] . ''), 'post');
        $$listtype .= "<input type='image' src='" . $imageurl . "edit_16.png' alt='Edit this Question' />
                    </form>
                </td>
                <td>" . $usr['id'] . "</td>              
                <td>" . htmlspecialchars($usr['short_title']) . "</td>
                <td>$cstatus</td>
            </tr>";
        $row++;
    }
    ?>

    <?php
    $listreg .= '</tbody></table>';
    $listgeo .= '</tbody></table>';
    $listprof .= '</tbody></table>';
    $listproj .= '</tbody></table>';
    ?>


    <div id="registration">        
        <?php echo $listreg; ?>
    </div>        
    <div id="geographical">        
        <?php echo $listgeo; ?>
    </div>    
    <div id="profile">        
        <?php echo $listprof; ?>
    </div>
    <div id="project">        
        <?php echo $listproj; ?>
    </div>
</div>


</tbody>
</table>