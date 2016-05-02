<style type="text/css">    /* Query Boxes */
    span.trues {border:1px solid green;padding:2px;}
    span.falses {border:1px solid red;padding:2px;}
</style>
<script>
    $(document).ready(function() {	
        stack = $('#stack');
        totalr = $('#total_r');
        stack.blur(getprice);
        stack.keyup(getprice);
        function getprice(){
            var stack_v = stack.val();
            var totalr_v = totalr.val();
            var r = totalr_v-stack_v ;
            $("#dispr").text("");
            if(stack_v > totalr_v){
                $("#dispr").removeClass("trues");
                $("#dispr").addClass("falses");
                $("#dispr").text(r);
            } else {
                $("#dispr").removeClass("falses");
                $("#dispr").addClass("trues");
                $("#dispr").text(r);	
            }
        }
    });
</script>

<script>
    $(function() {
        $("form").bind("keypress", function(e) {
            if (e.keyCode == 13) return false;
        });
    });
</script>

<script>    
    function saveQuery(){
        var Error = 0;
        if($("#st").val() == 1){
            Error = 1;
            alert("This Project is on Hold");
        }else{
            if($("#dispr").text()<0){
                Error = 1;        
                alert("Sending amount can not exceed remainig counts");
            }
            if($("#stack").val()<1){
                Error = 1;
                alert("Sending amount should be higher than 0");
            }
        
            if(Error == 0){
                document.forms["sqlqueryform"].submit();
            }
        }
    }

</script>
<?php
$project = Project::model()->findAllByPk($project_id);
$st = 0;
if ($project[0]['project_status_id'] == getGlobalSetting('project_status_hold')) {
    $st = 1;
}
?>
<div class='header ui-widget-header'>
    <?php $clang->eT($type . ' Query : ' . $query_name . ' [' . $query_id . '] ' . ' For Project :' . $project[0]['project_name'] . ' [' . $project_id . ']'); ?>
</div>
<br />
<?php echo CHtml::form(array("admin/pquery/sa/send"), 'post', array('class' => 'form30', 'id' => 'sqlqueryform', 'enctype' => 'multipart/form-data')); ?>
<?php $total_r = count(GetPanellistIDsForSend($query_id, $project_id, $type)); ?>

N : <input type="text" name="stack" id="stack" required>
<input type="hidden" value="<?php echo $total_r; ?>" name="total_r" id="total_r"> &nbsp; Balance: 
<input type="hidden" value="<?php echo $st; ?>" name="st" id="st"/>
<span id="dispr" class="trues"><?php echo $total_r; ?></span>
&nbsp; &nbsp; &nbsp;<span  style="background:blue; color:white; padding:2px 4px; cursor:pointer;"  onclick="saveQuery();">Send</span>                
<input type='hidden' name='action' value='<?php echo $type; ?>' />
<input type="hidden" name="query_id" value="<?php echo $query_id; ?>" id="query_id" />
<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" id="project_id" />
<input type='hidden' name='pid' value='<?php echo $prjid ?>' />
<input type='hidden' name='vid' value='<?php echo $vid ?>' />
</form>
