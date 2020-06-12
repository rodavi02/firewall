$(document).ready(function() {
	//Ejecutamos la función getRules() siempre para que no recargue las tablas.
	getRules();
	//Mantemenos edit en falso cada vez que iniciamos la página.
	let edit = false;

	//Para añadir reglas iptables...
	$('#add-iptables').submit(function(e) {
		const postData = {
			action: $('#action').val(),
			traffic: $('#traffic').val(),
			protocol: $('#protocol').val(),
			target: $('#target').val(),
			int_in: $('#int_in').val(),
			int_out: $('#int_out').val(),
			source: $('#source').val(),
			destination: $('#destination').val(),
			sport: $('#sport').val(),
			dport: $('#dport').val(),
			id: $('#id').val()
		};
		//Establecemos que si edit=false entonces url=add.php. Por el contrario, si edit=true entonces url=edit.php.
		//Si la url es add.php, el botón del formulario añade una regla. Si es edit.php, el botón sobreescribe la regla.
		let url = edit === false ? 'add.php' : 'edit.php';
		$.post(url, postData, function(response) {
			getRules();
			//Condición para distintos tipos de alert.
			if (response === "Regla añadida correctamente" || response === "Regla modificada correctamente") {
				swal(response,"","success");
			} else {
				swal(response,"","error");
			}
			//Limpiamos el formulario.
			$('#add-iptables').trigger('reset');
		});
		//Para que no recargue la página
		e.preventDefault();
		//Terminado esto, volvemos a poner edit con su valor por defecto.
		edit = false;
	});

	//Genera las tres tablas de reglas iptables: INPUT, OUTPUT y FORWARD.
	function getRules() {
		$.ajax({
			url: 'list_input.php',
			type: 'GET',
			success: function(response) {
				let rule = JSON.parse(response);
				let template = '';
				rule.forEach(rule => {
					template += `
						<tr ruleInt_in="${rule.int_in}" ruleInt_out="${rule.int_out}" ruleSource="${rule.source}" ruleDestination="${rule.destination}" ruleProtocol="${rule.protocol}" ruleSport="${rule.sport}" ruleDport="${rule.dport}" ruleTarget="${rule.target}">
							<td>${rule.int_in}</td>
							<td>${rule.int_out}</td>
							<td>${rule.source}</td>
							<td>${rule.destination}</td>
							<td>${rule.protocol}</td>
							<td>${rule.sport}</td>
							<td>${rule.dport}</td>
							<td>${rule.target}</td>
							<td>
								<button class="input-edit btn btn-dark">
									<i class='fa fa-pencil'></i>
								</button>
							</td>
							<td>
								<button class="input-delete btn btn-dark">
									<i class='fa fa-trash'></i>
								</button>
							</td>
						</tr>
					`
				});
				$('#input').html(template);
			}
		});

		$.ajax({
			url: 'list_output.php',
			type: 'GET',
			success: function(response) {
				let rule = JSON.parse(response);
				let template = '';
				rule.forEach(rule => {
					template += `
						<tr ruleInt_in="${rule.int_in}" ruleInt_out="${rule.int_out}" ruleSource="${rule.source}" ruleDestination="${rule.destination}" ruleProtocol="${rule.protocol}" ruleSport="${rule.sport}" ruleDport="${rule.dport}" ruleTarget="${rule.target}">
							<td>${rule.int_in}</td>
							<td>${rule.int_out}</td>
							<td>${rule.source}</td>
							<td>${rule.destination}</td>
							<td>${rule.protocol}</td>
							<td>${rule.sport}</td>
							<td>${rule.dport}</td>
							<td>${rule.target}</td>
							<td>
								<button class="output-edit btn btn-dark">
									<i class='fa fa-pencil'></i>
								</button>
							</td>
							<td>
								<button class="output-delete btn btn-dark">
									<i class='fa fa-trash'></i>
								</button>
							</td>
						</tr>
					`
				});
				$('#output').html(template);
			}
		});

		$.ajax({
			url: 'list_forward.php',
			type: 'GET',
			success: function(response) {
				let rule = JSON.parse(response);
				let template = '';
				rule.forEach(rule => {
					template += `
						<tr ruleInt_in="${rule.int_in}" ruleInt_out="${rule.int_out}" ruleSource="${rule.source}" ruleDestination="${rule.destination}" ruleProtocol="${rule.protocol}" ruleSport="${rule.sport}" ruleDport="${rule.dport}" ruleTarget="${rule.target}">
							<td>${rule.int_in}</td>
							<td>${rule.int_out}</td>
							<td>${rule.source}</td>
							<td>${rule.destination}</td>
							<td>${rule.protocol}</td>
							<td>${rule.sport}</td>
							<td>${rule.dport}</td>
							<td>${rule.target}</td>
							<td>
								<button class="forward-edit btn btn-dark">
									<i class='fa fa-pencil'></i>
								</button>
							</td>
							<td>
								<button class="forward-delete btn btn-dark">
									<i class='fa fa-trash'></i>
								</button>
							</td>
						</tr>
					`
				});
				$('#forward').html(template);
			}
		});
		//Para que cada vez que se ejecute la función se exporten las reglas al archivo JSON.
		$.post('export.php', function(response) {})
	}

	//Si se pulsa el botón de borrar, te borra esa regla concreta de la tabla.
	$(document).on('click', '.input-delete', function() {
		swal({
			title: "¿Seguro que quieres borrar esta regla?",
			text: "Esta acción borrará la regla del cortafuegos",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}) .then((willDelete) => {
			if (willDelete) {
				swal("La regla ha sido eliminada", {
				icon: "success",
				});
				let element = $(this)[0].parentElement.parentElement;
				let int_in = $(element).attr('ruleInt_in');
				let int_out = $(element).attr('ruleInt_out');
				let source = $(element).attr('ruleSource');
				let destination = $(element).attr('ruleDestination');
				let protocol = $(element).attr('ruleProtocol');
				let sport = $(element).attr('ruleSport');
				let dport = $(element).attr('ruleDport');
				let target = $(element).attr('ruleTarget');
				$.post('delete_input.php', {int_in, int_out, source, destination, protocol, sport, dport, target}, function(response) {
					getRules();
				})
			} else {
    			swal("La regla continúa en el cortafuegos");
  			}
		});
	})

	//Si se pulsa el botón de borrar, te borra esa regla concreta de la tabla.
	$(document).on('click', '.output-delete', function() {
		swal({
			title: "¿Seguro que quieres borrar esta regla?",
			text: "Esta acción borrará la regla del cortafuegos",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}) .then((willDelete) => {
			if (willDelete) {
				swal("La regla ha sido eliminada", {
				icon: "success",
				});
				let element = $(this)[0].parentElement.parentElement;
				let int_in = $(element).attr('ruleInt_in');
				let int_out = $(element).attr('ruleInt_out');
				let source = $(element).attr('ruleSource');
				let destination = $(element).attr('ruleDestination');
				let protocol = $(element).attr('ruleProtocol');
				let sport = $(element).attr('ruleSport');
				let dport = $(element).attr('ruleDport');
				let target = $(element).attr('ruleTarget');
				$.post('delete_output.php', {int_in, int_out, source, destination, protocol, sport, dport, target}, function(response) {
					getRules();
				})
			} else {
    			swal("La regla continúa en el cortafuegos");
  			}
		});
	})

	//Si se pulsa el botón de borrar, te borra esa regla concreta de la tabla.
	$(document).on('click', '.forward-delete', function() {
		swal({
			title: "¿Seguro que quieres borrar esta regla?",
			text: "Esta acción borrará la regla del cortafuegos",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}) .then((willDelete) => {
			if (willDelete) {
				swal("La regla ha sido eliminada", {
				icon: "success",
				});
				let element = $(this)[0].parentElement.parentElement;
				let int_in = $(element).attr('ruleInt_in');
				let int_out = $(element).attr('ruleInt_out');
				let source = $(element).attr('ruleSource');
				let destination = $(element).attr('ruleDestination');
				let protocol = $(element).attr('ruleProtocol');
				let sport = $(element).attr('ruleSport');
				let dport = $(element).attr('ruleDport');
				let target = $(element).attr('ruleTarget');
				$.post('delete_forward.php', {int_in, int_out, source, destination, protocol, sport, dport, target}, function(response) {
					getRules();
				})
			} else {
    			swal("La regla continúa en el cortafuegos");
  			}
		});
	})

	//Si se pulsa el botón de editar, te edita esa regla concreta de la tabla.
	$(document).on('click', '.input-edit', function() {
		let element = $(this)[0].parentElement.parentElement;
		let int_in = $(element).attr('ruleInt_in');
		let int_out = $(element).attr('ruleInt_out');
		let source = $(element).attr('ruleSource');
		let destination = $(element).attr('ruleDestination');
		let protocol = $(element).attr('ruleProtocol');
		let sport = $(element).attr('ruleSport');
		let dport = $(element).attr('ruleDport');
		let target = $(element).attr('ruleTarget');
		let id = $(element).attr('ruleId');
		let traffic = $(element).attr('ruleTraffic');
		$.post('load_input.php', {int_in, int_out, source, destination, protocol, sport, dport, target, id, traffic}, function(response) {
			const rule = JSON.parse(response);
			$('#action').val('-A');
			$('#traffic').val(rule.traffic);
			$('#int_in').val(rule.int_in);
			$('#int_out').val(rule.int_out);
			$('#source').val(rule.source);
			$('#destination').val(rule.destination);
			$('#protocol').val(rule.protocol);
			$('#sport').val(rule.sport);
			$('#dport').val(rule.dport);
			$('#target').val(rule.target);
			$('#id').val(rule.id);
			edit = true;
		});
	})

	//Si se pulsa el botón de editar, te edita esa regla concreta de la tabla.
	$(document).on('click', '.output-edit', function() {
		let element = $(this)[0].parentElement.parentElement;
		let int_in = $(element).attr('ruleInt_in');
		let int_out = $(element).attr('ruleInt_out');
		let source = $(element).attr('ruleSource');
		let destination = $(element).attr('ruleDestination');
		let protocol = $(element).attr('ruleProtocol');
		let sport = $(element).attr('ruleSport');
		let dport = $(element).attr('ruleDport');
		let target = $(element).attr('ruleTarget');
		let id = $(element).attr('ruleId');
		let traffic = $(element).attr('ruleTraffic');
		$.post('load_output.php', {int_in, int_out, source, destination, protocol, sport, dport, target, id, traffic}, function(response) {
			const rule = JSON.parse(response);
			$('#action').val('-A');
			$('#traffic').val(rule.traffic);
			$('#int_in').val(rule.int_in);
			$('#int_out').val(rule.int_out);
			$('#source').val(rule.source);
			$('#destination').val(rule.destination);
			$('#protocol').val(rule.protocol);
			$('#sport').val(rule.sport);
			$('#dport').val(rule.dport);
			$('#target').val(rule.target);
			$('#id').val(rule.id);
			edit = true;
		});
	})

	//Si se pulsa el botón de editar, te edita esa regla concreta de la tabla.
	$(document).on('click', '.forward-edit', function() {
		let element = $(this)[0].parentElement.parentElement;
		let int_in = $(element).attr('ruleInt_in');
		let int_out = $(element).attr('ruleInt_out');
		let source = $(element).attr('ruleSource');
		let destination = $(element).attr('ruleDestination');
		let protocol = $(element).attr('ruleProtocol');
		let sport = $(element).attr('ruleSport');
		let dport = $(element).attr('ruleDport');
		let target = $(element).attr('ruleTarget');
		let id = $(element).attr('ruleId');
		let traffic = $(element).attr('ruleTraffic');
		$.post('load_forward.php', {int_in, int_out, source, destination, protocol, sport, dport, target, id, traffic}, function(response) {
			const rule = JSON.parse(response);
			$('#action').val('-A');
			$('#traffic').val(rule.traffic);
			$('#int_in').val(rule.int_in);
			$('#int_out').val(rule.int_out);
			$('#source').val(rule.source);
			$('#destination').val(rule.destination);
			$('#protocol').val(rule.protocol);
			$('#sport').val(rule.sport);
			$('#dport').val(rule.dport);
			$('#target').val(rule.target);
			$('#id').val(rule.id);
			edit = true;
		});
	})
});