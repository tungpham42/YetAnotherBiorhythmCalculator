<?php
//Start the session
session_start();
//error_reporting(E_ALL | E_STRICT);
// Set UTF-8 encoding
mb_internal_encoding('UTF-8');
setlocale(LC_ALL, 'en_US.UTF-8');

// include autoload and some helper functions
include 'autoload.php';
include 'helpers.php';

// Get the request string
$r = str_replace(array(".html", ".php"), "", _v($_GET, 'r'));
// Split string into segments
$segment = !empty($r) ? explode("/", $r) : array();
// Get the controller, if request is empty then set default controller to "main"
$c = _v($segment, 0, "main");
// Initialize router class
$router = new Router(
	$routes = ConfigFactory::load("routes")->toArray(), // Config with routes
	ROOT.'controllers'.DS // Path to the controllers
);
// Get addition parameters
$params = array_shift($segment);

$amp = ConfigFactory::load("app")->rewrite ? "?" : "&";

// Set og meta properties in <head> section
HtmlHead::setOg(ConfigFactory::load("og")->toArray());

// Start buffer
ob_start();
// If the controller exists, then include it, otherwise drop 404
$controller = array_search($c, $routes);
if($file = $router->getController($c)) {
	include $file;
}
else {
	header("Status: 404 Not Found");
	$controller = '404';
	include $router->getController($controller);
}

// Set title
HtmlHead::setTitle(_v(ConfigFactory::load("seo")->$controller, 'title'));
// Set keywords
HtmlHead::setKeywords(_v(ConfigFactory::load("seo")->$controller, 'keywords'));
// Set description
HtmlHead::setDescription(_v(ConfigFactory::load("seo")->$controller, 'description'));

// get the buffer
$content = ob_get_contents();
// clean buffer
ob_end_clean();

// Output result to main template
include ROOT.'tmpl'.DS.'template.php';