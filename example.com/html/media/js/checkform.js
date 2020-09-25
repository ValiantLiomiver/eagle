//File javascript2.js
//Versione: 4.2
//Creatore: Andrisani Umberto
//Accorgimenti:
/*
 * 	Nella proprietÃ  "rel" utilizziamo usare la stringa 'obbligatorio' per tutti gli oggetti obbligatori
 * 	e 'obbligatorio_gruppo' per tutti gli oggetti obbligatori in gruppo.
 * 	
 * 	La proprietÃ  usata per fare questo Ã¨ il rel. Quindi gli oggetti obbligatori nell'html devono avere specificato
 * 	nell'attributo "rel" il valore obbligatorio o obbligatorio_gruppo.
 * 	
 * 	Gli oggetti alternativi che subiscono il controllo per mezzo di un oggetto principale, vengono specificati nella proprietÃ  alter
 * 	con una lista di id, i quali identificano gli oggetti alternativi. Di conseguenza viene esaminato un oggetto, il suo alter, e 
 * 	tutti gli oggetti che hanno l'id presente nell'alter dell'oggetto principale
 * 	
 * 	Oppure vengono presi se nel gruppo hanno tutti un nome simile; in questo caso nell'alter viene messo il nome comune di tutti
 * 	(simile e non uguale) che poi verrÃ  utilizzato per fare un controllo con un espressione regolare
 * 
 */
//INIZIO CONTROLLA FORM
function CheckForm(form,_alert){
	_alert = typeof _alert === 'undefined' ? false : _alert;
	var errori=Array();
	for(var j=0; j<document.forms[form].elements.length; j++){
		var obj=document.forms[form].elements[j];
		var alt='';
		var reg='';
		var rel='';
		var alter='';
		var id='';
		var splittato=Array();
		var flag=false;
		for(var k=0; k<obj.attributes.length; k++){
			if(obj.attributes[k].name=='alt'){
				alt=obj.attributes[k].value;
			}
			if(obj.attributes[k].name=='reg'){
				reg=obj.attributes[k].value;
			}
			if(obj.attributes[k].name=='rel'){
				rel=obj.attributes[k].value;
			}
			if(obj.attributes[k].name=='alter'){
				alter=obj.attributes[k].value;
			}
			if(obj.attributes[k].name=='id'){
				id=obj.attributes[k].value;
			}
			if(obj.attributes[k].name=='required'){
				rel='obbligatorio';
			}
		}
		if(rel!=''){
			var flag2=false;
			var flag1=false;
			var flag3=false;
			var cont=0;
			flag1=TrovaValoreCampo(obj);
			if(((!flag1 && rel=='obbligatorio')||(rel=='obbligatorio_gruppo'))&& alter!=''){
				if(obj.name.match('^.+\[[0-9]+\]$')){
					flag2=VerificaValoreCampo(form,alter,true,'');
				}
				else{
					splittato=alter.split(',');
					for(var k=0; k<splittato.length; k++){
						flag2=VerificaValoreCampo('','',false,splittato[k]);
						if(rel=='obbligatorio' && flag2){
							break;
						}
						if(rel=='obbligatorio_gruppo' && !flag2){
							break;
						}
					}
				}
			}
			flag=((rel=='obbligatorio' && (flag1||flag2))||(rel=='obbligatorio_gruppo' && (flag1 && flag2)));
			if(rel=='obbligatorio_option'){
				flag=flag1;
			}
			RimuoviClasse(obj.name.replace('Field','foli'),'error');
			if(!flag){
				AggiungiClasse(obj.name.replace('Field','foli'),'error');
				errori+='-'+alt+'\n';
			}
		}
	}
	if(_alert && errori!='') alert("Attenzione! I seguenti campi non sono stati compilati correttamente:\n"+errori);
	return (errori=='');
}

//Funzione che trova il tipo di un campo e verifica se Ã¨ valorizzato o meno
//Sintassi: var flag=TrovaValoreCampo(document.forms['form'].elements[i])
/*
 * 		input:
 * 			-obj: oggetto da esaminare. La funzione trova il tipo e ci da un risultato a seconda se Ã¨ valorizzato o menoÃ¹
 * 
 * 		output:
 * 			-{true,false}: true=oggetto valorizzato. false=oggetto non valorizzato
 */
function TrovaValoreCampo(obj)
{
	if(!obj)
	{
		return false;
	}
	var cont=0;	
	var reg='';
	var rel='';
	for(var p=0; p<obj.attributes.length; p++)
	{
		if(obj.attributes[p].name=='reg')
		{
			reg=obj.attributes[p].value;
		}
		if(obj.attributes[p].name=='rel')
		{
			rel=obj.attributes[p].value;
		}
	}
	var regolare=new RegExp(reg,'g')
	switch(obj.type)
	{
		case 'text':
		case 'select-one':
		case 'hidden':
		case 'textarea':
		case 'file':
		case 'submit':
		case 'button':
		case 'reset':
		case 'password':
			if(obj.value)
			{
				if(reg)
				{
					if(obj.value.match(regolare))
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
			break;
		case 'checkbox':
		case 'radio':
			if(obj.checked)
			{
				return true;
			}
			else
			{
				return false;
			}
			break;
		case 'select-multiple':
			for(var j=0; j<obj.options.length; j++)
			{
				if(obj.options[j].selected)
				{
					cont++;
				}
			}
			if(ControllaOption(obj) && rel=='obbligatorio_option')
			{
				return true
			}
			else
			{
				if(cont>0 && obj.value)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			break;
		
	}
}

//Funzione che verifica se il campo o se i relativi campi associati sono valorizzati
//Sintassi: var=VerificaValoreCampo('formanagrafica','nome',false,'id')
/*	
 * 		input:
 *			-form: il nome del form;
 *			-inputname: il nome dell'oggetto
 *			-flagsearch: {true,false}
 *			-id: facoltativo ai 3 parametri precedenti, id dell'oggetto
 * 
 * 		output:
 * 			-{true,false}: true=i campi associati sono valorizzati. false=i campi associati non sono valorizzati.
 */
function VerificaValoreCampo(form,inputname,flagsearch,id)
{
	var obj='';
	var flag=false;
	if(form && inputname)
	{
		if(flagsearch)
		{
			for(var i=0; i<document.forms[form].elements.length; i++)
			{
				var src=document.forms[form].elements[i];
				if(src.name.match('^'+inputname+'.+$'))
				{
					obj=src;
					flag=TrovaValoreCampo(obj);
					if(flag)
					{
						break;
					}
				}

			}
		}
		else
		{
			obj=document.forms[form].elements[inputname];
			if(obj)
			{
				flag=TrovaValoreCampo(obj);
			}
		}
	}
	else
	{
		obj=document.getElementById(id);
		if(obj)
		{
			flag=TrovaValoreCampo(obj);
		}
	}
	return flag;
}

//Funzione che serve per far scomparire campi alternativi
//Sintassi: SwitchVisibile('form',this.checked,lista,lista1)
/*
 * 		input:
 * 			-check: criterio di controllo
 * 			-lista[]: lista di valori di cui si vogliono cambiare le proprietÃ 
 * 			-lista1[]: lista di valori di cui si vogliono cambiare i valori dei campi (es. rel)
 */
function SwitchVisibile(form,check,lista,lista1)
{
	if(check)
	{
		CambiaValoreCampo(form,lista1,'rel','obbligatorio');
		for(var i=0; i<lista.length; i++)
		{
			document.getElementById(lista[i]).className='visibile';
		}
	}
	else
	{
		CambiaValoreCampo(form,lista1,'rel','');
		for(var i=0; i<lista.length; i++)
		{
			document.getElementById(lista[i]).className='invisibile';
		}
		for(var i=0; i<lista1.length; i++)
		{
			CambiaClasse(form,lista1[i],'');
		}
	}
}

//Funzione che cambia il valore di un attribbuto specificato in una lista di oggetti
//Sintassi: CambiaValoreCampo('form',lista,'rel','obbligatorio')
/*
 * 		input:
 * 			-form: nome del form in questione
 * 			-lista: array che contiene i nomi di tutti gli oggetti di cui voglio cambiare il valore
 * 			-attributo: nome dell'attributo da modificare
 * 			-valore: nuovo valore da assegnare all'attributo preso in input
 * 		output:
 * 			-lista: lista di oggetti di cui si sono modificati gli attributi 
 */
function CambiaValoreCampo(form,lista,attributo,valore)
{
	for(var k=0; k<lista.length; k++)
	{
		for(var i=0; i<document.forms[form].elements.length; i++)
		{
			if(document.forms[form].elements[i].name==lista[k])
			{
				for(var j=0; j<document.forms[form].elements[i].attributes.length; j++)
				{
					if(document.forms[form].elements[i].attributes[j].name==attributo)
					{
						document.forms[form].elements[i].attributes[j].value=valore;
					}
				}
			}
		}
	}
	
}

//Funzione che cambia l'obbligatorietÃ  di un elemento a seconda della valorizzazione di un elemento precedente
//Sintassi: SwitchObbligatorio('form',document.forms['form'].elements['campopadre'],document.forms['form'].elements['campofiglio'].nome,'Licenza')
/*
 * 		input:
 * 			-form: nome del form in questione;
 * 			-campopadre: nome dell'elemento padre che determina l'obbligatorietÃ  di un elemento figlio
 * 			-campofiglio[]: array di elementi figli che cambieranno il loro stato
 * 			-valore: valore che serve a confrontare lo stato del campopadre
 */
function SwitchObbligatorio(form,campopadre,campofiglio,valore)
{
	if(campopadre.value!=valore)
	{
		CambiaValoreCampo(form,campofiglio,'rel','obbligatorio');
	}
	else
	{
		CambiaValoreCampo(form,campofiglio,'rel','');
	}
}

//Funzione che genera le option di una select
//Sintassi: GeneraElementi(document.forms['form'].elements['select1'],['','ciao','miao'],['','1','2,])
/*
 * 		input:
 * 			-obj: oggetto in questione(select) dove andranno a crearsi le options
 * 			-elements[]: array delle lable degli elementi da inserire nelle options+
 * 			-value[]: array dei valori da assegnare rispettivamente alle options 
 */
function GeneraElementi(obj,elements,value)
{
	var elemento
	var node;
	for(var i=0; i<elements.length; i++)
	{
		elemento=document.createElement('option');
		elemento.value=value[i];
		node=document.createTextNode(elements[i]);
		elemento.appendChild(node);
		obj.appendChild(elemento);
	}
	
}

//Funzione che genera le option in un elemento prendendo un valore ed un testo di label
function GeneraElemento(elemento,elements,value){
	var elementa=document.createElement('option');
	elementa.value=value;
	var node=document.createTextNode(elements);
	elementa.appendChild(node);
	elemento.appendChild(elementa);
}

//Funzione che cancella tutte le options di una data select
//Sintassi: CancellaElementi(document.forms['form'].elements['select1']);
/*
 * 		input:
 * 			-obj: oggetto in questione(select) dove di cancelleranno tutte le options
 * 
 */
function CancellaElementi(obj){
	for(var i=(obj.options.length-1); i>=0; i--) obj.options[i]=null;
}

//Funzione che ha effetto solo sul form in questione
function GeneraEffetto(obj,elemento)
{
	CancellaElementi(elemento);
	if(obj.value=='')
	{
		GeneraElementi(elemento,['   '],[''])
	}
	if(obj.value=='1')
	{
		request("cardiologia.xml?"+ Math.random(),'tendina',elemento);
		//GeneraElementi(elemento,al[1],av[1]);
	}
	if(obj.value=='2')
	{
		request("odontoiatria.xml?"+ Math.random(),'tendina',elemento);
		//GeneraElementi(elemento,al[2],av[2]);
	}
	if(obj.value=='3')
	{
		request("ginecologia.xml?"+ Math.random(),'tendina',elemento);
		//GeneraElementi(elemento,al[3],av[3]);
	}
}

//Funzione che splitta il responso per attribuire il value e il testo alle option
//Sintassi: splitta(req.responseText,document.getElemById('select'))
/* 
 * 		input:
 * 			-valore: valore da splittare. Una volta splittato viene inserito nelle option (ar[1]=label e ar[0]=value)
 * 			-elemento: select di destinazione. Le option create con la funzione GeneraElemento si accoderanno a questo elemento
 */
function splitta(valore,elemento)
{
	var array=valore.split('\n');
	for(var i=0; i<array.length; i++)
	{
		var ar=array[i].split(',');
		if(ar[1] && ar[0])
		{
			GeneraElemento(elemento,ar[1],ar[0]);
		}		
	}
}

function splittaXML(valore,elemento)
{
	var principale = valore.getElementsByTagName('options');
	for(var t=0; t<principale.length; t++)
	{
		var array = principale[t].childNodes;
		for(var i=0; i<array.length; i++)
		{
			if(array[i].nodeName=='Riga')
			{
				var listafigli = array[i].childNodes;
				for(var c=0; c<listafigli.length; c++)
				{
					if(listafigli[c].nodeName=='attributo')
					{
						var attributo = listafigli[c].getAttribute("type");
						if(attributo && attributo=='value')
						{
							var value=listafigli[c].childNodes[0].nodeValue;
						}
						if(attributo && attributo=='label')
						{
							var label=listafigli[c].childNodes[0].nodeValue;
						}
					}
				}
				if(value && label)
				{
					GeneraElemento(elemento,label,value);
				}
			}
		}
	}
}

//Funzione che riscrive il testo del responso in un contenitore
//Sintassi: RiscriviTestoContenitore(id_div,req.responceText)
/* 
 * 		input:
 * 			-contenitore: id del contenitore alla quale allegare il testo di responso
 * 			-valore: valore del responso
 */
function RiscriviTestoContenitore(contenitore,valore){
	var obj = document.getElementById(contenitore);
	obj.innerHTML = valore;
}

var req=false;

//Funzione che fa la richiesta AJAX
//Sintassi: request("odontoiatria.txt?"+ Math.random(),'misto',elemento)
/*
 * 		input:
 * 			-url: percorso remoto del file da richiedere al server
 * 			-modalita: modo in cui si interagisce con gli elementi da compilare o da riscrivere (es. 'tendina'=select, 'riscrittura'=contenitore, 'script'=esegue uno script)
 */
function request(url,modalita,elemento)
{
	req=new XMLHttpRequest();
	if(req)
	{
		req.onreadystatechange = function(){
				if (req.readyState == 4)
				{
					// only if "OK"
					if (req.status == 200)
					{
						switch(modalita)
						{
							case 'tendina':
								splittaXML(req.responseXML,elemento);
								break;
							case 'riscrittura':
								RiscriviTestoContenitore(elemento,req.responseText);
								break;
							case 'script':
								eval(req.responseText);
								break;
						}
						// ...processing statements go here...
					}
					else
					{
						alert("There was a problem retrieving the XML data:\n" +req.statusText);
					}
				}
			}
		req.open("GET",url,true);
		req.send();
	}
}

//Funzione che passa le option ad un altra select
function PrendiOptions(indice,obj,el){
	var c=0;
	for(var i=0; i<obj.length; i++){
		if(obj.options[i].selected){
			var element=document.createElement("option");
			//var textobj=document.createTextNode(al[indice][i]);
			var textobj = document.createTextNode(obj.options[i].firstChild.nodeValue);
			element.appendChild(textobj);
			element.value=obj.options[i].value;
			element.selected=false;
			if(NonInsertDuplicati(el.options,element)) el.appendChild(element);
			else alert('Non puoi inserire un duplicato');
		}
	}
}

//Funzione che controlla se nella destinazione del dato, il dato stesso non Ã¨ gia presente
function NonInsertDuplicati(obj,name){
	for(var i=0; i<obj.length; i++){
		if(obj[i].value==name.value) return false;
	}
	return true;
}

//Funzione che elimina le option da una select obj
function TogliOptions(obj){
	for(var i=(obj.length-1); i>=0; i--){
		if(obj[i].selected) obj[i]=null;
	}
}

//Funzione che conta la lunghezza delle option di una select obj
function ControllaOption(obj){
	return (obj.options.length>0);
}

//Funzione che cambia la classe delle lable quando si verificano errori nei campi
//Sintassi: CambiaClasse('formanagrafica','nome',false);
/*
 * 		input:
 * 			-form: nome del form in questione;
 * 			-name: nome del campo errato;
 * 			-giusto: {true, false} true=non Ã¨ un errore. false=Ã¨ un errore.
 *
 */
function CambiaClasse(form,name,classe){
	var id=form+'_label_'+name;
	var obj=document.getElementById(id);
	if(obj) obj.className=classe;
}

function AggiungiClasse(id,classe){
	var obj=document.getElementById(id);
	if(obj) obj.className+=" "+classe;
}

function RimuoviClasse(id,classe){
	var obj=document.getElementById(id);
	if(obj){
		var classi = obj.className.split(' ');
		obj.className = '';
		for(var i=0; i<classi.length; i++){
			if(classi[i]!=classe) obj.className+=(obj.className ? " "+classi[i] : classi[i]);
		}
	}
}

//Funzione specifica che azzera la classe di tutti gli elementi del Form
function AzzeraClasse(form){
	var obj=document.forms[form].elements;
	for(var i=0; i<obj.length; i++) CambiaClasse(form,obj[i].name,'');
}

function CambiaAbilitazione(id,flag){
	var obj=document.getElementById(id);
	if(obj) obj.disabled=flag;
}

//implementazioni fatte il 22-03-2012
function SwitchOb(form,campopadre,value,el,el_group,simb){
	if(campopadre.value==value){
		CambiaValoreCampo(form,el,'rel','');
		CambiaValoreCampo(form,el_group,'rel','');
		CambiaLabel(form,el,'rem',simb);
		CambiaLabel(form,el_group,'rem',simb);
	}
	else{
		if(campopadre.value){
			CambiaValoreCampo(form,el,'rel','obbligatorio');
			CambiaValoreCampo(form,el_group,'rel','obbligatorio_gruppo');
			CambiaLabel(form,el,'add',simb);
			CambiaLabel(form,el_group,'add',simb);
		}
		else{
			CambiaValoreCampo(form,el,'rel','');
			CambiaValoreCampo(form,el_group,'rel','');
			CambiaLabel(form,el,'rem',simb);
			CambiaLabel(form,el_group,'rem',simb);
		}
	}
	for(var i=0; i<el.length; i++){
		CambiaClasse(form,el[i],'');
	}
	for(var i=0; i<el_group.length; i++){
		CambiaClasse(form,el_group[i],'');
	}
}

function DisableField(form,field){
	document.forms[form].elements[field].disabled=true;
}

function EnableField(form,field){
	document.forms[form].elements[field].disabled=false;
}

function SwitchDisabled(form,campopadre,value,el){
	if(campopadre.value==value){
		for(var i=0; i<el.length; i++){
			DisableField(form,el[i]);
		}
	}
	else{
		for(var i=0; i<el.length; i++){
			EnableField(form,el[i]);
		}
	}
}

function SwitchEnabled(form,campopadre,value,el){
	if(campopadre.value==value){
		for(var i=0; i<el.length; i++){
			EnableField(form,el[i]);
		}
	}
	else{
		for(var i=0; i<el.length; i++){
			DisableField(form,el[i]);
		}
	}
}

function CambiaLabel(form,el,method,simb){
	for(var i=0; i<el.length; i++){
		var obj = document.getElementById(form+'_label_'+el[i]);
		var html = obj.innerHTML;
		if(method=='rem'){
			var spl = html.split(simb);
			var res='';
			for(var c=0; c<spl.length; c++){
				res+=spl[c];
			}
			obj.innerHTML = res;
		}
		else{
			if(html.indexOf(simb)==-1) obj.innerHTML = html+simb;
		}
	}
}

function intval(mixed_var, base) {
    var tmp;
 
    var type = typeof(mixed_var);
     if (type === 'boolean') {
        return +mixed_var;
    } else if (type === 'string') {
        tmp = parseInt(mixed_var, base || 10);
        return (isNaN(tmp) || !isFinite(tmp)) ? 0 : tmp;    } else if (type === 'number' && isFinite(mixed_var)) {
        return mixed_var | 0;
    } else {
        return 0;
    }
}

function ctrl(frame,form,actionmyax,p1,p2,p3,p4,p5,feedback_callback){
	/*if(!window.frames[frame].document.getElementById('file').value){
		window.frames[frame].document.getElementById(form+'_label_file').className='error';
	}
	else{
		window.frames[frame].document.getElementById(form+'_label_file').className='';
	}*/
	if(CheckForm(form)){
		/*if(!window.frames[frame].document.getElementById('file').value){
			window.frames[frame].document.getElementById(form+'_label_file').className='error';
		}
		else{
			window.frames[frame].document.getElementById(form+'_label_file').className='';*/
			var attualmente_occupato=intval(document.forms[form].elements['occupazioneattuale'].value);
			var anno_att_dal=intval(document.forms[form].elements['occupazione_attuale_dal'].value);
			var mese_att_dal=intval(document.forms[form].elements['occupazione_attuale_dal_mese'].value);
			var anno_prec_dal=intval(document.forms[form].elements['occupazione_precedente_dal'].value);
			var mese_prec_dal=intval(document.forms[form].elements['occupazione_precedente_dal_mese'].value);
			var anno_prec_al=intval(document.forms[form].elements['occupazione_precedente_al'].value);
			var mese_prec_al=intval(document.forms[form].elements['occupazione_precedente_al_mese'].value);
			if(attualmente_occupato==1 && anno_prec_dal!=''){
				//devo valutare
				if((anno_att_dal==anno_prec_dal && mese_att_dal<mese_prec_dal) || (anno_att_dal==anno_prec_al && mese_att_dal<mese_prec_al) || (anno_prec_dal==anno_prec_al && mese_prec_dal<mese_prec_al)){
					alert('Errore nella coerenza delle date dell\'occupazione.');
					return false;
				}
			}
			myax(actionmyax,'raw-rewrite-intolayer','feedform',form,'form_container',myax_getformdatas(document.forms[form]),p1,p2,p3,p4,p5,feedback_callback);
		//}
	} 
}

function ChangeSelectedTo(form,value,valto,el,sel){
	if(value==valto){
		for(var i=0; i<el.length; i++){
			var opt = document.forms[form].elements[el[i]].options;
			for(var o=0; o<opt.length; o++){
				if(opt[o].value==sel) opt[o].selected=true;
				else opt[o].selected=false;
			}
		}
	}
}

function SetValue(form,el,value){
	for(var i=0; i<el.length; i++){
		var e = document.forms[form].elements[el[i]].value=value;
	}
}

var av=Array();
var al=Array();
av = [0];
al = [0];
//Cardiologia
av[1] = ['ca1','ca2','ca3','ca4','ca5','ca6'];
al[1] = ['Cardiologia1','Cardiologia2','Cardiologia3','Cardiologia4','Cardiologia5','Cardiologia6']; //Cardiologia
//Odontoiatria
av[2] = ['od1','od2','od3'];
al[2] = ['Odontoiatria1','Odontoiatria2','Odontoiatria3']; //Odontoiatria
//Ginecologia
av[3] = ['gi1','gi2','gi3'];
al[3] = ['Ginecologia1','Ginecologia2','Ginecologia3']; //Ginecologia
