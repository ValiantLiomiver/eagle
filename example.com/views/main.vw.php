<pre>PROVA DEL MAIN CONTROLLER</pre><br /><?php
if(isset($content) && $content){
	?>Il contenuto &egrave; il seguente <b><?=ucwords(strtolower($content));?></b><?php
}
$this->html()->set_type('html')
	->table(array('border'=>0,'cellpadding'=>5,'cellspacing'=>1))
		->tr()
			->td()->b()->add_text("id")->_b()->_td()
			->td()->b()->add_text("idstrada")->_b()->_td()
			->td()->b()->add_text("idsestiere")->_b()->_td()
			->td()->b()->add_text("dug")->_b()->_td()
			->td()->b()->add_text("dugcompl")->_b()->_td()
			->td()->b()->add_text("denominazione")->_b()->_td()
		->_tr();

foreach($rows as $row){
	if(isset($is_object_indirizzo) && $is_object_indirizzo){
		$this->html()->tr()
			->td()->add_text($row->getId())->_td()
			->td()->add_text($row->getIdStrada())->_td()
			->td()->add_text($row->getIdSestiere())->_td()
			->td()->add_text($row->getDug())->_td()
			->td()->add_text($row->getDugCompl())->_td()
			->td()->add_text($row->getDenominazione())->_td()
		->_tr();
	}
	else{
		$this->html()->tr()
			->td()->add_text($row['id'])->_td()
			->td()->add_text($row['idstrada'])->_td()
			->td()->add_text($row['idsestiere'])->_td()
			->td()->add_text($row['dug'])->_td()
			->td()->add_text($row['dugcompl'])->_td()
			->td()->add_text($row['denominazione'])->_td()
		->_tr();
	}
}

$this->html->_table()
			->br();
if($page>1) $this->html->a(array('href'=>'/page/'.($page-1)))->add_text("&lt;")->_a()->add_text("&nbsp;");
$this->html->add_text("Pagina $page di $pages");
if($page<$pages) $this->html->add_text("&nbsp;")->a(array('href'=>'/page/'.($page+1)))->add_text("&gt;")->_a();


$this->load->view("aocchio");
