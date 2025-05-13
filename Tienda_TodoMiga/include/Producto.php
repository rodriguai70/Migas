<?php
class Producto {
    protected $cod_producto;
    protected $nombre_prod;
    protected $precio;
    protected $categoria;
    protected $cantidad;
    protected $imagen;
    
    public function getcodproducto() {return $this->cod_producto; }
    public function getnombreprod() {return $this->nombre_prod; }
    public function getprecio() {return $this->precio; }
    public function getcategoria() {return $this->categoria; }
    public function getcantidad() {return $this->cantidad; }
    public function getimagen() {return $this->imagen; }
        
    public function muestra() { print "<p>" . $this->cod_producto . "</p>"; }
    
    public function __construct($row) {
        $this->cod_producto = $row['cod_producto'];
        $this->nombre_prod = $row['nombre_prod'];
        $this->precio = $row['precio'];
        $this->categoria = $row['cod_categoria'];        
        $this->imagen = $row['imagen_prod'] ?? null; 
        
    }

    public function muestraConCantidad($cantidad) {
        $this->$cantidad=$cantidad;
        echo "<p>" . $this->getnombreprod() . " x ". $cantidad . "</p>";
      }

   
}
?>
