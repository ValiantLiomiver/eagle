<?php
define('DS',DIRECTORY_SEPARATOR);
if(!defined('__DIR__')) define('__DIR__',DS.'var'.DS.'www'.DS.'prova.it'.DS.'html');
define('APP_DIR',__DIR__.DS.'..'.DS.'libraries');
define('PATH_CONTROLLER',__DIR__.DS."..".DS."controllers");
define('PATH_VIEW',__DIR__.DS."..".DS."views");
define('PATH_MODEL',__DIR__.DS."..".DS."models");
define('PATH_BUSINESS',__DIR__.DS."..".DS."business");
define('PATH_LANGUAGE',__DIR__.DS."..".DS."languages");

include APP_DIR.DS."eagle.php";
$rules = array();
$rules['ciccio(/)*'] = '/secondo';
$rules['ciccio(/prova)'] = '/secondo/$1';
$rules['ciccio(/prova)(/umberto)'] = '/secondo/$1/$2';
$rules['page/(\d+)'] = '/main/index/$1';
$rules['insert(/\d*)?'] = '/main/prova$1';
$rules['logout(/)*'] = '/login/deauth';

$eagle = new Eagle($rules,true,"error");
//$eagle->set_debug_level(5)->start();
