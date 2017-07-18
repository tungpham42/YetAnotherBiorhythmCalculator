<?php
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__FILE__).DS);

spl_autoload_register('autoload');

function autoload($className)
{
	$file = ROOT.DS.'class'.DS.'class.'.strtolower($className).'.php';
	if(file_exists($file))
		include $file;
}