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
    public function obtenerOrdenPorCodigoReserva($id_reserva)
    {
        try {
            $sql = "SELECT * FROM ordenes WHERE id_reserva = :id_reserva";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_reserva', $id_reserva);
            $stmt->execute();

            $orden = $stmt->fetch(PDO::FETCH_ASSOC);

            return $orden;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener la orden: " . $e->getMessage());
        }
    }

    //Método para modificar una orden asociada a una reserva
    public function modificarOrdenPorCodigoOrden($precio_total, $montante_adelantado, $id_orden, $id_producto, $cantidad_pedido, $id_reserva)
    {
        try {

            $this->connection->beginTransaction();
            $orden = self::obtenerOrdenPorCodigo($id_orden);
            $pago = array();
            if ($orden['montante_adelantado'] > $montante_adelantado) {
                $pago['abonar'] = abs(($orden['montante_adelantado'] - $montante_adelantado) * 0.1);
            } elseif ($orden['montante_adelantado'] < $montante_adelantado) {
                $pago['devolver'] = abs(($montante_adelantado - $orden['montante_adelantado']) * 0.1);
            } else {
                $pago['diferenca'] = 0;
            }



            // Actualizamos datos de la reserva
            $sql1 = "UPDATE ordenes 
                SET fecha = CURDATE(), 
                precio_total = :precio_total,
                montante_adelantado = :montante_adelantado
                WHERE id_orden = :id_orden
                AND id_reserva = :id_reserva";

            $stmt = $this->connection->prepare($sql1);
            $stmt->bindParam(':precio_total', $precio_total);
            $stmt->bindParam(':montante_adelantado', $montante_adelantado);
            $stmt->bindParam(':id_orden', $id_orden);
            $stmt->bindParam(':id_reserva', $id_reserva);

            $stmt->execute();

            $sqlEliminar = "DELETE FROM productos_ordenes WHERE id_orden = :id_orden";
            $stmt = $this->connection->prepare($sqlEliminar);
            $stmt->bindParam(':id_orden', $id_orden);
            $stmt->execute();

            $resultado = array_count_values(array_column($_SESSION['carrito'], 'id_producto'));

            $sql2 = "INSERT INTO productos_ordenes (id_orden, id_producto, cantidad_pedido)
         VALUES (:id_orden, :id_producto, :cantidad_pedido)";

            $stmt2 = $this->connection->prepare($sql2);

            foreach ($resultado as $id_producto => $cantidad) {
                $stmt2->bindParam(':id_orden', $id_orden);
                $stmt2->bindParam(':id_producto', $id_producto);
                $stmt2->bindParam(':cantidad_pedido', $cantidad);
                $stmt2->execute();
            }





            //Al tener tabla intermedia con PK compuesta, eliminamos la relación existente
            /*$sqlEliminar = "DELETE FROM productos_ordenes WHERE id_orden = :id_orden";
            $stmt = $this->connection->prepare($sqlEliminar);
            $stmt->bindParam(':id_orden', $id_orden);
            $stmt->execute();

            //Una vez eliminada la relación anterior, insertamos los datos actualizados
            $sqlInsertar = "INSERT INTO productos_ordenes (id_orden, id_producto, cantidad_pedido) 
                      VALUES (:id_orden, :id_producto, :cantidad_pedido)";
            $stmt = $this->connection->prepare($sqlInsertar);
            $stmt->bindParam(':id_orden', $id_orden);
            $stmt->bindParam(':id_producto', $id_producto);
            $stmt->bindParam(':cantidad_pedido', $cantidad_pedido);
            $stmt->execute();*/

            $this->connection->commit();

            return $pago;
        } catch (PDOException $e) {
            $this->connection->rollBack();
            throw new Exception("Error al modificar la reserva: " . $e->getMessage());
        }
    }

    //Método para obtener una orden mediante su código
    public function obtenerOrdenPorCodigo($id_orden)
    {
        try {
            $sql = "SELECT * FROM ordenes WHERE id_orden = :id_orden";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_orden', $id_orden);
            $stmt->execute();

            $orden = $stmt->fetch(PDO::FETCH_ASSOC);

            return $orden;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener la orden: " . $e->getMessage());
        }
    }

    //Método para obtener los productos de una orden
    public function obtenerProductosPorOrden($id_orden)
    {
        try {
            $sql = "SELECT * FROM productos_ordenes WHERE id_orden = :id_orden";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_orden', $id_orden);
            $stmt->execute();

            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $productos;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener los productos de la orden: " . $e->getMessage());
        }
    }

    //Método para obtener los datos de productos y productos_ordenes
    public function obtenerDetallesProductosPorOrden($id_orden)
    {
        try {
            $sql = "SELECT productos_ordenes.id_producto, productos.nombre_corto, productos.descripcion_corta, productos.precio_unitario, productos_ordenes.cantidad_pedido
            FROM productos_ordenes
            JOIN productos ON productos_ordenes.id_producto = productos.id_producto
            WHERE productos_ordenes.id_orden = :id_orden";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_orden', $id_orden);
            $stmt->execute();

            $detallesProductos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $detallesProductos;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener los detalles de los productos de la orden: " . $e->getMessage());
        }
    }
}
