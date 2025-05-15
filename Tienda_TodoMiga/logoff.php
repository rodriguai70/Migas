<?php
session_start();


require_once('include/Producto.php');
require_once('include/DB.php');
require_once('include/CestaCompra.php');

// Verificamos si hay una cesta activa
if (isset($_SESSION['cesta']) && $_SESSION['cesta'] instanceof CestaCompra) {
    $cesta = $_SESSION['cesta'];
    $productos = $cesta->get_productos(); 
    setcookie('cesta_temporal', serialize($productos), time() + 7*24*60*60, "/"); // Cookie por 1 semana
}

// Destruimos la sesión
session_unset();
session_destroy();

// Redirigir al login
header("Location: login.php");
exit();
?>
