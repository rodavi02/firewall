<?php
    #Control de Sesiones. Si hay usuario logueado, tiene acceso. De lo contrario redirecciona a index.php
    session_start();
    if (empty($_SESSION['user'])) {
        header('Location: index.php');
    }
    require 'database.php';
    $error = "";
    if (isset($_POST['change_pass'])) {
        $usuario=$_SESSION['user'];
        $clave=$_SESSION['password'];
        $clave1=$_POST['old_pass'];
        $clave2=$_POST['new_pass'];
        $check=$_POST['check_pass'];

        if (empty($clave1) || empty($clave2) ||empty($check)) {
            $error = "<b><center>Debes rellenar todos los campos.</center></b>";
        } elseif ($clave==$clave1) {
            if ($clave1!=$clave2 && $clave2==$check) {
                $sql = "UPDATE access SET password=SHA('".$clave2."') WHERE user='".$usuario."'";
                $sqlchp = $conn->prepare($sql);

                if (strlen($clave2) < 6) {
                    $error = "<b><center>La contraseña debe tener mínimo 6 caracteres.</center></b>";
                } elseif (strlen($clave2) > 16) {
                    $error = "<b><center>La contraseña debe tener máximo 16 caracteres.</center></b>";
                } elseif (!preg_match('`[a-z]`', ($clave2))) {
                    $error = "<b><center>La contraseña debe tener al menos una minúscula.</center></b>";
                } elseif (!preg_match('`[A-Z]`', ($clave2))) {
                    $error = "<b><center>La contraseña debe tener al menos una mayúscula.</center></b>";
                } elseif ($sqlchp->execute()) {            
                    $error = "<b><center>Se ha cambiado la contraseña.</center></b>";
                } else {
                    $error = "<b><center>El usuario ya tiene esa contraseña.</center></b>";
                }
            } else {
                $error = "<b><center>La contraseña nueva no coincide con la verificación.</center></b>";
            }
        } else {
            $error = "<b><center>La contraseña actual introducida no es correcta.</center></b>";
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
                    <span class="name">Cambiar Contraseña</span>
                    <hr class="star-light">
                    <?php 
                        if (!empty($error)) {
                            echo "<p class=\"error\">". $error . "</p>";
                        }
                    ?>
                    <div>
                        <form action="change_pass.php" method="post">
                            <p><label>Contraseña Actual<br>
                            <input type="password" name="old_pass" id="old_pass" class="form-control"></label></p>
                            <p><label>Contraseña Nueva<br>
                            <input type="password" name="new_pass" id="new_pass" class="form-control"></label></p>
                            <p><label>Verifica Contraseña Nueva<br>
                            <input type="password" name="check_pass" id="check_pass" class="form-control"></label></p>
                            <input type="submit" name="change_pass" id="change_pass" value="Cambiar">
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