<?php

namespace ModelsFrontend;

use Config\DB;
use PDO;
use PDOException;
use Exception;

require_once dirname(__DIR__, 2) . '/config/DB.php';

class Usuario
{
    private int $id_usuario;
    private int $id_rol;
    private string $nombre_usuario;
    private string $apellidos_usuario;
    private string $password_usuario;
    private string $email_usuario;
    private string $telefono_usuario;

    public function __construct($nombre_usuario, $apellidos_usuario, $password_usuario, $email_usuario, $telefono_usuario)
    {
        $this->nombre_usuario = $nombre_usuario;
        $this->apellidos_usuario = $apellidos_usuario;
        $this->password_usuario = $password_usuario;
        $this->email_usuario = $email_usuario;
        $this->telefono_usuario = $telefono_usuario;
    }

    //Metodos getters
    public function getIdUsuario(): int
    {
        return $this->id_usuario;
    }

    public function getIdRol(): int
    {
        return $this->id_rol;
    }

    public function getNombreUsuario(): string
    {
        return $this->nombre_usuario;
    }

    public function getApellidosUsuario(): string
    {
        return $this->apellidos_usuario;
    }

    public function getPasswordUsuario(): string
    {
        return $this->password_usuario;
    }

    public function getEmailUsuario(): string
    {
        return $this->email_usuario;
    }

    public function getTelefonoUsuario(): string
    {
        return $this->telefono_usuario;
    }

    /*
     * Función que devuelve los el valor de los atributos serializados.
     * Esto es útil para el almacenamiento de valores en PHP sin perder su tipo y estructura.
     */

    /*public function serialize() {
        return serialize([$this->usuario, $this->password, $this->nombre, $this->apellidos]);
    }*/

    /*
     * Crea un valor PHP a partir de una representación almacenada, es decir, crea un objeto de la clase
     */

    /*public function unserialize($serialized) {
        list(
                $this->usuario,
                $this->password,
                $this->nombre,
                $this->apellidos
            ) = unserialize($serialized);
    }*/

}
