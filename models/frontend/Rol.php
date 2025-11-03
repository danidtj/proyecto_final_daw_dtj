<?php

namespace ModelsFrontend;

use Config\DB;
use PDO;
use PDOException;
use Exception;

require_once dirname(__DIR__, 2) . '/config/DB.php';

class Rol
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = DB::getInstance()->getConnection();
    }

    //Método para obtener el id_rol en función del nombre_rol
    public function obtenerIdRolPorNombre(string $nombre_rol)
    {
        try {
            $sql = "SELECT id_rol FROM roles WHERE nombre_rol = :nombre_rol";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':nombre_rol', $nombre_rol, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['id_rol'] : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al obtener el ID del rol: " . $e->getMessage();
            return null;
        }
    }
    
    
}
