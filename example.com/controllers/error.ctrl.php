<?php

class error extends Eagle_controller{
	function error(){
		parent::__construct();
	}
	
	function error_start(){
		$this->load()->view("parts/header",array('title'=>"Mio progetto"))
						->view("parts/errore404")
					->view("parts/footer");
	}
}
