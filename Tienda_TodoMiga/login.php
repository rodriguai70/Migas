<?php
require_once('include/DB.php');

$error = "";
// Comprobamos si ya se ha enviado el formulario
if (isset($_POST['enviar'])) {

    if (empty($_POST['usuario']) || empty($_POST['password']))
        $error = "Debes introducir un nombre de usuario y una contraseña";
    else {
        // Comprobamos las credenciales con la base de datos
        if (DB::verificaCliente($_POST['usuario'], $_POST['password'])) {
            session_start();
            $_SESSION['usuario'] = $_POST['usuario'];
            header("Location: productos.php");
        } else {
            // Si las credenciales no son válidas, se vuelven a pedir
            $error = "Usuario o contraseña no válidos!";
        }
    }
}
?>
<?php
$fondos = ['fondopanes1.png', 'fondopanes2.png', 'fondopanes3.png', 'comprapan.png', 'cestafondo.png'];
$fondoAleatorio = $fondos[array_rand($fondos)];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title> Tienda Web</title>
    <link href="tienda.css" rel="stylesheet" type="text/css">
</head>

<body class="pag-login" style="background-image: url('img/<?php echo $fondoAleatorio; ?>'); background-size: cover;">

    <div id='login'>
        <form action='login.php' method='post'>
            <fieldset>
                <div class="logo">
                    <img src="img/logo.png" alt="Panadería Todomiga" style="display: block; margin: 0 auto; width: 100px;">
                </div>
                <h1>Bienvenido a TodoMiga</h1>
                <p>Por favor, complete los datos solicitados:</p>
                <legend>Login</legend>
                <div><span class='error'><?php echo $error; ?></span></div>
                <div class='campo'>
                    <label for='usuario'>Usuario:</label><br />
                    <input type='text' name='usuario' id='usuario' maxlength="50" /><br />
                </div>
                <div class='campo'>
                    <label for='password'>Contraseña:</label><br />
                    <input type='password' name='password' id='password' maxlength="50" /><br />
                </div>

                <div class='campo'>
                    <input type='submit' name='enviar' value='Enviar' />
                    <a href="registro.php"><button type="button">Registro</button></a>
                    <a href="productos.php?invitado=1">Entrar como Invitado</a>
                </div>
            </fieldset>
        </form>
    </div>
</body>

</html>