<?php
    #Control de Sesiones. Si hay usuario logueado, tiene acceso. De lo contrario redirecciona a index.php
    session_start();
    if (empty($_SESSION['user'])) {
        header('Location: index.php');
    }
    require 'database.php';
    $error = "";
    if (isset($_POST['delete_user'])) {
        $usuario=$_SESSION['user'];
        $usuario1=$_POST['user1'];
        $usuario2=$_POST['user2'];

        if (empty($usuario1) || empty($usuario2)) {
            $error = "<b><center>Debes rellenar todos los campos.</center></b>";
        } elseif ($usuario1==$usuario2) {
            if ($usuario1==$usuario) {
                $sql = "DELETE FROM access WHERE user='".$usuario1."'";
                $sqldelu = $conn->prepare($sql);
                $sqldelu->execute();
                $error = "<b><center>Se ha borrado usuario.</center></b>";
                header('Location: logout.php');
            } else {
                $error = "<b><center>Solo puedes borrar tu usuario.</center></b>";
            }
        } else {
            $error = "<b><center>El usuario no coincide con la verificación.</center></b>";
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
                    <span class="name">Borrar Usuario</span>
                    <hr class="star-light">
                    <?php 
                        if (!empty($error)) {
                            echo "<p class=\"error\">". $error . "</p>";
                        }
                    ?>
                    <div>
                        <form action="delete_user.php" method="post">
                            <p><label>Usuario<br>
                            <input type="text" name="user1" id="user1" class="form-control"></label></p>
                            <p><label>Verifica Usuario<br>
                            <input type="text" name="user2" id="user2" class="form-control"></label></p>
                            <input type="submit" name="delete_user" id="delete_user" value="Borrar">
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