<?php

namespace ControllerFrontend;

session_start();

use ModelsFrontend\Reserva;
use ModelsFrontend\Orden;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';

class OrdenController
{

    public function modificarOrdenReservaExistente()
    {

        $orden = new Orden();
        foreach ($_SESSION['carrito'] as $producto) {
            foreach ($_SESSION['orden_original'] as $ordenOriginal) {
                $_SESSION['idOrdenModificar'] = $ordenOriginal['id_orden'];
                $_SESSION['idReservaModificar'] = $ordenOriginal['id_reserva'];

                //$orden->reembolsarOrdenPorStripePaymentId($ordenOriginal['id_orden']);

                $ordenModificada = $orden->modificarOrdenPorCodigoOrden(
                    $_SESSION['precioTotalCarrito'],
                    $_SESSION['nuevoPagoAdelantado'],
                    $ordenOriginal['id_orden'],
                    $producto['id_producto'],
                    array_count_values(array_column($_SESSION['carrito'], 'id_producto'))[$producto['id_producto']] ?? 0,
                    $ordenOriginal['id_reserva'],
                    $_SESSION['carrito'],
                    $_SESSION['stripe_payment_id'] ?? null
                );
            }
        }


        unset($_SESSION['confirmarModificacionReserva']);
        unset($_SESSION['modificar_orden']);
        unset($_SESSION['orden_original']);
        $_SESSION['carrito'] = []; // Vaciamos el carrito después de modificar la orden
    }
}
