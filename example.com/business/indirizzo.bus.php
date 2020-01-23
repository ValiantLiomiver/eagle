<?php

class Indirizzo extends Eagle_Object{
	private $id;
	private $idstrada;
	private $idsestiere;
	private $dug;
	private $dugcompl;
	private $denominazione;
	
	function __construct(){
		parent::__construct();
	}
	
	function __destruct(){
		
	}
	
	public function setId($id){
		if(intval($id) && intval($id)>0) $this->id = $id;
		return $this;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setIdStrada($idstrada){
		if(intval($idstrada) && intval($idstrada)>0) $this->idstrada = $idstrada;
		return $this;
	}
	
	public function getIdStrada(){
		return $this->idstrada;
	}
	
	public function setIdSestiere($idsestiere){
		if(intval($idsestiere) && intval($idsestiere)>0) $this->idsestiere = $idsestiere;
		return $this;
	}
	
	public function getIdSestiere(){
		return $this->idsestiere;
	}
	
	public function setDug($dug){
		if("$dug") $this->dug = $dug;
		return $this;
	}
	
	public function getDug(){
		return $this->dug;
	}
	
	public function setDugCompl($dugcompl){
		if("$dugcompl") $this->dugcompl = $dugcompl;
		return $this;
	}
	
	public function getDugCompl(){
		return $this->dugcompl;
	}
	
	public function setDenominazione($denominazione){
		if("$denominazione") $this->denominazione = $denominazione;
		return $this;
	}
	
	public function getDenominazione(){
		return $this->denominazione;
	}
}
