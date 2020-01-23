<?php

class login extends Eagle_controller{
	private $utenze = array(
							'ValiantLiomiver'=>'201423'
							);
	
	function login(){
		parent::__construct();
		$this->load = $this->load();
		$this->start_session();
		if(!isset($this->session) || !is_array($this->session) || !$this->session){
			$this->session = array();
			$this->session['logged'] = false;
		}
	}
	
	function login_start(){
		if($this->session['logged']){
			header("Location: /");
			exit();
		}
		$this->load->view("parts/header",array('title'=>"Mio progetto - login"))
			->view("login")
		->view("parts/footer");
	}
	
	function auth(){
		$username = '';
		$password = '';
		$this->set_type_post_var('username','string',$username);
		$this->set_type_post_var('password','string',$password);
		if(array_key_exists($username,$this->utenze) && $this->utenze[$username]==$password){
			$this->session['logged'] = true;
			header("Location: /");
			exit();
		}
		else{
			header("Location: /login");
			exit();
		}
	}
	
	function deauth(){
		$this->session['logged'] = false;
		header("Location: /login");
		exit();
	}
}
