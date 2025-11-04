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
            //Insertamos en la tabla RESERVAS los datos de la reserva
            $sqlReserva = "INSERT INTO reservas (id_usuario, numero_comensales, comanda_previa) 
                       VALUES (:id_usuario, :numero_comensales, :comanda_previa)";

            $stmt = $this->connection->prepare($sqlReserva);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':numero_comensales', $numero_comensales);
            $stmt->bindParam(':comanda_previa', $comanda_previa);

            $stmt->execute();

            //Obtenemos el id de la reserva insertada
            $id_reserva = $this->connection->lastInsertId();

            //Calculamos la duración de la reserva en base a la hora de inicio
            $hora_inicio = date('Y-m-d H:i:s', strtotime($hora_reserva));
            $hora_fin = date('Y-m-d H:i:s', strtotime($hora_inicio . ' +1 hour 30 minutes'));

            //Insertamos en la tabla RESERVAS_MESAS los datos de la mesa asignada a la reserva
            $sqlMesa = "INSERT INTO reservas_mesas (id_reserva, id_mesa, fecha, hora_inicio, hora_fin)
                    VALUES (:id_reserva, :id_mesa, :fecha, :hora_inicio, :hora_fin)";
            $stmtMesa = $this->connection->prepare($sqlMesa);
            $stmtMesa->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
            $stmtMesa->bindParam(':id_mesa', $numero_mesa, PDO::PARAM_INT);
            $stmtMesa->bindParam(':fecha', $fecha_reserva);
            $stmtMesa->bindParam(':hora_inicio', $hora_inicio);
            $stmtMesa->bindParam(':hora_fin', $hora_fin);
            $stmtMesa->execute();

            return $id_reserva;
        } catch (PDOException $e) {
            throw new Exception("Error al realizar la reserva: " . $e->getMessage());
        }
    }

    //Método para obtener una reserva mediante toda su información a excepción del id_reserva
    /*public function obtenerReservaCompleta($id_usuario, $numero_comensales, $comanda_previa, $id_mesa, $fecha_reserva, $hora_inicio)
    {
        try {
            $sql = "SELECT r.*, rm.id_mesa, rm.fecha, rm.hora_inicio, rm.hora_fin
                    FROM reservas r
                    JOIN reservas_mesas rm ON r.id_reserva = rm.id_reserva
                    WHERE r.id_usuario = :id_usuario 
                    AND r.numero_comensales = :numero_comensales
                    AND r.comanda_previa = :comanda_previa
                    AND rm.id_mesa = :id_mesa
                    AND rm.fecha = :fecha_reserva
                    AND TIME_FORMAT(rm.hora_inicio, '%H:%i') = :hora_inicio";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':numero_comensales', $numero_comensales);
            $stmt->bindParam(':comanda_previa', $comanda_previa);
            $stmt->bindParam(':id_mesa', $id_mesa);
            $stmt->bindParam(':fecha_reserva', $fecha_reserva);
            $stmt->bindParam(':hora_inicio', $hora_inicio);
            $stmt->execute();

            $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

            return $reserva;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener la reserva completa: " . $e->getMessage());
        }
    }*/

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
            $sql = "SELECT reservas.id_reserva, reservas.id_usuario, reservas.numero_comensales, reservas.comanda_previa, reservas_mesas.id_mesa,  
            DATE(reservas_mesas.fecha) AS fecha,
            TIME_FORMAT(reservas_mesas.hora_inicio, '%H:%i') AS hora_inicio, 
            TIME_FORMAT(reservas_mesas.hora_fin, '%H:%i') AS hora_fin
            FROM reservas
            JOIN reservas_mesas ON reservas.id_reserva = reservas_mesas.id_reserva
            WHERE reservas.id_usuario = :id_usuario
            ORDER BY reservas_mesas.fecha ASC, reservas_mesas.hora_inicio ASC;";


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
            $sql = "SELECT reservas.id_reserva, reservas.id_usuario, reservas.numero_comensales, reservas.comanda_previa, 
            reservas_mesas.id_mesa, reservas_mesas.fecha, reservas_mesas.hora_inicio, reservas_mesas.hora_fin
            FROM reservas
            JOIN reservas_mesas ON reservas.id_reserva = reservas_mesas.id_reserva
            ORDER BY reservas_mesas.fecha ASC, reservas_mesas.hora_inicio ASC;";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $reservas;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener todas las reservas: " . $e->getMessage());
        }
    }

    //Cancelar una reserva por su código
    public function cancelarReserva($id_reserva)
    {
        try {
            $sql = "DELETE FROM reservas WHERE id_reserva = :id_reserva";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_reserva', $id_reserva);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al cancelar la reserva: " . $e->getMessage());
        }
    }

    public function obtenerReservaPorCodigo($id_reserva)
    {
        try {
            $sql = "SELECT reservas.id_reserva, reservas.numero_comensales, reservas.comanda_previa, 
            reservas_mesas.id_mesa, reservas_mesas.fecha, reservas_mesas.hora_inicio, reservas_mesas.hora_fin
            FROM reservas
            JOIN reservas_mesas ON reservas.id_reserva = reservas_mesas.id_reserva
            WHERE reservas.id_reserva = :id_reserva";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_reserva', $id_reserva);
            $stmt->execute();

            $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

            return $reserva;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener la reserva por código: " . $e->getMessage());
        }
    }

    //Método para modificar una reserva existente
    public function modificarReserva($id_reserva, $id_mesa, $fecha, $hora_inicio, $numero_comensales, $comanda_previa)
    {
        try {

            //Calculamos la duración de la reserva en base a la hora de inicio
            $hora_inicio = date('Y-m-d H:i:s', strtotime($hora_inicio));
            $hora_fin = date('Y-m-d H:i:s', strtotime($hora_inicio . ' +1 hour 30 minutes'));

            // Actualizamos datos de la reserva
            $sql1 = "UPDATE reservas 
                SET numero_comensales = :numero_comensales, 
                comanda_previa = :comanda_previa 
                WHERE id_reserva = :id_reserva";

                // Actualizamos mesa y horarios en reservas_mesas para una mesa específica
            $sql2 = "UPDATE reservas_mesas 
                SET fecha = :fecha,
                hora_inicio = :hora_inicio,
                hora_fin = :hora_fin
                WHERE id_reserva = :id_reserva
                AND id_mesa = :id_mesa";

            //Ejecutamos los datos de la reserva
            $stmt = $this->connection->prepare($sql1);
            $stmt->bindParam(':numero_comensales', $numero_comensales);
            $stmt->bindParam(':comanda_previa', $comanda_previa);
            $stmt->bindParam(':id_reserva', $id_reserva);

            $stmt->execute();

            //Ejecutamos los datos de reservas_mesas
            $stmt = $this->connection->prepare($sql2);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora_inicio', $hora_inicio);
            $stmt->bindParam(':hora_fin', $hora_fin);
            $stmt->bindParam(':id_reserva', $id_reserva);
            $stmt->bindParam(':id_mesa', $id_mesa);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al modificar la reserva: " . $e->getMessage());
        }
    }
}
