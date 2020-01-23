<?php

class main_db extends Eagle_model{
	function main_db(){
		parent::__construct();
	}
	
	function get_stradaalias($page=1){
		$count = $this->db->count("stradaalias","id");
		
		$limit = 10;
		$pages = ($count-($count%$limit))/$limit;
		if(($count%$limit)>0) $pages++;
		$page = isset($page) && $page>=1 ? ($page<=$pages ? intval($page) : $pages) : 1;
		
		$offset = (($page-1)*$limit);
		$rs = $this->db->get("stradaalias",'id',$limit,$offset);
		$is_object_indirizzo = false;
		
		$this->load()->business("indirizzo");
		if(class_exists('Indirizzo')) $is_object_indirizzo = true;
		$rows = array();
		while($row = $this->db->Fetch($rs)){
			if(class_exists('Indirizzo')){
				$app = new Indirizzo();
				$app->setId($row['id']);
				$app->setIdStrada($row['idstrada']);
				$app->setIdSestiere($row['idsestiere']);
				$app->setDug($row['dug']);
				$app->setDugCompl($row['dugcompl']);
				$app->setDenominazione($row['denominazione']);
				$rows[] = $app;
				$app = null;
				unset($app);
			}
			else{
				$rows[] = $row;
			}
		}
		return array($page,$pages,$rows,$is_object_indirizzo);
	}
	
	function insert($number){
		$dati = array(
			'number'=>$number
		);
		$this->db->Insert("ciccio",$dati);
	}
}
