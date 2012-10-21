<?php

namespace xtend\core\interfaces;


interface config {
	
	public static function innit(\xtend\core\classes\file $file,$namespace);
	
	public function onSet($bool);
	
	public function onGet();
	
	public function onDelete($bool);
	
	
	 	
}
