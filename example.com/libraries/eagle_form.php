<?php

if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
if(!defined('APP_DIR')){
	define('APP_DIR',dirname(__FILE__));
}

if(!class_exists('Eagle_Xhthml')){
	if(is_file(APP_DIR.DS."eagle_xhtml.php")) include(LIBRARIES_DIR."/eagle_xhtml.php");
	else die("Attenzione! Impossibile individuare il xhtml builder");
}


class Eagle_form{
	private $name = 'unknowform';
	private $action = '';
	private $method = 'post';
	
	private $button_save = '';
	
	private $title = '';
	private $subtitle = '';
	
	private $session = array();
	private $post = array();
	private $get = array();
	private $files = array();
	private $globals = array();
	
	private $js_path = '';
	private $css_path = '';
	private $ckeditor_path = 'ckeditor/ckeditor.js';
	
	private $js_files = array();
	private $css_files = array();
	private $generic_files = array();
	
	private $css_inline = '';
	
	private $fields = array();
	private $groups = array();
	
	private $ckeditor_istances = array();
	
	private $html = null;
	
	function __construct($name=null){
		if(isset($_SESSION) && $_SESSION) $this->session =  &$_SESSION;
		if(isset($_POST) && $_POST) $this->post =  &$_POST;
		if(isset($_GET) && $_GET) $this->get =  &$_GET;
		if(isset($_FILES) && $_FILES) $this->files =  &$_FILES;
		if(isset($GLOBALS) && $GLOBALS) $this->globals =  &$GLOBALS;
		
		if(!array_key_exists('NUMBER_OF_FORMS',$GLOBALS)) $this->globals['NUMBER_OF_FORMS'] = 0;
		else $this->globals['NUMBER_OF_FORMS']++;
		
		$this->name = isset($name) && $name ? $name : $this->name.$this->globals['NUMBER_OF_FORMS'];
		
		$this->html = new Eagle_Xhthml(true);
	}
	
	function __destruct(){
		$this->globals['NUMBER_OF_FORMS']--;
	}
	
	function setName($newname){
		if(isset($newname) && $newname){
			$this->name = $newname;
		}
	}
	
	function setMethod($newmethod){
		if(isset($newmethod) && $newmethod){
			$this->method = $newmethod;
		}
	}
	
	function setAction($newaction){
		if(isset($newaction) && $newaction){
			$this->action = $newaction;
		}
	}
	
	function setTitle($newtitle){
		if(isset($newtitle) && $newtitle){
			$this->title = $newtitle;
		}
	}
	
	function setSubtitle($newsubtitle){
		if(isset($newsubtitle) && $newsubtitle){
			$this->subtitle = $newsubtitle;
		}
	}
	
	function setJSPath($newpath){
		if(isset($newpath) && $newpath){
			$this->js_path = $newpath;
		}
	}
	
	function setCSSPath($newpath){
		if(isset($newpath) && $newpath){
			$this->css_path = $newpath;
		}
	}
	
	function setCSSinline($cssinline){
		if(isset($cssinline) && $cssinline){
			$this->css_inline.= $cssinline;
		}
	}
	
	function setCKeditorPath($newpath){
		if(isset($newpath) && $newpath){
			$this->ckeditor_path = $newpath;
		}
	}
	
	function setSaveText($newtext){
		if(isset($newtext) && $newtext){
			$this->button_save = $newtext;
		}
	}
	
	function addJSFile($filename){
		if(isset($filename) && $filename && !in_array($filename,$this->js_files)){
			array_push($this->js_files,$this->js_path.'/'.$filename);
		}
	}
	
	function addCSSFile($filename){
		if(isset($filename) && $filename && !in_array($filename,$this->css_files)){
			array_push($this->css_files,$this->css_path.'/'.$filename);
		}
	}
	
	function addGenericFile($filename){
		if(isset($filename) && $filename && !in_array($filename,$this->generic_files)){
			array_push($this->generic_files,$filename);
		}
	}
	
	function addField($fname,$type,$required=false){
		$id = null;
		if(isset($fname) && $fname){
			switch($type){
				case 'html':
					$this->addJSFile($this->ckeditor_path);
					$css_ckeditor = "form li div span.cke_bottom{
						float:none;
					}
					form li div span.cke_reset_all{
						float:none;
					}
					form li span.cke_bottom{
						float:none;
					}
					form li span.cke_reset_all{
						float:none;
					}
					";
					$this->setCSSinline($css_ckeditor);
					unset($css_ckeditor);
					break;
			}
			
			array_push($this->fields,array(
				'name'=>$fname,
				'type'=>$type,
				'required'=>(isset($required) && $required ? $required : false)
			));
			$id = (count($this->fields)-1);
		}
		return $id;
	}
	
	function addPropriety($fid,$prop,$value){
		if(isset($fid) && is_numeric($fid) && intval($fid)>=0) $this->fields[$fid][$prop] = $value;
	}
	
	function addGroup(){
		$arguments = func_get_args();
		$id_group = null;
		if(count($arguments)>0){
			$name_group = array_shift($arguments);
			array_push($this->fields,array(
				'name'=>$name_group,
				'type'=>'group',
				'required'=>false
			));
			$id_group = (count($this->fields)-1);
			if(count($arguments)>0){
				foreach($arguments as $fid){
					if(array_key_exists($fid,$this->fields)){
						$this->fields[$fid]['group'] = $id_group;
						if(!array_key_exists($id_group,$this->groups)) $this->groups[$id_group] = array();
						$this->groups[$id_group][] = $fid;
					}
				}
			}
		}
		return $id_group;
	}
	
	private function GetHead(){
		if(isset($this->title) && $this->title){
			$this->html
					->header(array('id'=>'header','class'=>'info'))
						->h2()->add_text($this->title)->_h2();
			if(isset($this->subtitle) && $this->subtitle) $this->html->div()->add_text($this->subtitle)->_div();
			$this->html->_header();
		}
	}
	
	private function GetFieldType($id,$group=null){
		$field = $this->fields[$id];
		if(array_key_exists('prehtml',$field)) $this->html->add_text($field['prehtml']);
		switch($field['type']){
			case 'text':
				$pr = array(
							"id"=>"Field".$id,
							"name"=>$field['name'],
							"type"=>"text",
							"class"=>((isset($group) && strlen($group)>0)?("field text large ".$group):("field text medium")),
							"tabindex"=>($id+1),
							"onkeyup"=>"validateRange(".$id.", 'character');"
						);
				if(isset($field['value']) && $field['value']) $pr["value"] = $field['value'];
				if(isset($field['maxlength']) && $field['maxlength']) $pr["maxlength"] = $field['maxlength'];
				if(isset($field['required']) && $field['required']) $pr["required"] = 'required';
				if(isset($field['title']) && $field['title']) $pr["alt"] = $field['title'];
				if(isset($field['onchange']) && $field['onchange']) $pr["onchange"] = $field['onchange'];
				$this->html->input($pr);
				$pr = null;
				unset($pr);
				break;
			case 'password':
				$pr = array(
							"id"=>"Field".$id,
							"name"=>$field['name'],
							"type"=>"password",
							"class"=>((isset($group) && strlen($group)>0)?("field text large ".$group):("field text medium")),
							"tabindex"=>($id+1)
						);
				if(isset($field['value']) && $field['value']) $pr["value"] = $field['value'];
				if(isset($field['maxlength']) && $field['maxlength']) $pr["maxlength"] = $field['maxlength'];
				if(isset($field['required']) && $field['required']) $pr["required"] = 'required';
				if(isset($field['title']) && $field['title']) $pr["alt"] = $field['title'];
				$this->html->input($pr);
				$pr = null;
				unset($pr);
				break;
			case 'select':
				$pr = array(
							"id"=>"Field".$id,
							"name"=>$field['name'],
							"class"=>((isset($group) && strlen($group)>0)?("field select large ".$group):("field select medium")),
							"tabindex"=>($id+1)
						);
				$options = array();
				if(isset($field['options']) && is_array($field['options']) && count($field['options'])>0){
					foreach($field['options'] as $key=>$label){
						$app = array('pr'=>array('value'=>$key),'label'=>$label);
						if(isset($field['value']) && $field['value'] && $field['value']==$key) $app['pr']["selected"] = "selected";
						$options[] = $app;
						unset($app);
					}
				}
				if(isset($field['required']) && $field['required']) $pr["required"] = 'required';
				if(isset($field['title']) && $field['title']) $pr["alt"] = $field['title'];
				//$this->html->input($pr);
				$this->html->select($pr);
				if(count($options)>0){
					foreach($options as $opt){
						$this->html->option($opt['pr'])
							->add_text($opt['label'])
						->_option();
					}
				}
				$this->html->_select();
				$pr = null;
				unset($pr);
				break;
			case 'file':
				$pr = array(
							"id"=>"Field".$id,
							"name"=>$field['name'],
							"type"=>"file",
							"class"=>((isset($group) && strlen($group)>0)?("field text large ".$group):("field text medium")),
							"tabindex"=>($id+1)
						);
				//if(isset($field['value']) && $field['value']) $pr["value"] = $field['value'];
				if(isset($field['maxlength']) && $field['maxlength']) $pr["maxlength"] = $field['maxlength'];
				if(isset($field['required']) && $field['required']) $pr["required"] = 'required';
				if(isset($field['title']) && $field['title']) $pr["alt"] = $field['title'];
				$this->html->input($pr)->add_text(((isset($field['value']) && $field['value'])?("<div style='font-size:85%;'><a href='".$field['preurl'].$field['value']."'>".$field['value']."</a></div>"):('')));
				$pr = null;
				unset($pr);
				break;
			case 'textarea':
				$pr = array(
							"id"=>"Field".$id,
							"name"=>$field['name'],
							"class"=>((isset($group) && strlen($group)>0)?("field textarea large ".$group):("field textarea medium")),
							"spellcheck"=>"true",
							"rows"=>((isset($field['rows']) && intval($field['rows']))?(intval($field['rows'])):(10)),
							"cols"=>((isset($field['cols']) && intval($field['cols']))?(intval($field['cols'])):(50)),
							"tabindex"=>($id+1),
							"onkeyup"=>"validateRange(".$id.", 'character');"
						);
				if(isset($field['required']) && $field['required']) $pr["required"] = 'required';
				if(isset($field['title']) && $field['title']) $pr["alt"] = $field['title'];
				$this->html->textarea($pr)->add_text((isset($field['value']) && $field['value'])?($field['value']):(''))->_textarea();
				$pr = null;
				unset($pr);
				break;
			case 'html':
				$pr = array(
							"id"=>"Field".$id,
							"name"=>$field['name'],
							"class"=>"field textarea medium".((isset($group) && strlen($group)>0)?(" ".$group):('')),
							"spellcheck"=>"true",
							"rows"=>((isset($field['rows']) && intval($field['rows']))?(intval($field['rows'])):(10)),
							"cols"=>((isset($field['cols']) && intval($field['cols']))?(intval($field['cols'])):(50)),
							"tabindex"=>($id+1),
							"onkeyup"=>"validateRange(".$id.", 'character');",
							"contenteditable"=>"true"
						);
				array_push($this->ckeditor_istances,$pr['id']);
				if(isset($field['required']) && $field['required']) $pr["required"] = 'required';
				if(isset($field['title']) && $field['title']) $pr["alt"] = $field['title'];
				
				$attr = array();
				foreach($pr as $attname=>$attvalue) $attr[] = $attname.'="'.$attvalue.'"';
				
				$this->html->add_text("<textarea".((count($attr))?(" ".implode(' ',$attr)):('')).">".((isset($field['value']) && $field['value'])?($field['value']):(''))."</textarea>");
				$attr = $pr = null;
				unset($pr,$attr);
				break;
		}
		if(array_key_exists('posthtml',$field)) $this->html->add_text($field['posthtml']);
	}
	
	private function GetFields(){
		if(isset($this->fields) && is_array($this->fields) && count($this->fields)){
			foreach($this->fields as $id=>$field){
				if((!array_key_exists('group',$field) || !array_key_exists($field['group'],$this->fields)) && $field['type']!='group'){
					//non sono oggetti in gruppo e li disegno
					if($field['type']=='html') $field['maxlength'] = null;
					$this->html
								->li(array('id'=>"foli".$id,'class'=>'notranslate'))
									->label(array('class'=>'desc','id'=>'title'.$id,'for'=>'Field'.$id));
						if(array_key_exists('title',$field)) $this->html->add_text($field['title']);
						if(array_key_exists('required',$field) && $field['required']) $this->html->span(array('id'=>'req_'.$id,'class'=>'req'))->add_text("*")->_span();
						$this->html->_label()
									->div();
										$this->GetFieldType($id);
						if(isset($field['maxlength']) && $field['maxlength']) $this->html->label(array('for'=>'Field'.$id))->add_text("Massimo consentito: ")->var(array("id"=>'rangeMaxMsg'.$id))->add_text($field['maxlength'])->_var()->add_text(" caratteri.&nbsp;&nbsp;&nbsp; ")->em(array('class'=>'currently'))->add_text("Attualmente in uso: ")->var(array("id"=>'rangeUsedMsg'.$id))->add_text((isset($field['value']) && $field['value'])?(strlen($field['value'])):('0'))->_var()->add_text(" caratteri.")->_em()->_label();
						$this->html->_div()
								->_li();
				}
				elseif($field['type']=='group'){
					$elements = $this->groups[$id];
					if($elements && count($elements)>0){
						$this->html
								->li(array('id'=>"foli".$id,'class'=>'notranslate'))
									->label(array('class'=>'desc','id'=>'title'.$id,'for'=>'Field'.$id));
						if(array_key_exists('title',$field)) $this->html->add_text($field['title']);
						if(array_key_exists('required',$field) && $field['required']) $this->html->span(array('id'=>'req_'.$id,'class'=>'req'))->add_text("*")->_span();
						$this->html->_label()->div();
						$perc = intval((100 - (count($elements)*5))/count($elements));
						foreach($elements as $id_el){
							$element = array_key_exists($id_el,$this->fields)?$this->fields[$id_el]:null;
							if($element){
								$this->html->div(array('style'=>'float:left; width:'.$perc.'%; padding-right:5%;'));
								if(array_key_exists('title',$element)){
									$this->html->label(array('class'=>'desc','id'=>'title'.$id_el,'for'=>'Field'.$id_el))->add_text($element['title']);
									if(array_key_exists('required',$element) && $element['required']) $this->html->span(array('id'=>'req_'.$id_el,'class'=>'req'))->add_text("*")->_span();
									$this->html->_label();
								}
								
								$this->GetFieldType($id_el,'group');
								
								$this->html->_div();
							}
						}
						$this->html->_div();
						if(isset($field['maxlength']) && $field['maxlength']) $this->html->label(array('for'=>'Field'.$id))->add_text("Massimo consentito: ")->var(array("id"=>'rangeMaxMsg'.$id))->add_text($field['maxlength'])->_var()->add_text(" caratteri.&nbsp;&nbsp;&nbsp; ")->em(array('class'=>'currently'))->add_text("Attualmente in uso: ")->var(array("id"=>'rangeUsedMsg'.$id))->add_text((isset($field['value']) && $field['value'])?(strlen($field['value'])):('0'))->_var()->add_text(" caratteri.")->_em()->_label();
						$this->html->_li();
					}
				}
			}
		}
	}
	
	function GetForm(){
		if(isset($this->css_inline) && $this->css_inline){
			$this->html->style(array('type'=>'text/css'))->add_text($this->css_inline)->_style();
		}
		if(isset($this->css_files) && count($this->css_files)){
			foreach($this->css_files as $path) $this->html->link(array('type'=>'text/css','rel'=>'stylesheet','href'=>$path));
		}
		if(isset($this->js_files) && count($this->js_files)){
			foreach($this->js_files as $path) $this->html->script(array('type'=>'text/javascript','src'=>$path))->_script();
		}
		if(isset($this->generic_files) && count($this->generic_files)){
			foreach($this->generic_files as $file) $this->html->add_text($file);
		}
		$this->html
				->div(array('id'=>'container','class'=>'ltr','style'=>''))
					->form(array('id'=>$this->name,'name'=>$this->name,'class'=>'wufoo topLabel page','autocomplete'=>'off','enctype'=>'multipart/form-data','method'=>$this->method,'novalidate'=>'novalidate','action'=>"".$this->action))
						->input(array('type'=>'hidden','name'=>$this->name."_posted",'value'=>'1'));
						$this->GetHead();
				$this->html->ul();
							$this->GetFields();
				$this->html->li(array('class'=>'buttons'))
								->div()
									->input(array('id'=>'saveForm','name'=>'saveForm','class'=>'btTxt submit','type'=>'submit','value'=>((isset($this->button_save) && $this->button_save)?($this->button_save):('Invia')),'onclick'=>((isset($this->ckeditor_istances) && count($this->ckeditor_istances))?('updateAllIstance(); '):('')).'return CheckForm(this.form.name,true);'))
								->_div()
							->_li()
						->_ul()
					->_form()
				->_div();
		if(isset($this->ckeditor_istances) && count($this->ckeditor_istances)){
			$arr = array();
			foreach($this->ckeditor_istances as $name){
				$arr[] = "CKEDITOR.replace('".$name."');
					CKEDITOR.instances['".$name."'].on('focus',function(){highlight(document.getElementById('".$name."'), 2); CKEDITOR.instances['".$name."'].updateElement();});
					CKEDITOR.instances['".$name."'].on('blur',function(){CKEDITOR.instances['".$name."'].updateElement();});
					addEvent(document.getElementById('".str_replace('Field','title',$name)."'),'click',function(){ highlight(document.getElementById('".$name."'), 2);});";
			}
			$script = implode("\n\n",$arr)."\n\nfunction updateAllIstance(){\n";
			foreach($this->ckeditor_istances as $name){
				$script.="CKEDITOR.instances['".$name."'].updateElement();\n";
			}
			$script.="}";
			$this->html->script(array("type"=>'text/javascript'))->add_text($script)->_script();
			$arr = null;
			unset($arr);
		}
		return $this->html->get_content();
	}
	
	function isPosted(){
		return isset($this->post[$this->name."_posted"]) && intval($this->post[$this->name."_posted"])>0;
	}
	
	function getDatas(){
		$ret = null;
		if(isset($this->fields) && is_array($this->fields) && count($this->fields)){
			foreach($this->fields as $id=>$field){
				$ret[$field['name']] = isset($this->post[$field['name']]) && trim($this->post[$field['name']]) ? trim($this->post[$field['name']]) : null;
			}
		}
		return $ret;
	}
}
