<?php

namespace xtend\core\classes;

class router {

	private $routes;
	private static $instance;
	private $parent_class;
	private $parent_method;
	private $matches = array();
	private $tags = array();
	private $current_pattern = array("/");
	private $compiled_routes;
	private $route_errors = array();
	
	
	private function __construct($file) {
		$this->routes = simplexml_load_file($file);
		$this->buildRoutes($this->routes);
		echo var_dump($this->compiled_routes);
		//echo var_dump($this->route_errors);
		//echo var_dump($this->matches);
	}
	
	public static function router($file = null) {
		if(!self::$instance) {
			self::$instance = new router($file);
		}
		return self::$instance;
	}
	
	private function buildRoutes($route) {
	
		//Determines whether the slash needs to be readded at the end.
		$slash_toggle = false;
	
		if($route->getName() == "route") {
			
			//Route validation
			if(empty($route['id']))
				return $this->failRoute("The ID attribute is missing",$route);
		
			if(empty($route['pattern']) && empty($this->current_pattern))
				return $this->failRoute("The PATTERN attribute is missing",$route);
			
			if(empty($route['controller']) && empty($this->parent_class))
				return $this->failRoute("The CONTROLLER attribute is missing",$route);
				
			if(empty($route['method']) && empty($this->parent_method))
				return $this->failRoute("The METHOD attribute is missing",$route);
						
			if(!empty($route['pattern'])){
				
				if(preg_match('{^/.+}', $route['pattern']))
					return $this->failRoute("The PATTERN attribute must not start with a / ", $route);

				if(!preg_match("%/$%",$route['pattern'])) {
					$slash_toggle = true;
					$route['pattern'] = $route['pattern'] . '/';
				}
				
				$this->current_pattern[] = (string) $route['pattern'];

			}
				
			
			if(isset($route['method']))
				$this->parent_method =  (string) $route['method'];
				
			if(isset($route['controller']))
				$this->parent_class = (string) $route['controller'];
	
			//Compile a route if validation passed
			$this->compiled_routes[(string)$route['id']] = array( "pattern" => implode(null,$this->current_pattern),
											 			 		  "controller" => $this->parent_class,
											 			 		  "method" => $this->parent_method,
											 			 		  "matches" => $this->matches,
											 			 		  "regex" => implode(null,$this->current_pattern));
			
			//Now remove the slash again if needs be								 			 		  
			if($slash_toggle == true) {
				$this->compiled_routes[(string)$route['id']]['pattern'] = rtrim($this->compiled_routes[(string)$route['id']]['pattern'],'/');
			}
			
			//Tag section - works out tags from the pattern to determine how many matches there should be
			$current_tags = $this->tags;
			$tag_count = count($current_tags);
			$temp_tags = array();
			preg_match_all('%\{{1}([^{}]+)\}{1}%', (string) $route['pattern'], $tags);
			if(isset($tags[1])) {
				foreach($tags[1] as $key => &$value) {
					$temp_tags[$key+$tag_count] = $value;
						
				}
				$this->tags = array_merge($this->tags,array_flip($temp_tags));
			}
			
			//Recurse through the matches
			foreach($route->match as $match) {
				if($match->getName() == "match") {
					if(empty($match['name'])) {
						$this->failMatch("The NAME attribute must be set",$match);
						continue;
					}
					
					if(empty($match[0])) {
						$this->failMatch("The TEXT value must be set",$match);
						continue;
					}
					
					if(@preg_match("%".$match[0]."%", "") === false) {
						$this->failMatch("The REGEX is invalid",$match);
						continue;
					}
					//If the tag exists add it in to the matches
					if(isset($this->tags[(string)$match['name']])) {
						$position = $this->tags[(string) $match['name']];
						$this->compiled_routes[(string)$route['id']]['matches'][$position] = 
						$this->matches[$position] = array( "tag" => (string)$match['name'], "regex" => (string)$match[0]);
					}	
					
				}
								
			}
			
			//Re-order the keys to fill in any blanks in the matches so that they start at 0
			$this->compiled_routes[(string)$route['id']]['matches'] = array_values($this->compiled_routes[(string)$route['id']]['matches']);
			$this->matches = array_values($this->matches);
			
			
			//Save current patterns/matches here so after child recursion it has a copy before it was modified by the child
			$current_pattern = $this->current_pattern;
			$current_match = $this->matches;			
			$this->compiled_routes[(string)$route['id']]['regex'] = $this->createRegularExpression($this->compiled_routes[(string)$route['id']]['pattern'],$this->matches);
	
			//Recurse children
			foreach($route->route as $childRoute) {
				
				if($childRoute->getName() == "route") {
					$this->buildRoutes($childRoute);
				}

				//Reset tags/patterns/matches back to saved values so that inheritance doesnt break peers on the same level
				$this->tags = $current_tags;
				$this->matches = $current_match; 
				$this->current_pattern = $current_pattern;
					
				if(!empty($route['method']))
					$this->parent_method = (string) $route['method'];
			
				if(!empty($route['controller']))
					$this->parent_class = (string) $route['controller'];
			
			}
			
			
		}
			
		
	}
	
	private function failRoute($message,$node) {
		$this->route_errors[] = array("error" => $message,
									  "route" => $node->asXML());
		
	}
	
	private function failMatch($message,$node) {
		$this->route_errors[] = array("error" => $message,
									  "match" => $node->asXML());
		
	}
	
	private function createRegularExpression($pattern,$matches) {
		foreach($matches as $matchArray) {
			$pattern = preg_replace('%{'.$matchArray['tag'].'}%',"(".$matchArray['regex'].")", $pattern);
		}
		return $pattern;
	}
	
	public function route($url) {	
		foreach($this->compiled_routes as $id => $route) {
			if(preg_match('%^'.$route['regex'].'$%', $url, $matches)) {
				array_shift($matches);
				foreach($matches as $k => $v) {
					$matches[$route['matches'][$k]['tag']] = $v;
					unset($matches[$k]);
				}
				return array("route-id" => $id,
							"pattern" => $route['pattern'], 
							"controller" => $route['controller'],
							"method" => $route['method'],
							"matches" => $matches);
			}
			
		}
		
	}
	
}

?>