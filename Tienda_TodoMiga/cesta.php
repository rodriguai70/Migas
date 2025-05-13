<?php
require_once('include/CestaCompra.php');
require_once('include/Producto.php');

// Recuperamos la información de la sesión
session_start();

// Y comprobamos que el usuario se haya autentificado
if (!isset($_SESSION['usuario'])) 
    die("Error - debe <a href='login.php'>identificarse</a>.<br />");

// Recuperamos la cesta de la compra
$cesta = CestaCompra::carga_cesta();


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

  echo "<form action='pagar.php' method='post'>";
  echo "<input type='submit' name='pagar' value='Pagar'/>";
  echo "</form>";
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Cesta de la Compra</title>
  <link href="tienda.css" rel="stylesheet" type="text/css">
</head>

<body class="pagcesta">

<div id="contenedor">
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
        <input type='submit' name='desconectar' value='Desconectar usuario <?php echo $_SESSION['usuario']; ?>'/>
    </form>        
  </div>
</div>
</body>
</html>
