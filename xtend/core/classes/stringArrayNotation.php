<?php

namespace xtend\core\classes;
use xtend\core;
use xtend\core\classes\exceptions;


class stringArrayNotation {
	
	private $arr;
	
	public function __construct(array &$arr) {
		$this->arr =& $arr;
	}
	
	public static function request($string) {
		if(preg_match('/^((\W|_)+.*|.*(\W|_)+)$/',$string)) {
			throw new exceptions\invalidParameterException("The string cannot start or end with non alpha numeric characters");
		}	
		return explode('.', $string);
	}
	
	public static function &searchArray($string,array &$arr) {
		$request_array = self::request($string);
		$request_last = end($request_array);
		reset($request_array);
		$my_null = new core\classes\unassigned();
		$pointer =& $arr;
		
		foreach($request_array as $request) {
			if(isset($pointer[$request])) {
				if($request !== $request_last && is_array($pointer[$request])) {
					$pointer =& $arr[$request];
				} elseif($request == $request_last) {
					return $pointer[$request];
				}
			} else {
				return $my_null;
			}
		}		
	}
	
	public function get($name) {
		$get = self::searchArray($name,$this->arr);
		if(!$get instanceof unassigned){
			return $get;
		} else {
			throw new \Exception("The element you are getting does not exist");
		}
	}
	
	public function set($name,$value) {
		$request = self::request($name);
		$last_request = end($request);
		reset($request);
		$myArr =& $this->arr;
		
		foreach($request as $key) {	
			if($key !== $last_request) {
				if(!isset($myArr[$key])) {
					$myArr[$key] = null;
					$myArr =& $myArr[$key];
				} else {
					if(!is_array($myArr[$key]))
						$myArr[$key] = array();
					$myArr =& $myArr[$key];
				}
			} else {
				$myArr[$key] = $value;
				return true;
			}
		}
		return false;
	}
	
	public function delete($name) {
		$search = self::request($name);
		$search_count = count($search);
		$remove_key = array_pop($search);
		
		if(!self::searchArray($name,$this->arr) instanceof unassigned){
			if($search_count > 1) {
				$search = implode('.',$search);
				$remove =& self::searchArray($search,$this->arr);
				unset($remove[$remove_key]);
			} else {
				unset($this->arr[$remove_key]);
			}
			return true;
		} 
		return false; 
	}
	
	public function getArray() {
		return $this->arr;
	}
}



?>