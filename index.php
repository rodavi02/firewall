<?php
    #Control de Sesiones. Si hay usuario logueado lo manda directo a firewall.php, ya que no puedes estar en index.php con sesión iniciada.
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: firewall.php');
    }
    require 'database.php';
    $error = "";
    if (isset($_POST['login'])) {
        $usuario = $_POST['user'];
        $clave = $_POST['password'];

        if (empty($usuario) || empty($clave)) {
            $error = "<b>Debes introducir un nombre de usuario y una contraseña</b>";
        } else {
            $sqlind = $conn->prepare("SELECT user, password FROM access WHERE user='".$usuario."' AND password=SHA('".$clave."')");
            $sqlind->execute();
            $results = $sqlind->fetch();
            if ($results) {
                $_SESSION['user'] = $usuario;
                $_SESSION['password'] = $clave;
                header("Location: firewall.php");
            } else {
                $error = "<b>Los datos no coinciden con los de ningún usuario.</b>";
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
                <a class="navbar-brand" href="#page-top"><i class='fa fa-shield'></i> Cortafuegos</a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <img class="img-responsive" src="./img/firewall.png" width="400" height="300">
                </div>
                <div class="col-lg-6">
                    <div class="intro-text">
                        <span class="name">Inicio de Sesión</span>
                        <hr class="star-light">
                        <?php 
                            if (!empty($error)) {
                                echo "<p class=\"error\">". $error . "</p>";
                            }
                        ?>
                        <div class="login">
                            <form action="index.php" method="POST">
                                <p><label>Usuario<br>
                                <input type="text" name="user" id="user" class="form-control"></label></p>
                                <p><label>Contraseña<br>
                                <input type="password" name="password" id="password" class="form-control"></label></p>
                                <p class="submit">
                                <input type="submit" name="login" class="button" value="Iniciar Sesión" /></p>
                            </form>
                        </div>
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