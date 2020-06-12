<?php
    #Control de Sesiones. Si hay usuario logueado, tiene acceso. De lo contrario redirecciona a index.php
    session_start();
    if (empty($_SESSION['user'])) {
        header('Location: index.php');
    }
    require 'database.php';

    #Sacamos la política por defecto que tiene iptables para INPUT, OUTPUT y FORWARD para usarlo posteriormente en la cabecera de cada tabla.
    $input_query = "SELECT target FROM rule WHERE traffic='INPUT' AND action='-P' ORDER BY id DESC LIMIT 1";
    $input_result = mysqli_query($conn, $input_query);
    $input_row = mysqli_fetch_assoc($input_result);
    $input_policy = $input_row['target'];

    $output_query = "SELECT target FROM rule WHERE traffic='OUTPUT' AND action='-P' ORDER BY id DESC LIMIT 1";
    $output_result = mysqli_query($conn, $output_query);
    $output_row = mysqli_fetch_assoc($output_result);
    $output_policy = $output_row['target'];

    $forward_query = "SELECT target FROM rule WHERE traffic='FORWARD' AND action='-P' ORDER BY id DESC LIMIT 1";
    $forward_result = mysqli_query($conn, $forward_query);
    $forward_row = mysqli_fetch_assoc($forward_result);
    $forward_policy = $forward_row['target'];
?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<head>

    <title>Cortafuegos - Juanfran</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

</head>

<body id="page-top" class="index">
    <!-- Navbar -->
    <nav class="navbar navbar-fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div class="">
                <a class="navbar-brand" align="left" href="firewall.php"><i class='fa fa-shield'></i> Cortafuegos</a>
            </div>
            <div class="">
                <a class="navbar-brand" href="profile.php">
                <i class='fa fa-user'></i> 
                <?php echo $_SESSION['user']; ?></a>
            </div>
            <div class="" align="right">
                <a class="navbar-brand" href="logout.php"><i class='fa fa-close'></i> Cerrar Sesión</a>       
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-text">
                        <span class="name">Reglas del Cortafuegos</span>
                        <hr class="star-light">
                        <h5>Añadir regla al Cortafuegos</h5>
                    </div>
                </div>
            </div>
            <form id="add-iptables">
                <div class="form-group">
                    <div class="row">
                        <input type="hidden" id="id">
                        <div class="col-lg-2 col-sm-2">      
                            <select class="form-control" id="action">
                              <option>-A</option>
                              <option>-I</option>
                              <option>-P</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-sm-2">                   
                            <select class="form-control" id="traffic">
                              <option>INPUT</option>
                              <option>OUTPUT</option>
                              <option>FORWARD</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-sm-2">                   
                            <input type="text" id="protocol" placeholder="Protocolo" class="form-control text-center">
                        </div>
                        <div class="col-lg-2 col-sm-2">                   
                            <select class="form-control" id="target">
                              <option>ACCEPT</option>
                              <option>DROP</option>
                              <option>REJECT</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-sm-4">                   
                            <input type="submit" id="add" class="btn btn-block" value="Añadir Regla">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-2 col-sm-2">                   
                            <input type="text" id="int_in" placeholder="Int. Entrada" class="form-control text-center">
                        </div>
                        <div class="col-lg-2 col-sm-2">                   
                            <input type="text" id="int_out" placeholder="Int. Salida" class="form-control text-center">
                        </div>
                        <div class="col-lg-2 col-sm-2">                   
                            <input type="text" id="source" placeholder="Origen" class="form-control text-center">
                        </div>
                        <div class="col-lg-2 col-sm-2">                   
                            <input type="text" id="destination" placeholder="Destino" class="form-control text-center">
                        </div>
                        <div class="col-lg-2 col-sm-2">                   
                            <input type="text" id="sport" placeholder="Puerto Origen" class="form-control text-center">
                        </div>
                        <div class="col-lg-2 col-sm-2">                   
                            <input type="text" id="dport" placeholder="Puerto Destino" class="form-control text-center">
                        </div>
                    </div>                  
                </div>
            </form>
            <br>
            <h5>- Reglas de INPUT - (<?php echo $input_policy ?>) -</h5>
            <div class="row">
                <div class="col-lg-12">
                   <table class="table table-bordered bg-white">
                       <thead>
                           <tr>
                               <td>Int. Entrada</td>
                               <td>Int. Salida</td>
                               <td>Origen</td>
                               <td>Destino</td>
                               <td>Protocolo</td>
                               <td>Puerto Origen</td>
                               <td>Puerto Destino</td>
                               <td>Acción</td>
                               <td>Editar</td>
                               <td>Borrar</td>
                           </tr>
                       </thead>
                       <tbody id="input">
                           
                       </tbody>
                   </table>
                </div>
            </div>
            <br>
            <h5>- Reglas de OUTPUT - (<?php echo $output_policy ?>) -</h5>
            <div class="row">
                <div class="col-lg-12">
                   <table class="table table-bordered bg-white">
                       <thead>
                           <tr>
                               <td>Int. Entrada</td>
                               <td>Int. Salida</td>
                               <td>Origen</td>
                               <td>Destino</td>
                               <td>Protocolo</td>
                               <td>Puerto Origen</td>
                               <td>Puerto Destino</td>
                               <td>Acción</td>
                               <td>Editar</td>
                               <td>Borrar</td>
                           </tr>
                       </thead>
                       <tbody id="output">
                           
                       </tbody>
                   </table>
                </div>
            </div>
            <br>
            <h5>- Reglas de FORWARD - (<?php echo $forward_policy ?>) -</h5>
            <div class="row">
                <div class="col-lg-12">
                   <table class="table table-bordered bg-white">
                       <thead>
                           <tr>
                               <td>Int. Entrada</td>
                               <td>Int. Salida</td>
                               <td>Origen</td>
                               <td>Destino</td>
                               <td>Protocolo</td>
                               <td>Puerto Origen</td>
                               <td>Puerto Destino</td>
                               <td>Acción</td>
                               <td>Editar</td>
                               <td>Borrar</td>
                           </tr>
                       </thead>
                       <tbody id="forward">
                           
                       </tbody>
                   </table>
                </div>
            </div>
        </div>
    </header>

    <!-- Footer -->
    <footer class="text-center">
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyright &copy; Juanfran Rodríguez Ávila - 2020
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <script src="app.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>

</html>