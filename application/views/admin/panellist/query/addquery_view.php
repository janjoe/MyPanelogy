<style type="text/css">
    /* Query Boxes */
    div.projectbox {border:1px solid #999; width:100%; height:30px;padding:10px;}
    div.buildcontainer {width:28%; float:left; height:100%; min-height:200px; margin-bottom: 25px;}     
    div.innercontainer {border:1px solid #999; width:100%; height:auto; padding:10px; display:inline-block; vertical-align:top; margin-bottom:10px;}
    #querycontainer{margin-left:20px; width:68%; float:left; padding-left:10px;}
    #result{line-height:35px;margin:2px 0px; background:green; border:1px solid #e5e5e5; width:100%; color:white; padding:0px 10px;}
    #resexclude,#resinclude,#resgeography {width:100%; border:1px solid #999; padding:5px;display:inline-block;  margin-bottom:10px;}
    a.qfilter{cursor:pointer; font-wieght:bold; font-size:14px; background:#e5e5e5; padding:2px 4px;}
</style>
<style type="text/css">
    .inline_block{
        float:left; padding:5px;clear: both;width: 100%;
    }
    .inline_block:hover{
        background-color: #F5F5F3;
    }
</style>
<script type="text/JavaScript" src="<?php echo Yii::app()->baseUrl ?>/scripts/ajax.js"></script>
<script>
    $(function() {
        $("form").bind("keypress", function(e) {
            if (e.keyCode == 13) return false;
        });
    });
</script>
<script>
    function fill_questions(){        
        var sValue = document.getElementById("question").value;        
        var url = '<?php echo CController::createUrl('admin/pquery/sa/fillquestions') . '/sValue/'; ?>'+sValue;
        //alert(url);
        callAjax("qList",url);
    }
    
    function add_filter(qID,qName){        
        var qFilterHtml = "";
        var url = '<?php echo CController::createUrl('admin/pquery/sa/disp_query_questions') . '/sValue/'; ?>'+qID+'/tValue/'+qName;
        //alert(url);
        qFilterHtml = callAjaxContent(url);
        $("#resinclude").append(qFilterHtml).hide().show('slow');
    }
    function removeNode(nodeID){
        $("div."+nodeID).remove().show().hide('slow');
    }
    
    function filterPanelist() {    
        var sList = $("#sqlqueryform").serialize();
        $("#result").html("Please wait while we filter data...").hide().show('slow');
        var url = '<?php echo CController::createUrl('admin/pquery/sa/disp_query_result') . '/?' ?>'+ sList;
        //alert(url);
        var reData1;
        $.ajax({
            async: false,
            type:'GET',
            url: url ,
            success: function(data){
                reData1 = data;
            }});
        //qFilterHtml = callAjaxContent(url);
        $("#result").html(reData1).hide().show('slow');
    }
    
    function saveQuery(){
        var Error = 0;
        if($("#query_title").val() == ""){
            $("#query_title").focus();
            return 1;
            Error = 1;
        }
    
        if($("#project_id").val() == ""){
            $("#project_id").focus();        
            return 1;
            Error = 1;
        }

        if($("#query_sql").length == 1){
            // do nothing
        } else {
            Error = 1;
            alert("Result is not filtered!!!, Before saving count please filter result...");
        }
        if(Error == 0){
            document.forms["sqlqueryform"].submit();
        }
    }

</script>
<div class='header ui-widget-header'><?php $clang->eT("Add Query"); ?></div>
<br />
<?php echo CHtml::form(array("admin/pquery/sa/add"), 'post', array('class' => 'form30', 'id' => 'sqlqueryform', 'enctype' => 'multipart/form-data')); ?>
<div class="projectbox">
    <table>
        <tr>
            <td><label for='query_title'>Query Title : </label></td>
            <td ><input type="text" name="query_title" id="query_title" class="select"></td>
            <td ><label>Project : </label></td>  
            <td>
                <?php
                $qpro = "SELECT project_id,CONCAT(project_id,'-',LEFT(project_name,20)) as pr_name FROM {{project_master}} WHERE (trueup IS NULL or trueup='' or trueup='0000-00-00 00:00:00') order by project_id desc";
                $r_project = Yii::app()->db->createCommand($qpro)->query();
                $projectlist = CHtml::listData($r_project, 'project_id', 'pr_name');
                echo CHtml::dropDownList('project_id', $prjid, $projectlist, array('prompt' => 'Select Project','title' => 'List of Projects which are not rectified'));
                ?>
                <input type='hidden' name='pid' value='<?php echo $prjid ?>' />
                <input type='hidden' name='vid' value='<?php echo $vid ?>' />
            </td >
            <td>
                <span  style="background:blue; color:white; padding:2px 4px; cursor:pointer;"  onclick="saveQuery();">Save</span>                
                <input type='hidden' name='action' value='addquery' />
            </td>
            <td>                 
            </td>
        </tr>        
    </table>
</div>
<div style="margin-top:10px; width:100%; height:100%;">
    <div class="buildcontainer">
        <div class="innercontainer">
            <div id="searchquestion"><?php $clang->eT("Profile Questions: "); ?>
                <input type="text" id="question" name="question" style="width:150px;">
                <span  style="background:blue; color:white; padding:2px 4px; cursor:pointer;"  onclick="fill_questions();">Search</span>                 
            </div>            
            <div id="qList" style="height:auto; display:block;"></div>
        </div>
    </div>
    <div id="querycontainer"><?php $clang->eT("Search Profile Questions on the left to select filters. Ensure you add a geographic filter."); ?>
        <div id="result"></div>
        <div id="resinclude">
            <div><?php $clang->eT("Filter Query: "); ?>
                <span  style="background:blue; color:white; padding:2px 4px; cursor:pointer;"  onclick="filterPanelist();">Filter It</span>            
            </div>
        </div>
        <div id="resgeography"><?php $clang->eT("Geographical Filter: "); ?>
            <label>Zipcode/Postal Code: </label>
            <textarea rows="1" id="zipcode" cols="30" name="zipcode">
            </textarea>
            <label>Country: </label>
            <?php
            $country = Country::model()->findAll(array('order' => 'country_name'));
            $countrylist = CHtml::listData($country, 'country_id', 'country_name');
            echo CHtml::dropDownList('country', '', $countrylist, array('prompt' => 'Select Country'));
            ?>
            <br /><br/>
            <label>[Age] </label> <lable> From : </lable><input type="text" name="fromage" id="fromage"  /> 
            <lable> To : </lable><input type="text" name="toage" id="toage" />
        </div>
    </div>
    <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; ?>" class="limebutton" style="margin-top:3%;margin-left: 35%;">Cancel</a>
</form>
