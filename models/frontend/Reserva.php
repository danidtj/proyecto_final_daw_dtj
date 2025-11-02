<?php

namespace ModelsFrontend;

use Config\DB;
use PDO;
use PDOException;
use Exception;

require_once dirname(__DIR__, 2) . '/config/DB.php';

class Reserva
{

    private PDO $connection;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->connection = DB::getInstance()->getConnection();
    }

    //método con consulta preparada sql para insertar una nueva reserva en la DB
    public function realizarReserva($fecha_reserva, $hora_reserva, $numero_comensales, $comanda_previa, $numero_mesa, $id_usuario)
    {
        try {

            $sql = "INSERT INTO reservas (id_usuario, numero_mesa, fecha_reserva, hora_reserva, numero_comensales, comanda_previa) 
                    VALUES (:id_usuario, :numero_mesa, :fecha_reserva, :hora_reserva, :numero_comensales, :comanda_previa)";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':numero_mesa', $numero_mesa);
            $stmt->bindParam(':fecha_reserva', $fecha_reserva);
            $stmt->bindParam(':hora_reserva', $hora_reserva);
            $stmt->bindParam(':numero_comensales', $numero_comensales);
            $stmt->bindParam(':comanda_previa', $comanda_previa);

            $stmt->execute();



            //Devuelve el último dato autogenerado en la última conexión, es decir, el código de la reserva creada
            $codigo_reserva = $this->connection->lastInsertId();

            return $codigo_reserva;
        } catch (PDOException $e) {
            throw new Exception("Error al realizar la reserva: " . $e->getMessage());
        }
    }

    //Método para obtener una reserva mediante el número de teléfono y la fecha de la reserva
    public function obtenerReservaPorTelefonoYFecha($telefono_usuario, $fecha_reserva)
    {
        try {
            $sql = "SELECT r.* 
                    FROM reservas r
                    JOIN usuarios u ON r.id_usuario = u.id_usuario
                    WHERE u.telefono_usuario = :telefono_usuario 
                    AND r.fecha_reserva = :fecha_reserva";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':telefono_usuario', $telefono_usuario);
            $stmt->bindParam(':fecha_reserva', $fecha_reserva);
            $stmt->execute();

            $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

            return $reserva;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener la reserva por teléfono y fecha: " . $e->getMessage());
        }
    }

    //Método para obtener todas las reservas de un mismo usuario
    public function obtenerReservasPorUsuario($id_usuario)
    {
        try {
            $sql = "SELECT * FROM reservas WHERE id_usuario = :id_usuario ORDER BY fecha_reserva ASC, hora_reserva ASC;";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();

            $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $reservas;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener las reservas del usuario: " . $e->getMessage());
        }
    }

    //Método para obtener todas las reservas
    public function obtenerTodasLasReservas()
    {
        try {
            $sql = "SELECT * FROM reservas ORDER BY fecha_reserva ASC, hora_reserva ASC;";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $reservas;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener todas las reservas: " . $e->getMessage());
        }
    }

    //Cancelar una reserva por su código
    public function cancelarReserva($codigo_reserva)
    {
        try {
            $sql = "DELETE FROM reservas WHERE codigo_reserva = :codigo_reserva";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':codigo_reserva', $codigo_reserva);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al cancelar la reserva: " . $e->getMessage());
        }
    }

    public function obtenerReservaPorCodigo($codigo_reserva)
    {
        try {
            $sql = "SELECT * FROM reservas WHERE codigo_reserva = :codigo_reserva";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':codigo_reserva', $codigo_reserva);
            $stmt->execute();

            $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

            return $reserva;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener la reserva por código: " . $e->getMessage());
        }
    }

    //Método para modificar una reserva existente
    public function modificarReserva($codigo_reserva, $numero_mesa, $fecha_reserva, $hora_reserva, $numero_comensales, $comanda_previa)
    {
        try {
            $sql = "UPDATE reservas 
                    SET numero_mesa = :numero_mesa,
                    fecha_reserva = :fecha_reserva, 
                        hora_reserva = :hora_reserva, 
                        numero_comensales = :numero_comensales, 
                        comanda_previa = :comanda_previa 
                    WHERE codigo_reserva = :codigo_reserva";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':numero_mesa', $numero_mesa);
            $stmt->bindParam(':fecha_reserva', $fecha_reserva);
            $stmt->bindParam(':hora_reserva', $hora_reserva);
            $stmt->bindParam(':numero_comensales', $numero_comensales);
            $stmt->bindParam(':comanda_previa', $comanda_previa);
            $stmt->bindParam(':codigo_reserva', $codigo_reserva);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al modificar la reserva: " . $e->getMessage());
        }
    }
}
