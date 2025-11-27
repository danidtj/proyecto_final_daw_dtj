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
    public function crearOrden($id_reserva, $metodo_pago, $precio_total, $montante_adelantado, $stripe_payment_id)
    {
        try {

            $sqlOrden = "INSERT INTO ordenes (id_reserva, fecha, metodo_pago, precio_total, montante_adelantado, stripe_payment_id)
        VALUES (:id_reserva, NOW(), :metodo_pago, :precio_total, :montante_adelantado, :stripe_payment_id)";

            
            $stmt = $this->connection->prepare($sqlOrden);
            $stmt->bindParam(':id_reserva', $id_reserva);
            $stmt->bindParam(':metodo_pago', $metodo_pago);
            $stmt->bindParam(':precio_total', $precio_total);
            $stmt->bindParam(':montante_adelantado', $montante_adelantado);
            $stmt->bindParam(':stripe_payment_id', $stripe_payment_id);

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

            return $id_orden;
        } catch (PDOException $e) {
            throw new Exception("Error al crear la orden: " . $e->getMessage());
        }
    }

    //Método para actualizar stripe_payment_id para una reserva en concreto
    public function actualizarStripePaymentId($id_orden, $stripe_payment_id)
    {
        try {
            $sql = "UPDATE ordenes SET stripe_payment_id = :stripe_payment_id WHERE id_orden = :id_orden";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':stripe_payment_id', $stripe_payment_id);
            $stmt->bindParam(':id_orden', $id_orden);
            
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar el ID de pago de Stripe: " . $e->getMessage());
        }
    }

    //Método para obtener stripe_payment_id de una orden por código de la orden
    public function obtenerStripePaymentIdPorCodigoOrden($id_orden)
    {
        try {
            $sql = "SELECT stripe_payment_id FROM ordenes WHERE id_orden = :id_orden";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_orden', $id_orden);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el ID de pago de Stripe: " . $e->getMessage());
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
    public function modificarOrdenPorCodigoOrden($precio_total, $montante_adelantado, $id_orden, $id_producto, $cantidad_pedido, $id_reserva, $carrito, $stripe_payment_id)
    {
        try {

            $this->connection->beginTransaction();
            $orden = self::obtenerOrdenPorCodigo($id_orden);
            if (!$orden) {
                throw new Exception("La orden no existe.");
            }
            $pago = array();
            if ($orden['montante_adelantado'] > $montante_adelantado) {
                $pago['abonar'] = abs(($orden['montante_adelantado'] - $montante_adelantado) * 0.1);
            } elseif ($orden['montante_adelantado'] < $montante_adelantado) {
                $pago['devolver'] = abs(($montante_adelantado - $orden['montante_adelantado']) * 0.1);
            } else {
                $pago['diferenca'] = 0;
            }

            // Actualizamos datos de la orden
            $sql1 = "UPDATE ordenes 
                SET fecha = CURDATE(), 
                precio_total = :precio_total,
                montante_adelantado = :montante_adelantado,
                stripe_payment_id = :stripe_payment_id
                WHERE id_orden = :id_orden
                AND id_reserva = :id_reserva";

            $stmt1 = $this->connection->prepare($sql1);
            $stmt1->execute([
                ':precio_total' => $precio_total,
                ':montante_adelantado' => $montante_adelantado,
                ':stripe_payment_id' => $stripe_payment_id,
                ':id_orden' => $id_orden,
                ':id_reserva' => $id_reserva
            ]);

            //Obtenemos los datos de la orden para actualizar el stock
            $datosOrdenProducto = $this->obtenerProductosPorOrden($id_orden);

            //Eliminamos los productos asociados a la orden
            $sqlEliminar = "DELETE FROM productos_ordenes WHERE id_orden = :id_orden";
            $stmtEliminar = $this->connection->prepare($sqlEliminar);
            $stmtEliminar->execute([':id_orden' => $id_orden]);

            //Obtenemos la cantidad de unidades que se van a pedir de cada producto
            $resultado = array_count_values(array_column($carrito, 'id_producto'));

            //Insertamos los productos de la orden modificada respetando su id_orden
            $sql2 = "INSERT INTO productos_ordenes (id_orden, id_producto, cantidad_pedido)
                        VALUES (:id_orden, :id_producto, :cantidad_pedido)";

            $stmt2 = $this->connection->prepare($sql2);

            foreach ($resultado as $idProd => $cant) {
                $stmt2->execute([
                    ':id_orden' => $id_orden,
                    ':id_producto' => $idProd,
                    ':cantidad_pedido' => $cant
                ]);
            }


            $sqlStock = "UPDATE productos SET uds_stock = :uds_stock WHERE id_producto = :id_producto";
            $stmtStock = $this->connection->prepare($sqlStock);

            //Recorrer datosOrdenProducto para encontrar el producto correspondiente y actualizar el stock
            foreach ($datosOrdenProducto as $producto) {

                //Comprobamos que el producto modificado está en el carrito
                $nuevaCantidad = 0;
                foreach ($resultado as $idProd => $cant) {
                    if ($idProd == $producto['id_producto']) {
                        $nuevaCantidad += $cant;
                    }
                }


                //Almacenamos los datos del producto para actualizar el stock
                $datosProducto = Producto::getUnProducto($producto['id_producto']);
                //Almacenamos el stock actual del producto a comprobar
                $nuevoStock = $datosProducto['uds_stock'];

                //Comprobamos si es mayor o menor la cantidad pedida para actualizar el stock
                if ($producto['cantidad_pedido'] > $nuevaCantidad) {
                    $diferencia = $producto['cantidad_pedido'] - $nuevaCantidad;
                    $nuevoStock += $diferencia;
                } elseif ($producto['cantidad_pedido'] < $nuevaCantidad) {
                    $diferencia = $nuevaCantidad - $producto['cantidad_pedido'];
                    $nuevoStock -= $diferencia;
                } else {
                    continue;
                }

                //Comprobamos que el stock no sea negativo
                if ($nuevoStock < 0) {
                    throw new Exception("Stock insuficiente para el producto ID $idProd");
                }

                $stmtStock->execute([
                    ':uds_stock' => $nuevoStock,
                    ':id_producto' => $producto['id_producto']

                ]);
            }

            $this->connection->commit();

            return true;
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
            $sql = "SELECT productos_ordenes.id_producto, productos.nombre_corto, productos.descripcion_corta, productos.precio_unitario, 
            productos_ordenes.cantidad_pedido
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

    //Método para eliminar una orden por su código
    public function eliminarOrdenPorCodigoReserva($id_reserva)
    {
        try {
            $sql = "DELETE FROM ordenes WHERE id_reserva = :id_reserva";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id_reserva', $id_reserva);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar la orden: " . $e->getMessage());
        }
    }

    //Método para reembolsar una orden mediante Stripe
    public function reembolsarOrden($id_reserva)
    {
        try {
            $orden = $this->obtenerOrdenPorCodigoReserva($id_reserva);

            if (empty($orden['stripe_payment_id'])) {
                throw new Exception("No se encontró el ID de pago de Stripe para esta orden.");
            }

            // Configurar Stripe
            \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

            // Crear el reembolso
            $refund = \Stripe\Refund::create([
                'payment_intent' => $orden['stripe_payment_id'],
            ]);

            return [
                'success' => true,
                'refund_id' => $refund->id
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new Exception("Error al procesar el reembolso: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    //Método para reembolsar mediante id de stripe_payment_id
    public function reembolsarOrdenPorStripePaymentId($idOrden)
    {
        try {
            $stripe = $this->obtenerStripePaymentIdPorCodigoOrden($idOrden);

            if (empty($stripe)) {
                throw new Exception("No se encontró el ID de pago de Stripe para esta orden.");
            }

            // Configurar Stripe
            \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

            // Crear el reembolso
            $refund = \Stripe\Refund::create([
                'payment_intent' => $stripe,
            ]);

            return [
                'success' => true,
                'refund_id' => $refund->id
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new Exception("Error al procesar el reembolso: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
