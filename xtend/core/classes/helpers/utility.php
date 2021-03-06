<?php

namespace xtend\core\classes\helpers;

class utility {
		
	/**
	 * superImplode function.
	 * 
	 * @description will go through a key hash and create a super glue. 
	 * @access public
	 * @return (array) $imploded
	 */

	public static function superImplode($glue1,$glue2,$array) {
		$working_array = array();
		foreach($array as $key => $value) {
			$working_array[] = implode($glue1, array($key,(string)$value));	
		}
		
		return implode($glue2, $new_array);
	
	}
	
	/**
	 * formatJson function.
	 *
	 * @description will lint a Json Array
	 * @access public
	 * @return (string) Json
	 */
	
    public static function formatJson($json) {
	    $result      = '';
	    $pos         = 0;
	    $strLen      = strlen($json);
	    $indentStr   = '  ';
	    $newLine     = "\n";
	    $prevChar    = '';
	    $outOfQuotes = true;
	
	    for ($i=0; $i<=$strLen; $i++) {
	        // Grab the next character in the string.
	        $char = substr($json, $i, 1);
	
	        // Are we inside a quoted string?
	        if ($char == '"' && $prevChar != '\\') {
	            $outOfQuotes = !$outOfQuotes;
	        
	        // If this character is the end of an element, 
	        // output a new line and indent the next line.
	        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
	            $result .= $newLine;
	            $pos --;
	            for ($j=0; $j<$pos; $j++) {
	                $result .= $indentStr;
	            }
	        }
	        
	        // Add the character to the result string.
	        $result .= $char;
	        // If the last character was the beginning of an element, 
	        // output a new line and indent the next line.
	        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
	            $result .= $newLine;
	            if ($char == '{' || $char == '[') {
	                $pos ++;
	            }
	            for ($j = 0; $j < $pos; $j++) {
	                $result .= $indentStr;
	            }
	        }
	        
	        $prevChar = $char;
	    }
	
	    return $result;
	    
    }
}


?>