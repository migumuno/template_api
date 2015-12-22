<?php
	//codificaci�n del site (iso -> mssql / utf8 -> eoc)
	define("CODIFICACION", "iso-8859-1");
	
	//DB CONSTANTS
	define("DB_HOST", "innova");
	define("DB_USER", "sa");
	define("DB_PASS", "Pr0gr4m4c10n");
	define("DB_NAME", "ICEMD_20131204");
	define("DB_TYPE", "sqlsvr"); //mysql/sqlsvr
	
	//p�ginas permitidas sin sesi�n
	$not_session_required = array(
		 '/mantenimiento/index.php' //hay que poner la url entera sin el dominio ej: /usuarios/acciones/listar.php
		,'/mantenimiento/php/contact.php'
		,'/index.php' 
	);
	
	//etiquetas permitidas a la hora de que los campos puedan tener c�digo html (para proteger de xss)
	define("ALLOWED_HTML_TAGS",
		implode(",", 
			array(
				 "p"
				,"b"
				,"a[href]"
				,"i"
				,"ul"
				,"li"
			)
		)
	);
	
	//archivo de descarga
	define("DWFS","/common/dwfs.php?key=");
	
	//includes del header (principalmente los css)
    $header_includes = array(
    	 "css"		=> array(
    	 		
    	 ) //lo normal es meter los css en la cabecera. Ejemplo para hacerlo seguro: encrypt($_SERVER['DOCUMENT_ROOT']."/css/prueba.css")
    	,"js"		=> array(
    	 		
    	 ) //por si en alg�n momento debemos incluir un js en la cabecera. Ejemplo para hacerlo seguro: encrypt($_SERVER['DOCUMENT_ROOT']."/js/prueba.js")
    	,"script"	=> array(
    	 		
    	 ) //por si queremos meter un script a pelo
    );
    
    //js a incluir en el pie
    $footer_includes = array(
    	 "js"		=> array(
    	 		
    	 )
    	,"script"	=> array(
    	 		
    	 ) //por si queremos meter un script a pelo
    	,"css"		=> array(
    	 		
    	 ) //por si en alg�n momento debemos incluir un css en el pie
    );
	
	$dias_semana = array ("Lunes", "Martes", "Mi�rcoles", "Jueves", "Viernes", "S�bado", "Domingo");
    $meses 		 = array ("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	
    define("security_mail", "javier.djt@innovadsl.es");
    
	//develpment potencia el debugeo de la aplicaci�n y configura cosas como el env�o de emails
	define("DEVELOPMENT", serialize(
		array(
			 'enabled'			=> true
			,'development_mail'	=> 'javier.djt@innovadsl.es'
			,'querys'			=> true
			,'query_params'		=> false
			,'query_result'		=> false
		))
	);
	
	//gesti�n de todo el m�dulo de mantenimiento
	define("MAINTENANCE",	serialize(
		 array(
			 'enabled' 			=> false
			,'allowed_ips' 		=> array()
			,'message'			=> "La web se encuentra en mantenimiento, sentimos<br>las molestias."
			,'company'			=> 'Innova D.S.'
			//,'twitter'		=> '@company' a configurar en /mantenimiento/php/get-tweets.php 
			,'year'				=> 2015
			,'month'			=> 06
			,'day'				=> 09
			,'hour'				=> 0
			,'minute'			=> 0
			,'second'			=> 0
			,'about_header'		=> "Marcamos la diferencia"
			,'about_text'		=> "Lorem fistrum incididunt esse cillum reprehenderit magna a peich pecador exercitation commodo. Pecador duis aliqua cillum no puedor amatomaa llevame al sircoo. Incididunt apetecan laboris papaar papaar magna. Amatomaa reprehenderit amatomaa magna sit amet sexuarl. Adipisicing pecador a peich no te digo trigo por no llamarte Rodrigor a wan mamaar ahorarr se calle ust�e hasta luego Lucas magna."
			,'address'			=> "C/ Arturo Soria 262 Bajo Izq &#183; Madrid"
			,'telephone'		=> "914126884"
			,'email'			=> "soporte@innovadsl.es"//este email es el que sacamos en la info
			,'maintenance_email'=> "javier.djt@innovadsl.es"//este mail recibe los correos del formulario de contacto
			,'facebook_url'		=> "https://facebook.com/innovadsl"
			,'twitter_url'		=> "https://twitter.com/innovadsl"
			,'gplus_url'		=> "https://plus.google.com/innovadsl"
			,'pinterest_url'	=> "https://pinterest.com/innovadsl"
			,'youtube_url'		=> "https://youtube.com/innovadsl"
			,'linkedin_urk'		=> "https://linkedin.com/innovadsl"
		))
	);
	
	//carpeta temporal para hacer cosas
	define("TMP_DIR", "tmp");
	
	//carpeta de archivos subidos
	define("UPLOAD_DIR", "uploads");
	
	//en funci�n del sistema operativo las barras cambian (para el tema de subir archivos, moverlos etc)
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	    define("BARRA_SERVIDOR", "\\");
	} else {
	    define("BARRA_SERVIDOR", "/");
	}
	
	//tama�o de los thumbnails
	define("THUMBNAIL_WIDTH", "236");
	define("THUMBNAIL_HEIGHT", "236");
?>	