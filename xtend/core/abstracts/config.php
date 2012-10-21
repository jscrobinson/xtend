<?php

namespace xtend\core\abstracts;
use xtend\core;
use xtend\core\exceptions;

abstract class config implements core\interfaces\config {

	protected static $global_namespace = array();
	protected $options = array();
	protected $file = null;
	private $current_scope;
	
	final protected function __construct(array $options, $namespace, \xtend\core\classes\file $file) {
	
		if($namespace == "" || $namespace == NULL)
			throw new exceptions\invalidParameterException("The namespace parameter must be set");
		
		if(isset(self::$global_namespace[$namespace]))
			throw new \Exception("The configuration namespace already exists");
			
		$this->options = new core\classes\stringArrayNotation($options);
		self::$global_namespace[$namespace] = $this;
		$this->current_scope = $namespace;
		$this->file = $file;
	}
	
	final static function getNameSpaces() {
		return self::$global_namespaces;
	}
	
	final static function getConfigObject($name) {
		if(isset(self::$global_namespace[$name])) {
			return self::$global_namespace[$name];
		} else {
			throw new \Exception("The config object does not exist");
		}
	}
	
	final public function whoAmI() {
		return $this->current_scope;
	}
	
	
	final public function get($name) {
		$this->onGet($name);
		return $this->options->get($name);
	}
	
	final public function set($name, $value) {
		return $this->onSet($this->options->set($name,$value));
	}
	
	final public function delete($name) {
		return $this->onDelete($this->options->delete($name));
	}
	
}



?>