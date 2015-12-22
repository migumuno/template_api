<?php 
	class User {
		var $db = null;
		
		function User($db) {
			$this->db = $db;
		}
		
		/**
		 * Obtiene todos los registros de la tabla usuario
		 * que cumplan los requisitos pasado por los filtros
		 * @param array $filters se recibe un array con 2 posiciones
		 * en la primera posición tenemos las keys generadas con anade_filtrado
		 * en la segunda posicion tenemos los values en un array 
		 * del tipo [':campo'] => 'valor'
		 * @param string $order
		 */
		function get_usuario($filters, $order="id_usuario asc") {
			$ro = new Response();
			$ro->resultado = true;
			
			//preparo la query
			$filtros = prepare_filters($filters['keys']);
			$this->db->query("SELECT * FROM usuarios ".$filtros." order by ".$order." ;");
			$this->db->prebind($filters['values']);
			
			//la ejecuto
			$rows = $this->db->resultset();
			
			//proceso los datos
			if(is_array($rows) and sizeof($rows) >0) {
				$ro->datos = $rows;
			}  else {
				$ro->resultado = false;
				$ro->mensaje = "Error: Se ha producido un error al obtener los registros";
			}
			
			return $ro;
		}
		
		/**
		 * Realiza un insert / update automáticamente
		 * en función de si le pasamos el id_usuario o no
		 * @param array $datos array completo con TODOS los campos
		 * que interactuan en la query
		 * del tipo [':campo'] => 'valor'
		 */
		function stor_direccion($datos) {  // recibe un array asociativo
			$ro = new Response();
			$ro->resultado = true;
			if (sizeof($datos) != null) { 
				if ($result = $this->db->stor($datos, "direcciones")){
					$ro->id = $this->db->lastInsertId();
				} else {
					$ro->resultado = false;
					$ro->mensaje = "Error al Insertar/Modificar.";
				}
			} else {
					$ro->resultado = false;
					$ro->mensaje = "Error al Insertar/Modificar. Se ha pasado un array nulo.";
			}
			return $ro;	
		}
		
		/**
		 * Realiza un insert / update automáticamente
		 * en función de si le pasamos el id_usuario o no
		 * @param array $datos array completo con TODOS los campos
		 * que interactuan en la query
		 * del tipo [':campo'] => 'valor'
		 */
		function stor_usuario($datos) {  // recibe un array asociativo
			$ro = new Response();
			$ro->resultado = true;
			if (sizeof($datos) != null) { 
				if ($result = $this->db->stor($datos, "usuarios")){
					$ro->id = $this->db->lastInsertId();
				} else {
					$ro->resultado = false;
					$ro->mensaje = "Error al Insertar/Modificar.";
				}
			} else {
					$ro->resultado = false;
					$ro->mensaje = "Error al Insertar/Modificar. Se ha pasado un array nulo.";
			}
			return $ro;	
		}
		
		/**
		 * Realiza un insert / update a pelo sin función stor en la tabla usuario
		 * en función de si le pasamos el id_usuario o no  hace update o no.
		 * @param array $datos array completo con TODOS los campos
		 * que tiene la tabla para insertar/actualizar 
		 * del tipo [':campo'] => 'valor'
		 */
		function update_usuario($datos) {
			$ro 			= new Response();
			$ro->resultado 	= true;
			
			if ($datos[':id_usuario'] != null) {
				//preparo la query
				$this->db->query("update usuario set id_usuario = :id_usuario, nombre = :nombre, apellido1 = :apellido1, apellido2 = :apellido2, sexo = :sexo  where id_usuario = :id_usuario ;");
				$this->db->prebind($datos);
				
				//la ejecuto
				if ($this->db->execute() == false) {//si va mal
					$ro->resultado = false;
					$ro->mensaje   = "No se ha podido actualizar la tabla usuario.";
				} else {//si va bien
					$ro->id = $datos[':id_usuario'];//devuelvo el id actualizado
				}
				
			} else {
				//preparo la query
				$this->db->query("insert into usuario (id_usuario, nombre, apellido1, apellido2, sexo) values (:id_usuario, :nombre, :apellido1, :apellido2, :sexo) ;");
				$this->db->prebind($datos);
				//la ejecuto
				if ($this->db->execute() == false) {//va mal
					$ro->resultado = false;
					$ro->mensaje   = "Error: No se ha podido insertar la tabla usuario.";
				} else {//si va bien
					$ro->id = $this->db->lastInsertId();//obtengo el ultimo id insertado
				}
			}
			return $ro;
		}
		
		/**
		 * Obtiene el total de los registros buscados de la tabla usuario
		 * que cumplan los requisitos pasado por los filtros
		 * @param array $filters se recibe un array con 2 posiciones
		 * en la primera posición tenemos las keys generadas con anade_filtrado
		 * en la segunda posicion tenemos los values en un array 
		 * del tipo [':campo'] => 'valor'
		 */
		function get_total_usuario($filters) {
			$ro 			= new Response();
			$ro->resultado 	= true;
			
			//preparo la query
			$filtros = prepare_filters($filters['keys']);
			$this->db->query("SELECT * FROM usuario ".$filtros." ;");
			$this->db->prebind($filters['values']);
			
			//la ejecuto
			$this->db->resultset();
			
			//obtengo los resultados
			$ro->datos 			= array();
			
			$ro->datos['total'] 	= $this->db->rowCount();
			return $ro;
		}
		
		/**
		 * Borra un registro de la tabla usuario, comprobando previamente su existencia
		 * @param array $id_usuario id del registro a borrar
		 */
		function delete_usuario($id_usuario){
			$ro = new Response();
			$ro->resultado = true;
 
			//busco el registro en la tabla
			$this->db->query("select * FROM usuario where id_usuario= :id_usuario ;");
			$this->db->bind(":id_usuario", $id_usuario);
			$arr_res = $this->db->single();
			
			//si lo encuentro
			if(sizeof($arr_res) > 0) {
				//preparo la query
				$this->db->query("delete from usuario where id_usuario= :id_usuario ;");
				$this->db->bind(":id_usuario", $id_usuario);
				//lo borro
				$this->db->execute() ;
				//devuelvo el id del registro borrado
				$ro->id = $id_usuario;
			} else {//si no lo encuento, peto
				$ro->resultado = false;
				$ro->id        = $id_usuario;
				$ro->mensaje = "Error: se ha producido un error al borrar el registro ".$id_usuario." de la tabla usuario";
			}
			
			return $ro;
		}
	} // class
		
?>