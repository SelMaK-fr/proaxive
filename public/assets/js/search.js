/**
 * Recherche un HttpRequest
 * @param str
 * @param url
 */
function searchHttpRequest(str, url){
    if (str.length == 0){ //exit function if nothing has been typed in the textbox
        document.getElementById("htmlContent").innerHTML=""; //clear previous results
        document.getElementById('table-visible').style.display = "";
        return;
    }
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function(){
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
            document.getElementById("htmlContent").innerHTML=xmlhttp.responseText;
            document.getElementById('table-visible').style.display = "none";
        }
    }
    xmlhttp.open("GET",url + "/" + str,true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlhttp.send();
}
