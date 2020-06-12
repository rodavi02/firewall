<?php
    #Control de Sesiones. Si hay usuario logueado, tiene acceso. De lo contrario redirecciona a index.php
    session_start();
    if (empty($_SESSION['user'])) {
        header('Location: index.php');
    }
    require 'database.php';
    $error = "";
    if (isset($_POST['register'])) {
        $usuario=$_POST['user'];
        $clave=$_POST['password'];
        $clave2=$_POST['password2'];

        if (empty($usuario) || empty($clave) || empty($clave2)) {
            $error = "<b><center>Debes rellenar todos los campos.</center></b>";
        } else {
            $sql = "INSERT INTO access values('".$usuario."',SHA('".$clave."'))";
            $sqlreg = $conn->prepare($sql);
            
            if (strlen($_POST['password']) < 6) {
                $error = "<b><center>La contraseña debe tener mínimo 6 caracteres.</center></b>";
            } elseif (strlen($_POST['password']) > 16) {
                $error = "<b><center>La contraseña debe tener máximo 16 caracteres.</center></b>";
            } elseif (!preg_match('`[a-z]`', ($_POST['password']))) {
                $error = "<b><center>La contraseña debe tener al menos una minúscula.</center></b>";
            } elseif (!preg_match('`[A-Z]`', ($_POST['password']))) {
                $error = "<b><center>La contraseña debe tener al menos una mayúscula.</center></b>";
            } elseif ($clave2!=$clave) {
                $error = "<b><center>La contraseña no es igual, vuelva a escribirla.</center></b>";
            } elseif ($sqlreg->execute()) {            
                $error = "<b><center>Se ha registrado el usuario.</center></b>";
            } else {
                $error = "<b><center>El usuario introducido ya existe.</center></b>";
            }
        }
    }

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
            <div class="navbar-header page-scroll">
                <a class="navbar-brand" href="firewall.php"><i class='fa fa-shield'></i> Cortafuegos</a>
            </div>
            <div class="hijo">
                <a class="navbar-brand" href="profile.php"><i class='fa fa-user'></i> 
                <?php echo $_SESSION['user']; ?></a>
            </div>
            <div class="hijo">
                <a class="navbar-brand" href="logout.php"><i class='fa fa-close'></i> Cerrar Sesión</a>       
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="col-lg-12">
                <div class="intro-text">
                    <span class="name">Registrar nuevo usuario</span>
                    <hr class="star-light">
                    <?php 
                        if (!empty($error)) {
                            echo "<p class=\"error\">". $error . "</p>";
                        }
                    ?>
                    <div>
                        <form action="register.php" method="post">
                            <p><label>Usuario<br>
                            <input type="text" name="user" id="user" class="form-control"></label></p>
                            <p><label>Contraseña<br>
                            <input type="password" name="password" id="password" class="form-control"></label></p>
                            <p><label>Confirme la Contraseña<br>
                            <input type="password" name="password2" id="password2" class="form-control"></label></p>
                            <input type="submit" name="register" id="register" value="Registrar">
                        </form>
                    </div>
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