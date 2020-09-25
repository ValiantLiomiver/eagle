<?php
/*
 * EAGLE LOADER
 * v 1.0
 * date: 03/04/2013
 * made by: Andrisani Umberto
 * copyright: Andrisani Umberto 2013
 * 
 * */
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
if(!defined('APP_DIR')) define('APP_DIR',dirname(__FILE__));
class Eagle_loader extends Eagle_core{
	private $_parent = null;
	
	private $app_dir = null;
	
	private $debug_level = null;
	private $controller_ext = null;
	private $path_controller = null;
	public $controller_name = null;
	private $view_ext = null;
	private $path_view = null;
	private $business_ext = null;
	private $path_business = null;
	public $args = array();
	private $html_builder = null;
	private $dbs = array();
	public $globals = array();
	private $dbconfig = array();
	private $error_controller = null;
	public $segments = array();
	
	public $load = null;
	public $html = null;
	public $view_builder = null;
	
	function __construct(&$parent=null){
		if($parent){
			$this->_parent =& $parent;
			$this->debug_level = $this->_parent->get_debug_level();
			$this->controller_ext = $this->_parent->get_controller_ext();
			$this->path_controller = $this->_parent->get_path_controller();
			$this->view_ext = $this->_parent->get_view_ext();
			$this->path_view = $this->_parent->get_path_view();
			$this->business_ext = $this->_parent->get_business_ext();
			$this->path_business = $this->_parent->get_path_business();
			$this->language_ext = $this->_parent->get_language_ext();
			$this->path_language = $this->_parent->get_path_language();
			$this->model_ext = $this->_parent->get_model_ext();
			$this->path_model = $this->_parent->get_path_model();
			$this->controller_name = $this->_parent->controller;
			$this->args = $this->_parent->args;
			$this->datas = $this->_parent->get_datas();
			$this->globals = $this->_parent->globals;
			$this->dbconfig = $this->_parent->get_dbconfig();
			$this->error_controller = $this->_parent->get_error_controller();
			$this->segments = $this->_parent->segments;
		}
		else{
			parent::__construct();
		}
		
		if(class_exists('Eagle_Xhthml')){
			$this->html_builder = new Eagle_Xhthml();
		}
		
		$this->app_dir = APP_DIR;
		
		$this->load =& $this;
		$this->html = $this->html();
		if(class_exists('Eagle_view')){
			$this->view_builder = new Eagle_view($this,$this->html);
		}
	}
	
	function __destruct(){
		
	}
	
	public function get_datas(){
		return $this->_parent->get_datas();
	}
	
	public function debug($var){
		if($this->_parent){
			$this->_parent->debug($var);
		}
	}
	
	public function html($return=null){
		$ret = null;
		if(isset($this->html_builder) && $this->html_builder){
			if(isset($return) && is_bool($return)) $this->html_builder->set_return_mode($return);
			$ret =& $this->html_builder;
		}
		return $ret;
	}
	
	public function load_controller($controller_name){
		if($controller_name){
			$controller_name.=".".$this->controller_ext;
			if(is_file($controller_name) && is_readable($controller_name)){
				if($this->debug_level==2) $this->debug($controller_name);
				if(strlen($this->app_dir)>0){
					if(is_file($this->app_dir.DS."eagle_controller.php")) include_once($this->app_dir.DS."eagle_controller.php");
					else die("Attenzione! Impossibile individuare il controller");
				}
				else die("Attenzione! Impossibile individuare il controller");
				include($controller_name);
			}
			elseif($this->path_controller && is_file($this->path_controller.DS.$controller_name) && is_readable($this->path_controller.DS.$controller_name)){
				if($this->debug_level==2) $this->debug($this->path_controller.DS.$controller_name);
				if(strlen($this->app_dir)>0){
					if(is_file($this->app_dir.DS."eagle_controller.php")) include_once($this->app_dir.DS."eagle_controller.php");
					else die("Attenzione! Impossibile individuare il controller");
				}
				else die("Attenzione! Impossibile individuare il controller");
				include($this->path_controller.DS.$controller_name);
			}
		}
	}
	
	public function load_model($model_name){
		if($model_name){
			$model_name.=".".$this->model_ext;
			if(is_file($model_name) && is_readable($model_name)){
				if($this->debug_level==2) $this->debug($model_name);
				if(strlen($this->app_dir)>0){
					if(is_file($this->app_dir.DS."eagle_model.php")) include_once($this->app_dir.DS."eagle_model.php");
					else die("Attenzione! Impossibile individuare il model");
				}
				else die("Attenzione! Impossibile individuare il model");
				include($model_name);
			}
			elseif($this->path_model && is_file($this->path_model.DS.$model_name) && is_readable($this->path_model.DS.$model_name)){
				if($this->debug_level==2) $this->debug($this->path_model.DS.$model_name);
				if(strlen($this->app_dir)>0){
					if(is_file($this->app_dir.DS."eagle_model.php")) include_once($this->app_dir.DS."eagle_model.php");
					else die("Attenzione! Impossibile individuare il model");
				}
				else die("Attenzione! Impossibile individuare il model");
				include($this->path_model.DS.$model_name);
			}
		}
	}
	
	public function load_view($view_name,$data=null){
		if($view_name){
			if(isset($_POST)) $this->post =& $_POST;
			if(isset($_GET)) $this->get =& $_GET;
			if(isset($_SESSION)) $this->session =& $_SESSION;
			if(isset($GLOBALS)) $this->globals =& $GLOBALS;
			if(isset($_SERVER)) $this->server =& $_SERVER;
			if(isset($_COOKIE)) $this->cookie =& $_COOKIE;
			if(isset($_FILES)) $this->files =& $_FILES;
			$view_name.=".".$this->view_ext;
			if(is_file($view_name) && is_readable($view_name)){
				if($this->debug_level==2) $this->debug($view_name);
				if($data && is_array($data)) foreach($data as $k=>$v) ${$k}=$v;
				include($view_name);
			}
			elseif($this->path_view && is_file($this->path_view.DS.$view_name) && is_readable($this->path_view.DS.$view_name)){
				if($this->debug_level==2) $this->debug($this->path_view.DS.$view_name);
				if($data && is_array($data)) foreach($data as $k=>$v) ${$k}=$v;
				include($this->path_view.DS.$view_name);
			}
		}
	}
	
	public function load_business($business_name){
		if($business_name){
			require_once INCLUDE_DIR.DS."eagle_object.php";
			$business_name.=".".$this->business_ext;
			if(is_file($business_name) && is_readable($business_name)){
				if($this->debug_level==2) $this->debug($business_name);
				include($business_name);
			}
			elseif($this->path_business && is_file($this->path_business.DS.$business_name) && is_readable($this->path_business.DS.$business_name)){
				if($this->debug_level==2) $this->debug($this->path_business.DS.$business_name);
				include($this->path_business.DS.$business_name);
			}
		}
	}
	
	public function load_language($language_name){
		$returned_language = array();
		if($language_name){
			$language_name.=".".$this->language_ext;
			if(is_file($language_name) && is_readable($language_name)){
				if($this->debug_level==2) $this->debug($language_name);
				include($language_name);
				$app = get_defined_vars();
				foreach($app as $var=>$v){
					if($var!='language_name'){
						$returned_language[$var] = $v;
					}
				}
			}
			elseif($this->path_language && is_file($this->path_language.DS.$language_name) && is_readable($this->path_language.DS.$language_name)){
				if($this->debug_level==2) $this->debug($this->path_language.DS.$language_name);
				include($this->path_language.DS.$language_name);
				$app = get_defined_vars();
				foreach($app as $var=>$v){
					if($var!='language_name'){
						$returned_language[$var] = $v;
					}
				}
			}
		}
		return $returned_language;
	}
	
	private function return_echo($func,$p1,$p2){
		ob_start();
		call_user_func(array($this, $func),$p1,$p2);
		return ob_get_clean();
	}
	
	function controller($controller_name,$method=null){
		if($this->debug_level==1) $this->debug("(Function controller) Controller => ".$controller_name);
		if(!class_exists($controller_name)) $this->load_controller($controller_name);
		if(class_exists($controller_name)){
			$tmpobj = new $controller_name();
			$tmpobj->controller = $controller_name;
			$tmpobj->method = $method;
			$tmpobj->args = $this->args;
			if($this->debug_level==1 && isset($method) && $method) $this->debug("Method => ".$method);
			if(isset($method) && $method && method_exists($tmpobj,$method)){
				if(is_callable(array($tmpobj, $method))){
					call_user_func(array($tmpobj, $method));
				}
				elseif(method_exists($tmpobj,$controller_name.'_error')){
					call_user_func(array($tmpobj, $controller_name.'_error'));
				}
				elseif(!method_exists($tmpobj,$controller_name.'_error')){
					if(isset($this->error_controller) && $this->error_controller){
						$this->controller($this->error_controller);
					}
				}
			}
			elseif(isset($method) && $method && !method_exists($tmpobj,$method) && method_exists($tmpobj,$controller_name.'_error')){
				call_user_func(array($tmpobj, $controller_name.'_error'));
			}
			elseif(isset($method) && $method && !method_exists($tmpobj,$method) && !method_exists($tmpobj,$controller_name.'_error')){
				if(isset($this->error_controller) && $this->error_controller){
					$this->controller($this->error_controller);
				}
			}
			elseif(method_exists($tmpobj,'index')){
				call_user_func(array($tmpobj, 'index'));
			}
			elseif(method_exists($tmpobj,$controller_name.'_start')){
				call_user_func(array($tmpobj, $controller_name.'_start'));
			}
		}
		elseif(isset($this->error_controller) && $this->error_controller){
			$this->controller($this->error_controller);
		}
	}
	
	function model($model_name,$label=null){
		$ret = null;
		if($this->debug_level==1) $this->debug("(Function model) Model => ".$model_name);
		if(!class_exists($model_name)) $this->load_model($model_name);
		if(class_exists($model_name)){
			$ret = new $model_name($label);
		}
		return $ret;
	}
	
	function view_old($view,$data=null,$return=false){
		if($this->debug_level==2) $this->debug("(Function view) View => ".$view);
		$return = isset($return) && is_bool($return) && $return ? true : false;
		if(!$return){
			$this->load_view($view,$data);
			$ret = $this;
		}
		else{
			$ret = $this->return_echo("load_view",$view,$data);
		}
		return $ret;
	}
	
	function view($view,$data=null,$return=false){
		if($this->debug_level==2) $this->debug("(Function view) View => ".$view);
		$return = isset($return) && is_bool($return) && $return ? true : false;
		if(!$return){
			$this->view_builder = $this->view_builder->view($view,$data,false);
			$ret = $this;
		}
		else{
			$ret = $this->view_builder->view($view,$data,true);
		}
		return $ret;
	}
	
	function business($business_name){
		$ret = null;
		if($this->debug_level==1) $this->debug("(Function business) Business => ".$business_name);
		if(!class_exists($business_name)) $this->load_business($business_name);
	}
	
	function language($language_name){
		$ret = array();
		if($this->debug_level==1) $this->debug("(Function language) Language => ".$language_name);
		$returned_language = $this->load_language($language_name);
		foreach($returned_language as $varname=>$content){
			if($varname=='LANGUAGE') $ret = $content;
		}
		return $ret;
	}
	
	function db($label=null){
		$ret = null;
		if(isset($label) && $label && array_key_exists($label,$this->dbs)) $ret = $this->dbs[$label];
		elseif(isset($label) && $label && !array_key_exists($label,$this->dbs)){
			if(is_array($this->dbconfig) && array_key_exists($label,$this->dbconfig)){
				$ret = $this->dbs[$label] = new Eagle_db($this->dbconfig[$label]);
			}
			elseif(!is_array($this->dbconfig)){
				if(is_string($this->dbconfig)){
					$ret = $this->dbs[$label] = new Eagle_db($this->dbconfig);
				}
			}
			elseif(array_key_exists($label,$this->dbconfig)){
				die("Config ".$label." not found");
			}
		}
		elseif(!isset($label) || !$label){
			if($this->dbconfig && is_string($this->dbconfig)){
				$id = count($this->dbs);
				$ret = $this->dbs[$id] = new Eagle_db($this->dbconfig);
			}
			elseif($this->dbconfig && is_array($this->dbconfig)){
				$id = count($this->dbs);
				$ret = $this->dbs[$id] = new Eagle_db($this->dbconfig);
			}
			else{
				die("Config not found");
			}
		}
		return $ret;
	}
	
	function load(){
		return $this;
	}
}
