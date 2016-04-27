/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function callAjax(elementID,PageUrl){
    //alert("On called ajax:"+PageUrl+elementID);
    var http = false;

    if(navigator.appName == "Microsoft Internet Explorer") {
        http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        http = new XMLHttpRequest();
    }

    http.abort();
    http.open("GET", PageUrl, true);
    http.onreadystatechange=function() {
        if(http.readyState == 4) {
            var loadcontent = http.responseText;
            var elementToLoad = "#"+elementID;
            if(http.responseText == "incorrect"){
                $(elementToLoad).text("Error!!!");
                //document.getElementById("save").disabled = true;
            }else{
                $(elementToLoad).html(loadcontent).hide().show('slow');;
                //document.getElementById("save").disabled = false;
            }
        }
    }
    http.send(null);	
}
//Brain
function callAjaxAsyncFalse(elementID,PageUrl){
    //alert("On called ajax:"+PageUrl+elementID);
    var http = false;

    if(navigator.appName == "Microsoft Internet Explorer") {
        http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        http = new XMLHttpRequest();
    }

    http.abort();
    http.open("GET", PageUrl, false);
    http.onreadystatechange=function() {
        if(http.readyState == 4) {
            var loadcontent = http.responseText;
            var elementToLoad = "#"+elementID;
            if(http.responseText == "incorrect"){
                $(elementToLoad).text("Error!!!");
                //document.getElementById("save").disabled = true;
            }else{
                $(elementToLoad).html(loadcontent).hide().show('slow');;
                //document.getElementById("save").disabled = false;
            }
        }
    }
    http.send(null);	
}

function callAjaxContent(PageUrl){
    var http = false;
    var loadcontent = "";
    if(navigator.appName == "Microsoft Internet Explorer") {
        http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        http = new XMLHttpRequest();
    }

    http.open("GET", PageUrl, false);
    http.send(null);
	
    if (http.status === 200) {
        loadcontent = http.responseText;
    }
    else {
        loadcontent = "Request Failed..";
    }

    return loadcontent;
}


