<?php

class primo extends Eagle_controller{
	function primo(){
		parent::__construct();
	}
	
	function primo_start(){
		$dati = array();
		$dati['controller'] = $this->controller;
		$dati['valore'] = (isset($this->method) && $this->method=='b') ? true : false;
		$dati['metodo'] = isset($this->method) ? $this->method : null;

		$this->set_datas($dati);
		$this->load()->view("parts/header",array('title'=>'Mio progetto - primo'))
						->view("primo")
					->view("parts/footer");
	}
	
	function prova(){
		$dati=array();
		$this->set_type_arguments(0,'string',$dati['nome']);
		$this->set_type_arguments(1,'integer',$dati['anni']);
		if($dati['nome']){
			$this->set_datas($dati);
		}
		$this->load()->view("parts/header",array('title'=>'Mio progetto - a occhio'))
						->view("aocchio")
					->view("parts/footer");
	}
	
	function example(){
		$this->set_type_arguments(0,'int');
		$this->set_type_arguments(1,'double');
		$this->set_type_arguments(2,'string');
		var_dump($this->args);
	}
}
