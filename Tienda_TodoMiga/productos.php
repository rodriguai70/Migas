<?php
require_once('include/DB.php');
require_once('include/CestaCompra.php');

session_start();
// Invitado por parámetro
if (isset($_GET['invitado']) && $_GET['invitado'] == 1) {
  $_SESSION['invitado'] = true;
}

// Verificar acceso
if (!isset($_SESSION['usuario']) && !isset($_SESSION['invitado'])) {
  die("Error - debe <a href='login.php'>identificarse</a>.<br />");
}

// Cargar la cesta adecuada
if (isset($_SESSION['invitado'])) {
  // INVITADO → sesión
  if (!isset($_SESSION['cesta'])) {
    $_SESSION['cesta'] = new CestaCompra();
  }
  $cesta = $_SESSION['cesta'];

  // Cookie temporal de respaldo
  setcookie('cesta_invitado', serialize($cesta->get_productos()), time() + 7 * 24 * 60 * 60, "/");
} else {
  // USUARIO → base de datos
  $cesta = CestaCompra::carga_cesta();
}

if (isset($_POST['vaciar'])) {
  if (isset($_SESSION['invitado'])) {
    unset($_SESSION['cesta']);
    setcookie('cesta_invitado', '', time() - 3600, "/"); // Borra cookie
  } else {
    DB::vaciarCesta($_SESSION['usuario']);
  }
  $cesta = new CestaCompra();
}



if (isset($_POST['enviar']) && isset($_POST['cod_producto'])) {
  $cesta->nuevo_articulo($_POST['cod_producto']);

  if (isset($_SESSION['invitado'])) {
    $_SESSION['cesta'] = $cesta; // Actualiza la sesión
    setcookie('cesta_invitado', serialize($cesta->get_productos()), time() + 7 * 24 * 60 * 60, "/");
  } else {
    $cesta->guarda_cesta(); // Guarda en sesión solo por seguridad
    DB::anadirProductoCesta($_POST['cod_producto'], $_SESSION['usuario']);
  }
}

if (isset($_POST['quitar'])) {
  $cesta->eliminaProducto($_POST['cod_producto']);

  if (!isset($_SESSION['invitado'])) {
    DB::quitarProductoCesta($_SESSION['usuario'], $_POST['cod_producto']);
  }
  header("Location: productos.php");
  exit();
}


function creaFormularioProductos($nomCateg = null)
{
  if ($nomCateg) {
    $codCateg = DB::getCodCategoria($nomCateg);
    $productos = DB::obtieneProductos($codCateg);
  } else {
    $productos = DB::obtieneTodosProductos();
  }

  echo "<div class='contenedor-tabla'>";
  echo "<h2>Productos disponibles</h2>";
  echo "<table class='tablaProductos'>";
  echo "<thead>
          <tr>
              <th>IMAGEN</th>    
              <th>CÓDIGO</th>
              <th>NOMBRE</th>
              <th>PRECIO</th>
              <th>AÑADIR A CESTA</th>
          </tr>
        </thead>";
  echo "<tbody>";

  foreach ($productos as $p) {
    $cod_producto = htmlspecialchars($p->getcodproducto());
    $nombre_prod = htmlspecialchars($p->getnombreprod());
    $precio = htmlspecialchars($p->getprecio());
    $rutaImagen = htmlspecialchars($p->getImagen());

    echo "<tr>";
    echo "<td><img src='$rutaImagen' alt='$nombre_prod' width='80' onerror=\"this.src='imagenes/default.png'\"/></td>";
    echo "<td>$cod_producto</td>";
    echo "<td>$nombre_prod</td>";
    echo "<td>$precio €</td>";
    echo "<td>
            <form action='productos.php' method='post'>
  <input type='hidden' name='cod_producto' value='$cod_producto'/>
  <input type='hidden' name='cod_categoria' value='" . (isset($_POST['cod_categoria']) ? htmlspecialchars($_POST['cod_categoria']) : 'todas') . "'/>
  <input type='submit' name='enviar' value='Añadir'/>
</form>
          </td>";
    echo "</tr>";
  }

  echo "</tbody>";
  echo "</table>";
  echo "</div>";
}

function muestraCestaCompra($cesta)
{
  echo "<h3><img src='cesta.png' alt='Cesta' width='24' height='21'> Cesta</h3>";
  echo "<hr />";
  $cesta->muestra();
  echo "<form id='vaciar' action='productos.php' method='post'>";
  echo "<input type='submit' name='vaciar' value='Vaciar Cesta' ";
  if ($cesta->vacia()) echo "disabled='true'";
  echo "/></form>";
  echo "<form id='comprar' action='cesta.php' method='post'>";
  echo "<input type='submit' name='comprar' value='Comprar' ";
  if ($cesta->vacia()) echo "disabled='true'";
  echo "/></form>";
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Listado de Productos</title>
  <link href="tienda.css" rel="stylesheet" type="text/css">
</head>

<body class="pagproductos">
  <div id="contenedor">
    <div id="encabezado">
      <h1>Listado de productos</h1>


      <form method="post" action="productos.php" class="formCategoria">
        <label for="cod_categoria">Categoría:</label>
        <select id="cod_categoria" name="cod_categoria">
          <option value="todas" <?php if (!isset($_POST['cod_categoria']) || $_POST['cod_categoria'] === 'todas') echo "selected"; ?>>Todos los productos</option>
          <?php
          $categorias = DB::getCategorias();
          if (!empty($categorias)) {
            foreach ($categorias as $categoria) {
              echo "<option value=\"" . htmlspecialchars($categoria) . "\"";
              if (isset($_POST['cod_categoria']) && $_POST['cod_categoria'] === $categoria) {
                echo " selected";
              }
              echo ">" . $categoria . "</option>";
            }
          } else {
            echo "<option disabled>No hay categorías disponibles</option>";
          }
          ?>
        </select>
        <input type="submit" name="mostrar" value="Mostrar" />
      </form>

      <br><br>
    </div>

    <div class="cesta" id="cesta">
      <?php muestraCestaCompra($cesta); ?>
    </div>

    <div class="productos" id="productos">
      <?php
      if (isset($_POST['mostrar']) && isset($_POST['cod_categoria'])) {
        if ($_POST['cod_categoria'] === 'todas') {
          creaFormularioProductos(); // sin filtro
        } else {
          creaFormularioProductos($_POST['cod_categoria']); // con filtro
        }
      } elseif (isset($_POST['enviar']) && isset($_POST['cod_categoria'])) {
        // caso especial: se ha añadido un producto
        if ($_POST['cod_categoria'] === 'todas') {
          creaFormularioProductos();
        } else {
          creaFormularioProductos($_POST['cod_categoria']);
        }
      } else {
        creaFormularioProductos();
      }

      ?>
    </div>

    <br class="divisor" />
    <div id="pie">
      <form action='logoff.php' method='post'>
        <input type='submit' name='desconectar' value='Desconectar <?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : "invitado"; ?>' />
      </form>

    </div>
</body>

</html>