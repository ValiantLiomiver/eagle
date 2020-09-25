<?php
/*
 * EAGLE CONTROLLER
 * v 1.0
 * date: 03/04/2013
 * made by: Andrisani Umberto
 * copyright: Andrisani Umberto 2013
 * 
 * */

class Eagle_controller extends Eagle_core{
	private $html_builder = null;
	var $post = array();
	var $get = array();
	var $session = array();
	var $globals = array();
	var $server = array();
	var $cookie = array();
	var $files = array();
	public $extension = "php";
	
	public $load = null;
	
	function __construct(){
		parent::__construct();
		
		if(defined('DEBUG_LEVEL')){
			parent::set_debug_level(DEBUG_LEVEL);
		}
		
		if(class_exists('Eagle_Xhthml')){
			$this->html_builder = new Eagle_Xhthml();
		}
		
		if(isset($_POST)) $this->post =& $_POST;
		if(isset($_GET)) $this->get =& $_GET;
		if(isset($_SESSION)) $this->session =& $_SESSION;
		if(isset($GLOBALS)) $this->globals =& $GLOBALS;
		if(isset($_SERVER)) $this->server =& $_SERVER;
		if(isset($_COOKIE)) $this->cookie =& $_COOKIE;
		if(isset($_FILES)) $this->files =& $_FILES;
		
		$this->extension = $this->get_controller_ext();
		$this->load = $this->load();
	}
	
	function __destruct(){
		
	}
	
	public function html($return=null){
		if(isset($return) && is_bool($return)) $this->html_builder->set_return_mode($return);
		return $this->html_builder;
	}
	
	function set_datas(&$datas){
		parent::set_datas($datas);
	}
	
	function set_debug_level($level=null){
		return parent::set_debug_level($level);
	}
	
	function set_type_arguments($index,$type,&$val=null){
		return parent::set_type_arguments($index,$type,$val);
	}
	
	function set_type_var($type,&$var){
		switch($type){
			case 'int':
			case 'integer':
				if(trim($var)){
					$var = intval($var);
				}
				else $var = null;
				break;
			case 'double':
				if(trim($var)){
					if(is_numeric($var)) $var = number_format($var,2,'.','');
					else $var = number_format('0',2,'.','');
				}
				else $var = null;
				break;
			case 'bool':
			case 'boolean':
				//$var = intval($var);
				if(trim($var)){
					if(intval($var)==1){
						$var = true;
					}
					else{
						if(is_string($var)){
							if($var==='true'){
								$var = true;
							}
							else{
								$var = false;
							}
						}
						else{
							$var = false;
						}
					}
				}
				else $var = null;
				break;
			case 'string':
			default:
				if(trim($var)){
					$var = trim(urldecode($var));
				}
				else $var = null;
				break;
		}
	}
	
	function set_type_post_var($index,$type,&$val=null){
		$ret = false;
		if(isset($index) && isset($type) && $type){
			if(array_key_exists($index,$this->post)){
				$ret = true;
				switch($type){
					case 'int[]':
					case 'integer[]':
					case 'double[]':
					case 'bool[]':
					case 'boolean[]':
					case 'string[]':
						//array of int
						if(is_array($this->post[$index])){
							$app = $this->post[$index];
							foreach($app as $k=>$v){
								$this->set_type_var(substr($type,0,-2),$v);
								$this->post[$index][$k] = $v;
							}
							$app = null;
							unset($app);
						}
						else{
							$this->post[$index] = array();
						}
						break;
					case 'int':
					case 'integer':
						if(trim($this->post[$index])){
							$this->post[$index] = intval($this->post[$index]);
						}
						else $this->post[$index] = null;
						break;
					case 'double':
						if(trim($this->post[$index])){
							if(is_numeric($this->post[$index])) $this->post[$index] = number_format($this->post[$index],2,'.','');
							else $this->post[$index] = number_format('0',2,'.','');
						}
						else $this->post[$index] = null;
						break;
					case 'bool':
					case 'boolean':
						//$this->post[$index] = intval($this->post[$index]);
						if(trim($this->post[$index])){
							if(intval($this->post[$index])==1){
								$this->post[$index] = true;
							}
							else{
								if(is_string($this->post[$index])){
									if($this->post[$index]==='true'){
										$this->post[$index] = true;
									}
									else{
										$this->post[$index] = false;
									}
								}
								else{
									$this->post[$index] = false;
								}
							}
						}
						else $this->post[$index] = null;
						break;
					case 'string':
					default:
						if(trim($this->post[$index])){
							$this->post[$index] = trim(urldecode($this->post[$index]));
						}
						else $this->post[$index] = null;
						break;
				}
			}
			else{
				$this->post[$index] = null;
			}
			
			$val = $this->post[$index];
		}
		
		return $ret;
	}
	
	function start_session(){
		if(strlen(session_id())==0) session_start();
		$this->session =& $_SESSION;
	}
	
	function id_session($sessid){
		$ret = false;
		if(preg_match('/^[a-z0-9]{32}$/', strtolower($sessid))){
			session_id($sessid);
			$ret = true;
		}
		return $ret;
	}
	
	function destroy_session(){
		session_destroy();
		unset($this->cookie['PHPSESSID']);
		$this->session = array();
	}
}
