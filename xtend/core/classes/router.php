<?php

namespace xtend\core\classes;

class router {

	private $routes;
	private static $instance;
	
	private function __construct($file) {
		$this->routes = simplexml_load_file($file);
		$this->recurseRoutes($this->routes);
	}
	
	public static function router($file = null) {
		if(!self::$instance) {
			self::$instance = new router($file);
		}
		return self::$instance;
	}
	
	private function recurseRoutes($route) {
		if($route->count() >= 1) {
			
		}
		
	}
	
}

?>