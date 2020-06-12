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

	$query = "DELETE FROM rule WHERE int_in='".$int_in."' AND int_out='".$int_out."' AND source='".$source."' AND destination='".$destination."' AND protocol='".$protocol."' AND sport='".$sport."' AND dport='".$dport."' AND target='".$target."' AND traffic='OUTPUT'";
	$result = mysqli_query($conn, $query);

	if(!$result) {
		die('La consulta ha fallado');
	}
	echo "Consulta borrada correctamente.";
?>