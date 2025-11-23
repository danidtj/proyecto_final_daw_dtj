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

    header("Location: /proyecto_final_daw_dtj/views/frontend/miPerfil.php");
    exit();
} //else {

if (isset($_SESSION['confirmarReserva']) && !empty($_SESSION['confirmarReserva'])) {
    $reservaController->crearOrdenYReserva();
}

//Confirmamos si se quiere modificar una reserva existente sin orden y se le asocia una
if (
    isset($_SESSION['confirmarModificacionReserva']) && $_SESSION['confirmarModificacionReserva'] === true &&
    isset($_SESSION['mod_reserva_con_comanda']) && $_SESSION['mod_reserva_con_comanda'] == "1"
) {

    $reservaController->crearOrdenReservaExistente();

    $_SESSION['email_nueva_orden'] = true;
}

header("Location: /proyecto_final_daw_dtj/views/frontend/miPerfil.php");
exit();

echo json_encode(['success' => true]);
