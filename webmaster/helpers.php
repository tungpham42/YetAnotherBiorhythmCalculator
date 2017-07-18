<?php
function _v($a, $k, $d=null)
{
	return isset($a[$k]) ? $a[$k] : $d;
}

function base_url()
{
	static $base;
	if(!empty($base)) return $base;
	$protocol = $_SERVER['SERVER_PORT'] != 443 ? "http" : "https";
	$currentPath = $_SERVER['PHP_SELF'];
	$pathInfo = pathinfo($currentPath);
	$hostName = $_SERVER['HTTP_HOST'];
	$base = rtrim($protocol . "://" . $hostName . $pathInfo['dirname'], "/") . '/';
  return $base;
}

function create_url(array $params, $suffix = 'html')
{
	$r = implode("/", array_map("urlencode", $params)).(($suffix and ConfigFactory::load("app")->rewrite) ? '.'.$suffix : null);
	if(!ConfigFactory::load("app")->rewrite)
		$r = '?r='.$r;
	return base_url().$r;
}


function redirect($url, $suffix = true)
{
	$url = is_array($url) ? create_url($url, $suffix) : $url;
	header("Location: {$url}");
	exit(0);
}

function dump()
{
	$args = func_get_args();
	echo '<pre>';
	foreach($args as $arg)
		var_dump($arg);
	echo '</pre>';
}

function checkDomain($domain)
{
	return preg_match("#^[a-z\d-]{1,62}\.[a-z\d-]{1,62}(.[a-z\d-]{1,62})*$#i", $domain) ? true : false;
}

function isAjaxRequest()
{
	return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ? true : false;
}

function highLightMenuItem($controller, $item)
{
	return $controller == $item ? "active" : NULL;
}