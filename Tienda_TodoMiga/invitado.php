<?php 
session_start();
require_once('include/DB.php');

// LOGIN
if (isset($_POST['Entrar'])) {
    if (empty($_POST['usuario']) || empty($_POST['contrasena'])) {
         $error_login = "Debes introducir un nombre de usuario y una contraseña";
    } else {
        if (DB::verificaCliente($_POST['usuario'], $_POST['contrasena'])) {
            $_SESSION['usuario'] = $_POST['usuario'];

            // Recuperar cesta del invitado si la hay
            if (isset($_SESSION['cesta'])) {
                $_SESSION['cesta'] = $_SESSION['cesta'];
            }

            // Redirigir si venía de pagar
            if (!empty($_POST['redirigir_a_pago']) && $_POST['redirigir_a_pago'] == 1) {
                header("Location: pagar.php");
            } else {
                header("Location: productos.php");
            }
            exit();
        } else {
            $error_login = "Usuario o contraseña no válidos!";
        }
    }
}

// REGISTRO
if (isset($_POST['Registro'])) {
    if (!empty($_POST['nuevo_usuario']) && !empty($_POST['nueva_contrasena'])) {
        if (DB::verificaClienteExiste($_POST['nuevo_usuario'])) {
            $error_registro = "El usuario ya existe. Elige otro nombre.";
        } else {
            // Copiar cesta del invitado
            $cestaInvitado = $_SESSION['cesta'] ?? [];

            DB::insertarUsuario($_POST['nuevo_usuario'], $_POST['nueva_contrasena']);
            $_SESSION['usuario'] = $_POST['nuevo_usuario'];
            $_SESSION['cesta'] = $cestaInvitado;
            $_SESSION['invitado']=false;

            // Redirigir según el flujo
            if (!empty($_POST['redirigir_a_pago']) && $_POST['redirigir_a_pago'] == 1) {
                header("Location: pagar.php");
            } else {
                header("Location: productos.php");
            }
            exit();
        }
    } else {
        $error_registro = "Debes introducir usuario y contraseña para registrarte";
    }
}
?>

<?php
$fondos = ['fondopanes1.png', 'fondopanes2.png', 'fondopanes3.png', 'comprapan.png', 'cestafondo.png'];
$fondoAleatorio = $fondos[array_rand($fondos)];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Acceso a TodoMiga</title>
    <link href="tienda.css" rel="stylesheet" type="text/css">
</head>

<body class="pag-login" style="background-image: url('img/<?php echo $fondoAleatorio; ?>'); background-size: cover;">
    <div class="contenedor-acceso">

        <!-- Panel Login -->
        <div class="panel">
            <img src="img/logo.png" alt="TodoMiga" />
            <h1>Bienvenido a TodoMiga</h1>
            <p>Por favor, complete los datos solicitados:</p>
            <legend>Login</legend>
            <form method="post" action="invitado.php">
                <input type="hidden" name="redirigir_a_pago" value="<?php echo isset($_POST['redirigir_a_pago']) ? 1 : 0; ?>">
                <label for="usuario">Usuario:</label><br>
                <input type="text" name="usuario" id="usuario" required><br>

                <label for="contrasena">Contraseña:</label><br>
                <input type="password" name="contrasena" id="contrasena" required><br>

                <input type="submit" class="boton-verde" name="Entrar" value="Entrar">
            </form>

            <form method="post" action="productos.php">
               <!-- <button type="submit" name="entrar_invitado" class="invitado-btn"></button>-->
            </form>

            <?php if (isset($error_login)) echo "<p class='error'>$error_login</p>"; ?>

        </div>

        <!-- Panel Registro -->
        <div class="panel">
            <img src="img/logo.png" alt="TodoMiga" />
            <h1>Registro en TodoMiga</h1>
            <p>Por favor, complete el formulario a continuación:</p>
            <legend>Registro</legend>
            <form method="post" action="invitado.php">
                <input type="hidden" name="redirigir_a_pago" value="<?php echo isset($_POST['redirigir_a_pago']) ? 1 : 0; ?>">

                <label for="nuevo_usuario">Usuario:</label><br>
                <input type="text" name="nuevo_usuario" id="nuevo_usuario" required><br>

                <label for="nueva_contrasena">Contraseña:</label><br>
                <input type="password" name="nueva_contrasena" id="nueva_contrasena" required><br>

                <input type="submit" class="red-button" name="Registro" value="Registro">
                <a href="index.php" class="red-button">Volver</a>
            </form>

           <?php if (isset($error_registro)) echo "<p class='error'>$error_registro</p>"; ?>

        </div>
    </div>
</body>
</html>
