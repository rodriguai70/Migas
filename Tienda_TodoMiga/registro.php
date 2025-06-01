<?php
session_start();
require_once('include/DB.php');



// Comprobamos si ya se ha enviado el formulario
if (isset($_POST['Registro'])) {

    if (empty($_POST['usuario']) || empty($_POST['password']))
        $error = "Debes introducir un nombre de usuario y una contraseña";
    else {
        $usuario = $_POST["usuario"] ?? '';
        $password = $_POST["password"] ?? '';
        $_SESSION['usuario'] = $usuario;
        // comprobar que el usuario con el que se quiere registrar no exista ya en la bd usuarios
        //si ya existe, msj error, usuario ya existe
        //sino, entonces insertar en la bd con ese usuario y esa contraseña (DB::insertarUsuario($_POST['usuario'],$_POST['password']))
        //, mostrar msj de usuario registrado con exito y llevarlo a productos.php
        // Verificar si el usuario ya existe en la base de datos 

        if (DB::verificaClienteExiste($usuario)) {
            $error = "El usuario ya existe";
        } else {

            if (DB::insertarUsuario($usuario, $password)) {
                $_SESSION['usuario'] = $usuario;
                header("Location: productos.php");
                exit;
            } else {
                $error = "Error al registrar el usuario";
            }
        }
    }
}

?>
<?php
$fondos = ['fondopanes1.png', 'fondopanes2.png', 'fondopanes3.png', 'comprapan.png', 'cestafondo.png'];
$fondoAleatorio = $fondos[array_rand($fondos)];
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registro | Panadería Todomiga</title>
    <link rel="stylesheet" type="text/css" href="tienda.css">
</head>

<body class="pag-panaderia" style="background-image: url('img/<?php echo $fondoAleatorio; ?>');">
<div class="registro-centrado">
<form action="registro.php" method="post" class="form-registro">
            <div class="logo">
                <img src="img/logo.png" alt="Panadería Todomiga" />
            </div>
            <h1>Bienvenido a TodoMiga</h1>
            <p>Por favor, complete el formulario a continuación:</p>

            <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
            <div class="campo">
                <label for="usuario">Usuario:</label><br>
                <input type="text" name="usuario" id="usuario" required>
            </div>
            <div class="campo">
                <label for="password">Contraseña:</label><br>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="campo">
                <input type="submit" name="Registro" value="Registro">
                <a href="index.php"><input type="button" value="Volver"></a>
            </div>

        </form>
    </div>
</body>

</html>