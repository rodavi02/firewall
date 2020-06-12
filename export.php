<?php
	#Control de Sesiones. Si hay usuario logueado, tiene acceso. De lo contrario redirecciona a index.php
	session_start();
    if (empty($_SESSION['user'])) {
        header('Location: firewall.php');
    }
	require 'database.php';

	#Creamos una consulta que nos saque los datos que queremos extraer al archivo JSON.
	$query = "SELECT action, traffic, int_in, int_out, source, destination, protocol, sport, dport, target FROM rule";
	$result = mysqli_query($conn, $query);
	if(!$result) {
		die('La consulta ha fallado');
	}

	#Creamos el array con los datos de la consulta, los cuales se van a extraer.
	$json = array();
	while($row = mysqli_fetch_array($result)) {
		$json[] = array(
			'action' => $row['action'],
			'traffic' => $row['traffic'],
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

	#Pasamos el array a formato JSON.
	$jsonData = json_encode($json);
	file_put_contents("rules.json", $jsonData);
?>