<?php
    #Control de Sesiones. Si hay usuario logueado, tiene acceso. De lo contrario redirecciona a index.php
    session_start();
    if (empty($_SESSION['user'])) {
        header('Location: index.php');
    }
    require 'database.php';
    $error = "";
    if (isset($_POST['change_user'])) {
        $usuario=$_SESSION['user'];
        $usuario1=$_POST['old_user'];
        $usuario2=$_POST['new_user'];

        if (empty($usuario1) || empty($usuario2)) {
            $error = "<b><center>Debes rellenar todos los campos.</center></b>";
        } elseif ($usuario==$usuario1) {
            if ($usuario1!=$usuario2) {
                $sql = "UPDATE access SET user='".$usuario2."' WHERE user='".$usuario1."'";
                $sqlchu = $conn->prepare($sql);
                $sqlchu->execute();
                $_SESSION['user']=$usuario2;
                $error = "<b><center>Se ha cambiado el nombre de usuario.</center></b>";
            } else {
                $error = "<b><center>Los usuarios introducidos no pueden ser iguales.</center></b>";
            }
        } else {
            $error = "<b><center>Solo puedes cambiar tu nombre de usuario.</center></b>";
        }
    }
?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<head>

    <title>Cortafuegos - Juanfran</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- CSS -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Fuentes -->
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
                    <span class="name">Cambiar nombre de Usuario</span>
                    <hr class="star-light">
                    <?php 
                        if (!empty($error)) {
                            echo "<p class=\"error\">". $error . "</p>";
                        }
                    ?>
                    <div>
                        <form action="change_user.php" method="post">
                            <p><label>Usuario Actual<br>
                            <input type="text" name="old_user" id="old_user" class="form-control"></label></p>
                            <p><label>Usuario Nuevo<br>
                            <input type="text" name="new_user" id="new_user" class="form-control"></label></p>
                            <input type="submit" name="change_user" id="change_user" value="Cambiar">
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