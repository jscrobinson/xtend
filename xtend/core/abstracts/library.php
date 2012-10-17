<?php

/**
 * Abstract xtend_library class.
 * 
 * @abstract
 */
 
namespace xtend\core\abstracts; 
use xtend\core\classes\exceptions;
 
abstract class library {

	private static $global_library = array();
	//private $local_library = array();
	
	private function classLoaded($name) {
		
		if(isset(self::$global_library[$name])) {
			
			return true;
			
		}
		
		return false;
		
	}
	
	
	public function loadClass($class,$name = null) {
		
		if(is_object($class)) {
			
			if($name == null) {
				
				$name = array_pop(explode('\\',get_class($name)));
				
			}
			
			
			if(!$this->classLoaded($name)) {
				
				self::$global_library[$name] = $class;
				
			} else {
				
				trigger_error(get_class($this)."::loadClass() has the ".$name. " class already loaded" ,E_USER_WARNING);
				
			}
			
			
			
		} else {
			
			throw new exceptions\invalidParameterException("The class needs an object");
			
		}
		
		
	}
			
	public function __get($class) {
		
		if(isset(self::$global_library[$class])) {
			
			return self::$global_library[$class]; 
			
		}
		
		
	}
	
	public function __set($class,$value) {
	
		if(isset(self::$global_library[$class])) {
			
			self::$global_library[$class]->$value; 
			
		}
	
	
	}
	
	public function dumpClasses() {
		
		echo print_r(self::$global_library);
		
	}
		
			
}

?>