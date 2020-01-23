<pre>PROVA A OCCHIO</pre><?php
$dati = $this->get_datas();
?><br /><?php
if(isset($dati['nome']) && $dati['nome']){
	$this->html()
		->b()->add_text("Ciao, il tuo nome è ".ucwords(strtolower($dati['nome'])))->_b();
	if(isset($dati['anni']) && $dati['anni']){
		$this->html()->br()
		->b()->add_text("Hai ".$dati['anni']." anni")->_b();
	}
}
//var_dump($this);
