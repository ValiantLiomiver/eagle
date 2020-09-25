<?php
include "../libraries/eagle.php";

$rules = array();
$rules['primo(\/[a-z]){0,1}'] = '/primo/$1';

$eagle = new Eagle($rules);
$eagle->set_debug_level(3)->start();
//$eagle->start();
