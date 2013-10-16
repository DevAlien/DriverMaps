<?php
use libs\Session;
use libs\Config;
use libs\Language;
use libs\System;
use libs\Router;
use libs\Spyc;
use libs\Template;
use libs\tools\StringTools;
	
 function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    function endsWith($haystack, $needle) {
        $length = strlen($needle);
        $start = $length * -1; //negative
        return (substr($haystack, $start) === $needle);
    }

function __autoload($class) {  
	if(startsWith($class, 'app') || startsWith($class, 'libs')){
    	$class = '' . str_replace('\\', '/', $class) . '.php';  
    	require_once($class);  
    }
	
}  
Config::init();
Session::getInstance();

Language::getInstance();
$system = System::getInstance();
$toolbox = RedBean_Setup::kickstart(Config::get('dbtype') . ':host=' . Config::get('dbhost') . ';dbname=' . Config::get('dbname'),Config::get('dbusername'),Config::get('dbpassword'), false);
$system->setToolbox($toolbox);
$router = Router::getInstance();
$routings = Spyc::YAMLLoad('app/config/' . Config::get('routing') . '.routing.yaml');
$router->setRoutings($routings);
$router->init();

$tpl = new Template('personal');
?>