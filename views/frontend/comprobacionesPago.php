<?php

use ModelsFrontend\Orden;
use ModelsFrontend\Reserva;
use ModelsAdmin\Producto;
use ControllerFrontend\ReservaController;
use ControllerFrontend\OrdenController;

@session_start();

//require_once dirname(__DIR__, 2) . '/utilidades/enviarEmail.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/admin/Producto.php';
require_once dirname(__DIR__, 2) . '/controllers/frontend/ReservaController.php';
require_once dirname(__DIR__, 2) . '/controllers/frontend/OrdenController.php';


//header('Content-Type: application/json');
$orden = new Orden();
$reserva = new Reserva();
$reservaController = new ReservaController();
$ordenController = new OrdenController();


//Bloque para modificar la orden
if (isset($_SESSION['modificar_orden']) && $_SESSION['modificar_orden'] === true && isset($_SESSION['orden_original'])) {

    $ordenController->modificarOrdenReservaExistente();

    header("Location: /views/frontend/miPerfil.php");
    exit();

} //else {

    if (isset($_SESSION['confirmarReserva']) && !empty($_SESSION['confirmarReserva'])) {
        $reservaController->crearOrdenYReserva();
    }

    //Confirmamos si se quiere modificar una reserva existente sin orden y se le asocia una
    if (isset($_SESSION['confirmarModificacionReserva']) && $_SESSION['confirmarModificacionReserva'] === true) {

        $reservaController->crearOrdenReservaExistente();
    }

    header("Location: /views/frontend/miPerfil.php");
    exit();
//}

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
