<?php

spl_autoload_register(function ($clase) {
    include  $clase . '.php';
});




$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");


$servidor = "localhost";
$baseDatos = "todomiga";
$dns = "mysql:host=$servidor;dbname=$baseDatos";
$usuario = "root";
$contrasena = "";

try {
    $todomiga = new PDO($dns, $usuario, $contrasena, $opc);
    $config = new Configuracion();
    $config->setServidor("localhost");
    $config->setBaseDatos("todomiga");
    $config->setUsuario("root");
    $config->setPassword("");
    session_start();

    $_SESSION['configura'] = $config->serialize($config);
    header("Location: login.php");
} catch (PDOException $e) {
    $error = "Datos erróneos de conexión";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Configuración</title>
    <link href="tienda.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div id='login'>
        <form action='index.php' method='post'>
           
        </form>
    </div>
</body>

</html>