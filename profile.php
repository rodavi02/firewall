<?php
    #Control de Sesiones. Si hay usuario logueado, tiene acceso. De lo contrario redirecciona a index.php
    session_start();
    if (empty($_SESSION['user'])) {
        header('Location: index.php');
    }
    require 'database.php';
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
            <div class="" align="right">
                <a class="navbar-brand" href="logout.php"> <i class='fa fa-close'></i> Cerrar Sesión</a>       
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-text">
                        <span class="name">- <?php echo $_SESSION['user']; ?> -</span>
                        <hr class="star-light">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <a class="btn btn-block profile" href="register.php"><i class='fa fa-plus'></i> Registrar Usuario</a>
                </div>
                <div class="col-lg-3">
                    <a class="btn btn-block profile" href="change_user.php"><i class='fa fa-pencil'></i> Cambiar Nombre Usuario</a>
                </div>
                <div class="col-lg-3">
                    <a class="btn btn-block profile" href="change_pass.php"><i class='fa fa-lock'></i> Cambiar Contraseña</a>
                </div>
                <div class="col-lg-3">
                    <a class="btn btn-block profile" href="delete_user.php"><i class='fa fa-trash'></i> Borrar Usuario</a>
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
</body>

</html>