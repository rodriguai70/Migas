<?php
session_start();
require_once('include/DB.php');

if (isset($_POST['Entrar'])) {

    if (empty($_POST['usuario']) || empty($_POST['password']))
        $error = "Debes introducir un nombre de usuario y una contraseña";
    else {
        // Comprobamos las credenciales con la base de datos
        if (DB::verificaCliente($_POST['usuario'], $_POST['contrasena'])) {
            session_start();
            $_SESSION['usuario'] = $_POST['usuario'];
            header("Location: pagar.php");
        } else {
            // Si las credenciales no son válidas, se vuelven a pedir
            $error = "Usuario o contraseña no válidos!";
        }
    }
}

// Si viene del login o registro, redirige según validación (esto lo delegas a login.php y registro.php)
?>
<?php
$fondos = ['fondopanes1.png', 'fondopanes2.png', 'fondopanes3.png', 'comprapan.png', 'cestafondo.png'];
$fondoAleatorio = $fondos[array_rand($fondos)];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acceso a TodoMiga</title>
    <link href="tienda.css" rel="stylesheet" type="text/css">


</head>

<body class="pag-login" style="background-image: url('img/<?php echo $fondoAleatorio; ?>'); background-size: cover;">
    <div class="contenedor-acceso">

        <!-- Login -->
        <div class="panel">
            <img src="imagenes/logo.png" alt="TodoMiga" />
            <h2>Login</h2>
            <form method="post" action="invitado.php">
                <div class="logo">
                    <img src="img/logo.png" alt="Panadería Todomiga" style="display: block; margin: 0 auto; width: 100px;">
                </div>
                <label for="usuario">Usuario:</label><br>
                <input type="text" name="usuario" id="usuario" required><br>

                <label for="contrasena">Contraseña:</label><br>
                <input type="password" name="contrasena" id="contrasena" required><br>
                <input type="submit" class="boton-verde" name="Entrar" value="Entrar">

            </form>

            <form method="post" action="productos.php">
                <type="submit" name="entrar_invitado" class="invitado-btn"></button>
            </form>
        </div>

        <!-- Registro -->
        <div class="panel">
            <img src="imagenes/logo.png" alt="TodoMiga" />
            <h2>Registro</h2>
            <form method="post" action="invitado.php">
                <div class="logo">
                    <img src="img/todomiga3.png" alt="Panadería Todomiga" style="display: block; margin: 0 auto; width: 100px;">
                </div>
                <label for="nuevo_usuario">Usuario:</label><br>
                <input type="text" name="nuevo_usuario" id="nuevo_usuario" required><br>

                <label for="nueva_contrasena">Contraseña:</label><br>
                <input type="password" name="nueva_contrasena" id="nueva_contrasena" required><br>

                <input type="submit" class="red-button" name="Registro" value="Registro">
               <a href="index.php" class="red-button">Volver</a>
            </form>
        </div>
    </div>
</body>

</html>