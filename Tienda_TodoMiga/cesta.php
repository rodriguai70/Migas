<?php
require_once('include/CestaCompra.php');
require_once('include/Producto.php');

// Recuperamos la información de la sesión
session_start();

// Verificamos si el usuario es autenticado o invitado
if (!isset($_SESSION['usuario']) && !isset($_SESSION['invitado'])) {
    die("Error - debe <a href='login.php'>identificarse</a>.<br />");
}

// Cargamos la cesta (desde sesión o base de datos)
if (isset($_SESSION['invitado'])) {
    // Cesta desde sesión
    if (!isset($_SESSION['cesta'])) {
        $_SESSION['cesta'] = new CestaCompra();
    }
    $cesta = $_SESSION['cesta'];
} else {
    // Cesta desde base de datos
    $cesta = CestaCompra::carga_cesta();
}

function listaProductos($productos) {
  $coste = 0;

  foreach ($productos as $item) {
      $producto = $item['producto'];
      $cantidad = $item['cantidad'];
      $precio_unitario = number_format($producto->getprecio(), 2);

      echo "<p>";
      echo "<strong>Código:</strong> " . htmlspecialchars($producto->getcodproducto()) . " &nbsp;&nbsp; ";
      echo "<strong>" . htmlspecialchars($producto->getnombreprod()) . ":</strong> ";
      echo $cantidad . " x " . $precio_unitario . " €/unidad";
      echo "</p>";

      $coste += $producto->getprecio() * $cantidad;
  }

  echo "<hr />";
  echo "<p><strong>Precio total:</strong> " . number_format($coste, 2) . " €</p>";
 
  if($coste>0){//hay productos en la cesta, procedemos a pagar
    // Si es invitado, redirigir a invitado al hacer clic en Pagar
    //mostrar por pantalla el estado de $_session['invitado']
   
    if ($_SESSION['invitado']==1) {
        echo "<form action='invitado.php' method='post'>";
        echo "<input type='hidden' name='redirigir_a_pago' value='1' />";

        echo "<form action='pagar.php' method='post'>";
        echo "<input type='submit' name='pagar' value='Pagar'/>";
        echo "</form>";
    }else {
        // Usuario registrado → ir a pagar.php
        echo "<form action='pagar.php' method='post'>";
        echo "<input type='submit' name='pagar' value='Pagar'/>";
        echo "</form>";
    } 
  }else{//no hay productos en la cesta, msj de error
    echo "Agregue un producto a la cesta";


  }
}
// Guardar el total y los productos en cookies (válido para invitados y usuarios)
$total = 0;
$productos = $cesta->get_productos();
$codigos = [];

foreach ($productos as $item) {
    $producto = $item['producto'];
    $cantidad = $item['cantidad'];
    $total += $producto->getprecio() * $cantidad;
    $codigos[] = $producto->getcodproducto();
}

// Guardar cookies por 1 día
setcookie("total_cesta", number_format($total, 2), time() + 86400, "/");
setcookie("productos_cesta", implode(",", $codigos), time() + 86400, "/");
setcookie("ultima_visita", date("Y-m-d H:i:s"), time() + 86400, "/");// Guardar fecha de última visita

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Cesta de la Compra</title>
  <link href="tienda.css" rel="stylesheet" type="text/css">
</head>

<body class="pagcesta">

<div id="contenedor">
  <?php
if (isset($_COOKIE['total_cesta'])) {
    echo "<p><strong>Última cesta:</strong> " . htmlspecialchars($_COOKIE['total_cesta']) . " €</p>";
}
if (isset($_COOKIE['ultima_visita'])) {
    echo "<p><strong>Última visita:</strong> " . htmlspecialchars($_COOKIE['ultima_visita']) . "</p>";
}
?>
  <div id="encabezado">
    <h1>Cesta de la compra</h1>
  </div>
  <div id="productos">
<?php listaProductos($cesta->get_productos()); ?>
  </div>
  <form action="productos.php" method="get">
    <input type="submit" value="Volver a productos" />
</form>

  <br class="divisor" />
  <div id="pie">
    <form action='logoff.php' method='post'>
        <input type='submit' name='desconectar' value='Desconectar usuario <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : "invitado"; ?>'/>
    </form>        
  </div>
</div>
</body>
</html>
