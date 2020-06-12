<?php
	$host = 'localhost';
	$user = 'juanfran';
	$password = 'juanfran';
	$db = 'firewall_db';

	$conn = new mysqli($host,$user,$password,$db);
	if($conn->connect_error) {
	    echo 'No se ha podido conectar con la base de datos';
	}
?>