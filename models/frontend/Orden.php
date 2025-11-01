<?php

namespace ModelsFrontend;

use Config\DB;
use PDO;
use PDOException;
use Exception;

require_once dirname(__DIR__, 2) . '/config/DB.php';

class Orden
{

    private PDO $connection;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->connection = DB::getInstance()->getConnection();
    }


    public function crearOrden($codigo_reserva, $id_usuario, $numero_mesa)
    {
        try {

            $sql = "INSERT INTO ordenes (codigo_reserva, id_usuario, numero_mesa, estatus, fecha_creacion, fecha_cierre)
        VALUES (:codigo_reserva, :id_usuario, :numero_mesa, 'ABIERTA', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 DAY))";


            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':codigo_reserva', $codigo_reserva);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':numero_mesa', $numero_mesa);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al crear la orden: " . $e->getMessage());
        }
    }
}
