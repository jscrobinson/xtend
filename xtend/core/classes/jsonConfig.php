<?php

namespace xtend\core\classes;
use xtend\core;

class jsonConfig extends \xtend\core\abstracts\config {
	
	public static function innit(\xtend\core\classes\file $file,$namespace = 0) {
			$options = json_decode($file->read(), true);
			if($options === NULL) {
				throw new \Exception("Unable to decode Json config file - Is it malformed?");
			}
			return new jsonConfig($options, $namespace, $file); 
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
			if($this->file->write(core\classes\helpers\utility::formatJson(json_encode($this->options->getArray()))) === false)
				throw new \Exception("The config file failed to write, changes in memory have not been written");
	}
	
}