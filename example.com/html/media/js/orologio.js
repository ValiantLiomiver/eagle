function clock(path){
	var val = [];
	val[0] = '0.png';
	val[1] = '1.png';
	val[2] = '2.png';
	val[3] = '3.png';
	val[4] = '4.png';
	val[5] = '5.png';
	val[6] = '6.png';
	val[7] = '7.png';
	val[8] = '8.png';
	val[9] = '9.png';
	val[10] = 'p.png';
	val[11] = 'p.png';
	var time=new Date ();
	var secs=time.getSeconds();
	document.getElementById('secondo_d').src = path+val[lpad(secs.toString(),2).charAt(0)];
	document.getElementById('secondo_d').alt = lpad(secs.toString(),2).charAt(0);
	document.getElementById('secondo_u').src = path+val[lpad(secs.toString(),2).charAt(1)];
	document.getElementById('secondo_u').alt = lpad(secs.toString(),2).charAt(1);
	var mins=time.getMinutes();
	document.getElementById('minuto_d').src = path+val[lpad(mins.toString(),2).charAt(0)];
	document.getElementById('minuto_d').alt = lpad(mins.toString(),2).charAt(0);
	document.getElementById('minuto_u').src = path+val[lpad(mins.toString(),2).charAt(1)];
	document.getElementById('minuto_u').alt = lpad(mins.toString(),2).charAt(1);
	var hr=time.getHours();
	document.getElementById('ora_d').src = path+val[lpad(hr.toString(),2).charAt(0)];
	document.getElementById('ora_d').alt = lpad(hr.toString(),2).charAt(0);
	document.getElementById('ora_u').src = path+val[lpad(hr.toString(),2).charAt(1)];
	document.getElementById('ora_u').alt = lpad(hr.toString(),2).charAt(1);
	setTimeout(function(){clock(path);},50);
}

function preloadImages() {
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function lpad(value, padding){
	var zeroes = "0";
	for (var i = 0; i < padding; i++) { zeroes += "0"; }
	return (zeroes + value).slice(padding * -1);
}
