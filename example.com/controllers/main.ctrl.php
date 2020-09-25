<?php
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
include APP_DIR.DS."Generic_controller.php";

class main extends Generic_controller{
	private $model = null;
	function main(){
		parent::__construct();
		$this->model = $this->load->model("main_db");
		$this->init_module();
	}
	
	function index(){
		$this->set_type_arguments(0,'integer',$page);
		
		list($page,$pages,$rows,$is_object_indirizzo) = $this->model->get_stradaalias($page);
		
		$this->load->view("parts/header",array('title'=>"Mio progetto"));
		if(count($rows)){
			$this->load->view("main",array('page'=>$page,'pages'=>$pages,'rows'=>$rows,'is_object_indirizzo'=>$is_object_indirizzo));
		}
		$this->load->view("parts/footer");
	}
	
	function prova(){
		$this->set_type_arguments(0,'integer',$number);
		var_dump($number);
		$this->model->insert($number);
	}
}
