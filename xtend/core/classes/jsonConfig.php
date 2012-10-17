<?php

namespace xtend\core\classes;
use xtend\core;

class jsonConfig extends core\abstracts\config {
	
	public static function innit($path = NULL, $namespace = 0) {
		if($json = @file_get_contents($path)) {
			if(!is_writable($path)) {
				throw new \Exception("The config file is unwritable at $path");
			} else {
				$resources['json_config'] = $path;
			}
			$options = json_decode($json, true);
			if($options === NULL) {
				throw new \Exception("Unable to decode Json config file - Is it malformed?");
			}
			
			return new jsonConfig($options, $namespace, $resources); 
			
		} else {
			throw new exceptions\invalidParameterException("Json file could not be read");
		}
	}
	
	public function onGet() {
		//Silence is golden	
	}
	
	public function onDelete($b) {	
		$this->rewriteConfig($b);
	}
	
	public function onSet($b) {
		$this->rewriteConfig($b);
	}
	
	private function rewriteConfig($b) {
	 	if($b)	
			if(file_put_contents($this->resources['json_config'],core\classes\helpers\utility::formatJson(json_encode($this->options->getArray())), LOCK_EX) === false)
				throw new \Exception("The config file failed to write, changes in memory have not been written");
	}
	
}