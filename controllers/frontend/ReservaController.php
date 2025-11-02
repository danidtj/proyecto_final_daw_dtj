<?php

namespace ControllerFrontend;

session_start();

use ModelsFrontend\Reserva;
use ModelsFrontend\Orden;

require_once dirname(__DIR__, 2) . '/models/frontend/Reserva.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';

$reserva = new Reserva();
$orden = new Orden();
class ReservaController
{
    //Método para mostar la vista de Reserva
    public function mostrarVistaReserva()
    {
        require_once dirname(__DIR__, 2) . '/views/frontend/reserva.php';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmarReserva'])) {

    $_SESSION['confirmarReserva'] = true;

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


if (isset($_POST['modificarReserva'])) {

    $_SESSION['codigo_reserva'] = $_POST['codigo_reserva'];
    $_SESSION['fecha'] = $_POST['fecha'];
    $_SESSION['hora'] = $_POST['hora'];
    $_SESSION['comensales'] = $_POST['comensales'];
    $_SESSION['comanda'] = $_POST['comanda'];
    $_SESSION['numero_mesa'] = $_POST['numero_mesa'];
}




if (isset($_POST['confirmarModificacionReserva'])) {

    $_SESSION['confirmarModificacionReserva'] = true;

    $reserva->modificarReserva(
        $_SESSION['codigo_reserva'],
        $_POST['mesa_id'],
        $_SESSION['fecha'],
        $_SESSION['hora'],
        $_SESSION['comensales'],
        $_SESSION['comanda']
    );

    if ($_SESSION['comanda'] === "1") {

        //Si el usuario ha hecho una comanda previa, redirige a la carta
        header("Location: /views/frontend/carta.php");
        exit();
    } else {

        //Si no ha hecho comanda previa, redirige a la página principal
        echo "entro";
    }
}

$reservaController = new ReservaController();
//La vista de Reserva siempre tiene que ejecutarse independientemente de si se envía o no el formulario.
$reservaController->mostrarVistaReserva();
