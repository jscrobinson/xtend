<?php

/**
 * Abstract xtend\registry class.
 * 
 * @abstract
 */
 
namespace xtend\core\classes; 
use xtend\core\classes\exceptions;
 
class registry {

	private static $global_registry = array();
	
	private function __construct() {}
	
	public static function add($key, $store) {
		if(!isset(self::$global_registry[$key])) {
			self::$global_registry[$key] =& $store;
		} else {
			throw new exceptions\invalidParameterException("Failed to add the registry. The key $key already exists.");
		}
	}
	
	public static function remove($key) {
		if(isset(self::$global_registry[$key])) {
			unset(self::$global_registry[$key]);
			return true;
		} else {
			throw new exceptions\invalidParameterException("Cannot remove key $key does not exist in the registry");
		}
	}
	
	public static function &get($key) {
		if(isset(self::$global_registry[$key])) {
			return self::$global_registry[$key];
		} else {
			throw new exceptions\invalidParameterException("Cannot get key $key does not exist in the registry");
		}
	}
			
}

?>