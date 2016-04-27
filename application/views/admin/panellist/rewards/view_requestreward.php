<div class='header ui-widget-header'><?php $clang->eT("Reward Request"); ?></div><br />
<script>
    $(document).ready(function() {
        $('#tblPending').dataTable({"sPaginationType": "full_numbers"});
        $('#tblProcessing').dataTable({"sPaginationType": "full_numbers"});
        $('#tblFulfilled').dataTable({"sPaginationType": "full_numbers"});       
    } );   
    
    function checkAll(){
        $("INPUT[type='checkbox']").attr('checked', true);
    }

    function uncheckAll(){
        $("INPUT[type='checkbox']").attr('checked', false);
    }
    
    function reloadpage(){
        location.reload();
    }
</script>

<div id='tabs'>
    <ul>
        <li><a href='#pending' id="tab-01"><?php $clang->eT("Pending"); ?></a></li>
        <li><a href='#processing' id="tab-02"><?php $clang->eT("Processing"); ?></a></li>
        <li><a href='#fulfilled' id="tab-03"><?php $clang->eT("Fulfilled"); ?></a></li>
    </ul>
    <div id="pending">        
        <?php
        echo "<div id='your-form-block-id'>";
        echo CHtml::beginForm();
        echo "<table id='tblPending' style='width:100%'>";
        $sql = "select * from {{view_reward_request}} where status=0";
        $result = Yii::app()->db->createCommand($sql)->query()->readAll();
        echo "<thead>
                <tr>
                    <th></th>
                    <th>Id</td>
                    <th>Panelist Name</th>
                    <th>Panelist Email</th>
                    <th>Date of Request</th>
                    <th>Reward Type</th>
                    <th>Reward Title</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
             </thead>
             <tbody>";
        foreach ($result as $key => $value) {
            echo "<tr>
                    <td><input type='checkbox' name='chk[]' value=" . $value['id'] . " id='chk' /></td>
                    <td>" . $value['id'] . "</td>
                    <td>" . $value['full_name'] . "</td>
                    <td>" . $value['email'] . "</td>
                    <td>" . $value['date'] . "</td>
                    <td>" . $value['name'] . "</td>
                    <td>" . $value['title'] . "</td>
                    <td>" . $value['amount'] . "</td>
                    <td>Pending</td>
                </tr>";
        }
        echo "</tbody>
        </table>";
        echo '<br/><br/><br/>';
        if (count($result) > 0) {
            ?>
            <a href="<?php echo CController::createUrl('admin/rewards/sa/rewardprocess/type/process') ?>" class="class-link limebutton" >
                Process
            </a>
            <input type="button" name="CheckAll" value="Check All" onClick="checkAll()"/>
            <input type="button" name="UnCheckAll" value="Uncheck All" onClick="uncheckAll()"/>
            <?php
        }
        echo CHtml::endForm();
        echo "</div>";
        ?>
    </div>        
    <div id="processing">        
        <?php
        echo "<div id='your-form-block-id'>";
        echo CHtml::beginForm();
        echo "<table id='tblProcessing' style='width:100%'>";
        $sql = "select * from {{view_reward_request}} where status=1";
        $result = Yii::app()->db->createCommand($sql)->query()->readAll();
        echo "<thead>
                <tr>
                    <th></th>
                    <th>Id</td>
                    <th>Panelist Name</th>
                    <th>Panelist Email</th>
                    <th>Date of Request</th>
                    <th>Reward Type</th>
                    <th>Reward Title</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Note</th>
                </tr>
             </thead>
             <tbody>";
        foreach ($result as $key => $value) {
            echo "<tr>
                    <td><input type='checkbox' name='chk[]' value=" . $value['id'] . " id='chk' /></td>
                    <td>" . $value['id'] . "</td>
                    <td>" . $value['full_name'] . "</td>
                    <td>" . $value['email'] . "</td>
                    <td>" . $value['date'] . "</td>
                    <td>" . $value['name'] . "</td>
                    <td>" . $value['title'] . "</td>
                    <td>" . $value['amount'] . "</td>
                    <td>Processing</td>
                    <td><input maxlength='200' type='text' name=" . $value['id'] . " id=" . $value['id'] . " /></td>
                </tr>";
        }
        echo "</tbody>
        </table>";
        echo '<br/><br/><br/>';
        if (count($result) > 0) {
            ?>
            <a href="<?php echo CController::createUrl('admin/rewards/sa/rewardprocess/type/fulfilled') ?>" class="class-link limebutton" >
                Fulfilled
            </a>
            <input type="button" name="CheckAll" value="Check All" onClick="checkAll()"/>
            <input type="button" name="UnCheckAll" value="Uncheck All" onClick="uncheckAll()"/>
            <?php
        }
        echo CHtml::endForm();
        echo "</div>";
        ?>
    </div>    
    <div id="fulfilled">        
        <?php
        echo "<table id='tblFulfilled' style='width:100%'>";
        $sql = "select * from {{view_reward_request}} where status=2";
        $result = Yii::app()->db->createCommand($sql)->query()->readAll();
        echo "<thead>
                <tr>
                    <th>Id</td>
                    <th>Panelist Name</th>
                    <th>Panelist Email</th>
                    <th>Date of Request</th>
                    <th>Reward Type</th>
                    <th>Reward Title</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Note</th>
                </tr>
             </thead>
             <tbody>";
        foreach ($result as $key => $value) {
            echo "<tr>
                    <td>" . $value['id'] . "</td>
                    <td>" . $value['full_name'] . "</td>
                    <td>" . $value['email'] . "</td>
                    <td>" . $value['date'] . "</td>
                    <td>" . $value['name'] . "</td>
                    <td>" . $value['title'] . "</td>
                    <td>" . $value['amount'] . "</td>
                    <td>Fullfilled</td>
                    <td>" . $value['paypal_trnaid'] . "</td>
                </tr>";
        }
        echo "</tbody>
        </table>";
        ?>
    </div>
</div>
