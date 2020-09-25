function CreateGallery(classname){
	classname = classname ? classname : 'overlay_element';
	(function(classname){
		var DOMElement = {
			extend: function (name, fn) {
				if (!document.all || navigator.appName.match(/(Microsoft Internet Explorer)/i)){
					var elementPrototype = typeof HTMLElement !== "undefined" ? HTMLElement.prototype : Element.prototype;
					eval("elementPrototype."+name+" = fn");
				}
				else{
					var _createElement = document.createElement;
					//override document.createElement
					document.createElement = function (tag) {
						var _elem = _createElement(tag);
						eval("_elem." + name + " = fn");
						return _elem;
					}
					
					//document.getElementById
					var _getElementById = document.getElementById;
					//override document.getElementById
					document.getElementById = function (id) {
						var _elem = _getElementById(id);
						eval("_elem." + name + " = fn");
						return _elem;
					}
					
					//document.getElementsByTagName
					var _getElementsByTagName = document.getElementsByTagName;
					//override document.getElementsByTagName
					document.getElementsByTagName = function (tag) {
						var _arr = _getElementsByTagName(tag);
						for (var _elem = 0; _elem < _arr.length; _elem++) eval("_arr[_elem]." + name + " = fn");
						return _arr;
					}
				}
			}
		};
		
		DOMElement.extend("setId",function (value) {
			setId(this,value);
			return this;
		});
		
		DOMElement.extend("getId",function () {
			return getId(this);
		});
		
		DOMElement.extend("setSrc",function (value) {
			setSrc(this,value);
			return this;
		});
		
		DOMElement.extend("getSrc",function () {
			return getSrc(this);
		});
		
		DOMElement.extend("setAlt",function (value) {
			setAlt(this,value);
			return this;
		});
		
		DOMElement.extend("getAlt",function () {
			return getAlt(this);
		});
		
		DOMElement.extend("setTitle",function (value) {
			setTitle(this,value);
			return this;
		});
		
		DOMElement.extend("getTitle",function () {
			return getTitle(this);
		});
		
		DOMElement.extend("getOffset",function () {
			return getOffset(this);
		});
		
		DOMElement.extend("onEvent",function (eventType, callBack) {
			addEvent(this, eventType, callBack);
			if (!this.myListeners) {
				this.myListeners = [];
			};
			this.myListeners.push({ eType: eventType, callBack: callBack });
			return this;
		});
		
		DOMElement.extend("removeEvent",function(eventType){
			if (!this.myListeners) {
				this.myListeners = [];
			};
			var tmp_list = [];
			for (var i = 0; i < this.myListeners.length; i++) {
				if(this.myListeners[i].eType != eventType){
					tmp_list.push(this.myListeners[i]);
				}
				else{
					removeEvent(this, this.myListeners[i].eType, this.myListeners[i].callBack);
				}
			};
			this.myListeners = tmp_list;
			tmp_list = [];
			
			return this;
		});
		
		DOMElement.extend("removeListeners",function () {
			if (this.myListeners) {
				for (var i = 0; i < this.myListeners.length; i++) {
					removeEvent(this, this.myListeners[i].eType, this.myListeners[i].callBack);
				};
			   this.myListeners = [];
			};
		});
		
		DOMElement.extend("rewrite",function (HTML) {
			this.innerHTML = HTML;
			return this;
		});
		
		function gallery(classname){
			var overlay = document.createElement('div');
			overlay.setId(id_overlay);
			var lightbox = document.createElement('div');
			lightbox.setId(id_lightbox);
			document.getElementsByTagName('body')[0].appendChild(overlay);
			document.getElementsByTagName('body')[0].appendChild(lightbox);
			content = document.getElementById(id_lightbox);
			PrepareImages();
			PrepareLoaders();
		}
		
		function PrepareImages(){
			for(var i=0; i<divs.length; i++){
				if(!divs[i].getId()){
					divs[i].setId('divcontainer_'+i);
				}
				imgs[i] = {src:'',title:'',alt:''};
				imgs[i].src = divs[i].childNodes[0].getSrc();
				imgs[i].title = divs[i].childNodes[0].getTitle();
				imgs[i].alt = divs[i].childNodes[0].getAlt();
			}
		}
		
		function PrepareLoaders(noanimation){
			noanimation = noanimation ? noanimation : true;
			for(var i=0; i<divs.length; i++){
				eval("divs["+i+"].onEvent('click', function(){index_real="+i+"; showContent("+noanimation+"); divs["+i+"].removeListeners();});");
			}
		}
		
		function showContent(noanimation){
			var obj = divs[index_real];
			var obj_id = getId(obj);
			//alert(index_real);
			index_prec = index_real==0 ? (divs.length-1) : (index_real-1);
			index_succ = index_real==(divs.length-1) ? 0 : (index_real+1);
			
			var container = document.getElementById(id_overlay);
			
			//preloadImages(imgs[index_real].src);
			if(document.getElementById('overlay-img') === null){
				content.innerHTML = prec+"<img id='overlay-img' src='/media/img/loader.gif' alt='loading...' title='loading...' />"+succ;
				
				var valign = 'top';
				if(!noanimation) show(container.id);
				else container.style.display = 'block';
				content.style.display = 'block';
				var prec = "<div id='outer'><div id='fancybox-bg-n' class='fancybox-bg'></div><div id='fancybox-bg-ne' class='fancybox-bg'></div><div id='fancybox-bg-e' class='fancybox-bg'></div><div id='fancybox-bg-se' class='fancybox-bg'></div><div id='fancybox-bg-s' class='fancybox-bg'></div><div id='fancybox-bg-sw' class='fancybox-bg'></div><div id='fancybox-bg-w' class='fancybox-bg'></div><div id='fancybox-bg-nw' class='fancybox-bg'></div><table border='0' cellpadding='0' cellspacing='0'><tr><td><table border='0' cellpadding='1' cellspacing='5' width='738'><tr><td align='left' valign='"+valign+"' id='button_prec' style='cursor:pointer; padding:10px;' width='44'><div class='arrows'></div></td><td align='center' valign='middle' class='gallery_img' width='650'><table border='0' cellpadding='0' cellspacing='0' width='650'><tr><td width='650' align='center'>";
				info = obj.childNodes[1].childNodes.length>0 ? obj.childNodes[1].firstChild.data : '';
				var succ = "</td></tr></table></td><td align='right' valign='"+valign+"' id='button_succ' style='cursor:pointer; padding:10px;' width='44'><div class='arrows'></div></td></tr></table></td><td valign='top'><div class='description' id='info_description'>"+info+"</div></td></tr></table></div><div id='close'></div>";
				var img = "<img id='overlay-img' src='"+imgs[index_real].src+"' alt='"+imgs[index_real].alt+"' title='"+imgs[index_real].title+"' />";
				content.innerHTML = prec+img+succ;
			}
			else{
				info = obj.childNodes[1].childNodes.length>0 ? obj.childNodes[1].firstChild.data : '';
				document.getElementById('overlay-img').setSrc(imgs[index_real].src);
				document.getElementById('overlay-img').setAlt(imgs[index_real].alt);
				document.getElementById('overlay-img').setTitle(imgs[index_real].title);
				if(document.getElementById('info_description') !== null){
					document.getElementById('info_description').rewrite(info);
				}
			}
			
			//for(var i=0; i<divs.length; i++) eval("divs[i].removeListeners();");
			
			container.onEvent('click', function(e){
				container.removeListeners();
				OffContent(id_overlay);
				PrepareLoaders(noanimation);
			});
			
			document.getElementById('close').onEvent('click', function(e){
				document.getElementById('close').removeListeners();
				OffContent(id_overlay);
				PrepareLoaders(noanimation);
			});
			
			document.getElementById('button_prec').onEvent('click', function(){
				document.getElementById('button_prec').removeEvent('click');
				LoadImage(document.getElementById("overlay-img"));
				index_real=index_prec;
				showContent(true);
			});
			
			document.getElementById('button_succ').onEvent('click', function(){
				document.getElementById('button_succ').removeEvent('click');
				LoadImage(document.getElementById("overlay-img"));
				index_real=index_succ;
				showContent(true);
			});
			
			var handler = function(e){eval("capturekey(e,'"+id_overlay+"'); removeEvent(document,'keyup',handler);");};
			addEvent(document,'keyup',handler);
		}
		
		function LoadImage(overlayimg){
			//preloadImages('/media/img/loader.gif');
			overlayimg.setSrc('/media/img/loader.gif');
			overlayimg.setAlt('loading...');
			overlayimg.setTitle('loading...');
		}
		
		function OffContent(id_container,notanimation){
			notanimation = notanimation ? notanimation : true;
			if(!notanimation) hide(id_container);
			else{
				var container = document.getElementById(id_container);
				container.innerHTML = '';
				container.setAttribute('style','');
				container.removeAttribute('style');
			}
			content.innerHTML = '';
			content.setAttribute('style','');
			content.removeAttribute('style');
			for(var i=0; i<divs.length; i++) eval("divs[i].removeListeners();");
			PrepareLoaders();
		}
		
		function capturekey(event,id){
			if(event.which){
				//alert(event.which);
				if(event.which==27){
					//ho premuto esc
					OffContent(id);
				}
				else if(event.which==39){
					//freccia destra
					index_real=index_succ;
					showContent(true);
				}
				else if(event.which==37){
					//freccia sinistra
					index_real = index_prec;
					showContent(true);
				}
				else{
					//index_real = th;
					showContent(true);
				}
			}
			else if(event.keyCode){
				//alert('keycode : '+event.keyCode);
				if(event.keyCode==27){
					//ho premuto esc
					OffContent(id);
				}
				else if(event.keyCode==39){
					//freccia destra
					index_real=index_succ;
					showContent(true);
				}
				else if(event.keyCode==37){
					//freccia sinistra
					index_real = index_prec;
					showContent(true);
				}
				else{
					//index_real = th;
					showContent(true);
				}
			}
		}
		
		function getId(element){
			try{
				if(element.id){
					return element.id;
				}
				else if(element.getAttribute) return element.getAttribute('id');
				else if(element.attributes){
					var id;
					for(var i=0; i<element.attributes.length; i++){
						if(element.attributes[i].name=='id') id=element.attributes[i].value;
					}
					return id;
				}
			}
			catch(err){}
		}
		
		function getAlt(element){
			try{
				if(element.alt){
					return element.alt;
				}
				else if(element.getAttribute) return element.getAttribute('alt');
				else if(element.attributes){
					var id;
					for(var i=0; i<element.attributes.length; i++){
						if(element.attributes[i].name=='alt') id=element.attributes[i].value;
					}
					return id;
				}
			}
			catch(err){}
		}
		
		function getTitle(element){
			try{
				if(element.title){
					return element.title;
				}
				else if(element.getAttribute) return element.getAttribute('title');
				else if(element.attributes){
					var id;
					for(var i=0; i<element.attributes.length; i++){
						if(element.attributes[i].name=='title') id=element.attributes[i].value;
					}
					return id;
				}
			}
			catch(err){}
		}
		
		function getSrc(element){
			try{
				if(element.src){
					return element.src;
				}
				else if(element.getAttribute) return element.getAttribute('src');
				else if(element.attributes){
					var id;
					for(var i=0; i<element.attributes.length; i++){
						if(element.attributes[i].name=='src') id=element.attributes[i].value;
					}
					return id;
				}
			}
			catch(err){}
		}
		
		function setAlt(element,val){
			if(element.alt) element.alt=val;
			else if(element.setAttribute){
				element.setAttribute('alt',val);
			}
			else if(element.attributes){
				element.attributes[element.attributes.length] = { name:'alt', value:val };
			}
		}
		
		
		function setTitle(element,val){
			if(element.title) element.title=val;
			else if(element.setAttribute){
				element.setAttribute('title',val);
			}
			else if(element.attributes){
				element.attributes[element.attributes.length] = { name:'title', value:val };
			}
		}
		
		function setSrc(element,val){
			if(element.src) element.src=val;
			else if(element.setAttribute){
				element.setAttribute('src',val);
			}
			else if(element.attributes){
				element.attributes[element.attributes.length] = { name:'src', value:val };
			}
		}
		
		function setId(element,val){
			if(element.id) element.id=val;
			else if(element.setAttribute){
				element.setAttribute('id',val);
			}
			else if(element.attributes){
				element.attributes[element.attributes.length] = { name:'id', value:val };
			}
		}
		
		function addEvent(element, evnt, funct){
			if(element.attachEvent) return element.attachEvent('on'+evnt, funct);
			else return element.addEventListener(evnt, funct, false);
		}
		function removeEvent(element, evnt, funct){
			if (element.removeEventListener) element.removeEventListener(evnt, funct, false);
			else if(element.detachEvent) element.detachEvent('on'+evnt, funct);
		}
		
		function show(id){
			PrenoteVisibility(id,0,0.7);
		}
		
		function hide(id){
			PrenoteInvisibility(id,1);
		}
		
		function PrenoteInvisibility(layername,op,timeout,timerID){
			layer = document.getElementById(layername);
			if(!timeout) timeout = 50; //millisecond
			if(op>=0){
				clearTimeout(timerID);
				nop=op - 0.1;
				layer.style.opacity = nop;
				layer.style.filter = "alpha(opacity="+(nop*100)+")";     
				timerID = setTimeout(function () {PrenoteInvisibility(layername,nop,null,timerID);}, timeout);
			}
			else{
				LayerOff(layer);
				layer.removeAttribute('style');
			}
		}
		
		function PrenoteVisibility(layername,op,forceblock,timeout,timerID){
			var layer = document.getElementById(layername);
			if(!timeout) timeout = 50; //millisecond
			if(!forceblock) forceblock=1;
			LayerOn(layer,forceblock);
			if(op<forceblock){
				clearTimeout(timerID);
				nop=op + 0.1;
				layer.style.opacity = nop;
				layer.style.filter = "alpha(opacity="+(nop*100)+")";          
				timerID = setTimeout(function () {PrenoteVisibility(layername,nop,forceblock,null,timerID);}, timeout);
			}
			else LayerOn(layer,forceblock);
		}
		
		function LayerOn(layer,op){
			layer.style.visibility = 'visible';
			layer.style.display = 'block';
			if(op){
				layer.style.opacity = op;
				layer.style.filter = "alpha(opacity="+(op*100)+")";
			}
		}
		
		function LayerOff(layer){
			layer.style.visibility = 'hidden';
			layer.style.display = 'none';
		}
		
		var MyGetElementsByClassName = function (className){
			var ret = [];
			var type = ['a','abbr','acronym','address','applet','area','b','base','basefont','bdo','big','blockquote','body','br','button','caption','center','cite','code','col','colgroup','dd','del','dfn','dir','div','dl','dt','em','fieldset','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','i','iframe','img','input','ins','isindex','kbd','label','legend','li','link','map','menu','meta','noframes','noscript','object','ol','optgroup','option','p','param','pre','q','s','samp','script','select','small','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','u','ul','var'];
			for(var a=0; a<type.length; a++){
				var elements = document.getElementsByTagName(type[a]);
				if(elements.length){
					for(var i=0; i<elements.length; i++){
						if(elements[i].className==className) ret[ret.length] = elements[i];
					}
				}
			}
			
			return ret;
		}
		
		function preloadImages(){
			var d=document;
			if(d.images){
				if(!d.MM_p) d.MM_p=new Array();
				var i,j=d.MM_p.length,a=preloadImages.arguments;
				for(i=0; i<a.length; i++){
					if(a[i].indexOf("#")!=0){
						d.MM_p[j]=new Image;
						d.MM_p[j++].src=a[i];
					}
				}
			}
		}
		
		function getOffsetSum(elem){
			var top=0, left=0
			while(elem) {
				top = top + parseInt(elem.offsetTop)
				left = left + parseInt(elem.offsetLeft)
				elem = elem.offsetParent        
			}
			return {top: top, left: left};
		}
		
		function getOffsetRect(elem) {
			var box = elem.getBoundingClientRect();
			
			var body = document.body;
			var docElem = document.documentElement;
			
			var scrollTop = window.pageYOffset || docElem.scrollTop || body.scrollTop;
			var scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft;
			
			var clientTop = docElem.clientTop || body.clientTop || 0;
			var clientLeft = docElem.clientLeft || body.clientLeft || 0;
			
			var top  = box.top +  scrollTop - clientTop;
			var left = box.left + scrollLeft - clientLeft;
			
			return { top: Math.round(top), left: Math.round(left) };
		}
		
		function getOffset(elem){
			return elem.getBoundingClientRect ? getOffsetRect(elem) : getOffsetSum(elem);
		}
		
		function getPageSize() {
			var xScroll, yScroll;
			if(window.innerHeight && window.scrollMaxY){
				xScroll = window.innerWidth + window.scrollMaxX;
				yScroll = window.innerHeight + window.scrollMaxY;
			}
			else if (document.body.scrollHeight > document.body.offsetHeight){
				xScroll = document.body.scrollWidth;
				yScroll = document.body.scrollHeight;
			}
			else{
				xScroll = document.body.offsetWidth;
				yScroll = document.body.offsetHeight;
			}
			var windowWidth, windowHeight;
			
			if(self.innerHeight){
				if(document.documentElement.clientWidth){
					windowWidth = document.documentElement.clientWidth;
				}
				else{
					windowWidth = self.innerWidth;
				}
				windowHeight = self.innerHeight;
			}
			else if(document.documentElement && document.documentElement.clientHeight){
				windowWidth = document.documentElement.clientWidth;
				windowHeight = document.documentElement.clientHeight;
			}
			else if(document.body){
				windowWidth = document.body.clientWidth;
				windowHeight = document.body.clientHeight;
			}
			
			if(yScroll < windowHeight){
				pageHeight = windowHeight;
			}
			else{
				pageHeight = yScroll;
			}
			
			if(xScroll < windowWidth){
				pageWidth = xScroll;
			}
			else{
				pageWidth = windowWidth;
			}
			return {pageWidth:pageWidth,pageHeight:pageHeight,windowWidth:windowWidth,windowHeight:windowHeight};
		}

		
		var index_real = 0, index_succ = 0, index_prec = 0;
		var info = '';
		var id_overlay = 'overlay';
		var id_lightbox = 'lightbox';
		var content;
		var imgs = [];
		var divs = MyGetElementsByClassName(classname);
		gallery(classname);
	})(classname);
}
