<?php
define('CSS_PATH','/media/css');
define('JS_PATH','/media/js');
define('LIBRARIES_DIR','../libraries');
include LIBRARIES_DIR."/eagle_form.php";

$form = new Eagle_form();
$form->setAction($_SERVER['PHP_SELF']);

$form->setCSSPath(CSS_PATH);
$form->setJSPath(JS_PATH);

$form->addCSSFile("form.css");
$form->addJSFile("wufoo.js");
$form->addJSFile("checkform.js");

$id = $form->addField('nome','text',true);
$form->addPropriety($id,'title','Nome');
$form->addPropriety($id,'maxlength','50');
$form->addPropriety($id,'value','ciccio pasticcio');

$id = $form->addField('descrizione','html',true);
$form->addPropriety($id,'title','Descrizione');
$form->addPropriety($id,'maxlength','250');
$form->addPropriety($id,'value','<h1>Il f&ugrave; siccome immobile dato il mortal sospiro</h1>');

if(!$form->isPosted()){ ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>senza nome</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.19.1" />
	<!--[if lt IE 10]>
	<script src=\"https://html5shiv.googlecode.com/svn/trunk/html5.js\"></script>
	<![endif]-->
</head>

<body><?php
	echo $form->GetForm();
	?>

</body>

</html><?php
}
else{
	var_dump($form->getDatas());
}
