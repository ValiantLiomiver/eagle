<?php
class Umberto extends Eagle_controller{
	public $nome = 'Umberto';
	public $eta = 23;
	public $compleanno = '1989-08-14';
	private $dateseparator = '/';
	function Umberto(){
		parent::__construct();
	}
	function Umberto_start(){
		echo "Ciao!<br />Il mio nome &egrave; ".$this->nome." e ho ".$this->eta."<br />Sono nato il ".$this->dateconverter($this->compleanno);
	}
	private function dateconverter($data){
		list($aaaa,$mm,$gg) = explode('-',$data);
		return $gg.$this->dateseparator.$mm.$this->dateseparator.$aaaa;
	}
}
