<?php
/*
 * EAGLE CONTROLLER
 * v 1.0
 * date: 03/04/2013
 * made by: Andrisani Umberto
 * copyright: Andrisani Umberto 2013
 * 
 * */

class Eagle_model extends Eagle_core{
	public $db = null;
	
	function __construct($label=null){
		parent::__construct();
		
		if(defined('DEBUG_LEVEL')){
			parent::set_debug_level(DEBUG_LEVEL);
		}
		
		$this->db = $this->db($label);
		if(!$this->db) die("Attenzione! Il database".(($label)?(" ".$label):(""))." non &egrave; stato caricato");
	}
	
	function __destruct(){
		
	}
}
