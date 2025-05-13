<?php
require_once('include/DB.php');
require_once('include/Producto.php');

class CestaCompra {
    // Cambiamos productos para almacenar el código del producto y su cantidad
    protected $productos = array();
    
    // Introduce un nuevo artículo en la cesta de la compra
    public function nuevo_articulo($cod_producto) {
        if (isset($this->productos[$cod_producto])) {
            // Si el producto ya está en la cesta, aumentamos la cantidad
            $this->productos[$cod_producto]['cantidad']++;
        } else {
            // Si es un nuevo producto, lo añadimos con cantidad 1
            $producto = DB::obtieneProducto($cod_producto);
            $this->productos[$cod_producto] = array('producto' => $producto, 'cantidad' => 1);
        }
    }
    
    // Obtiene los artículos en la cesta (devolviendo el array con productos y cantidades)
    public function get_productos() { return $this->productos; }
    
    // Obtiene el coste total de los artículos en la cesta
    public function get_coste() {
        $coste = 0;
        foreach($this->productos as $item) {
            $coste += $item['producto']->getprecio() * $item['cantidad'];
        }
        return $coste;
    }
    
    // Devuelve true si la cesta está vacía
    public function vacia() {
        return count($this->productos) == 0;
    }
    
    // Guarda la cesta de la compra en la sesión del usuario
    public function guarda_cesta() {
        $_SESSION['cesta'] = $this;
    }
    
    // Recupera la cesta de la compra almacenada en la sesión del usuario
    public static function carga_cesta() {
        if (!isset($_SESSION['cesta'])) return new CestaCompra();
        else return ($_SESSION['cesta']);
    }
    
    // Muestra el HTML de la cesta de la compra, con todos los productos y cantidades
  
    public function muestra() {
        if (count($this->productos) == 0) {
            print "<p>Cesta vacía</p>";
        } else {
            foreach ($this->productos as $item) {
                echo "<p>";
                $item['producto']->muestraConCantidad($item['cantidad']);
                echo "<form action='productos.php' method='post'>";
                echo "<input type='hidden' name='cod_producto' value='" . htmlspecialchars($item['producto']->getcodproducto()) . "'/>";
                echo "<input type='submit' name='quitar' value='Quitar'/>";
                echo "</form></p>";
            }
        }
    }
    public function eliminaProducto($cod_producto) {
        foreach ($this->productos as $key => $item) {
            if ($item['producto']->getcodproducto() == $cod_producto ) {
                if($this->productos[$key]['cantidad']==1){
                    unset($this->productos[$key]);
                    break;
                }else{
                    $this->productos[$key]['cantidad']--;
                    break;
                }
                
            }
        }
        // Reindexar el array y guardar cambios en la sesión
        $this->productos = array_values($this->productos);
        $this->guarda_cesta();
}
}


?>
