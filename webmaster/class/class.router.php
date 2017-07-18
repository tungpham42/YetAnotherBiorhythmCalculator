<?php
class Router {
	private $routes = array();
	private $c_path;

	public function __construct(array $routes, $c_path) {
		$this -> routes = $routes;
		$this -> c_path = $c_path;
	}

	public function getController($c) {
		$c = array_search($c, $this->routes);
		$file = $this->c_path.$c.'.php';
		return (isset($this->routes[$c]) AND file_exists($this->c_path.$c.'.php')) ? $file : false;
	}
}