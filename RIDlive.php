<html>
<head id="Head1" runat="server">
    <title></title>
</head>
<body>
    <form id="form1" runat="server">
	
			<input type="hidden" name="wm" id="wm" value="0" />
	        <input type="hidden" name="RelevantID" id="RelevantID" />
			<input type="hidden" name="PanelID" id="PanelID" value="" />
			<input type="hidden" name="Ext" id="Ext" value="" />
			<input type="hidden" name="SurveyID" id="SurveyID" value="SurveyID" />
	        <input type="hidden" name="PanelistID" id="PanelistID"  value=""/>
			<input type="hidden" name="ClientID" id="ClientID" value="6D5D16C8-25B1-4C3F-860C-D8805FCF9CFD" />
	        <input type="hidden" name="CID" id="CID"  />
	        <input type="hidden" name="TID" id="TID"  />
	        <input type="hidden" name="TimePeriod" id="TimePeriod"  />
	        <input type="hidden" name="GeoCodes" id="GeoCodes" />
	        <input type="hidden" name="RVIDCompleted" id="RVIDCompleted" value="0" />
			<input type="hidden" name="RVid" id="RVid" value="" />
			<input type="hidden" name="propstr" id="propstr" value="0" />
			<input value="" type="hidden" name="panelID" id="panelID" />
			<div style="text-align: center;color: #447BF7;font-size: 20px;font-weight: bold;margin: 10px 0px;">Processing...</div>
			
            <!--<select id="cboClientIDs" name ="cboClientIDs">
            </select>-->
            <!--<input type="button" id="btnRVID" name="btnRVID" onclick=""  value="Start" /><br />-->

			<!--<textarea style="font-family:verdana;font-size:10pt" rows="35" cols="100" name="Results" id="Results" value=""></textarea>-->
	<script type="text/javascript">   		RVIDTrack=-1;</script>


			<script type="text/javascript" src="http://relevantid.imperium.com/RVIDWrapperAjax.js"> </script>  
	
		<script type="text/javascript">
		
 		
		function RVIDResponseComplete() {
		
	    // Client will implement appropriate redirect logic in this function
		    //document.getElementById('btnRVID').disabled = false;
			var laterdate = new Date();

			document.getElementById('RVIDCompleted').value = "1";
			
			var results="";
			//passing back the basic informations
			//results = results +  '&v=' + document.getElementById('PanelID').value ;
                        results = results +  '&vpid=' + document.getElementById('PanelID').value ;
			results = results +  '&ext=' + document.getElementById('Ext').value;
		    results = results +  '&pid=' + document.getElementById('PanelistID').value;
			
			
			// outoput values 
			results = results +  '&RVid=' + document.getElementById('RVid').value ;
			results = results +  '&isNew=' + document.getElementById('isNew').value ;
			results = results +  '&Score=' + document.getElementById('Score').value ;
			//results = results +  '&GeoIP=' + document.getElementById('GeoIP').value ;
			results = results +  '&Country=' + document.getElementById('Country').value ;
			results = results +  '&OldId=' + document.getElementById('OldId').value ;
			results = results +  '&OldIDDate=' + encodeURIComponent(document.getElementById('OldIDDate').value) ;
			//results = results +  '&CIDFlag=' + document.getElementById('CIDFlag').value ;
			//results = results +  '&TIDFlag=' + document.getElementById('TIDFlag').value ;
			//results = results +  '&CompleteFlag=' + document.getElementById('CompleteFlag').value ;
			//results = results +  '&CompleteDate=' + document.getElementById('CompleteDate').value ;
			//results = results +  '&ScreenoutFlag=' + document.getElementById('ScreenoutFlag').value ;
			//results = results +  '&ScreenoutDate=' + document.getElementById('ScreenoutDate').value ;
			//results = results +  '&TotalCompletes=' + document.getElementById('TotalCompletes').value ;
			results = results +  '&Domain=' + encodeURIComponent(document.getElementById('Domain').value) ;
			results = results +  '&FraudProfileScore=' + document.getElementById('FraudProfileScore').value;
			results = results +  '&FPF1=' + document.getElementById('FPF1').value;
			results = results +  '&FPF2=' + document.getElementById('FPF2').value;
			results = results +  '&FPF3=' + document.getElementById('FPF3').value;
			results = results +  '&FPF4=' + document.getElementById('FPF4').value;
			results = results +  '&FPF5=' + document.getElementById('FPF5').value;
			results = results +  '&FPF6=' + document.getElementById('FPF6').value;
			//results = results +  '&RVIDHash2=' + document.getElementById('RVIDHash2').value;
			results = results +  '&isMobile=' + document.getElementById('isMobile').value;
			//alert(results);
			location.href='capture.php?check=1'+results;
		}

		
		function RVIDNoResponse() {
		    // Client will implement appropriate logic is a response is not received from RVID within the given time period
		    if (document.getElementById('RVIDCompleted').value == "0") {
		        //document.getElementById('btnRVID').disabled = false;
				var results="";
				//passing back the basic informations
				//results = results +  '&v=' + document.getElementById('PanelID').value ;
                                results = results +  '&vpid=' + document.getElementById('PanelID').value ;
				results = results +  '&ext=' + document.getElementById('Ext').value;
				results = results +  '&pid=' + document.getElementById('PanelistID').value;
				// outoput values 
				results = results +  '&Score=-2';
				location.href='capture.php?check=1'+results;
			}
			
		}

       function getQueryStringParam(param) {
           querySt = window.location.search.substring(1);
           queryStringArray = querySt.split("&");
           for (i = 0; i < queryStringArray.length; i++) {
               ft = queryStringArray[i].split("=");
               if (ft[0].toLowerCase() == param.toLowerCase()) {
                   return ft[1];
               } 	            // end if
           } 		            // end for
           return "";           // not found in query string params
       } 				        // end function


       function populateInputFields() {

        //document.getElementById('ClientID').value = document.getElementById('cboClientIDs').value;
	    document.getElementById('PanelID').value = getQueryStringParam('v');
		document.getElementById('Ext').value = getQueryStringParam('ext');
		document.getElementById('PanelistID').value = getQueryStringParam('id');
	    document.getElementById('SurveyID').value = getQueryStringParam('survey');
	    document.getElementById('GeoCodes').value = getQueryStringParam('geocodes');
		document.getElementById('CID').value = getQueryStringParam('CID');
		document.getElementById('TID').value = getQueryStringParam('TID');
		document.getElementById('TimePeriod').value = getQueryStringParam('TimePeriod');
	}
	
	function timeDifference(laterdate,earlierdate) {
		var difference = laterdate.getTime() - earlierdate.getTime();
		var secondsDifference = Math.floor(difference/1000);
		var msDifference = Math.floor(difference);
 
		return(msDifference + ' ms');
}


window.onload = function() {
    //addValueTOSelect("6D5D16C8-25B1-4C3F-860C-D8805FCF9CFD", "EMpanel");	
	startRVID();
}
function addValueTOSelect(value, text) {
    var selectbox = document.getElementById('cboClientIDs');
    var optn = document.createElement("OPTION");
    optn.value = value;
    optn.text = text; 
    selectbox.options.add(optn);
}
function startRVID() {
		earlierdate = new Date();
        //document.getElementById('btnRVID').disabled = true;
        populateInputFields();
        var RVIDTimeOut = setTimeout("RVIDNoResponse();", 5000);  // 1000 = 1 second; suggested value 5000
        callRVIDNow();
    }
   
     </script>
	 
	     </form>
	
</body>
</html>