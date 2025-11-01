<?php

namespace ModelsFrontend;

use Config\DB;
use PDO;
use PDOException;
use Exception;

require_once dirname(__DIR__, 2) . '/config/DB.php';

class Mesa
{

    private PDO $connection;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->connection = DB::getInstance()->getConnection();
    }

    //Método para obtener las mesas con disponibilidad =1 de la DB en base a la fecha de reserva, la hora de la reserva y el número de comensales
    public function obtenerMesasDisponibles($fecha_reserva, $hora_reserva, $numero_comensales)
    {
        try {

            $sql = "SELECT numero_mesa FROM mesas WHERE disponibilidad_mesa = 1 AND capacidad_mesa >= :numero_comensales AND numero_mesa NOT IN (
                        SELECT numero_mesa FROM reservas 
                        WHERE fecha_reserva = :fecha_reserva 
                        AND hora_reserva = :hora_reserva
                    )";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':numero_comensales', $numero_comensales);
            $stmt->bindParam(':fecha_reserva', $fecha_reserva);
            $stmt->bindParam(':hora_reserva', $hora_reserva);

            $stmt->execute();
            //Almacena en un array solamente los números de las mesas disponibles
            $mesasDisponibles = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'numero_mesa');

            return $mesasDisponibles;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener las mesas disponibles: " . $e->getMessage());
        }
    }

    public function obtenerDisponibilidadMesa($id_mesa)
    {
        try {
            $sql = "SELECT disponibilidad_mesa FROM mesas WHERE numero_mesa = :id_mesa";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_mesa', $id_mesa);

            $stmt->execute();

            $disponibilidad = $stmt->fetchColumn();

            if ($disponibilidad === 0) {
                return false;
            } else {
                return true;
            }
        } catch (PDOException $e) {
            throw new Exception("Error al obtener la disponibilidad de la mesa: " . $e->getMessage());
        }
    }
}
