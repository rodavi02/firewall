<?php
	#Control de Sesiones. Si hay usuario logueado, tiene acceso. De lo contrario redirecciona a index.php
	session_start();
    if (empty($_SESSION['user'])) {
        header('Location: index.php');
    }
	require 'database.php';

	#En caso de ser regla de política por defecto, solo pueden tener valores los campos de traffic y target.
	if($_POST['action'] == '-P') {
		if (!empty($_POST['int_in']) || !empty($_POST['int_out']) || !empty($_POST['protocol']) || !empty($_POST['source']) || !empty($_POST['destination']) || !empty($_POST['sport']) || !empty($_POST['dport'])) {
			die('Regla de política por defecto no válida.');
		}
	}

	#En caso de añadir una regla nueva debe tener unos campos mínimos.
	if($_POST['action'] == '-A') {
		if (empty($_POST['int_in']) && empty($_POST['int_out']) && empty($_POST['protocol']) && empty($_POST['source']) && empty($_POST['destination']) && empty($_POST['sport']) && empty($_POST['dport'])) {
			die('Datos insuficientes para la regla.');
		}
	}

	#En caso de introducir una regla de política por defecto, eliminamos la anterior.
	if($_POST['action'] == '-P') {
		$policy_query = "SELECT id FROM rule WHERE traffic='".$_POST['traffic']."' AND action='".$_POST['action']."'";
		$policy_result = mysqli_query($conn, $policy_query);
		$policy_row = mysqli_fetch_assoc($policy_result);
		$policy_id = $policy_row['id'];
		$policy_delete = "DELETE FROM rule where id='".$policy_id."'";
		$policy_finish = mysqli_query($conn, $policy_delete);
	}

	#Comprobamos que el campo protocol tenga un valor adecuado.
	if($_POST['protocol'] != 'icmp' && $_POST['protocol'] != 'tcp' && $_POST['protocol'] != 'udp' && $_POST['protocol'] != 'all' && $_POST['protocol'] != '') {
		die('Valor no válido para el campo "Protocolo"');
	}

	#Comprobamos que las interfaces no sean demasido largas y no sean iguales.
	if(strlen($_POST['int_in']) > 10) {
        die('Valor no válido para el campo "Int. Entrada"');
    }
    
    if(strlen($_POST['int_out']) > 10) {
        die('Valor no válido para el campo "Int. Salida"');
    }

    if($_POST['int_in'] == $_POST['int_out'] && $_POST['int_in'] != '' && $_POST['int_out'] != '') {
    	die('La interfaz de entrada y la de salida no pueden ser la misma.');
    }

    #Comprobamos que los campos Origen y Destino se correspondan con una dirección IP + Máscara y que no sean iguales.
    if(!empty($_POST['source'])) {
		if(!preg_match('/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\/(?:3[0-2]|2[0-9]|[01]?[0-9]?)$/', ($_POST['source']))) {
			die('Valor no válido para el campo "Origen"');
		}
	}

	if(!empty($_POST['destination'])) {
		if(!preg_match('/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\/(?:3[0-2]|2[0-9]|[01]?[0-9]?)$/', ($_POST['destination']))) {
			die('Valor no válido para el campo "Destino"');
		}
	}

	if($_POST['source'] == $_POST['destination'] && $_POST['source'] != '' && $_POST['destination'] != '') {
    	die('La dirección de origen y la de destino no pueden ser la misma.');
    }

    #Si no se especifica un protocolo, los campos sport y dport deben estar vacíos.
    if(empty($_POST['protocol'])) {
    	if(!empty($_POST['sport']) || !empty($_POST['dport'])) {
    		die('Si no hay un protocolo no puede haber puerto origen ni puerto destino');
    	}
    }

	#Comprobamos que los campos sport y dport tengan un valor adecuado y que no sean iguales.
	if(!empty($_POST['sport'])) {
		if(!preg_match('/^(6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{2}|6[0-4][0-9]{3}|[0-5]?([0-9]){0,3}[0-9])$/', ($_POST['sport']))) {
			die('Valor no válido para el campo "Puerto Origen"');
		}
	}

	if(!empty($_POST['dport'])) {
		if(!preg_match('/^(6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{2}|6[0-4][0-9]{3}|[0-5]?([0-9]){0,3}[0-9])$/', ($_POST['dport']))) {
			die('Valor no válido para el campo "Puerto Destino"');
		}
	}

	if($_POST['sport'] == $_POST['dport'] && $_POST['sport'] != '' && $_POST['dport'] != '') {
    	die('El puerto de origen y el de destino no pueden ser el mismo.');
    } else { #Por el contrario...
		$action = $_POST['action'];
		$traffic = $_POST['traffic'];
		$protocol = $_POST['protocol'];
		$target = $_POST['target'];
		$int_in = $_POST['int_in'];
		$int_out = $_POST['int_out'];
		$source = $_POST['source'];
		$destination = $_POST['destination'];
		$sport = $_POST['sport'];
		$dport = $_POST['dport'];

		#Comprobamos que no exista ya una regla así. En caso afirmativo, no se inserta.
		$redundant = $conn->prepare("SELECT * FROM rule WHERE action='".$action."' AND traffic='".$traffic."' AND protocol='".$protocol."' AND target='".$target."' AND int_in='".$int_in."' AND int_out='".$int_out."' AND source='".$source."' AND destination='".$destination."' AND sport='".$sport."' AND dport='".$dport."'");
		$redundant->execute();
        $redundantResult = $redundant->fetch();
        if($redundantResult) {
        	die('Ya existe una regla en el cortafuegos con esa configuración.');
        } else {
			$query = "INSERT INTO rule (action, traffic, protocol, target, int_in, int_out, source, destination, sport, dport) VALUES ('".$action."', '".$traffic."', '".$protocol."', '".$target."', '".$int_in."', '".$int_out."', '".$source."', '".$destination."', '".$sport."', '".$dport."')";
			$result = mysqli_query($conn, $query);
			if(!$result) {
				die('La consulta falló');
			}
			echo 'Regla añadida correctamente';
		}
	}
	
?>