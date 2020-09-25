<?php
/*
 * EAGLE OBJECT
 * v 1.0
 * date: 11/07/2013
 * made by: Andrisani Umberto
 * copyright: Andrisani Umberto 2013
 * 
 * */

class Eagle_Object extends Eagle_core{
	public $cookie;
	public $files;
	public $get;
	public $globals;
	public $post;
	public $server;
	public $session;
	
	public $params = array();
	public $type = '';
	public $istance_name = '';
	
	function __construct($type=null,$istance_name=null){
		parent::__construct();
		
		if(isset($_COOKIE)){
			$this->cookie =& $_COOKIE;
		}
		if(isset($_FILES)){
			$this->files =& $_FILES;
		}
		if(isset($_GET)){
			$this->get =& $_GET;
		}
		if(isset($GLOBALS)){
			$this->globals =& $GLOBALS;
		}
		if(isset($_POST)){
			$this->post =& $_POST;
		}
		if(isset($_SERVER)){
			$this->server =& $_SERVER;
		}
		if(isset($_SESSION)){
			$this->server =& $_SESSION;
		}
		
		if(isset($type) && $type) $this->type = $type;
		if(isset($istance_name) && $istance_name) $this->istance_name = $type;
	}
	
	function __destruct(){
		
	}
	
	function set_istance_name($istance_name){
		if(isset($istance_name) && $istance_name) $this->istance_name = $istance_name;
	}
	
	function set_type($type=null){
		if(isset($type) && $type) $this->type = $type;
	}
	
	function set_param($pname,$ptype,$pvalue,&$var){
		$this->params[$pname] = null;
		switch($ptype){
			case 'integer':
			case 'int': $this->params[$pname] = "$pvalue"?intval("$pvalue"):null; break;
			case 'bool':
			case 'boolean': $this->params[$pname] = ((is_bool($pvalue) && $pvalue) || "$pvalue"=='true' || "$pvalue"=='1'); break;
			case 'string':
			default: $this->params[$pname] = "$pvalue"; break;
		}
		if($var){
			$var = $this->params[$pname];
		}
	}
	
	function get_param($pname){
		$ret = null;
		if(array_key_exists($pname,$this->params)) $ret = $this->params[$pname];
		return $ret;
	}
	
	public function &get_session(){
		return (isset($this->session) && $this->type && array_key_exists($this->type,$this->session) && $this->istance_name && array_key_exists($this->istance_name,$this->session[$this->type]))?$this->session[$this->type][$this->istance_name]:null;
	}
}
