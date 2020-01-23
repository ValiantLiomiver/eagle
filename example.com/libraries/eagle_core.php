<?php
/*
 * EAGLE CORE
 * v 1.0
 * date: 02/04/2013
 * v 1.1
 * date: 26/07/2013
 * 		- Added language (PATH, EXT)
 * 		- Added segments param
 * 
 * made by: Andrisani Umberto
 * copyright: Andrisani Umberto 2013
 * 
 * */
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
if(!defined('APP_DIR')){
	define('APP_DIR',dirname(__FILE__));
}

if(is_file(APP_DIR.DS."eagle_config.php")) include(APP_DIR.DS."eagle_config.php");
else die("Attenzione! il file di configurazione non esiste");
if(is_file(APP_DIR.DS."eagle_loader.php")) include(APP_DIR.DS."eagle_loader.php");
else die("Attenzione! Impossibile individuare il loader");
if(is_file(APP_DIR.DS."eagle_xhtml.php")) include(APP_DIR.DS."eagle_xhtml.php");
else die("Attenzione! Impossibile individuare il builder");
if(is_file(APP_DIR.DS."eagle_view.php")) include(APP_DIR.DS."eagle_view.php");
else die("Attenzione! Impossibile individuare il view");
if(is_file(APP_DIR.DS."eagle_db.php")) include(APP_DIR.DS."eagle_db.php");
else die("Attenzione! Impossibile individuare il file del db");

class Eagle_core{
	public $post = array();
	public $get = array();
	public $session = array();
	public $server = array();
	public $globals = array();
	public $cookie = array();
	public $files = array();
	
	private $debug_level = 0;
	private $path_view = null;
	private $path_controller = null;
	private $path_model = null;
	private $path_business = null;
	private $path_language = null;
	private $base_path = null;
	private $request_uri = null;
	private $controller_ext = "php";
	private $view_ext = "php";
	private $business_ext = "php";
	private $language_ext = "php";
	private $model_ext = "php";
	private $error_controller = "error";
	public $controller = null;
	public $method = null;
	private $rules = array();
	private $path_separator = "/";
	private $_case = 1; /*
						 * 1 => CamelCase
						 * 2 => UPPERCASE
						 * 3 => LOWERCASE
						 */
	private $datas = array();
	public $args = array();
	private $dbconfig = array();
	private $loader = null;
	public $segments = array();
	
	function __construct(){
		if(isset($_POST)) $this->post =& $_POST;
		if(isset($_GET)) $this->get =& $_GET;
		if(isset($_SESSION)) $this->session =& $_SESSION;
		if(isset($GLOBALS)) $this->globals =& $GLOBALS;
		if(isset($_SERVER)) $this->server =& $_SERVER;
		if(isset($_COOKIE)) $this->cookie =& $_COOKIE;
		if(isset($_FILES)) $this->files =& $_FILES;
		
		if(defined('REQUEST_METHOD') && REQUEST_METHOD){
			$this->request_uri = $this->server[REQUEST_METHOD];
		}
		else{
			$this->request_uri = $this->server['REQUEST_URI'];
		}
		if(defined('BASE_URL')){
			$this->base_path = BASE_URL;
		}
		else{
			$this->base_path = '/';
		}
		if(defined('STRING_CASE') && intval(STRING_CASE)){
			$this->_case = intval(STRING_CASE);
		}
		else $this->_case = 1;
		if(defined('CONTROLLER_EXT') && CONTROLLER_EXT){
			$this->controller_ext = CONTROLLER_EXT;
		}
		else $this->controller_ext = 'php';
		if(defined('VIEW_EXT') && VIEW_EXT){
			$this->view_ext = VIEW_EXT;
		}
		else $this->view_ext = 'php';
		if(defined('BUSINESS_EXT') && BUSINESS_EXT){
			$this->business_ext = BUSINESS_EXT;
		}
		else $this->business_ext = 'php';
		if(defined('LANGUAGE_EXT') && LANGUAGE_EXT){
			$this->language_ext = LANGUAGE_EXT;
		}
		else $this->language_ext = 'php';
		if(defined('MODEL_EXT') && MODEL_EXT){
			$this->model_ext = MODEL_EXT;
		}
		else $this->model_ext = 'php';
		
		if(defined('PATH_VIEW') && PATH_VIEW){
			$this->path_view = PATH_VIEW;
		}
		if(defined('PATH_BUSINESS') && PATH_BUSINESS){
			$this->path_business = PATH_BUSINESS;
		}
		if(defined('PATH_LANGUAGE') && PATH_LANGUAGE){
			$this->path_language = PATH_LANGUAGE;
		}
		if(defined('PATH_CONTROLLER') && PATH_CONTROLLER){
			$this->path_controller = PATH_CONTROLLER;
		}
		if(defined('PATH_MODEL') && PATH_MODEL){
			$this->path_model = PATH_MODEL;
		}
		
		if(isset($this->request_uri) && $this->request_uri){
			$array_parsed = $this->parse_request();
			$this->controller = $array_parsed['controller'];
			$this->method = $array_parsed['method'];
			$this->args = $array_parsed['args'];
			unset($array_parsed);
		}
		if(!$this->controller && defined('MAIN_CONTROLLER')){
			$this->controller = MAIN_CONTROLLER;
		}
		
		if(array_key_exists('dbconfig',$this->globals) && is_array($this->globals['dbconfig'])){
			$this->dbconfig = $this->globals['dbconfig'];
		}
	}
	
	function __destruct(){
		unset($this->path_view,$this->path_controller,$this->request_uri);
	}
	
	function set_datas(&$datas){
		$this->datas =& $datas;
	}
	
	function get_datas(){
		return $this->datas;
	}
	
	function set_error_controller($cont){
		if($cont){
			$this->error_controller = $cont;
		}
	}
	
	function get_error_controller(){
		return $this->error_controller;
	}
	
	function get_dbconfig(){
		return $this->dbconfig;
	}
	
	function set_type_arguments($index,$type,&$val=null){
		$ret = false;
		if(isset($index) && isset($type) && $type){
			if(array_key_exists($index,$this->args)){
				$ret = true;
				switch($type){
					case 'int':
					case 'integer':
						if(trim($this->args[$index])){
							$this->args[$index] = intval($this->args[$index]);
						}
						else $this->args[$index] = null;
						break;
					case 'double':
						if(trim($this->args[$index])){
							if(is_numeric($this->args[$index])) $this->args[$index] = number_format($this->args[$index],2,'.','');
							else $this->args[$index] = number_format('0',2,'.','');
						}
						else $this->args[$index] = null;
						break;
					case 'bool':
					case 'boolean':
						//$this->args[$index] = intval($this->args[$index]);
						if(trim($this->args[$index])){
							if(intval($this->args[$index])==1){
								$this->args[$index] = true;
							}
							else{
								if(is_string($this->args[$index])){
									if($this->args[$index]==='true'){
										$this->args[$index] = true;
									}
									else{
										$this->args[$index] = false;
									}
								}
								else{
									$this->args[$index] = false;
								}
							}
						}
						else $this->args[$index] = null;
						break;
					case 'string':
					default:
						if(trim($this->args[$index])){
							$this->args[$index] = trim(urldecode($this->args[$index]));
						}
						else $this->args[$index] = null;
						break;
				}
			}
			else{
				$this->args[$index] = null;
			}
			
			$val = $this->args[$index];
		}
		
		return $ret;
	}
	
	function load_view($view_name){
		if(is_file($view_name) && is_readable($view_name)){
			if($this->debug_level==2) $this->debug($view_name);
			include($view_name);
		}
		elseif($this->path_view && is_file($this->path_view.DS.$view_name) && is_readable($this->path_view.DS.$view_name)){
			if($this->debug_level==2) $this->debug($this->path_view.DS.$view_name);
			include($this->path_view.DS.$view_name);
		}
		if(is_file($view_name.".".$this->view_ext) && is_readable($view_name.".".$this->view_ext)){
			if($this->debug_level==2) $this->debug($view_name.".".$this->view_ext);
			include($view_name.".".$this->view_ext);
		}
		elseif($this->path_view && is_file($this->path_view.DS.$view_name.".".$this->view_ext) && is_readable($this->path_view.DS.$view_name.".".$this->view_ext)){
			if($this->debug_level==2) $this->debug($this->path_view.DS.$view_name.".".$this->view_ext);
			include($this->path_view.DS.$view_name.".".$this->view_ext);
		}
	}
	
	function load_controller($controller_name){
		if(is_file($controller_name) && is_readable($controller_name)){
			if($this->debug_level==2) $this->debug($controller_name);
			include($controller_name);
		}
		elseif($this->path_controller && is_file($this->path_controller.DS.$controller_name) && is_readable($this->path_controller.DS.$controller_name)){
			if($this->debug_level==2) $this->debug($this->path_controller.DS.$controller_name);
			include($this->path_controller.DS.$controller_name);
		}
	}
	
	function set_view_path($path){
		$ret = false;
		if($path){
			$this->path_view = $path;
			if(!defined('PATH_VIEW')){
				define('PATH_VIEW',$this->path_view);
			}
			$ret = true;
		}
		
		return $ret;
	}
	
	function set_controller_path($path){
		$ret = false;
		if($path){
			$this->path_controller = $path;
			if(!defined('PATH_CONTROLLER')){
				define('PATH_CONTROLLER',$this->path_controller);
			}
			$ret = true;
		}
		
		return $ret;
	}
	
	function add($rules){
		$ret = false;
		if(is_array($rules)){
			$this->rules = $rules;
		}
		return $ret;
	}
	
	
	function load(){
		if(!isset($this->loader) || !$this->loader) $this->loader = new Eagle_Loader($this);
		return $this->loader;
	}
	
	public function db($label=null){
		return $this->load()->db($label);
	}
	
	function start(){
		if(isset($this->request_uri)){
			$array_parsed = $this->parse_request();
			$this->controller = $array_parsed['controller'];
			$this->method = $array_parsed['method'];
			$this->args = $array_parsed['args'];
			unset($array_parsed);
		}
		if(!$this->controller && defined('MAIN_CONTROLLER')){
			$this->controller = MAIN_CONTROLLER;
		}
		if($this->controller){
			if($this->debug_level==5) $this->debug(array($this->controller,$this->method,$this->args));
			$this->load()->controller($this->controller,$this->method);
		}
	}
	
	function parse_request(){
		if($this->path_separator && $this->request_uri){
			$this->segments = explode($this->path_separator,$this->request_uri);
			$first = array_shift($this->segments);
		}
		$args = array();
		$controller = $method = null;
		if(count($this->rules)){
			$rules = $this->rules;
			while(count($rules)>0 && !$controller){
				list($rule) = array_keys($rules);
				//$r = array($rule=>
				$route = $rules[$rule];
				unset($rules[$rule]);
				
				$ret = preg_replace($this->prepare_pattern($rule),$route,$this->caseControll($this->request_uri));
				if($this->debug_level==4) $this->debug(array('rule'=>$this->prepare_pattern($rule),'route'=>$route,'ret'=>$ret));
				if($ret && $ret!=$this->caseControll($this->request_uri)){
					if($this->path_separator && $this->request_uri){
						$args = explode($this->path_separator,$ret);
						$base = array_shift($args);
						
						$controller = $method = null;
						if(isset($args[0])){
							$controller = $args[0];
						}
						if(isset($args[1])){
							$method = $args[1];
						}
						if($controller){
							$controller = array_shift($args);
						}
						if($method){
							$method = array_shift($args);
						}
					}
				}
			}
			if(!$controller){
				if($this->path_separator && $this->request_uri){
					$args = explode($this->path_separator,$this->request_uri);
					$base = array_shift($args);
					
					$controller = $method = null;
					if(isset($args[0])){
						$controller = $args[0];
					}
					if(isset($args[1])){
						$method = $args[1];
					}
					if($controller){
						$controller = array_shift($args);
					}
					if($method){
						$method = array_shift($args);
					}
				}
			}
		}
		else{
			if($this->path_separator && $this->request_uri){
				$args = explode($this->path_separator,$this->request_uri);
				$base = array_shift($args);
				
				$controller = $method = null;
				if(isset($args[0])){
					$controller = $args[0];
				}
				if(isset($args[1])){
					$method = $args[1];
				}
				if($controller){
					$controller = array_shift($args);
				}
				if($method){
					$method = array_shift($args);
				}
			}
		}
		return array('controller'=>$controller,'method'=>$method,'args'=>$args);
	}
	
	function set_debug_level($level){
		$this->debug_level = $level;
		if(!defined('DEBUG_LEVEL')){
			define('DEBUG_LEVEL',$this->debug_level);
		}
	}
	
	function reg_escape($value){
		$ret = '';
		if($value){
			$slash = "\\";
			$esc = array();
			$esc[$slash] = $slash.$slash;
			$esc["/"] = $slash."/";
			$esc["."] = $slash.".";
			$esc["+"] = $slash."+";
			$esc["?"] = $slash."?";
			$esc["("] = $slash."(";
			$esc[")"] = $slash.")";
			$esc["{"] = $slash."{";
			$esc["}"] = $slash."}";
			$esc["["] = $slash."[";
			$esc["]"] = $slash."]";
			
			$ret = str_replace(array_keys($esc),$esc,$value);
		}
		return $ret;
	}
	
	function caseControll($val=''){
		$ret =& $val;
		switch($this->_case){
			case 2: //UPPERCASE
				$ret = strtoupper($ret);
				break;
			case 3: //lowercase
				$ret = strtolower($ret);
				break;
			case 1: //CamelCase (do nothing)
			default:
				$ret =& $val;
				break;
		}
		
		return $ret;
	}
	
	function prepare_pattern($pattern){
		$what = array("\/","/");
		$with = array("/","\/");
		$ret = "/^".$this->reg_escape($this->base_path).str_replace($what,$with,$pattern)."$/";
		if(!in_array($this->_case,array(2,3))){
			$ret.="i";
		}
		return $ret;
	}
	
	function get_debug_level(){
		return $this->debug_level;
	}
	
	function get_controller_ext(){
		return $this->controller_ext;
	}
	
	function get_path_controller(){
		return $this->path_controller;
	}
	
	function get_view_ext(){
		return $this->view_ext;
	}
	
	function get_path_view(){
		return $this->path_view;
	}
	
	function get_business_ext(){
		return $this->business_ext;
	}
	
	function get_path_business(){
		return $this->path_business;
	}
	
	function get_language_ext(){
		return $this->language_ext;
	}
	
	function get_path_language(){
		return $this->path_language;
	}
	
	function get_model_ext(){
		return $this->model_ext;
	}
	
	function get_path_model(){
		return $this->path_model;
	}
	
	function get_controller_name(){
		return $this->controller;
	}
	
	function extract_words($string,$number_of_words,$overtext=''){
		$overtext = isset($overtext) && $overtext ? $overtext : '';
		$arr = array();
		$word = '';
		$i=0;
		while($i<=strlen($string) && $number_of_words>0){
			$letter = substr($string,$i,1);
			if(strlen(trim($letter))==0){
				$arr[] = $word;
				$number_of_words--;
				$word = '';
			}
			else{
				$word.=$letter;
			}
			$i++;
		}
		
		return count($arr) ? implode(' ',$arr).(count($arr)<$number_of_words ? '' : $overtext) : '';
	}
	
	function Location($where){
		header("Location: $where");
		exit();
	}
	
	function HumanizeDate($date,$fout=''){
		return date($fout,strtotime($date));
	}
	
	function debug($mixed){
		echo "<div>[ ".date('d/m/Y H:i:s')." ] - ".print_r($mixed,true)."</div>";
	}
}
