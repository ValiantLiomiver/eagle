function getHTTPObject(){
	var ret = false;
	if (typeof XMLHttpRequest != 'undefined') ret = new XMLHttpRequest();
	else{
		try{
			ret = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e){
			try{
				ret = new ActiveXObject("Microsoft.XMLHTTP");
			} 
			catch(e){ 
				if(window.createRequest){
					try{
						ret = window.createRequest();
					}
					catch (e){ret = false;}
				}
			}
		}
	}
	return ret;
}
function sendHTTPRequest(method,url,param,callback,xml){
	var xmlhttp = getHTTPObject();
	if(method=='POST'){
		var params = param;
		xmlhttp.open("POST", url,true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
		xmlhttp.setRequestHeader("Content-length", params.length);
		xmlhttp.setRequestHeader("Connection", "close");
	}
	else{
		xmlhttp.open("GET", url + '&'+param,true);
	}
	xmlhttp.onreadystatechange = function (){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			if(xml) var response = callback(xmlhttp.responseXML);
			else var response = callback(xmlhttp.responseText);
		}
	}
	xmlhttp.send(params);
}

function preloadImages() {
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function getEventFromDate(date){
	document.getElementById('dettagli_evento').innerHTML = "<div class='agenda_eventi'><div style='padding:24px 0px 0px 133px;'><img src='/media/img/loader.gif' title='loading...' alt='loading...' /></div></div>";
	var callback = function(response){
		document.getElementById('dettagli_evento').innerHTML = response;
	};
	sendHTTPRequest('POST','index.php?Azione=ajax','act=eventFromDate&day='+date,callback,false);
}

function MyDialogOpen(id,message){
	jQuery("#"+id).html("<span style='font-size:13px;'>"+message+"</span>");
	jQuery("#"+id).dialog({
		position:{ my: "center", at: "top", of: window },
		closeOnEscape: false,
		open: function(event, ui){
			$(".ui-dialog-titlebar-close", ui.dialog || ui).hide();
			$(".ui-dialog-titlebar-close").hide();
		}
	});
	jQuery("#"+id).dialog("moveToTop");
}

function MyDialogClose(id,message,wait,fadeout){
	if(!fadeout) fadeout=3000;
	if(message) jQuery("#"+id).html("<span style='font-size:13px;'>"+message+"</span>");
	if(wait){
		var idtimeout = setTimeout(function(){
			jQuery("#"+id).parent().fadeOut(fadeout,function(){
				$("#"+id).dialog("close");
			});
			clearTimeout(idtimeout);
		},wait);
	}
	else{
		jQuery("#"+id).parent().fadeOut(fadeout,function(){
			$("#"+id).dialog("close");
		});
	}
}
