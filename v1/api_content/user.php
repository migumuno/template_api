<?php
	function updateUser() {
		require_once 'classes/Bd/class.user.php';
		global $db, $app;
		$User = new User($db);
		
		$request = \Slim\Slim::getInstance()->request();
	    $user_data = json_decode($request->getBody(), true);
	    
	    //1ª compruebo que el email enviado no está registrado
	    $filters = array();
	    add_filter($filters, "email", $user_data[':email']);
	    $response = $User->get_usuario($filters);
	    
	    $datos_direccion = array();
	    
	    //si existe el mail
	    if($response->resultado){
	    	//si el estado es 3, reactivamos el usuario
	    	if($response->datos['0']['cd_estado_usuario'] == 3){
	    		
	    		//preparo los datos
	    		$datos_direccion[':id_direccion'] 	= $response->datos['0']['id_direccion']; //direccion existente
	    		$user_data[':id_usuario']			= $response->datos[0]['id_usuario'];
	    		$user_data[':cd_estado_usuario']	= 2;	
	    		$user_data[':fh_baja']				= null;
	    		$user_data[':fh_valida']			= null;
	    		
	    	}else if($response->datos['0']['cd_estado_usuario'] == 2){//pendiente de optin
	    		//preparo los datos
	    		$cuerpo_email 						= file_get_contents("common/mailing/confirm_user.html");
	    		$cuerpo_email 						= str_replace("[URL_CONFIRMACION]", URL_CONFIRMACION."?cod=".$user_data[':cd_valida']."&amp;cd_origen=1", $cuerpo_email);
	    		
	    		//reenvío el mail
	    		enviar_email($user_data[':email'], "¡Estás a un paso de entrar en Mundo Facundo!", $cuerpo_email);
	    		
	    		$app->halt('200', get_error(5)); // finalizo con error
	    		
	    	}else{//si no está en estado 3, el usuario existe
	    		$app->halt('200', get_error(2)); // finalizo con error
	    	}
	    }
	    
	    //insertamos / actualizamos dirección
	    $datos_direccion[':cp'] 		= $user_data[':cp'];
	    $response_direccion  			= $User->stor_direccion($datos_direccion);
	    
	    //si va todo correcto
	    if($response_direccion->resultado){
	    	//genero optin
	    	$user_data[':cd_valida']			= generate_optin();
	    	$user_data[':id_usuario_perfil']	= ($user_data[':id_usuario_perfil'])?2:1;
	    	$user_data[':ib_recibir_boletines']	= intval($user_data[':ib_recibir_boletines']);
	    	$user_data[':cd_estado_usuario']	= 2;
			unset($user_data[':cp']);
	    	
	    	//inserto el usuario
	    	$response_usuario					= $User->stor_usuario($user_data);
	    	
	    	//si se inserta correctamente el usuario
	    	if($response_usuario->resultado){
	    		//preparo los datos
	    		$cuerpo_email 						= file_get_contents("common/mailing/confirm_user.html");
	    		$cuerpo_email 						= str_replace("[URL_CONFIRMACION]", URL_CONFIRMACION."?cod=".$user_data[':cd_valida']."&amp;cd_origen=1", $cuerpo_email);
	    		
	    		//reenvío el mail
	    		enviar_email($user_data[':email'], "¡Estás a un paso de entrar en Mundo Facundo!", $cuerpo_email);
	    		
	    		$app->halt('200', get_json("Tu cuenta se ha creado correctamente, revisa tu correo!")); // finalizo con éxito
	    	}else{
	    		$app->halt('200', get_error(4)); // finalizo con error 
	    	}
	    }else{
	    	$app->halt('200', get_error(3)); // finalizo con error
	    }
	}
	
	function login() {	    
	    require_once 'classes/Bd/class.user.php';
		global $db, $app;
		$User = new User($db);
		
		$request = \Slim\Slim::getInstance()->request();
	    $user_data = json_decode($request->getBody(), true);
	    
	    //1ª compruebo que el usuario está registrado
	    $filters = array();
	    add_filter($filters, "email", $user_data['correo']);
	    add_filter($filters, "pass", $user_data['password']);
	    $response = $User->get_usuario($filters);
		if($response->resultado){
			$usuario = $response->datos['0'];
	    	//si el estado es 3, está dado de baja
	    	if($usuario['cd_estado_usuario'] == 3){
	    		//se supone que no existe el usuario
	    		$app->halt('200', get_error(6));
	    	}else if($usuario['cd_estado_usuario'] == 2){//pendiente de optin
	    		//preparo los datos
	    		$cuerpo_email 						= file_get_contents("common/mailing/confirm_user.html");
	    		$cuerpo_email 						= str_replace("[URL_CONFIRMACION]", URL_CONFIRMACION."?cod=".$usuario['cd_valida']."&amp;cd_origen=1", $cuerpo_email);
	    		
	    		//reenvío el mail
	    		enviar_email($user_data['correo'], "¡Estás a un paso de entrar en Mundo Facundo!", $cuerpo_email);
	    		
	    		$app->halt('200', get_error(5)); // finalizo con error
	    		
	    	}else{//si no está en estado 3, ni en estado 2, nos logamos
	    		$app->halt('200', get_json("ok")); // finalizo con error
	    	}
	    }else{
	    	$app->halt('200', get_error(6));
	    }
	}
	
	function rememberPass(){
		require_once 'classes/Bd/class.user.php';
		global $db, $app;
		$User = new User($db);
		
		$request = \Slim\Slim::getInstance()->request();
	    $user_data = json_decode($request->getBody(), true);
	    
	    //1ª compruebo que el usuario está registrado
	    $filters = array();
	    add_filter($filters, "email", $user_data['correo']);
	    $response = $User->get_usuario($filters);
		if($response->resultado){
			$usuario = $response->datos['0'];
	    	//si el estado es 3, está dado de baja
	    	if($usuario['cd_estado_usuario'] == 3){
	    		//se supone que no existe el usuario
	    		$app->halt('200', get_error(6));
	    	}else if($usuario['cd_estado_usuario'] == 2){//pendiente de optin
	    		//preparo los datos
	    		$cuerpo_email 						= file_get_contents("common/mailing/confirm_user.html");
	    		$cuerpo_email 						= str_replace("[URL_CONFIRMACION]", URL_CONFIRMACION."?cod=".$usuario['cd_valida']."&amp;cd_origen=1", $cuerpo_email);
	    		
	    		//reenvío el mail
	    		enviar_email($usuario['email'], "¡Estás a un paso de entrar en Mundo Facundo!", $cuerpo_email);
	    		
	    		$app->halt('200', get_error(5)); // finalizo con error
	    		
	    	}else{//si no está en estado 3, ni en estado 2, nos logamos
	    		//recuerdo la pass
	    		$cuerpo_email 						= file_get_contents("common/mailing/remember_password.html");
	    		$cuerpo_email 						= str_replace(array("[NOMBRE]","[CORREO]","[PASSWORD]"), array(" ".$usuario['nombre'],$usuario['email'],$usuario['pass']), $cuerpo_email);
	    		
	    		//reenvío el mail
	    		enviar_email($usuario['email'], "Tus datos para entrar en Mundo Facundo", $cuerpo_email);
	    		
	    		$app->halt('200', get_json("¡Te hemos enviado un correo con tus datos!")); // finalizo con éxito
	    	}
	    }else{
	    	$app->halt('200', get_error(6));
	    }
	}
?>