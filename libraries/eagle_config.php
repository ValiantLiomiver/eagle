<?php
/*
 * EAGLE CONFIG
 * v 1.0
 * date: 02/04/2013
 * made by: Andrisani Umberto
 * copyright: Andrisani Umberto 2013
 * 
 * */
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
define('EAGLE_VERSION','1.0'); //my eagle version
define('REQUEST_METHOD','REQUEST_URI'); //method of $_SERVER
define('BASE_URL','/'); //method of $_SERVER
define('STRING_CASE',1); /*
						 * 1 => CamelCase
						 * 2 => UPPERCASE
						 * 3 => LOWERCASE
						 */
define('CONTROLLER_EXT','ctrl.php');
define('VIEW_EXT','vw.php');
define('MODEL_EXT','mdl.php');
define('BUSINESS_EXT','bus.php');
define('LANGUAGE_EXT','lng.php');
define('MAIN_CONTROLLER','main');
define('INCLUDE_DIR',"..".DS."libraries");
define('SYS_DIR',"..");

$dbconfig = array();
$dbconfig['dbtype'] = 'pgsql';
$dbconfig['dbname'] = 'capstreet';
$dbconfig['dbhostname'] = 'localhost';
$dbconfig['dbuser'] = 'capstreet';
$dbconfig['dbpassword'] = 'l3P05t3';

$GLOBALS['dbconfig'] = $dbconfig;
