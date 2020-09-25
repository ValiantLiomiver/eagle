<?php
/*
 * EAGLE VIEW
 * v 1.0
 * date: 24/08/2013
 * made by: Andrisani Umberto
 * copyright: Andrisani Umberto 2013
 * 
 * */

class Eagle_view extends Eagle_core{
	private $html_builder = null;
	var $post = array();
	var $get = array();
	var $session = array();
	var $globals = array();
	var $server = array();
	var $cookie = array();
	var $files = array();
	
	public $extension = "php";
	public $html = null;
	public $load = null;
	
	function __construct(&$load,&$html){
		parent::__construct();
		
		if(defined('DEBUG_LEVEL')){
			parent::set_debug_level(DEBUG_LEVEL);
		}
		
		if(isset($_POST)) $this->post =& $_POST;
		if(isset($_GET)) $this->get =& $_GET;
		if(isset($_SESSION)) $this->session =& $_SESSION;
		if(isset($GLOBALS)) $this->globals =& $GLOBALS;
		if(isset($_SERVER)) $this->server =& $_SERVER;
		if(isset($_COOKIE)) $this->cookie =& $_COOKIE;
		if(isset($_FILES)) $this->files =& $_FILES;
		
		$this->extension = $this->get_view_ext();
		$this->path = $this->get_path_view();
		$this->load =& $load;
		$this->html =& $html;
	}
	
	function __destruct(){
		
	}
	
	function html(){
		return $this->html;
	}
	
	public function _load_view($view_name,$data=null){
		if($view_name){
			if(isset($_POST)) $this->post =& $_POST;
			if(isset($_GET)) $this->get =& $_GET;
			if(isset($_SESSION)) $this->session =& $_SESSION;
			if(isset($GLOBALS)) $this->globals =& $GLOBALS;
			if(isset($_SERVER)) $this->server =& $_SERVER;
			if(isset($_COOKIE)) $this->cookie =& $_COOKIE;
			if(isset($_FILES)) $this->files =& $_FILES;
			
			$view_name.=".".$this->extension;
			if(is_file($view_name) && is_readable($view_name)){
				if($data && is_array($data)) foreach($data as $k=>$v) ${$k}=$v;
				include($view_name);
			}
			elseif($this->path && is_file($this->path.DS.$view_name) && is_readable($this->path.DS.$view_name)){
				if($data && is_array($data)) foreach($data as $k=>$v) ${$k}=$v;
				include($this->path.DS.$view_name);
			}
		}
	}
	
	function view($view,$data=null,$return=false){
		$return = isset($return) && is_bool($return) && $return ? true : false;
		if(!$return){
			$this->_load_view($view,$data);
			$ret = $this;
		}
		else{
			$ret = $this->return_echo("_load_view",$view,$data);
		}
		return $ret;
	}
	
	private function return_echo($func,$p1,$p2){
		ob_start();
		call_user_func(array($this, $func),$p1,$p2);
		return ob_get_clean();
	}
	
	function start_session(){
		session_start();
		$this->session =& $_SESSION;
	}
	
	function destroy_session(){
		session_destroy();
		$this->session = array();
	}
}
