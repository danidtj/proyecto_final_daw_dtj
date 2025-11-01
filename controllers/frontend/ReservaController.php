<?php

namespace ControllerFrontend;

session_start();

use ModelsFrontend\Reserva;
use ModelsFrontend\Orden;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';

$reservaController = new ReservaController();
//La vista de Reserva siempre tiene que ejecutarse independientemente de si se envía o no el formulario.
$reservaController->mostrarVistaReserva();

if (isset($_POST['confirmarReserva'])) {

    $_SESSION['confirmarReserva'] = true;

    $reserva = new Reserva();
    $orden = new Orden();

    $codigo_reserva = $reserva->realizarReserva(
        $_SESSION['fecha'],
        $_SESSION['hora'],
        $_SESSION['comensales'],
        $_SESSION['comanda'],
        $_POST['mesa_id'],
        $_SESSION['id_usuario']
    );

    $orden->crearOrden(
        $codigo_reserva,
        $_SESSION['id_usuario'],
        $_POST['mesa_id']
    );

    if ($_SESSION['comanda'] === "1") {

        //Si el usuario ha hecho una comanda previa, redirige a la carta
        header("Location: /views/frontend/carta.php");
        exit();
    } else {

        //Si no ha hecho comanda previa, redirige a la página principal
        header("Location: /home");
        exit();
    }
}


class ReservaController
{
    //Método para mostar la vista de Reserva
    public function mostrarVistaReserva()
    {
        require_once dirname(__DIR__, 2) . '/views/frontend/reserva.php';
    }
}
