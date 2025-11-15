<?php

use ModelsFrontend\Orden;
use ModelsFrontend\Reserva;
use ModelsAdmin\Producto;

@session_start();

//require_once dirname(__DIR__, 2) . '/utilidades/enviarEmail.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';

//header('Content-Type: application/json');
$orden = new Orden();
$reserva = new Reserva();


//Bloque para modificar la orden
if (isset($_SESSION['modificar_orden']) && $_SESSION['modificar_orden'] === true && isset($_SESSION['orden_original'])) {
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

    /*if($ordenModificada){
        $stripePayment = $orden->obtenerStripePaymentIdPorCodigoOrden($_SESSION['idOrdenModificar']);
        $orden->reembolsarOrden($_SESSION['idReservaModificar']);
        $orden->actualizarStripePaymentId($_SESSION['idOrdenModificar'], $_SESSION['stripe_payment_id']);
    }*/

    unset($_SESSION['confirmarModificacionReserva']);
    unset($_SESSION['modificar_orden']);
    unset($_SESSION['orden_original']);
    $_SESSION['carrito'] = []; // Vaciamos el carrito después de modificar la orden


    header("Location: /views/frontend/miPerfil.php");
    exit();
} else {
    if (isset($_SESSION['confirmarReserva']) && !empty($_SESSION['confirmarReserva'])) {
        $codigo_reserva = $reserva->realizarReserva(
            $_SESSION['fecha'],
            $_SESSION['hora_inicio'],
            $_SESSION['numero_comensales'],
            $_SESSION['comanda_previa'],
            $_SESSION['mesa_id'],
            $_SESSION['id_usuario']
        );

        // Almacenamos el id de la nueva reserva en session
        $_SESSION['id_reserva_nueva'] = $codigo_reserva;
        $stripePaymentId = $_SESSION['stripe_payment_id'] ?? null;

        $idOrdenCreada = $orden->crearOrden(
            $_SESSION['id_reserva_nueva'],
            'Tarjeta de crédito',
            $_SESSION['precioTotalCarrito'],
            $_SESSION['nuevoPagoAdelantado'],
            $stripePaymentId
        );

        if (isset($codigo_reserva) && isset($idOrdenCreada)) {
            $_SESSION['codigo_reserva'] = $codigo_reserva;
            $_SESSION['idOrdenCreada'] = $idOrdenCreada;
        }


        unset($_SESSION['confirmarReserva']);
        unset($_SESSION['comanda_previa']);
        $_SESSION['carrito'] = [];
        unset($_SESSION['id_reserva_nueva']);
    }

    if (isset($_SESSION['confirmarModificacionReserva'])) {

        $stripePaymentId = $_SESSION['stripe_payment_id'] ?? null;

        $idOrdenCreada = $orden->crearOrden(
            $_SESSION['id_reserva_nueva'],
            'Tarjeta de crédito',
            $_SESSION['precioTotalCarrito'],
            $_SESSION['nuevoPagoAdelantado'],
            $stripePaymentId
        );

        $reserva->modificarReserva(
            $_SESSION['id_reserva'],
            $_SESSION['mesa_id'],
            $_SESSION['fecha'],
            $_SESSION['hora_inicio'],
            $_SESSION['numero_comensales'],
            $_SESSION['comanda_previa']
        );

        unset($_SESSION['confirmarModificacionReserva']);
        unset($_SESSION['comanda_previa']);
        unset($_SESSION['carrito']); // Vaciamos el carrito después de crear la orden
    }

    header("Location: /views/frontend/miPerfil.php");
    exit();
}

//Bloque para confirmar modificación de reserva
/*if (isset($_SESSION['confirmarModificacionReserva'])) {

    $stripePaymentId = $_SESSION['stripe_payment_id'] ?? null;

    $idOrdenCreada = $orden->crearOrden(
        $_SESSION['id_reserva_nueva'],
        'Tarjeta de crédito',
        $_SESSION['precioTotalCarrito'],
        $_SESSION['nuevoPagoAdelantado'],
        $stripePaymentId
    );

    $reserva->modificarReserva(
        $_SESSION['id_reserva'],
        $_SESSION['mesa_id'],
        $_SESSION['fecha'],
        $_SESSION['hora_inicio'],
        $_SESSION['numero_comensales'],
        $_SESSION['comanda_previa']
    );

    unset($_SESSION['confirmarModificacionReserva']);
    unset($_SESSION['comanda_previa']);
    unset($_SESSION['carrito']); // Vaciamos el carrito después de crear la orden
}

//Bloque para confirmar reserva nueva
if (isset($_SESSION['confirmarReserva']) && !empty($_SESSION['confirmarReserva'])) {

    $codigo_reserva = $reserva->realizarReserva(
        $_SESSION['fecha'],
        $_SESSION['hora_inicio'],
        $_SESSION['numero_comensales'],
        $_SESSION['comanda_previa'],
        $_SESSION['mesa_id'],
        $_SESSION['id_usuario']
    );

    // Almacenamos el id de la nueva reserva en session
    $_SESSION['id_reserva_nueva'] = $codigo_reserva;
    $stripePaymentId = $_SESSION['stripe_payment_id'] ?? null;

    $idOrdenCreada = $orden->crearOrden(
        $_SESSION['id_reserva_nueva'],
        'Tarjeta de crédito',
        $_SESSION['precioTotalCarrito'],
        $_SESSION['nuevoPagoAdelantado'],
        $stripePaymentId
    );

    unset($_SESSION['confirmarReserva']);
    unset($_SESSION['comanda_previa']);
    unset($_SESSION['carrito']);
    unset($_SESSION['stripe_payment_id']);
}*/


echo json_encode(['success' => true]);
