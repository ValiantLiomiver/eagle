<?php

include "../libraries/eagle_xhtml.php";
include "../libraries/eagle_db.php";

$html = new Eagle_Xhthml(false);
$html->set_type('html')
	->table(array('border'=>0,'cellpadding'=>5,'cellspacing'=>1))
		->tr()
			->td()
				->b()->add_text("id")->_b()
			->_td()
			->td()
				->b()->add_text("idstrada")->_b()
			->_td()
			->td()
				->b()->add_text("idsestiere")->_b()
			->_td()
			->td()
				->b()->add_text("dug")->_b()
			->_td()
			->td()
				->b()->add_text("dugcompl")->_b()
			->_td()
			->td()
				->b()->add_text("denominazione")->_b()
			->_td()
		->_tr();

$db = new Eagle_db('pgsql:dbname=capstreet;host=localhost;user=capstreet;password=l3P05t3');
$rs = $db->select("*")
		->from("stradaalias")
		->where("id>=1")
		->or_where("id<=100")
	->order_by("id")
	->limit(10)
	->Execute();
//$rs = $db->Exec("SELECT * FROM stradaalias LIMIT 10");
while($row = $db->Fetch($rs)){
	$html->tr()
		->td()->add_text($row['id'])->_td()
		->td()->add_text($row['idstrada'])->_td()
		->td()->add_text($row['idsestiere'])->_td()
		->td()->add_text($row['dug'])->_td()
		->td()->add_text($row['dugcompl'])->_td()
		->td()->add_text($row['denominazione'])->_td()
	->_tr();
}

$html->_table();
