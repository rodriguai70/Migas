<?php

spl_autoload_register(function ($clase) {
    include  $clase . '.php';
});




$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");


$servidor = "sql302.infinityfree.com";
$baseDatos = "if0_38869507_todomiga";
$dns = "mysql:host=$servidor;dbname=$baseDatos";
$usuario = "if0_38869507";
$contrasena = "gqIo7sK34bD3Hr";

try {
    $todomiga = new PDO($dns, $usuario, $contrasena, $opc);
    $config = new Configuracion();
    $config->setServidor("sql302.infinityfree.com");
    $config->setBaseDatos("if0_38869507_todomiga");
    $config->setUsuario("if0_38869507");
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