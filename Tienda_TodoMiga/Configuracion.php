<?php



class Configuracion implements Serializable {

    private $servidor;
    private $baseDatos;
    private $usuario;
    private $password;

    function getServidor() {
        return $this->servidor;
    }

    function getBaseDatos() {
        return $this->baseDatos;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getPassword() {
        return $this->password;
    }

    function setServidor($servidor) {
        $this->servidor = $servidor;
    }

    function setBaseDatos($baseDatos) {
        $this->baseDatos = $baseDatos;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setPassword($password) {
        $this->password = $password;
    }

   

    public function serialize() {
        return serialize([$this->servidor, $this->baseDatos, $this->usuario, $this->password]);
    }

    

    public function unserialize($serialized) {
        list(
                $this->servidor,
                $this->baseDatos,
                $this->usuario,
                $this->password
                ) = unserialize($serialized);
    }

}
