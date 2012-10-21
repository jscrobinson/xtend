<?php

namespace xtend\core\classes;
use xtend\core;

class file {

	private $writable = null;
	private $file = null;
	
	public function __construct($file) {
		if(!is_file($file)) {
			throw new \Exception("The following is not a file $file");
		}
		if(!is_readable($file)) {
			throw new \Exception("The file $file exists but cannot be read");
		}
		if(is_writable($file)) {
			$this->writable = true;
		} else {
			$this->writable = false;
		}
		$this->file = $file;
	}
	
	public function read() {
		return file_get_contents($this->file);
	}
	
	public function write($data = '') {
		if($this->writable) {
			return file_put_contents($this->file, $data, LOCK_EX);
		} else {
			throw new \Exception("The file $file cannot be written");
		}
	}
		
}

?>