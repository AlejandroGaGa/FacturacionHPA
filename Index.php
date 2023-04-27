<?php
$alert = '';
session_start();
if (!empty($_SESSION['active'])) {
    header('location: sistema/');
} else {
    if (!empty($_POST)) {
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $alert = 'Ingrese su usuario y la clave';
        } else {
            require_once "conexion.php";
            $user = mysqli_real_escape_string($conection, $_POST['usuario']);
            $pass = md5(mysqli_real_escape_string($conection, $_POST['clave']));

            $query = mysqli_query($conection, "SELECT *from usuario WHERE usuario = '$user' AND clave = '$pass'");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result == 0) {
                $data = mysqli_fetch_array($query);

                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $data['idusuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['email'] = $data['correo'];
                $_SESSION['user'] = $data['usuario'];
                $_SESSION['rol'] = $data['rol'];
                header('location: sistema/');
            } else {
                $alert = 'El usuario con la clave es incorrecto';
                session_destroy();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión | HPA</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <section id="container">

        <form action="" method="post">
            <h3>INICIO DE SESIÓN</h3>

            <img src="img/HPAtrans.png" alt="Login"></img>
            <center>
                <p>Herrajes para aluminio S.A de C.V.</p>
            </center>
            <input type="text" name="usuario" placeholder="Usuario">
            <input type="password" name="clave" placeholder="Contraseña">
            <div class="alert">
                <?php echo isset($alert) ? $alert : ''; ?>
            </div>
            <input type=submit value="Iniciar Sesión">

        </form>

    </section>
    <footer class="footer">
        <p>Desing for Alejandro García Gallardo.</p>
        <p>Todos los derechos reservados | Copyrigth 2020.</p>
    </footer>
</body>

</html>