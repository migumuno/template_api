<?php
	require_once 'core/config.php'; //cerebro del sistema
	require_once 'lib/Slim/Slim.php'; // framework api rest
	require_once 'common/errors.php'; // api errors
	require_once 'classes/class.api_usage.php'; // api dependences
	
	//iniciamos la api
	\Slim\Slim::registerAutoloader();
	$app 	 = new \Slim\Slim();
	 
	//cargamos los métodos de los subsistemas
	require_once __DIR__.'/api_content/user.php'; //subsistema de usuarios
	
	//declaración de los métodos de la api
	/****************************
	 * 							*
	 * 			USUARIO			* 
	 * 							*
	 ****************************/
	/*
		$app->get('/wines/', 'getWines');
		$app->post('/wines/', 'insertWine');
		$app->get('/wines/:id',  'getWine');
		$app->get('/wines/search/:query', 'findByName');
		$app->put('/wines/:id', 'updateWine');
		$app->delete('/wines/:id',   'deleteWine');
	*/
	 
	/**
	 * Método que se encarga de la validación de la api key
	 */
	$app->hook('slim.before.dispatch', function () use ($app, $db){
		//obtengo el parámetro de la key que me tiene que venir como parámetro en el header
		$headers 		= apache_request_headers();
		$keyToCheck 	= $headers['Authorization'];
		
		//compruebo la api key
		$apiUsage 		= new api_usage($db);
	    $api_filter 	= array();
	    add_filter($api_filter, "apikey", $keyToCheck);
	    add_filter($api_filter, "enabled", 1);
	    $authorized 	= $apiUsage->authorize($api_filter);
	
	    $development 	= unserialize(DEVELOPMENT);
	    
	    //si no me autorizan el acceso, adios
	    if(!$authorized->resultado && !$development['enabled']){ //key is false
	        $app->halt('403', get_error(1)); // or redirect, or other something
	    }
	});
	
	//ejecutamos la api
	$app->run();
?>