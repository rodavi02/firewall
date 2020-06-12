<?php
	#Control de Sesiones. Si hay usuario logueado, tiene acceso. De lo contrario redirecciona a index.php
	session_start();
    if (empty($_SESSION['user'])) {
        header('Location: index.php');
    }
	require 'database.php';

	#Consulta para que muestre datos en la tabla INPUT.
	$query = "SELECT * FROM rule WHERE traffic='INPUT' AND action!='-P'";
	$result = mysqli_query($conn, $query);
	if(!$result) {
		die('La consulta falló');
	}

	#Almacenamos el resultado de la consulta para mandarlo a la tabla de jQuery.
	$json = array();
	while($row = mysqli_fetch_array($result)) {
		$json[] = array(
			'int_in' => $row['int_in'],
			'int_out' => $row['int_out'],
			'source' => $row['source'],
			'destination' => $row['destination'],
			'protocol' => $row['protocol'],
			'sport' => $row['sport'],
			'dport' => $row['dport'],
			'target' => $row['target']
		);
	}

	$jsonData = json_encode($json);
	echo $jsonData;
?>