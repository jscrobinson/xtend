<?php

namespace xtend\core\classes\exceptions;
use xtend\core\abstracts as abstracts;

class invalidParameterException extends abstracts\exception {

	public function __construct($string = "",$code = 0,$previous = NULL) {
		
		parent::__construct($string,$code, $previous);
		
	}
	
}


?>