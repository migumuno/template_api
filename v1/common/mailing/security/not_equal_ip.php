<?php 
	$cuerpo = "Se ha detectado una violaci�n de seguridad. 
	La Ip original desde la que se ha recibido la conexi�n ha cambiado durante la navegaci�n.
	<br><br>
	La ip nueva desde la que se realiz� la conexi�n ha sido: ".$actual_ip."<br><br>
	Aqu� tienes los datos que se han podido recopilar:<br><br>
	SESSION:<br>
	<pre>
	".print_r($_SESSION,1)."
	</pre>
	<br>
	REQUEST:<br>
	<pre>
	".print_r($_REQUEST,1)."
	</pre>
	SERVER:<br>
	<pre>
	".print_r($_SERVER,1)."
	</pre>
	<br>
	Si el problema persiste, recomendamos incluir las IPS implicadas en una lista negra para denegar el acceso.
	<br><br>
	Pasa un buen d�a.";
?>