<?php
	function get_json($string){
		return json_encode(
				array(
						"status_code" 	=> 200
						,"message" 		=> $string
					));
	}
	
	function get_json_data($data){
		return json_encode(
				array(
						"status_code" 	=> 200
						,"data" 		=> $data
					));
	}

	function get_error($code){
		$errors = array(
			 "1" => array(
						"status_code" 	=> 403
						,"message" 		=> "No tienes permiso para usar la api."
						,"error_code"	=> 1991
					)
		);	
		
		return json_encode($errors[$code]);
	}
?>