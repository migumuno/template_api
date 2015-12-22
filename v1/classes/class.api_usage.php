<?php 
	class api_usage {
		var $db = null;
		
		function api_usage($db) {
			$this->db = $db;
		}
		
		/**
		 * Obtiene la autorización para el uso de la api en funcion de la api key
		 * @param array $filters se recibe un array con 2 posiciones
		 * en la primera posición tenemos las keys generadas con anade_filtrado
		 * en la segunda posicion tenemos los values en un array 
		 * del tipo [':campo'] => 'valor'
		 * @param string $order
		 */
		function authorize($filters) {
			$ro = new Response();
			$ro->resultado = true;
			
			//preparo la query
			$filtros = prepare_filters($filters['keys']);
			$this->db->query("SELECT * FROM api_user ".$filtros." ;");
			$this->db->prebind($filters['values']);
			
			//la ejecuto
			$rows = $this->db->resultset();
			
			//proceso los datos
			if(!is_array($rows) || sizeof($rows) == 0) {
				$ro->resultado = false;
			}
			
			return $ro;
		}
		
	} // class
		
?>