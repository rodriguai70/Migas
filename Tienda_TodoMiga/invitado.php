<?php
session_start();
require_once('include/DB.php');

if (isset($_POST['Entrar'])) {

    if (empty($_POST['usuario']) || empty($_POST['password']))
        $error = "Debes introducir un nombre de usuario y una contraseña";
    else {
        // Comprobamos las credenciales con la base de datos
        if (DB::verificaCliente($_POST['usuario'], $_POST['password'])) {
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

    <style>
    .contenedor-acceso {
            display: flex;
            justify-content: center;
            gap: 40px;
            padding: 20px;
        }

        .panel {
            border: 3px solid #ccc;
            border-radius: 12px;
            padding: 20px;
            width: 300px;
            background-color: #fff5e1;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .panel img {
            display: block;
            margin: 0 auto 10px;
            width: 150px;
        }

        .panel h2 {
            text-align: center;
            color: #b85c00;
        }

        .panel input[type="text"],
        .panel input[type="password"] {
            width: 100%;
            padding: 6px;
            margin: 5px 0 15px;
            border-radius: 5px;
            border: 1px solid #aaa;
        }

        .boton-verde {
            background-color: green;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .boton-rojo {
            background-color: red;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .invitado-btn {
            display: block;
            margin: 10px auto 0;
            text-align: center;
            font-weight: bold;
            color: blue;
            text-decoration: underline;
            background: none;
            border: none;
            cursor: pointer;
        }
        </style>
    
</head>




<body>
    <div class="contenedor-acceso">

        <!-- Login -->
        <div class="panel">
            <img src="imagenes/logo.png" alt="TodoMiga" />
            <h2>Login</h2>
            <form method="post" action="invitado.php">
                <label for="usuario">Usuario:</label><br>
                <input type="text" name="usuario" id="usuario" required><br>

                <label for="contrasena">Contraseña:</label><br>
                <input type="password" name="contrasena" id="contrasena" required><br>

                <input type="submit" class="boton-verde" value="Entrar">
            </form>

            <form method="post" action="invitado.php">
                <button type="submit" name="entrar_invitado" class="invitado-btn">Entrar como Invitado</button>
            </form>
        </div>

        <!-- Registro -->
        <div class="panel">
            <img src="imagenes/logo.png" alt="TodoMiga" />
            <h2>Registro</h2>
            <form method="post" action="invitado.php">
                <label for="nuevo_usuario">Usuario:</label><br>
                <input type="text" name="nuevo_usuario" id="nuevo_usuario" required><br>

                <label for="nueva_contrasena">Contraseña:</label><br>
                <input type="password" name="nueva_contrasena" id="nueva_contrasena" required><br>

                <input type="submit" class="boton-verde" value="Registro">
                <a href="index.php"><button type="button" class="boton-rojo">Volver</button></a>
            </form>
        </div>
<div id="pie">
       <form action='logoff.php' method='post'>
  <input type='submit' name='desconectar' value='Desconectar <?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : "invitado"; ?>' />
</form>
    </div>
    </div>
</body>

</html>
