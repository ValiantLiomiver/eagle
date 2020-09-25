<?php

class secondo extends Eagle_controller{
	function primo(){
		parent::__construct();
	}
	
	function secondo_start(){
		$dati = array();
		$dati['controller'] = $this->controller;
		$dati['valore'] = (isset($this->method) && $this->method=='b') ? true : false;
		$dati['metodo'] = isset($this->method) ? $this->method : null;

		$this->set_datas($dati);
		$this->load()->view("parts/header",array('title'=>'Mio progetto - secondo'))
						->view("secondo",$dati)
					->view("parts/footer");
	}
	
	function prova(){
		$dati = array();
		$dati['nome'] = isset($this->args[0]) ? $this->args[0] : null;
		$this->set_datas($dati);
		$this->load()->view("parts/header",array('title'=>'Mio progetto - secondo (prova)'))
						->view("aocchio")
					->view("parts/footer");
	}
	
	function secondo_error(){
		$this->load()->view("parts/header",array('title'=>'Mio progetto - Errore'))
						->view("parts/errore404")
					->view("parts/footer");
	}
}
