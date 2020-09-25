<?php
/*
 * EAGLE USER
 * v 1.0
 * date: 03/04/2013
 * made by: Andrisani Umberto
 * copyright: Andrisani Umberto 2013
 * 
 * */

class Eagle_User{
	private $name = '';
	private $post = array();
	private $get = array();
	private $session = array();
	private $server = array();
	
	function __construct($name=''){
		$this->name = $name;
		$this->post =& $_POST;
		$this->get =& $_GET;
		if(isset($_SESSION)) $this->session =& $_SESSION;
		if(isset($_SERVER)) $this->session = $_SERVER;
	}
	
	function __destruct(){
		
	}
}
