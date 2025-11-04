<?php

namespace ModelsFrontend;

use ModelsAdmin\Producto;

use Config\DB;
use PDO;
use PDOException;
use Exception;

require_once dirname(__DIR__, 2) . '/config/DB.php';
require_once dirname(__DIR__) . '/admin/Producto.php';

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

    //Método para crear una nueva orden
    public function crearOrden($id_reserva, $metodo_pago, $precio_total, $montante_adelantado)
    {
        try {

            $sqlOrden = "INSERT INTO ordenes (id_reserva, fecha, metodo_pago, precio_total, montante_adelantado)
        VALUES (:id_reserva, NOW(), :metodo_pago, :precio_total, :montante_adelantado)";

            //VALUES (:codigo_reserva, :id_usuario, :numero_mesa, 'ABIERTA', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 DAY))";
            $stmt = $this->connection->prepare($sqlOrden);
            $stmt->bindParam(':id_reserva', $id_reserva);
            $stmt->bindParam(':metodo_pago', $metodo_pago);
            $stmt->bindParam(':precio_total', $precio_total);
            $stmt->bindParam(':montante_adelantado', $montante_adelantado);

            $stmt->execute();

            $id_orden = $this->connection->lastInsertId();

            $resultado = array_count_values(array_column($_SESSION['carrito'], 'id_producto'));

            $sqlPO = "INSERT INTO productos_ordenes (id_orden, id_producto, cantidad_pedido)
        VALUES (:id_orden, :id_producto, :cantidad_pedido)";

            $stmtPO = $this->connection->prepare($sqlPO);

            foreach ($resultado as $id_producto => $cantidad_pedido) {
                $stmtPO->bindParam(':id_orden', $id_orden);
                $stmtPO->bindParam(':id_producto', $id_producto);
                $stmtPO->bindParam(':cantidad_pedido', $cantidad_pedido);
                $stmtPO->execute();
            }
            //Actualizamos el stock de los productos de la orden
            Producto::actualizarStockProductosCarrito($_SESSION['carrito']);

        } catch (PDOException $e) {
            throw new Exception("Error al crear la orden: " . $e->getMessage());
        }
    }



    //Método para obtener la orden por código de reserva
    /*public function obtenerOrden($codigo_reserva)
    {
        try {
            $sql = "SELECT * FROM ordenes WHERE codigo_reserva = :codigo_reserva";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':codigo_reserva', $codigo_reserva);
            $stmt->execute();

            $orden = $stmt->fetch(PDO::FETCH_ASSOC);

            return $orden;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener la orden: " . $e->getMessage());
        }
    }*/
}
