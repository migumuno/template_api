<?php
	
	/*****************************/
	/*							 */
	/*			NIVEL1			 */
	/*							 */
	/*****************************/

	//obtengo la ip actual de manera segura
	$actual_ip = filter_var(getenv ( "REMOTE_ADDR" ), FILTER_VALIDATE_IP);
	
	//si no tengo guardada la ip desde la que se conectan
	if(!isset($_SESSION['ORIGIN_IP'])){
		$_SESSION['ORIGIN_IP'] = $actual_ip;
	}else{
		//si la tengo, comparo la original con la actual y si no es la misma, escapamos
		if (strcmp($_SESSION['ORIGIN_IP'] , $actual_ip) !== 0) {
			//enviamos un email de aviso a soporte
			$asunto = "Violación de seguridad nivel 1 en site:".$_SERVER['SERVER_NAME'];
			include $_SERVER['DOCUMENT_ROOT'].'/common/mailing/security/not_equal_ip.php'; //aquí tenemos el cuerpo del mensaje
			enviar_email(security_mail, $asunto, $cuerpo);
			//realizamos la expulsión de seguridad
			expulsion_seguridad();
		}
	}
	
	//libero memoria
	unset($actual_ip);
	
	/*****************************/
	/*							 */
	/*			NIVEL2			 */
	/*							 */
	/*****************************/
	
	//como medida de seguridad básica, vamos a limpiar todas las variables por defecto
	
	//con esto prevenimos el XSS
	require_once $_SERVER['DOCUMENT_ROOT'].'/lib/htmlpurifier/HTMLPurifier.auto.php';
	// Configuración básica
	$config = HTMLPurifier_Config::createDefault();
	$config->set('Core.Encoding', CODIFICACION);
	$config->set('HTML.Doctype', 'HTML 4.01 Transitional');
	// Creamos la whitelist
	$config->set('HTML.Allowed', ALLOWED_HTML_TAGS); // configuramos las etiquetas que permitimos
	$sanitiser = new HTMLPurifier($config);
	
	//aquí limpiamos todas las variables y combatimos de manera superficial el sql inject
	$_REQUEST = sanitize($_REQUEST, $sanitiser);
	$_POST 	  = sanitize($_POST, 	$sanitiser);
	$_GET 	  = sanitize($_GET, 	$sanitiser);
	$_COOKIE  = sanitize($_COOKIE, 	$sanitiser);
	
	//libero memoria
	unset($sanitiser);
?>