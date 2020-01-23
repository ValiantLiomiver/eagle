<?php
class alfonso extends Eagle_controller{
	private $numero = 8;
	function alfonso(){
		parent::__construct();
	}
	function alfonso_start(){
		if($this->numero==10) $this->SayHello("Umberto");
	}
	private function SayHello($name){
		echo "Ciao ".$name;
	}
}
