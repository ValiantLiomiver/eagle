<?php
class Generic_controller extends Eagle_controller{
	public $load = null;
	function Generic_controller(){
		parent::__construct();
		$this->load = $this->load();
	}
	
	function init_module(){
		$this->start_session();
		if(!isset($this->session) || !is_array($this->session) || !$this->session){
			$this->session = array();
			$this->session['logged'] = false;
		}
		
		if(!$this->session['logged']){
			$this->destroy_session();
			header("Location: /login");
			exit();
		}
	}
}
