<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_function' ) ) {
	class ni_function{
	
		function __construct() {
			
	    }
		/*Get Request*/
		public function get_request($name,$default = NULL,$set = false){
			if(isset($_REQUEST[$name])){
				$newRequest = $_REQUEST[$name];
			
			if(is_array($newRequest)){
				$newRequest = implode(",", $newRequest);
			}else{
				$newRequest = trim($newRequest);
			}
			
			if($set) $_REQUEST[$name] = $newRequest;
			
			return $newRequest;
				}else{
					if($set) 	$_REQUEST[$name] = $default;
				return $default;
			}
		}
		/*Print Data */
		function print_data($r)
		{
			echo '<pre>',print_r($r,1),'</pre>';	
		}
		/*Get Country Name*/
		function get_country_name($code)
		{ $name = "";
			if ($code){
				$name= WC()->countries->countries[ $code];	
				$name  = isset($name) ? $name : $code;
			
			}
			
			return $name;
		}
		function get_niprice($price = 0){
			$new_price = 0;
			if ($price){
				$new_price = wc_price($price );
			}else{
				$new_price = wc_price($price);
			}
			return $new_price;
		}
	}
	
}
?>