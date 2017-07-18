<?php
class ConfigFactory {
	private static $config = array();

	public static function load($key) {
		if(!isset(self::$config[$key])) {
			$config = include ROOT."config".DS.$key.".php";
			self::$config[$key] = new Config($config);
		}
		return self::$config[$key];
	}
}

class Config implements Iterator {
	private $config = array();

	public function __construct(array $config) {
		$this -> config = $config;
	}

	public function first() {
		return current(array_slice($this->config, 0, 1));
	}

	public function toArray() {
		return $this->config;
	}

	public function __get($key) {
		return isset($this->config[$key]) ? $this->config[$key] : NULL;
	}

	public function rewind() {
		reset($this->config);
	}

	public function current() {
		return current($this->config);
	}

	public function key() {
		return key($this->config);
	}

	public function next() {
		return next($this->config);
	}

	public function valid() {
		$key = key($this->config);
		return ($key !== NULL && $key !== FALSE);
	}
}