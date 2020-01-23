<?php
/*
 * EAGLE
 * v 1.0
 * date: 02/04/2013
 * made by: Andrisani Umberto
 * copyright: Andrisani Umberto 2013
 * 
 * */
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
if(defined('APP_DIR')){
	if(is_file(APP_DIR.DS."eagle_core.php")) include(APP_DIR.DS."eagle_core.php");
	else die("Attenzione! Impossibile individuare il core");
}

class Eagle extends Eagle_core{
	public $debug_level = 0;
	function __construct($rules=null,$exec=false,$error_controller=null){
		parent::__construct();
		
		if($rules && is_array($rules)){
			$this->add_rules($rules);
		}
		if(isset($error_controller) && $error_controller){
			$this->set_error_controller($error_controller);
		}
		if(isset($exec) && $exec){
			$this->start();
		}
	}
	
	function __destruct(){
		
	}
	
	function add_rules($rules){
		return $this->add($rules);
	}
	
	function set_debug_level($debug_level){
		if(intval($debug_level)){
			parent::set_debug_level($debug_level);
			$this->debug_level = $debug_level;
		}
		return $this;
	}
}
