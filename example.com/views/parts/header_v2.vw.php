<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title><?=$title;?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Umberto Andrisani" /><?php
		if(isset($links) && is_array($links) && count($links)){
			foreach($links as $l){
				echo "\n\t".$l;
			}
			echo "\n";
		}
	?>
</head>

<body<?=((isset($style) && strlen($style)>0)?(" style='$style'"):(''));?>>
