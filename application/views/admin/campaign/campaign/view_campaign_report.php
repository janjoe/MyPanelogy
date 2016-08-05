<div class='header ui-widget-header'><?php $clang->eT("Manage Campaigns Reports"); ?></div><br />

<style type="text/css">.popover {min-width: 300px;}</style>
<script src="<?php echo App()->baseUrl; ?>/scripts/jquery.table2excel.js"></script>
<script>
    

        
    $(document).ready(function() {
         $("#export").click(function(){
                var x = $("#listContactGroup").clone();
                $(x).find("tr td a").replaceWith(function(){
                  return $.text([this]);
                });

                $(x).table2excel({
                    exclude: ".noExl",
                    name: "Excel Document Name",
                    filename: "campaignreport",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    columns : [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17]
                });
            });

        $('#listContactGroup').dataTable({"sPaginationType": "full_numbers"});

        $('html').on('click', function(e) {
          if (typeof $(e.target).data('original-title') == 'undefined' &&
             !$(e.target).parents().is('.popover.in')) {
            $('[data-original-title]').popover('hide');
          }
        });

    });

    
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
});
</script>
<table border="0" cellspacing="5" cellpadding="5">
	<?php echo CHtml::form(array("admin/campaign/sa/campaignreport"), 'post', array('id' => 'filterform', 'enableClientValidation' => true )); ?>
	    <tbody>
	        <tr>
	            <td>Source</td>
	            <td>
	                <select id="source" name="source">
	                    <option value="">Select source</option>
	                    <?php  for ($i = 0; $i < count($cmp_source); $i++) {
	                        $source = $cmp_source[$i];
	                    ?>
	                        <option <?php if(isset($_POST['source']) && $_POST['source'] == $source['cmp_id'] ) echo 'selected'; ?> value="<?php echo $source['cmp_id']; ?>"><?php echo $source['source_name']; ?></option>
	                    <?php } ?>
	                </select>
	            </td>
	        
	            <td>Type</td>
	            <td>
	                <select id="sourcetype" name="sourcetype">
	                    <option value="">Select sourcetype</option>
	                    <?php  for ($i = 0; $i < count($cmp_source_type); $i++) {
	                        $source_type = $cmp_source_type[$i];
	                    ?>
	                        <option <?php if(isset($_POST['sourcetype']) && $_POST['sourcetype'] == $source_type['cst_id'] ) echo 'selected'; ?> value="<?php echo $source_type['cst_id']; ?>"><?php echo $source_type['name']; ?></option>
	                    <?php } ?>
	                </select>
	            </td>

	            <td>Campaign</td>
	            <td>
	                <select id="campaign" name="campaign">
	                    <option value="">Select campaign</option>
	                    <?php  for ($i = 0; $i < count($usr_arr); $i++) {
	                        $campaign = $usr_arr[$i];
	                    ?>
	                        <option <?php if(isset($_POST['campaign']) && $_POST['campaign'] == $campaign['id'] ) echo 'selected'; ?> value="<?php echo $campaign['id']; ?>"><?php echo $campaign['campaign_name']; ?></option>
	                    <?php } ?>
	                </select>
	            </td>
	            <td>Status</td>
	            <td>
	                <select id="status" name="status">
	                    <option value="">Select status</option>
	                    <?php  for ($i = 0; $i < count($cmp_status); $i++) {
	                        $cmp_st = $cmp_status[$i];
	                    ?>
	                        <option <?php if(isset($_POST['status']) && $_POST['status'] == $cmp_st['cs_id'] ) echo 'selected'; ?> value="<?php echo $cmp_st['cs_id']; ?>"><?php echo $cmp_st['status_name']; ?></option>
	                    <?php } ?>
	                </select>
	            </td>
	            </tr>
	            <tr>
	            	<td>Date Range</td>
	            	<td>
                        <?php
                            if(isset($_POST['from'])) $from = $_POST['from']; else $from = '';
                            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'name' => 'from',
                                'value' => $from,
                                // additional javascript options for the date picker plugin
                                'options' => array(
                                    'dateFormat' => 'dd-mm-yy',
                                    'showAnim' => 'blind',
                                    'changeMonth' => true,
                                    'changeYear' => true,
                                    'yearRange' => '1930:2030'
                                ),
                                'htmlOptions' => array(
                                    'style' => 'height:20px;',
                                    'required' => false
                                ),
                            ));
                        ?>


                    <!-- <input type="date" name="from" id="from" class="hasDatepicker" value="<?php //echo isset($_POST['from']) ? $_POST['from'] : ''; ?>"> -->


                    </td>
	            	<td>TO</td>
	            	<td>

                        <?php
                            if(isset($_POST['to'])) $to = $_POST['to']; else $to = '';
                            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'name' => 'to',
                                'value' => $to,
                                // additional javascript options for the date picker plugin
                                'options' => array(
                                    'dateFormat' => 'dd-mm-yy',
                                    'showAnim' => 'blind',
                                    'changeMonth' => true,
                                    'changeYear' => true,
                                    'yearRange' => '1930:2030'
                                ),
                                'htmlOptions' => array(
                                    'style' => 'height:20px;',
                                    'required' => false
                                ),
                            ));
                        ?>

                    <!-- <input type="text" name="to" id="to" class="hasDatepicker" value="<?php //echo isset($_POST['to']) ? $_POST['to'] : ''; ?>"> -->
                    </td>
	            	
	            </tr>
	            <tr>
	            	<td>Revenue</td>
	            	<td><input type="number" name="revenue" id="revenue" value="<?php echo isset($_POST['revenue']) ? $_POST['revenue'] : ''; ?>"></td>
	            	<td>Roi</td>	
	            	<td><input type="number" name="roi" id="roi" value="<?php echo  isset($_POST['roi']) ? $_POST['roi'] : ''; ?>"></td>
	            	<td>Unique hits to site</td>
	            	<td><input type="number" name="uniquehit" id="uniquehit" value="<?php echo isset($_POST['uniquehit']) ? $_POST['uniquehit'] : ''; ?>"></td>
	            	<td>Completed Initial registration</td>
	            	<td><input type="number" name="initreg" id="initreg" value="<?php echo  isset($_POST['initreg']) ? $_POST['initreg'] : ''; ?>"></td>
	        	</tr>
	        	<tr><td><input type="submit" name="filter" value="Filter Result"></td><td><input type="button" onclick="javascript:window.location.href='<?php echo $this->createUrl('admin/campaign/campaignreport'); ?>';" name="reset" value="Reset Filter"></td></tr>
	    </tbody>
	</form>    
</table>
<input style="float:right;" type="button" name="export" id="export" value="Export to excel">
<table id="listContactGroup" style="width:100%">
    <thead>
        <tr>
            <th class="noExl"><?php $clang->eT("Edit"); ?></th>
            <th><?php $clang->eT("ID"); ?></th>
            <th><?php $clang->eT("Campaign source"); ?></th>
            <th><?php $clang->eT("Campaign Name"); ?></th>
            <th><?php $clang->eT("Cost"); ?></th>
            <th><?php $clang->eT("Revenue"); ?></th>
            <th><?php $clang->eT("ROI"); ?></th>
            <th><?php $clang->eT("Unique Hit"); ?></th>
            <th><?php $clang->eT("Completed Initial registration"); ?></th>
            <th><?php $clang->eT("Completed page 2 registration"); ?></th>
            <th><?php $clang->eT("Invited to 1st survey"); ?></th>
            <th><?php $clang->eT("Completed 1st survey"); ?></th>
            <th><?php $clang->eT("Surveys completed"); ?></th>
            <th><?php $clang->eT("Response rate"); ?></th>
            <th><?php $clang->eT("% rejected completes"); ?></th>
            <th><?php $clang->eT("Fraud Status"); ?></th>
            <th><?php $clang->eT("Member Cancelled Membership"); ?></th>
            <th><?php $clang->eT("Culled"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count($usr_arr); $i++) {
            $usr = $usr_arr[$i];
            ?>
            <tr>
                <td class="noExl" style="padding:3px;">    
                    <?php echo CHtml::form(array('admin/campaign/sa/modifycampaign'), 'post'); ?>
                    <input type='image' src='<?php echo $imageurl; ?>edit_16.png' alt='<?php $clang->eT("Edit this Page"); ?>' />
                    <input type='hidden' name='action' value='modifycampaign' />
                    <input type='hidden' name='cp_id' value='<?php echo $usr['id']; ?>' />
                    </form>
                </td>

                <td><?php echo $usr['id']; ?></td>
                <td><?php echo htmlspecialchars($usr['source_name']); ?></td>
                <td><a href="#"  data-html="true" data-toggle="popover" data-trigger="hover" data-content="<?php echo htmlspecialchars($usr['notes']); ?>"><?php echo htmlspecialchars($usr['campaign_name']); ?></a></td>

                <?php /* <td><?php echo htmlspecialchars($usr['campaign_code']); ?></td>*/?>
                <td><?php echo $usr['cost']; ?></td>
                <td><?php echo ($usr['cost'] * $usr['total_revenue']); ?></td>
                
                <td><?php echo ($usr['cost'] * $usr['total_revenue']) - $usr['cost']; ?></td>
                <td><?php echo htmlspecialchars($usr['unique_hit']); ?></td>
                <?php 
                if($usr['unique_hit'] != 0){ 
                    $initregcomplete = ($usr['initregcomplete'] * 100 ) / $usr['unique_hit'];
                    $initregcomplete = number_format($initregcomplete, 0);
                 }   
                 ?>
                <td><a href="#"  data-html="true" data-toggle="popover" data-trigger="hover" data-content="<?php echo $initregcomplete.'%'; ?>"><?php echo htmlspecialchars($usr['initregcomplete']); ?></a>
                </td>
                

                <?php 
                if($usr['unique_hit'] != 0){ 
                    $complete = ($usr['complete'] * 100 ) / $usr['unique_hit'];
                    $complete = number_format($complete, 0);
                 }   
                 ?>

                <td><a data-html="true"  data-toggle="popover" data-placement="bottom" data-trigger="hover"  href='javascripit:' data-content="<?php echo $complete.'%'; ?>" ><?php echo htmlspecialchars($usr['complete']); ?></a>
                </td>
                
                <?php 
                    $invitetofirstservey = 0;
                    if($usr['complete'] != 0){ 
                        
                        $invitetofirstservey = ($usr['total_first_survey_sent_users'] * 100 ) / $usr['complete'];
                        $invitetofirstservey = number_format($invitetofirstservey, 0);
                    }  
                ?>
                <td>
                    <a data-html="true"  data-toggle="popover" data-placement="bottom" data-trigger="hover"  href='javascripit:' data-content="<?php echo $invitetofirstservey.'%'; ?>" >
                        <?php echo $usr['total_first_survey_sent_users'];  ?>
                    </a>       
                </td>
                <?php 
                    $completeinvitetofirstservey = 0;
                    if($usr['complete'] != 0){ 
                        
                        $completeinvitetofirstservey = ($usr['total_first_survey_sent_users_complete'] * 100 ) / $usr['total_first_survey_sent_users'];
                        $completeinvitetofirstservey = number_format($completeinvitetofirstservey, 0);
                    }  
                ?>
                <td><a data-html="true"  data-toggle="popover" data-placement="bottom" data-trigger="hover"  href='javascripit:' data-content="<?php echo $completeinvitetofirstservey.'%'; ?>" ><?php echo htmlspecialchars($usr['total_first_survey_sent_users_complete']); ?></a>
                </td>
                <td><?php echo $usr['total_revenue']; ?></td>
                <?php 
                    $totalresponse = 0;
                    if($usr['total_invite_users_per_campaign'] != 0){ 
                        
                        $totalresponse = ($usr['total_servey_response_by_campaign_user'] * 100 ) / $usr['total_invite_users_per_campaign'];
                        $totalresponse = number_format($totalresponse, 0);
                    }  
                ?>
                <td><?php  echo $totalresponse.'%'; ?></td>
                <td>0</td>
                <?php 
                    $frodcomplete = 0;
                    if($usr['complete'] != 0){ 
                        
                        $frodcomplete = ($usr['frod_user'] * 100 ) / $usr['complete'];
                        $frodcomplete = number_format($frodcomplete, 0);
                    }  
                ?>
                <td><a data-html="true"  data-toggle="popover" data-placement="bottom" data-trigger="hover"  href='javascripit:' data-content="<?php echo $frodcomplete.'%'; ?>" ><?php  echo $usr['frod_user']; ?></a></td>

                <?php 
                    $cancle_member = 0;
                    if($usr['complete'] != 0){ 
                        
                        $cancle_member = ($usr['cancle_account_user'] * 100 ) / $usr['complete'];
                        $cancle_member = number_format($cancle_member, 0);
                    }  
                ?>
                <td><a data-html="true"  data-toggle="popover" data-placement="bottom" data-trigger="hover"  href='javascripit:' data-content="<?php echo $cancle_member.'%'; ?>" ><?php  echo $usr['cancle_account_user']; ?></a></td>
                <td>0</td>
                
            </tr>
            <?php $row++;
        } ?>
    </tbody>
</table>