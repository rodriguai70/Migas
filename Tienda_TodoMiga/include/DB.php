<?php
require_once('include/Producto.php');
spl_autoload_register(function ($clase) {
    include  $clase . '.php';
});

class DB
{

    public static function insertarUsuario($usu, $password)
    {
        try {

            $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dsn = "mysql:host=sql302.infinityfree.com;dbname=if0_38869507_todomiga";
            $usuario = 'if0_38869507';
            $contrasena = 'gqIo7sK34bD3Hr';

            $todomiga = new PDO($dsn, $usuario, $contrasena, $opc);
            $resultado = false;
            if ($todomiga != false) {

                // Encriptamos la contraseña antes de insertarla
                $passwordEncriptada = password_hash($password, PASSWORD_DEFAULT);
                $consulta = $todomiga->prepare("INSERT INTO usuarios (usuario,contrasena) "
                    . "VALUES ('" . $usu . "','" . $passwordEncriptada . "')  ");

                $resultado = $consulta->execute();
                echo "insertado usuario correctamente";
            } else {
                echo "Error al conectar a la base de datos";
            }
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
            return false;
        }
        return $resultado;
    }
    protected static function ejecutaConsulta($sql)
    {
        $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $dsn = "mysql:host=sql302.infinityfree.com;dbname=if0_38869507_todomiga";
        $usuario = 'if0_38869507';
        $contrasena = 'gqIo7sK34bD3Hr';

        $todomiga = new PDO($dsn, $usuario, $contrasena, $opc);
        $resultado = null;
        if (isset($todomiga)) $resultado = $todomiga->query($sql);
        return $resultado;
    }


    public static function getCodCategoria($nomCateg)
    {
        $sql = "SELECT cod_categoria FROM categoria WHERE nombre_cat='" . $nomCateg . "';";
        $resultado = self::ejecutaConsulta($sql);
        $codCat = -1;



        if ($resultado) {
            // Iterar sobre cada fila de resultados
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {

                $codCat = $row['cod_categoria'];
            }
        }


        return $codCat;
    }

    public static function obtieneProductos($categ)
    {
        $sql = "SELECT cod_producto, nombre_prod, cod_categoria, precio, imagen_prod FROM producto WHERE cod_categoria='" . $categ . "';";
        $resultado = self::ejecutaConsulta($sql);
        $productos = array();


        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $productos[] = new Producto($row);
                $row = $resultado->fetch();
            }
        }

        return $productos;
    }

    public static function obtieneTodosProductos()
    {
        $sql = "SELECT cod_producto, nombre_prod, cod_categoria, precio, imagen_prod FROM producto;";
        $resultado = self::ejecutaConsulta($sql);
        $productos = array();


        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $productos[] = new Producto($row);
                $row = $resultado->fetch();
            }
        }

        return $productos;
    }

    public static function obtieneProducto($cod_producto)
    {
        $sql = "SELECT cod_producto, nombre_prod, cod_categoria, precio FROM producto";
        $sql .= " WHERE cod_producto='" . $cod_producto . "'";
        $resultado = self::ejecutaConsulta($sql);
        $producto = null;


        if (isset($resultado)) {
            $row = $resultado->fetch();
            $producto = new Producto($row);
        }

        return $producto;
    }

 

    public static function verificaCliente($usu, $password)
{
    try {
        $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $dsn = "mysql:host=sql302.infinityfree.com;dbname=todomiga";
        $usuario = 'if0_38869507';
        $contrasena = 'gqIo7sK34bD3Hr';

        $todomiga = new PDO($dsn, $usuario, $contrasena, $opc);

        $consulta = $todomiga->prepare("SELECT contrasena FROM usuarios WHERE usuario = :usuario");
        $consulta->bindParam(':usuario', $usu);
        $consulta->execute();

        if ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $hashAlmacenado = $fila['contrasena'];
            // Compara la contraseña introducida con el hash de la base de datos
            return password_verify($password, $hashAlmacenado);
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    return false;
}

    public static function verificaClienteExiste($nombre)
    {
        $sql = "SELECT usuario FROM usuarios ";
        $sql .= "WHERE usuario='$nombre'";
        $sql .= ";";
        $resultado = self::ejecutaConsulta($sql);
        $verificado = false;

        if (isset($resultado)) {
            $fila = $resultado->fetch();
            if ($fila !== false) $verificado = true;
        }
        return $verificado;
    }

    public static function anadirProductoCesta($codprod, $usuario)
{
    try {
        $dsn = "mysql:host=sql302.infinityfree.com;dbname=if0_38869507_todomiga";
        $pdo = new PDO($dsn, "if0_38869507", "", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        // Comprobamos si ya existe ese producto para el usuario
        $consulta = $pdo->prepare("SELECT unidades FROM cesta WHERE usuario = ? AND cod_producto = ?");
        $consulta->execute([$usuario, $codprod]);

        if ($consulta->rowCount() > 0) {
            // Ya existe: actualizamos la cantidad (+1)
            $actualiza = $pdo->prepare("UPDATE cesta SET unidades = unidades + 1 WHERE usuario = ? AND cod_producto = ?");
            $actualiza->execute([$usuario, $codprod]);
        } else {
            // No existe: lo insertamos con cantidad 1
            $inserta = $pdo->prepare("INSERT INTO cesta (usuario, cod_producto, unidades) VALUES (?, ?, 1)");
            $inserta->execute([$usuario, $codprod]);
        }
    } catch (PDOException $e) {
        echo "Error al añadir producto a la BD: " . $e->getMessage();
    }
}


public static function insertarProductoEnCesta($codprod, $usuario,$cantidad)
{
   
        $dsn = "mysql:host=sql302.infinityfree.com;dbname=if0_38869507_todomiga";
        $pdo = new PDO($dsn, "if0_38869507", "", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

       
       
            // No existe: lo insertamos con cantidad 1
            $inserta = $pdo->prepare("INSERT INTO cesta (usuario, cod_producto, unidades) VALUES (?, ?, ?)");
            $inserta->execute([$usuario, $codprod, $cantidad]);
        
   
}


public static function recuperarCestaBD($usuario) {
   

    $sql = "SELECT cod_producto, unidades FROM cesta WHERE usuario = '".$usuario."'";
    $resultado = self::ejecutaConsulta($sql);
    $productos = array();
    while ($fila = $resultado->fetch()) {
        $productos[] = array('cod_producto' =>$fila['cod_producto'],'unidades' =>$fila['unidades']);
    }
    return $productos; 

}



public static function getCategorias()
    {
        $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $dsn = "mysql:host=sql302.infinityfree.com;dbname=if0_38869507_todomiga";
        $usuario = 'if0_38869507';
        $contrasena = 'gqIo7sK34bD3Hr';

        $todomiga = new PDO($dsn, $usuario, $contrasena, $opc);
        $resultado = null;
        try {
            $sql = "SELECT nombre_cat FROM categoria;";
            $resultado = self::ejecutaConsulta($sql);
            $categorias = array();

            if ($resultado) {
                while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    $categorias[] = $row['nombre_cat'];
                }
            }


            return $categorias;;
        } catch (PDOException $e) {
            echo "ERROR - No se pudieron obtener las categorias: " . $e->getMessage();
        }
    }


    public static function vaciarCesta($usuario)
{
    try {
        $dsn = "mysql:host=sql302.infinityfree.com;dbname=if0_38869507_todomiga";
        $pdo = new PDO($dsn, "if0_38869507", "", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        $vaciar = $pdo->prepare("DELETE FROM cesta WHERE usuario = ? ");
        $vaciar->execute([$usuario]);
        
    } catch (PDOException $e) {
        echo "Error al eliminar producto de la cesta: " . $e->getMessage();
    }
}



    public static function quitarProductoCesta($usuario, $codprod)
{
    try {
        $dsn = "mysql:host=sql302.infinityfree.com;dbname=if0_38869507_todomiga";
        $pdo = new PDO($dsn, "if0_38869507", "", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        // Comprobamos si ya existe ese producto para el usuario
        $consulta = $pdo->prepare("SELECT unidades FROM cesta WHERE usuario = ? AND cod_producto = ?");
        $consulta->execute([$usuario, $codprod]);
        //comprobamos las unidades devueltas por la consulta
        $unidades = $consulta->fetchColumn();
        if ($unidades > 1) {
            //si hay unidades, las restamos

        
            
            $actualiza = $pdo->prepare("UPDATE cesta SET unidades = unidades - 1 WHERE usuario = ? AND cod_producto = ?");
            $actualiza->execute([$usuario, $codprod]);
        } else {
            //cantidad= 1, entonces eliminamos esa linea de  la cesta
            $vaciar = $pdo->prepare("DELETE FROM cesta WHERE usuario = ? AND cod_producto = ?");   
            $vaciar->execute([$usuario, $codprod]);    
        }
        
    } catch (PDOException $e) {
        echo "Error al eliminar producto de la cesta: " . $e->getMessage();
    }
}


}


   


