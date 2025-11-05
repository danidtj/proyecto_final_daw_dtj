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
    public function obtenerMesasDisponibles($fecha_reserva, $hora_inicio, $numero_comensales)
    {
        try {

            $fechaHoraInicio = strtotime("$fecha_reserva $hora_inicio");

            $calculoHoraFin = strtotime("+1 hour 30 minutes", $fechaHoraInicio);

            $hora_inicio_completa = date('Y-m-d H:i:s', $fechaHoraInicio);
            $hora_fin = date('Y-m-d H:i:s', $calculoHoraFin);

            $sql = "SELECT id_mesa FROM mesas 
            WHERE disponibilidad_mesa = 1
            AND capacidad_mesa >= :numero_comensales
            AND id_mesa NOT IN (
            SELECT id_mesa FROM reservas_mesas 
            WHERE fecha = :fecha_reserva
            AND (:hora_inicio < hora_fin AND :hora_fin > hora_inicio)
            )";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':numero_comensales', $numero_comensales);
            $stmt->bindParam(':fecha_reserva', $fecha_reserva);
            $stmt->bindParam(':hora_inicio', $hora_inicio_completa);
            $stmt->bindParam(':hora_fin', $hora_fin);

            $stmt->execute();
            //Almacena en un array solamente los números de las mesas disponibles
            $mesasDisponibles = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id_mesa');

            return $mesasDisponibles;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener las mesas disponibles: " . $e->getMessage());
        }
    }

    public function obtenerDisponibilidadMesa($id_mesa)
    {
        try {
            $sql = "SELECT disponibilidad_mesa FROM mesas WHERE id_mesa = :id_mesa";

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
