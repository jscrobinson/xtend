<?php

namespace xtend\entry;

class bootstrap {
	
	public static function main() {
		
		//Set Paths
		define('APP_PATH', ROOT .'/app');
		define('VENDOR_PATH', ROOT .'/vendors');
		define('PUBLIC_PATH', ROOT .'/public');
		define('XTEND_PATH', ROOT .'/xtend');
		
		//Set debug path and configuration folder.
		$debug_level = (defined('ENVIRONMENT')? ENVIRONMENT : 'production');
		define("CONFIG_PATH", XTEND_PATH . '/config/' . $debug_level);
		
		/* Set Autoload to load Namespaces like directory **/
		spl_autoload_register(function ($classname) { 
			$class_parts = explode('\\', $classname);
			$classname = implode('/', $class_parts);
			$class_path = ROOT . '/' . $classname . '.php';
			
			if(is_file($class_path)) {
				require($class_path);
			}
			
		});
		
		try{
			$site_configuration = \xtend\core\classes\jsonConfig::innit(new \xtend\core\classes\file(CONFIG_PATH . "/main.json"), 'core');
		} catch (Exception $e) {
	
			//Implement a error template class later on.
			echo $e->getMessage();
	
		}
		
		$router = \xtend\core\classes\router::router(APP_PATH . '/routes.xml');
		echo var_dump($router->route('/admin/en/de/action/'));
		
	}
	
}

?>