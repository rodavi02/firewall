<?php
	#Control de Sesiones. Si hay usuario logueado, tiene acceso. De lo contrario redirecciona a index.php
	session_start();
    if (empty($_SESSION['user'])) {
        header('Location: index.php');
    }
	require 'database.php';

	$int_in = $_POST['int_in'];
	$int_out = $_POST['int_out'];
	$source = $_POST['source'];
	$destination = $_POST['destination'];
	$protocol = $_POST['protocol'];
	$sport = $_POST['sport'];
	$dport = $_POST['dport'];
	$target = $_POST['target'];

	$query = "SELECT * FROM rule WHERE action='-A' AND traffic='INPUT' AND int_in='".$int_in."' AND int_out='".$int_out."' AND source='".$source."' AND destination='".$destination."' AND protocol='".$protocol."' AND sport='".$sport."' AND dport='".$dport."' AND target='".$target."'";
	$result = mysqli_query($conn, $query);
	if(!$result) {
		die('La consulta ha fallado');
	}

	$json = array();
	while($row = mysqli_fetch_array($result)) {
		$json[] = array(
			'id' => $row['id'],
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

	$jsonData = json_encode($json[0]);
	echo $jsonData;
?>