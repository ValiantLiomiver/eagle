<?php
/*
 * EAGLE XHTML
 * v 1.0
 * date: 07/04/2013
 * made by: Andrisani Umberto
 * copyright: Andrisani Umberto 2013
 * 
 * */

class Eagle_Xhthml{
	private $content = '';
	private $tabs = 0;
	private $returnmode = true;
	private $type = 'html';
	private $charset = 'utf-8';
	private $type_alowed = array('html','xml');
	private $header_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	private $html_tags_opened = array(
										'a',
										'abbr',
										'acronym',
										'address',
										'applet',
										'b',
										'bdo',
										'big',
										'blink',
										'blockquote',
										'button',
										'caption',
										'center',
										'cite',
										'code',
										'col',
										'colgroup',
										'dd',
										'del',
										'dfn',
										'dir',
										'div',
										'dl',
										'dt',
										'em',
										'fieldset',
										'font',
										'form',
										'frameset',
										'h1',
										'h2',
										'h3',
										'h4',
										'h5',
										'h6',
										'h7',
										'header',
										'i',
										'iframe',
										'ins',
										'isindex',
										'kbd',
										'label',
										'legend',
										'li',
										'map',
										'marquee',
										'menu',
										'closednoframes',
										'noscript',
										'object',
										'ol',
										'optgroup',
										'option',
										'p',
										'pre',
										'print',
										'q',
										's',
										'samp',
										'script',
										'select',
										'small',
										'span',
										'strike',
										'strong',
										'style',
										'sub',
										'sup',
										'table',
										'tbody',
										'td',
										'textarea',
										'tfoot',
										'th',
										'thead',
										'tr',
										'tt',
										'u',
										'ul',
										'var'
									);
	private $html_tags_closed = array(
									'area',
									'base',
									'basefont',
									'br',
									'frame',
									'img',
									'input',
									'hr',
									'link',
									'meta',
									'param'
								);
	
	private $tag_no_spaced = array('textarea');
	
	function __construct($return=false,$type='html',$charset='utf-8'){
		if(isset($return) && is_bool($return)) $this->returnmode = $return;
		if(in_array($type,$this->type_alowed)) $this->type = $type;
		if($charset) $this->charset = $charset;
	}
	
	function __destruct(){
		
	}
	
	public function __call($method, $args){
		if(($this->type!='html') || ($this->type=='html' && (in_array($method,$this->html_tags_opened) || in_array(substr($method,1),$this->html_tags_opened)))){
			if(substr($method,0,1)=='_') call_user_func(array($this,'_generic'),substr($method,1));
			else{
				if(isset($args[0])) call_user_func(array($this,'generic'),$method,$args[0]);
				else call_user_func(array($this,'generic'),$method);
			}
		}
		elseif($this->type=='html' && (in_array($method,$this->html_tags_closed))){
			if(isset($args[0])) call_user_func(array($this,'generic_closed'),$method,$args[0]);
			else call_user_func(array($this,'generic_closed'),$method);
		}
		return $this;
	}
	
	public function set_return_mode($return){
		if(isset($return) && is_bool($return)){
			$this->returnmode = $return;
		}
		return $this;
	}
	
	
	public function set_type($type){
		if($type){
			$this->type = $type;
		}
		return $this;
	}
	
	public function set_charset($charset){
		if($charset){
			$this->charset = $charset;
		}
		return $this;
	}
	
	public function set_header_html($header){
		if($header){
			$this->header_xml = $header;
		}
		return $this;
	}
	
	private function tabs(){
		$out = '';
		for($i=0; $i<$this->tabs; $i++){
			$out.="\t";
		}
		return $out;
	}
	
	function add_text($text,$translate=false){
		$translate = isset($translate) && $translate ? true : false;
		//if($this->returnmode) $this->content.="\n".$this->tabs();
		//else echo "\n".$this->tabs();
		if($this->type=='html'){
			if($this->returnmode) $this->content.=$translate ? htmlentities($text,ENT_COMPAT,strtoupper($this->charset)) : $text;
			else echo $translate ? htmlentities($text,ENT_COMPAT,strtoupper($this->charset)) : $text;
		}
		elseif($this->type=='xml'){
			if($this->returnmode) $this->content.=$translate ? htmlentities($text,ENT_XML,strtoupper($this->charset)) : $text;
			else echo $translate ? htmlentities($text,ENT_XML,strtoupper($this->charset)) : $text;
		}
		return $this;
	}
	
	function _print(){
		switch($this->type){
			case 'xml':
				header("Content-type: text/xml");
				echo $this->header_xml;
				break;
			case 'html':
			default:
				header("Content-type: text/html; charset=".$this->charset);
				break;
		}
		echo $this->content;
	}
	
	function get_content(){
		return $this->content;
	}
	
	function empty_content(){
		$this->content = '';
		return $this;
	}
	
	/*
	 | 
	 | OPENED TAGS
	 | 
	 */
	public function generic($tag,$prop=null){
		//if(!in_array($tag,$this->tag_no_spaced)){
			if($this->returnmode) $this->content.="\n".$this->tabs();
			else echo "\n".$this->tabs();
		//}
		$this->tabs++;
		$property = '';
		if(isset($prop) && is_array($prop) && count($prop)){
			$p = array();
			foreach($prop as $name=>$val) $p[] = $name.'="'.$val.'"';
			$property = ' '.implode(' ',$p);
		}
		if($this->returnmode) $this->content.="<".$tag.$property.">";
		else echo "<".$tag.$property.">";
		return $this;
	}
	
	public function _generic($tag){
		$this->tabs--;
		if($this->returnmode){
			if(!in_array($tag,$this->tag_no_spaced)) $this->content.="\n".$this->tabs();
			$this->content.="</".$tag.">";
		}
		else{
			if(!in_array($tag,$this->tag_no_spaced)) echo "\n".$this->tabs();
			echo "</".$tag.">";
		}
		return $this;
	}
	
	/*
	 | 
	 | CLOSED TAGS
	 | 
	 */
	function generic_closed($tag,$prop=null){
		if($this->returnmode) $this->content.="\n".$this->tabs();
		else echo "\n".$this->tabs();
		$property = '';
		if(isset($prop) && is_array($prop) && count($prop)){
			$p = array();
			foreach($prop as $name=>$val) $p[] = $name.'="'.$val.'"';
			$property = ' '.implode(' ',$p);
		}
		if($this->returnmode) $this->content.="<".$tag.$property." />";
		else echo "<".$tag.$property." />";
		return $this;
	}
}
