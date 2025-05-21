<?php
session_start();
require_once('include/DB.php');

// Si no está autenticado
if (!isset($_SESSION['usuario'])) {
    die("Debe iniciar sesión. <a href='login.php'>Volver al login</a>");
}

$mensaje = ""; // Variable para mostrar mensaje de confirmación o error

// Si se ha enviado el formulario de pago
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $titular = $_POST['titular'] ?? '';
    $tarjeta = $_POST['tarjeta'] ?? '';
    $caducidad = $_POST['caducidad'] ?? '';
    $cvc = $_POST['cvc'] ?? '';

    if (empty($titular) || empty($tarjeta) || empty($caducidad) || empty($cvc)) {
        $mensaje = "Faltan datos de pago. <a href='pagar.php'>Volver</a>";
    } else {
        // validación simple del nombre del titular
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $titular)) {
            $mensaje = "Nombre del titular inválido. <a href='pagar.php'>Volver</a>";
        } else {
            // Validar la fecha de caducidad
            $caducidad = DateTime::createFromFormat('m/y', $caducidad);
            if (!$caducidad) {
                $mensaje = "Fecha de caducidad inválida. <a href='pagar.php'>Volver</a>";
            } else {
                // Comparar si la fecha de caducidad es posterior a la fecha actual
                $currentDate = new DateTime(); // Fecha actual
                if ($caducidad <= $currentDate) {
                    $mensaje = "La fecha de caducidad debe ser posterior a la fecha actual. <a href='pagar.php'>Volver</a>";
                } else {
                    // Validar el CVC
                    if (strlen($cvc) !== 3 || !ctype_digit($cvc)) {
                        $mensaje = "CVC inválido. <a href='pagar.php'>Volver</a>";
                    } else {
                        // Lógica de pago o simulación de pago
                        unset($_SESSION['cesta']); // Vacía la cesta
                        DB::vaciarCesta($_SESSION['usuario']);
                        $mensaje = "<h2>¡Gracias por su compra, " . htmlspecialchars($_SESSION['usuario']) . "!</h2>
                        <p>El pago se ha realizado correctamente.</p>
                        <p><a href='productos.php'>Volver a la tienda</a></p>";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Pago</title>
    <link href="tienda.css" rel="stylesheet" type="text/css">
</head>

<body class="limpio">
    <div id="contenedor">
        <div id="encabezado">
            <h1>Formulario de Pago</h1>
        </div>

        <div id="productos" style="margin: 20px;">
            <!-- Si hay un mensaje (compra realizada o error), se muestra aquí -->
            <div id="mensaje">
                <?php echo $mensaje; ?>
            </div>

            <!-- Solo mostrar el formulario si no se ha enviado correctamente -->
            <?php if (empty($mensaje) || strpos($mensaje, 'Gracias') === false): ?>
                <form action="pagar.php" method="post">
                    <p>
                        <label for="titular"><strong>Nombre del titular:</strong></label><br>
                        <input type="text" name="titular" id="titular" required placeholder="Juan Pérez">
                    </p>
                    <p>
                        <label for="tarjeta"><strong>Número de tarjeta:</strong></label><br>
                        <input type="text" name="tarjeta" id="tarjeta" required pattern="\d{16}" maxlength="16" placeholder="1234 5678 9012 3456">
                    </p>
                    <p>
                        <label for="caducidad"><strong>Fecha de caducidad:</strong></label><br>
                        <input type="month" name="caducidad" id="caducidad" required>
                    </p>
                    <p>
                        <label for="cvc"><strong>CVC:</strong></label><br>
                        <input type="text" name="cvc" id="cvc" required pattern="\d{3}" maxlength="3" placeholder="123">
                    </p>
                    <p>
                        <input type="submit" value="Pagar" />
                    </p>
                </form>
            <?php endif; ?>
        </div>
 <form action="cesta.php" method="get">
    <input type="submit" value="Volver a Cesta" />
</form>
        <br class="divisor">
        <div id="pie">
            <form action="logoff.php" method="post">
                <input type="submit" name="desconectar" value="Desconectar usuario <?php echo htmlspecialchars($_SESSION['usuario']); ?>" />
            </form>
        </div>
    </div>
</body>

</html>